<?php

/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2017/1/8
 * Time: 23:30
 */
class History extends MY_Controller {

    public function __construct()
    {
        parent::__construct(TRUE, '作品管理');

        $this->data['c_menu'] = 'statistics';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_Click;

        $sortFields = '';
        $column = array(
            '流量总数' => 'total|show',
        );
        $this->_setConfig(array(
            'primary_key'=>'id',
            'name' => '历史流量',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => false,
            'can_edit' => false,
            'can_delete' => false,
            'list' => array(
                'showCanSel' => TRUE,
                'attrs' =>array('select'=>'COUNT(click) as total'),
                'columns' => $column,
                'page_size' => 10,
                //'sort' => d($sortFields, 'updatetime DESC, id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }

}


class MyScoffoldHelper extends CommonScaffoldHelper {

}