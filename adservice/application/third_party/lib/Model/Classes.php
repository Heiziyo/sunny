<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/28
 * Time: 11:21
 */
class Model_Classes extends Model_Handler
{
    public function __construct()
    {
        parent::__construct('classes', 'ad_service');
    }

    public function getMap($id)
    {
        if(empty($id)){
            return false;
        }
        $option = array(array('memberid' => $id));
        $rows = $this->selectOne($option);
        return $rows;
    }

    public function getSchoolName($id)
    {
        if(empty($id)){
            return false;
        }
       $classData = $this->getMap($id);
        if(empty($classData) || !$classData['schoolid']){
            return false;
        }
       $schoolid = $classData['schoolid'];
        $option = array(array('id' => $schoolid));
        $rows = F::$f->Model_School->selectOne($option);
        if(empty($rows) || !$rows['schoolName']){
            return false;
        }
        return $rows['schoolName'];
    }


}