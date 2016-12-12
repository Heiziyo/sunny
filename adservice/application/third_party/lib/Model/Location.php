<?php
class Model_Location extends Model_Handler
{
    const china = 'CN';
    public function __construct()
    {
        parent::__construct('location', 'ad_service');
    }
    
    public function getCN($location)
    {
        
        $str =  substr($location,2,2);
        if($str=='00'){
            $location2 = substr($location,0,2);
            $info = $this->selectOne(array('location2'=>$location2));
            $cn = $info['cn'];
            return $cn;
        }else{
            $info = $this->selectOne(array('location3'=>$str));
            $cn_city = $info['cn_city'];
            return $cn_city;
        }
    }

}