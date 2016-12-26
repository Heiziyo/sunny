<?php
/**
 * Created by PhpStorm.
 * User: heizi
 * Date: 2016/12/26
 * Time: 19:36
 */
class Competition extends MY_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE, '作品管理');

        $this->data['c_menu'] = 'advertise';
        $this->data['get_param'] = $_GET;
        $m = F::$f->Model_Competition;

        $sortFields = '';
        $this->rules = array(
            array(
                'field' => 'productname',
                'label' => '作品名称',
                'rules' => 'required'
            ),
            array(
                'field' => 'teachername',
                'label' => '指导老师',
                'rules' => 'required'
            ),
        );

        $column = array(
            '__checkbox__' => 'id',
            '作品名称' => 'cb_getproductname|show',
            '作品' => 'cb_getimg|show',
            '作者名称' => 'cb_getrelname|show',
            '指导老师' => 'cb_getteachername|show',
            '学校' => 'cb_getschoolName|show',
            '更新时间' => 'updatetime|show',
        );

        $this->_setConfig(array(
            'primary_key' => 'id',
            'name' => '所有参赛作品',
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
                    'like' => array('productname', 'nickname', 'realname'),
                ),
                'columns' => $column,
                'page_size' => 10,
                'sort' => d($sortFields, 'id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }

}


class MyScoffoldHelper extends CommonScaffoldHelper {

    public function cb_getproductname($item){
        $productId =$item['productid'];
        $m = F::$f->Model_Competition;
        $data = $m->getProductName(array('productid'=>$productId));
        return $data[0]['productname'];
    }

    public function cb_getrelname($item){
        $productId =$item['productid'];
        $m = F::$f->Model_Competition;
        $data = $m->getProductName(array('productid'=>$productId));
        return d($data[0]['realname'],"--");
    }
    public function cb_getteachername($item){
        $productId =$item['productid'];
        $m = F::$f->Model_Competition;
        $data = $m->getProductName(array('productid'=>$productId));
        return $data[0]['teachername'];
    }
    public function cb_getimg($item){
        $productId =$item['productid'];
        $m = F::$f->Model_Competition;
        $data = $m->getProductName(array('productid'=>$productId));

        if (!file_exists($data['thumbnail'])){
            return d("<img src='/images/top_logo.jpg' width='150' height='80'>");
        }
        return d("<img src='".$data['thumbnail']."'>");

    }
    //schoolName
    public function cb_getschoolName($item){
        $productId =$item['productid'];
        $m = F::$f->Model_Competition;
        $data = $m->getProductName(array('productid'=>$productId));
        return d($data[0]['schoolName'],"--");
    }
}