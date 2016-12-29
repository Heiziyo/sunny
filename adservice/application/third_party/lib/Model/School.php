<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/28
 * Time: 11:10
 */
class Model_School extends Model_Handler
{
    public function __construct()
    {
        parent::__construct('school', 'ad_service');
    }

    public function getMap()
    {
        $attr = array('order_by'=>'convert(province using gbk) asc,convert(city using gbk) asc,convert(district using gbk) asc,convert(schoolName using gbk) asc');

        $Map   = array();
        $option = array();
        $list = $this->select($option, $attr);
        foreach($list as $row) {
            $Map[$row['id']] = $row['province'].$row['city'].$row['district'].$row['schoolName'];
        }
        return $Map;
    }

}