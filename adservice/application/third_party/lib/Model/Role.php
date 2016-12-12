<?php
class Model_Role extends Model_Handler
{

    public function __construct()
    {
        parent::__construct('role', 'ad_service');
    }

    public static function get($ids, $useCache = TRUE)
    {
        return self::_get(__CLASS__, $ids, $useCache);
    }

    public  function getMap($option = array(), $isSelect = FALSE){
        $defaultOption = array(
            'status' => Model_User::STATUS_ACTIVE
        );

        if(empty($option)){
            $option = $defaultOption;
        }else{
            $option = array_merge($defaultOption, $option);
        }

        $attr = array('select' => 'id, name');

        $userMap   = array();
        if($isSelect){
            $userMap = array('' => '请选择');
        }

        $users = $this->select($option, $attr);
        if($users) {
            foreach ($users as $user) {
                $userMap[$user['id']] = $user['name'];
            }
        }

        return $userMap;
    }
}