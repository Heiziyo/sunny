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
    public function getProvince(){
        $Map   = array();
        $list = $this->select(array(),array('group_by'=>'province'));
        foreach($list as $row) {
            $Map[$row['province']] = $row['province'];
        }
        return $Map;
    }
    public function getCity(){
        $Map   = array();
        $list = $this->select(array(),array('group_by'=>'city'));
        foreach($list as $row) {
            $Map[$row['city']] = $row['city'];
        }
        return $Map;
    }
    public function getArea(){
        $Map   = array();
        $list = $this->select(array(),array('group_by'=>'district'));
        foreach($list as $row) {
            $Map[$row['district']] = $row['district'];
        }
        return $Map;
    }
    public function getSchool(){
        $Map   = array();
        $list = $this->select(array(),array('group_by'=>'schoolName'));
        foreach($list as $row) {
            $Map[$row['schoolName']] = $row['schoolName'];
        }
        return $Map;
    }
}