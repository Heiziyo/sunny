<?php
class Oplog extends MY_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE, '系统管理');

        $this->data['c_menu'] = 'system';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_PoLog;

        $column = array(
            'ID' =>'id',
            '项目名称' => 'op_title',
            '操作人ID' => 'op_uid|show',
            '操作账号' => 'op_username',
            '登陆时间' => 'op_login_time',
            'IP' => 'op_login_ip',
            '时间' => 'create_time',
            '操作类型' => 'cb_optype',
            '项目ID' => 'object_id',
        );

        $this->_setConfig(array(
            'name' => '操作日志',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => FALSE,
            'list' => array(
                'hide_op_column' => TRUE,
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('op_title','op_username','create_time'),
                ),
                'options' => array(
                    'cb_optype' => array('update'=>'更新','delete'=>'删除','insert'=>'新增'),
                ),
                'optionname' => array(
                    "cb_optype" => "操作类型",
                ),
                'where' => $this->formatWhereCond(),
                'columns' => $column,
                'page_size' => 10,
                'sort' =>  'id DESC',
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }

    public function formatWhereCond()
    {
        $params = array();
        param_get(array(
            'cb_optype' => 'STRING',
        ), '', $params, array());
        $where = array(array(
            'op_title' => array(
                '!=' => ''
            ),
            Db_Sql::LOGIC => 'AND',
        ));
        if ($params['cb_optype']) {
            $tj = explode(',',$params['cb_optype']);
            $where = array(
                array('op_type' => $tj),
            );
        }
/*        if (!empty($params['adunit-daterange'])) {

            $date = explode('至', $params['adunit-daterange']);
            $where[] = array('date' => array(
                array('>=' => $date['0']),
                array('<=' => $date['1']),
                Db_Sql::LOGIC => 'AND',
            ),
            );

        }*/
        return $where;

    }




}

class MyScoffoldHelper extends CommonScaffoldHelper {

    public function cb_optype($item){
        switch ($item['op_type']){
            case 'update':
                return '更新';
                break;
            case 'delete':
                return '删除';
                break;
            case 'insert':
                return '新增';
                break;
            default:
                return '--';
                break;
        }
    }
}