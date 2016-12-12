<?php
class Model_Department extends Model_Handler
{

    public function __construct()
    {
        parent::__construct('department', 'ad_service');
    }

    public static function get($ids, $useCache = TRUE)
    {
        return self::_get(__CLASS__, $ids, $useCache);
    }

    public function getMap($option = array(), $isSelect = FALSE)
    {

        if(empty($option)){
            $option = array(
                'status' => Model_User::STATUS_ACTIVE,
            );

        }else{
            $option = array_merge(array('status' => Model_User::STATUS_ACTIVE), $option);
        }

        $attr = array('select' => 'id, name');

        $departmentMap   = array();
        if($isSelect){
            $departmentMap   = array('0' => '请选择');
        }

        $list = $this->select($option, $attr);
        foreach($list as $department) {
            $departmentMap[$department['id']] = $department['name'];
        }
        return $departmentMap;
    }
}