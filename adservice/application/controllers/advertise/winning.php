<?php
/**
 * Created by PhpStorm.
 * User: heizi
 * Date: 2016/12/26
 * Time: 19:44
 */

class Winning extends MY_Controller
{

    public function __construct()
    {
        parent::__construct(TRUE, '作品管理');

        $this->data['c_menu'] = 'advertise';
        $this->data['get_param'] = $_GET;
        $m = F::$f->Model_Winning;

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
            array(
                'field' => 'prise',
                'label' => '奖项',
                'rules' => 'required'
            ),
        );

        $column = array(
            '__checkbox__' => 'id',
            '作品名称' => 'productname|show',
            '作品' => 'cb_getimg|show',
            '作者名称' => 'cb_name|show',
            '指导老师' => 'teachername|show',
            '学校' => 'schoolName|show',
            '奖项' => 'prise|show',
        );

        $this->_setConfig(array(
            'primary_key' => 'id',
            'name' => '所有获奖作品',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => false,
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

    public function edit(){

        if ($this->isAjax) {
            $this->form_validation->set_rules($this->rules);
            if (!$this->form_validation->run()) {
                return $this->_fail(validation_errors());
            }

            $param = array();
            param_request(array(
                'id'=>'UINT',
                'productname' => 'STRING',
                'teachername' => 'STRING',
                'prise' => 'STRING',
            ), '', $param, array());


            $data1['productname'] = $param['productname'];
            $data1['teachername'] = $param['teachername'];
            $data2['prise'] = $param['prise'];
            $update = F::$f->Model_Winning->update(array('id'=>$param['id']), $data1);
            if ($update) {
                $data = array(
                    'redirect_uri' => '/advertise/winning',
                    'msg' => '更新成功'
                );
                $update2 = F::$f->Model_Winning->update(array('id'=>$param['id']), $data2);
                if(!$update2){
                    $this->_fail(json_encode($data2).'更新失败');
                }

                $this->_succ('ERR_MSG_REDIRECT', $data);
            } else {
                $this->_fail('更新失败');
            }
        } else {
            parent::edit();
        }

    }

}


class MyScoffoldHelper extends CommonScaffoldHelper {
    public function cb_name($item){
        return d($item['realname'],$item['nickname'],'--');
    }
    public function cb_getimg($item){
        if (!file_exists($item['thumbnail'])){
            return d("<img src='/images/top_logo.jpg' width='150' height='80'>");
        }
        return d("<img src='".$item['thumbnail']."'>");

    }


}