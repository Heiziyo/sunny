<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2016/12/17
 * Time: 12:39
 */
class Members extends MY_Controller
{

    public $classData;
    public $schoolData;

    public function __construct()
    {
      //  $this->classData = F::$f->Model_Classes->select();
     //   $this->schoolData = F::$f->Model_School->getMap();
        parent::__construct(TRUE, '会员列表');

        $this->data['c_menu'] = 'system';
        $this->data['get_param']=$_GET;
        $m = F::$f->Model_HuiYuan;

        $column = array(
            '序号' => 'id',
            '姓名' => 'realname',
            '学校' => 'cb_school',
            '年级' => 'cb_grade',
            '班级' => 'cb_classnum',
            '性别' => 'cb_sex|show',
            '手机' => 'mobile|show',
            '身份类型' => 'membertype|show',
        );

        $this->rules = array(
            array(
                'field' => 'realname',
                'label' => '姓名',
               // 'rules' => 'required',
                'exp' => ''
            ),
            array(
                'field' => 'mobile',
                'label' => '手机',
                'rules' => 'valid_mobile',
            ),
            array(
                'field' => 'sex',
                'label' => '性别',
                'rules' => 'required',
                'type'   => array('请选择','男','女'),
            ),
            array(
                'field' => 'membertype',
                'label' => '身份类型',
                'rules' => 'required',
                'type'   => array('guest'=>'游客','parents'=>'家长','teacher'=>'老师'),
            ),
        );

        $this->_setConfig(array(
            'name' => '会员',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => false,
            'can_delete' => false,
            'fields' => $this->rules,
            'list' => array(
                'showCanSel' => TRUE,
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('realname','mobile'),
                ),
                'options' => array(
                    'membertype' => array('guest'=>'游客','parents'=>'家长','teacher'=>'老师'),
                 //   'schoolid'=>$this->schoolData
                ),
                'optionname' => array(
                    "membertype" => "身份类型",
                 //   "schoolid" => "学校"
                ),
                'where' => $this->formatWhereCond(),
                'columns' => $column,
                'page_size' => 10,
                'sort' => d( 'id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }

    public function formatWhereCond()
    {
        $params = array();
        param_get(array(
            'membertype' => 'STRING',
         //   'schoolid' => 'STRING',
        ), '', $params, array());
        $where = array(array(
            Db_Sql::LOGIC => 'AND',
        ));

        if ($params['membertype']) {
            $tj = explode(',',$params['membertype']);
            $where = array(
                array('membertype' => $tj),
            );
        }

        return $where;

    }


}

class MyScoffoldHelper extends CommonScaffoldHelper {

    public $classes;

    public function cb_sex($item){
        $map = array('未知','男','女',''=>'--');
        return d(@$map[$item['sex']],'--');
    }
    public function cb_school($item){
        $schoolName = F::$f->Model_Classes->getSchoolName($item['id']);
        return d($schoolName,'--');

    }


    public function cb_grade($item){
        $this->classes = F::$f->Model_classes->getMap($item['id']);
        return d($this->classes['grade'],'--');
    }

    public function cb_classnum($item){
        $this->classes = F::$f->Model_classes->getMap($item['id']);
        return d($this->classes['classnum'],'--');
    }

}