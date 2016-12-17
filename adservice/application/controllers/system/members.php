<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2016/12/17
 * Time: 12:39
 */
class Members extends MY_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE, '会员列表');

        $this->data['c_menu'] = 'system';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_HuiYuan;

        $column = array(
            'Id' => 'id',
            '姓名' => 'realname',
            '昵称' => 'nickname|show',
            '电话' => 'mobile|show',
            '身份' => 'cb_membertype|show',
        );

        $this->_setConfig(array(
            'name' => '广告主',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => $this->havePrivilege('acCreate'),
            'can_edit' => $this->havePrivilege('acEdit'),
            'can_delete' => $this->havePrivilege('acDelete'),
          //  'fields' => $this->rules,
            'list' => array(
                'showCanSel' => TRUE,
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('realname','nickname','mobile'),
                ),
                'columns' => $column,
                'page_size' => 10,
                'sort' => d( 'id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }


}

class MyScoffoldHelper extends CommonScaffoldHelper {

    public function cb_membertype($item){
        $map = array('');
    }
}