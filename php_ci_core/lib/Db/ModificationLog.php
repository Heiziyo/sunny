<?php
class DB_ModificationLog extends Db_Model_EventHandler
{
    protected $_primaryKey = 'id';
    protected $_logModel = NULL;
    protected $_cache = array();
    protected $_ignoreFields = array();
    protected $_logAuxiliary = NULL;

    const TYPE_INSERT = 'insert';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';

    public function __construct($logModel, $primaryKey = 'id', $ignoreFields = array('last_update_time'), $_logAuxiliary = NULL)
    {
        $this->_logModel = $logModel;
        $this->_primaryKey = $primaryKey;
        $this->_ignoreFields = $ignoreFields;
        $this->_logAuxiliary = $_logAuxiliary;
    }

    protected function _addLog($dbName, $table, $type, $objectId, $originData, $newData = array())
    {
        $changeData = array();

        if ($type == self::TYPE_UPDATE) {
            foreach ($originData as $field => $value) {
                if (in_array($field, $this->_ignoreFields)) {
                    continue;
                }
                if (isset($newData[$field]) && $newData[$field] != $value) {
                    $changeData[$field] = $newData[$field];
                }
            }
            if (empty($changeData)) {
                return FALSE;
            }
        }

        $changeFields = array_keys($changeData);
        if($this->_logAuxiliary === NULL) {
            $ins = array(
                'db_name' => $dbName,
                'table_name' => $table,
                'object_id' => $objectId,
                'op_username' => isset($GLOBALS['current_username']) ? $GLOBALS['current_username'] : '',
                'op_uid' => isset($GLOBALS['uid']) ? $GLOBALS['uid'] : '',
                'op_login_time' => isset($GLOBALS['login_time']) ? $GLOBALS['login_time'] : '',
                'op_login_ip' => isset($GLOBALS['login_ip']) ? $GLOBALS['login_ip'] : '',
                'op_title' => isset($GLOBALS['title']) ? $GLOBALS['title'] : '',
                'op_type' => $type,
                'origin_data' => json_encode($originData),
                'change_fields' => implode(',', $changeFields),
                'change_data' => json_encode($changeData)
            );

            return $this->_logModel->insert($ins);
        }else{
            $ins = array(
                'version_id' => md5(microtime()),
                'table_name' => $table,
                'pk' => $objectId,
                'data' => json_encode($newData),
                'modify_fields' => implode(',', $changeFields),
                'user_id' => isset($GLOBALS['userRealId']) ? $GLOBALS['userRealId'] : ''
            );

            $insertId = $this->_logModel->insert($ins, TRUE);
            if($insertId){
                $auxiliaryInsert = array(
                    'log_id' => $insertId,
                    'origin_data' => json_encode($originData)
                );
                return Factory::$f->Db_Model('auxiliary_data_version', 'ad_media')->insert($auxiliaryInsert);
            }

            return FALSE;

        }
    }

    public function afterInsert ($model, $data, $lastId) {
        $model->setReadOnMaster(TRUE); 
        $table = $model->table();
        
        if ( ! $lastId) {
            if (isset($data[$this->_primaryKey])) {
                $lastId = $data[$this->_primaryKey];
            } else {
                $lastId = $model->getLastId();
            }
        }

        if ($lastId) {
            $data = $model->selectOne(array($this->_primaryKey => $lastId));
        }

        $this->_addLog($model->getDatabaseName(), $model->table(), self::TYPE_INSERT, $lastId, $data); 
    }

    public function beforeUpdate ($model, &$where, $data)
    {
        $model->setReadOnMaster(TRUE); 

        $originRows = $model->select($where);

        if (empty($originRows)) {
            return;
        }

        $this->_cache[md5(serialize($where))] = $originRows;
    }

    public function afterUpdate ($model, $where, $data) 
    {
        $cacheKey = md5(serialize($where));
        if ( ! isset($this->_cache[$cacheKey])) {
            return;
        }

        $originRows = $this->_cache[$cacheKey];
        unset($this->_cache[$cacheKey]);

        array_change_key($originRows, $this->_primaryKey);
        $ids = array_keys($originRows);
        $rows = $model->select(array($this->_primaryKey => $ids));
        $table = $model->table(); 
        $dbName = $model->getDatabaseName();

        foreach ($rows as $row) {
            $id = $row[$this->_primaryKey];
            if (isset($originRows[$id])) {
                $this->_addLog($dbName, $table, self::TYPE_UPDATE, $id, $originRows[$id], $row);
            }
        }
    }

    public function beforeInsertReplace ($model, &$data, &$replace) 
    {
    }

    public function afterInsertReplace ($model, $data, $replace) 
    {
    }

    public function beforeDelete ($model, &$where) 
    {
        $model->setReadOnMaster(TRUE); 

        $originRows = $model->select($where);

        if (empty($originRows)) {
            return;
        }

        $this->_cache[md5(serialize($where))] = $originRows;
    }

    public function afterDelete ($model, $where) 
    {
        $cacheKey = md5(serialize($where));
        if ( ! isset($this->_cache[$cacheKey])) {
            return;
        }

        $originRows = $this->_cache[$cacheKey];
        unset($this->_cache[$cacheKey]);
        $table = $model->table(); 
        $dbName = $model->getDatabaseName();

        foreach ($originRows as $originRow) {
            $this->_addLog($dbName, $table, self::TYPE_DELETE, $originRow[$this->_primaryKey], $originRow);
        }
    }
}
