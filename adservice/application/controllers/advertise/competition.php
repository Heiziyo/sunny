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
                'rules' => 'required',
                'attrs'   =>  F::$f->Model_Advertiser->getTeacher(),
            ),

        );

        $column = array(
            '__checkbox__' => 'id',
            '作品名称' => 'productname|show',
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
    public function export(){
        $m = F::$f->Model_Competition;
        $DATA = $m->select(array(),array('select'=>'id,productname,realname,teachername,schoolName,province,city,district'));
        //id 作品名 作者名 指导老师  学校  省.市.区
        $title = array('ID','作品名称','作者名称','指导老师','学校','省','市','区');
        Export::exportExcel($title,$DATA,'作品列表',date('Y-m-d',time()).'_所有参赛作品.xlsx');
    }
}


class MyScoffoldHelper extends CommonScaffoldHelper {

    public function cb_getrelname($item){

        return d($item['realname'],"--");
    }
    public function cb_getteachername($item){
         return d($item['teachername'],"--");
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
        return d($item['schoolName'],"--");
    }
    public function beforeListTableFootRender(){
        $html = <<<HTML
    <tr class="dark">
        <td colspan="100">
            <input type="checkbox" class="sel-all"/>
            <a class='label label label-info' onclick="exports();">导出所有参赛作品</a>
        </td>
    </tr>
<script language="JavaScript" type="application/javascript">
        function exports(){
            
           location.href = "/advertise/competition/export";//location.href实现客户端页面的跳转
           
        }
</script>
HTML;
        echo $html;
    }
}