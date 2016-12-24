<?php
/**
 * Created by PhpStorm.
 * User: heizi
 * Date: 2016/12/25
 * Time: 0:55
 */

class Model_Schoole extends Model_Handler{

    public function __construct()
    {
        parent::__construct('vm_schoole', 'ad_service');
    }

    public function getMap($option)
    {

        if(!$option) return array();

//        $attr = array('select' => 'date, fee', 'order_by' => 'date ASC');
//
//        $map = array();
//        $rows = $this->select($option, $attr);
//        foreach($rows as $row) {
//            $map[$row['date']] = $row;
//        }

        //$sql = "select * from school as a LEFT JOIN classes as b ON a.id = b.schoolid WHERE b.memberid = ".$option['productid'];
        $sql = "select c.schoolName as choolename from product as a LEFT JOIN classes as b on a.memberid = b.memberid LEFT JOIN school as c on b.schoolid = c.id WHERE a.id= ".$option['productid'];
        $data = $this->execute($sql);
        return $data;
    }
}