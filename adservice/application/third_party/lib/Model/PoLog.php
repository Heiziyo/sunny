<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/28
 * Time: 17:37
 */
class Model_PoLog extends Model_Handler
{
    public function __construct()
    {
        parent::__construct('data_version', 'ad_service');
    }
}