<?php
class Model_Privilege extends Model_Handler{

    public static $defaultAction = array(
        array(
            'id' => 'acList',
            'name' => '列表',
            'open' => TRUE,
            'checked' => FALSE,
        ),
        array(
            'id' => 'acCreate',
            'name' => '增加',
            'open' => TRUE,
            'checked' => FALSE,
        ),
        array(
            'id' => 'acEdit',
            'name' => '编辑',
            'open' => TRUE,
            'checked' => FALSE,
        ),
        array(
            'id' => 'acDel',
            'name' => '删除',
            'open' => TRUE,
            'checked' => FALSE,
        ),
    );

    public function __construct()
    {
        parent::__construct('privilege', 'ad_service');
    }

    public function getCheckNodes($privilege, $isShort = TRUE){

        if(!$privilege){
            return '';
        }

        $l1 = $l2 = $l3 =  array();
        foreach($privilege as $p){
            if($p['level'] == '0'){
                $l1[$p['aid']] = TRUE;
            }else if($p['level'] == '1'){
                $l2[$p['aid']] = TRUE;
            }else if($p['level'] == '2'){
                $l3[$p['aid']] = TRUE;
            }
        }

        $allNodes = $this->getAllPriNodes(TRUE);
        $checks = array();
        foreach($allNodes as $key => &$node){
            if(isset($l1[$node['id']])){
                $children = $node['children'];
                $_children = array();
                if($children){
                    foreach($children as $cKey => &$child){
                        $childIndex = array(
                            $node['id'],
                            $child['id']
                        );
                        if(isset($l2[implode('_', $childIndex)])){
                            $cChildren = $child['children'];
                            $_cChildren = array();
                            if($cChildren) {
                                foreach ($cChildren as $cChild) {
                                    $cChildIndex = array(
                                        $node['id'],
                                        $child['id'],
                                        $cChild['id']
                                    );
                                    if (isset($l3[implode('_', $cChildIndex)])) {
                                        $cChild['checked'] = TRUE;
                                        $_cChildren[] = $cChild;
                                    }
                                }

                                if($isShort) {
                                    $_cChildren = array_map(function ($a){
                                        return $a['name'];
                                    }, $_cChildren);
                                    $_cChildren = array(
                                        array(
                                            'name' => implode(' | ', $_cChildren)
                                        )
                                    );
                                }

                                $child['children'] = $_cChildren;

                            }
                            $_children[] = $child;
                        }
                    }
                    $node['children'] = $_children;
                }
                $checks[] = $node;
            }
        }

        return $checks;

    }

    public function getAllPriNodes($noneKey = TRUE, $privilege = NULL){
        $where  = array(
            'status' => Model_User::STATUS_ACTIVE
        );
        $attrs = array(
            'order_by' => 'id DESC'
        );

        $rows = $this->select($where, $attrs);
        $nodes = array();
        if($rows){
            $l1 = $l2 = $l3 = array();
            if($privilege) {
                $privilege = json_decode($privilege, TRUE);
                foreach ($privilege as $p) {
                    if ($p['level'] == '0') {
                        $l1[$p['aid']] = TRUE;
                    } else if ($p['level'] == '1') {
                        $l2[$p['aid']] = TRUE;
                    } else if ($p['level'] == '2') {
                        $l3[$p['aid']] = TRUE;
                    }
                }
            }

            $da = self::$defaultAction;

            foreach($rows as &$row){

                if(!isset($nodes[$row['moudle_code']])){

                    $_checked = FALSE;
                    if(isset($l1[$row['moudle_code']])){
                        $_checked = TRUE;
                    }
                    $nodes[$row['moudle_code']] = array(
                        'id' => $row['moudle_code'],
                        'pid' => '0',
                        'aid' => $row['moudle_code'],
                        'name' => $row['moudle_name'],
                        'open' => TRUE,
                        'checked' => $_checked,
                        'children' => array()
                    );
                }

                if(!isset($nodes[$row['moudle_code']]['children'][$row['controller_code']])){
                    $_checked = FALSE;
                    if(isset($l2[$row['moudle_code'] . '_' . $row['controller_code']])){
                        $_checked = TRUE;
                    }

                    $isDefault = FALSE;
                    if(!empty($row['controller_sub'])){
                        $tmpDefaultAction = json_decode($row['controller_sub'], TRUE);

                    }else{
                        $isDefault = TRUE;
                        $tmpDefaultAction = $da;
                    }

                    $tmpDefaultAction = self::cChildrenFormatPid($tmpDefaultAction, $row['moudle_code'], $row['controller_code'], $l3, $isDefault);

                    $nodes[$row['moudle_code']]['children'][$row['controller_code']] = array(
                        'id' => $row['controller_code'],
                        'pid' => $row['moudle_code'],
                        'aid' => $row['moudle_code'] . '_' . $row['controller_code'],
                        'name' => $row['controller_name'],
                        'open' => TRUE,
                        'checked' => $_checked,
                        'children' => $tmpDefaultAction,
                    );
                }
            }

            if($noneKey) {
                $nodes = array_map(function ($a) {
                    if (!empty($a['children'])) {
                        $a['children'] = array_values($a['children']);
                    }
                    return $a;
                }, array_values($nodes));
            }

        }

        return $nodes;
    }

    public static function cChildrenFormatPid($children, $ppid, $pid, $privilege = NULL, $isDefault = FALSE){

        return array_map(function($a) use($pid, $ppid, $privilege, $isDefault){
            $a['pid'] = $ppid . '_' . $pid;
            if(!$isDefault){
                $a['id'] = $a['name'];
                $a['name'] = $a['realName'];
                $a['isDefault'] = 0;
                unset($a['realName']);
                $a['open'] = TRUE;
                $a['checked'] = FALSE;
            }else{
                $a['isDefault'] = 1;
            }

            $a['aid'] = implode('_', array($ppid, $pid, $a['id']));

            if(isset($privilege[$a['aid']])){
                $a['checked'] = TRUE;
            }
            return $a;
        }, $children);
    }

    public function truncate(){
        $db = $this->_getDbInstance();
        $dbh = $db->getDbWrite();
        return $dbh->query('TRUNCATE TABLE ' . $this->getTable());
    }

    public function getDbLog(){
        $db = $this->_getDbInstance();
        return $db->getWriteErrorInfo();
    }

}