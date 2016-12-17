<?php


class Advertise extends MY_Controller {

    public function __construct()
    {
        parent::__construct(TRUE, '添加作品');

        $this->data['c_menu'] = 'advertise';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_Advertiser;

        $sortFields = '';
        $this->rules = array(
            array(
                'field' => 'productname',
                'label' => '作品名称',
                'rules' => 'required'
            ),
        );

        $column = array(
            'id' => 'id',
            '作品名称' => 'productname|show',
            '作者名称' => 'cb_name|show',
            '指导老师' => 'teachername|show',
            '{sort|updatetime}更新时间' => 'updatetime|show',
        );

        $this->_setConfig(array(
            'primary_key'=>'id',
            'name' => '广告主',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => false,
            'can_edit' => $this->havePrivilege('acEdit'),
            'can_delete' => $this->havePrivilege('acDelete'),
//            'delete_alias' => array(
//                'field' => 'status',
//                'value' => Db_Model::STATUS_DELETE,
//            ),
            'fields' => $this->rules,
            'list' => array(
                'showCanSel' => TRUE,
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('productname'),
                ),
                "where"=>$this->fromWhere(),
                'columns' => $column,
                'page_size' => 10,
                'sort' => d($sortFields, 'id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }
    public function fromWhere(){

    }
}
class MyScoffoldHelper extends CommonScaffoldHelper {
    public function cb_name($item){
        $name = F::$f->Model_HuiYuan->getMap($item['memberid']);
        return d($name['nickname'],$name['realname'],'--');
    }



}