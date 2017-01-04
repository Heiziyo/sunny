<?php
/**
 * Created by PhpStorm.
 * User: heizi
 * Date: 2016/12/27
 * Time: 0:53
 */

class School extends MY_Controller {

    public function __construct()
    {
        parent::__construct(TRUE, '学校管理');

        $this->data['c_menu'] = 'school';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_School;

        $sortFields = '';
        $this->rules = array(
            array(
                'field' => 'province',
                'label' => '省份',
                'rules' => 'required'
            ),
            array(
                'field' => 'city',
                'label' => '市',
                'rules' => 'required'
            ),
            array(
                'field' => 'district',
                'label' => '地区',
                'rules' => 'required'
            ),
            array(
                'field' => 'schoolName',
                'label' => '学校名称',
                'rules' => 'required|trim|strip_tags|max_width[50]|unique_row[school.schoolName]'
            ),
        );

        $column = array(
            '序号' => 'id',
            '省份' => 'province|show',
            '市' => 'city|show',
            '地区' => 'district|show',
            '学校名称' => 'schoolName|show',
            '更新时间' => 'updatetime|show',
        );
        $this->_setConfig(array(
            'primary_key'=>'id',
            'name' => '学校',
            'ajax' => FALSE,
            'model' => $m,
            'fields' => $this->rules,
            'list' => array(
                'showCanSel' => TRUE,
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('province','city','district','schoolName'),
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




}