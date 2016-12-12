<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/25
 * Time: 14:42
 */
abstract class Model_Handler extends Model_Cacheable
{
    protected $COLUMN_ID  = 'id';
    public function __construct($table = NULL, $clusterId = NULL)
    {
        parent::__construct($table, $clusterId);
        $this->addEventHandler(new Db_ModificationLog(Factory::$f->Db_Model('data_version', 'ad_service'),$this->COLUMN_ID));
    }
}