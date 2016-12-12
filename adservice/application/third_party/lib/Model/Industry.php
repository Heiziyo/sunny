<?php
class Model_Industry extends Model_Handler
{

    public function __construct()
    {
        parent::__construct('industry', 'ad_service');
    }

    public static function get($ids, $useCache = TRUE)
    {
        return self::_get(__CLASS__, $ids, $useCache);
    }

    public function getMap($option = array(), $isSelect = FALSE){
        return $this->getIndustryMap($option, $isSelect);
    }

    public function getIndustryMap($option = array(), $isSelect = FALSE){

        if(empty($option)){
            $option = array(
                'status' => Model_User::STATUS_ACTIVE,
            );
        }else{
            $option = array_merge(array('status' => Model_User::STATUS_ACTIVE), $option);
        }

        $map = array();
        if($isSelect){
            $map = array('' => '请选择');
        }

        $rows = $this->select($option);
        foreach($rows as $row){
            $map[$row['id']] = $row['name'];
        }
        return $map;
    }
}