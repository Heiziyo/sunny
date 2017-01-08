<?php
/**
 * Created by PhpStorm.
 * User: heizi
 * Date: 2016/12/27
 * Time: 0:56
 */

class Statistics extends MY_Controller {

    public function __construct()
    {
        parent::__construct(TRUE, '作品管理');

        $this->data['c_menu'] = 'statistics';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_Click;

        $sortFields = '';
        $column = array(
            '登陆次数' => 'cb_member|show',
            '登陆时间' => 'updatetime|show',
        );
        $this->_setConfig(array(
            'primary_key'=>'id',
            'name' => '流量统计',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => false,
            'can_edit' => false,
            'can_delete' => false,
            'list' => array(
                'showCanSel' => TRUE,
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('updatetime'),
                ),
                'columns' => $column,
                'page_size' => 10,
                'sort' => d($sortFields, 'updatetime DESC, id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }




}
class MyScoffoldHelper extends CommonScaffoldHelper {

    public function cb_member($item){

       $res = F::$f->Model_HuiYuan->getMap($item['memberid']);
        return d($res['realname'],$res['nickname'],'--');
    }


}