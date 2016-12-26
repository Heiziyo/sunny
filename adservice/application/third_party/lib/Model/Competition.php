<?php
/**
 * Created by PhpStorm.
 * User: heizi
 * Date: 2016/12/26
 * Time: 23:55
 */

class Model_Competition extends Model_Handler{

    public function __construct()
    {
        parent::__construct('competitionproduct', 'ad_service');
    }


    public function getProductName($option){
        if(!$option) return array();

        $sql = "select a.thumbnail as thumbnail ,a.productname as productname,c.realname as realname,a.teachername  as teachername,e.schoolName as schoolName from product as a LEFT JOIN competitionproduct as b on a.id = b.productid LEFT JOIN memberinfo as c on a.memberid = c.id LEFT JOIN classes as d on c.id = d.memberid  LEFT JOIN school as e ON  d.schoolid = e.id WHERE a.productname != '' AND b.productid = ".$option['productid'];
        $data = $this->execute($sql);
        if (empty($data)){
            unset($data);
            return false;
        }
        return $data;

    }
}