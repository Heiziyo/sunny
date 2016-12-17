<?php


class Advertise extends MY_Controller {

    public function __construct()
    {
        parent::__construct(TRUE, '投放管理');

        $this->data['c_menu'] = 'advertise';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_Advertiser;
        $sortFields = '';
        $this->rules = array(
            array(
                'field' => 'name_zh',
                'label' => '中文名',
                'rules' => 'required'
            ),
            array(
                'field' => 'name_en',
                'label' => '英文名',
                'rules' => 'required',
            ),
            array(
                'field' => 'name_full',
                'label' => '全称',
                'rules' => 'required'
            )
        );

        $column = array(
            '中文名' => 'name_zh',
            '英文名' => 'name_en|show',
            '全称' => 'name_full|show',
            '{sort|update_time}更新时间' => 'cb_update_time|show',
        );

        $this->_setConfig(array(
            'primary_key'=>'entry_id',
            'name' => '广告主',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => $this->havePrivilege('acCreate'),
            'can_edit' => $this->havePrivilege('acEdit'),
            'can_delete' => $this->havePrivilege('acDelete'),
            'delete_alias' => array(
                'field' => 'status',
                'value' => Db_Model::STATUS_DELETE,
            ),
            'fields' => $this->rules,
            'list' => array(
                'showCanSel' => TRUE,
                'keyword' => array(
                    '=' => 'entry_id',
                    'like' => array('name_zh'),
                ),
                'columns' => $column,
                'page_size' => 10,
                'sort' => d($sortFields, 'entry_id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }

}