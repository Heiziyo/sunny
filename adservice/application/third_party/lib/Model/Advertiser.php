<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/17
 * Time: 12:39
 */

class Model_Advertiser extends Model_Handler{

    public function __construct()
    {
        parent::__construct('product', 'ad_service');
    }

}