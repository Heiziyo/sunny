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
        parent::__construct('vm_entries', 'ad_service');
    }



}