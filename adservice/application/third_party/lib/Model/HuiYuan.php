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
    public function getMap($option = array(), $isSelect = FALSE){
        return $this->getApptypeMap($option, $isSelect);
    }

    public function getApptypeMap($id){

            $option = array(array('id' => $id));


        $map = array();

        $rows = $this->select($option);
        foreach($rows as $row){
            $map[$row['id']] = $row['nickname'];
        }
        return $map;
    }
}