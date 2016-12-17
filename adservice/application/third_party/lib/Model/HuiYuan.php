<?php

/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2016/12/17
 * Time: 13:07
 */
class Model_HuiYuan extends Model_Handler
{
    public function __construct()
    {
        parent::__construct('memberinfo', 'ad_service');
    }

    public function getMap($id)
    {
        if(empty($id)){
            return false;
        }
        $option = array(array('id' => $id));
        $rows = $this->selectOne($option);
        return $rows;
    }
}