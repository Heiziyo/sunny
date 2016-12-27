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
            '序号' => 'id',
            '姓名' => 'realname',
            '学校' => 'cb_school',
            '年级' => 'cb_grade',
            '班级' => 'cb_calssnum',
            '性别' => 'sex|show',
            '手机' => 'mobile|show',
            '身份类型' => 'membertype|show',
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