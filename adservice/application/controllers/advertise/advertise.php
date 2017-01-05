<?php


class Advertise extends MY_Controller {

    public function __construct()
    {
        parent::__construct(TRUE, '作品管理');

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
            array(
                'field' => 'teachername',
                'label' => '指导老师',
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
            '更新时间' => 'updatetime|show',
        );
        $this->_setConfig(array(
            'primary_key'=>'id',
            'name' => '所有作品',
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
                    'like' => array('productname','nickname','realname'),
                ),
                'options' => array(
                    'province' => F::$f->Model_School->getProvince(),
                    'city' => F::$f->Model_School->getCity(),
                    'area' => F::$f->Model_School->getArea(),
                    'school' => F::$f->Model_School->getSchool(),
                ),
                'where' => $this->formatWhereCond(),
                'optionname' => array(
                    "province" => "省",
                    "city" => "市",
                    "area"=>"区",
                    "school"=>'学校'
                ),
                'columns' => $column,
                'page_size' => 8,
                'sort' => d($sortFields, 'id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }

    public function export(){
        $m = F::$f->Model_Advertiser;
        $DATA = $m->select(array(),array('select'=>'id,productname,realname,teachername,schoolName,province,city,district'));
        //id 作品名 作者名 指导老师  学校  省.市.区
        $title = array('ID','作品名称','作者名称','指导老师','学校','省','市','区');
        Export::exportExcel($title,$DATA,'作品列表',date('Y-m-d',time()).'_所有作品.xlsx');
    }
    public function formatWhereCond()
    {
        $params = array();
        param_get(array(
            'province' => 'STRING',
            'city' => 'STRING',
            'area' => 'STRING',
            'school' => 'STRING',
        ), '', $params, array());
        $where = array(array(
            Db_Sql::LOGIC => 'AND',
        ));

        if ($params['province']) {
            $tj = explode(',',$params['province']);
            $where = array(
                array('province' => $tj),
            );
        }
        if ($params['city']) {
            $tj = explode(',',$params['city']);
            $where = array(
                array('city' => $tj),
            );
        }
        if ($params['area']) {
            $tj = explode(',',$params['area']);
            $where = array(
                array('area' => $tj),
            );
        }
        if ($params['school']) {
            $tj = explode(',',$params['school']);
            $where = array(
                array('school' => $tj),
            );
        }

        return $where;
    }

}
class MyScoffoldHelper extends CommonScaffoldHelper {
    public function cb_name($item){
        return d($item['nickname'],$item['realname'],'--');
    }
    public function cb_getimg($item){
        if (!file_exists($item['thumbnail'])){
            return d("<img src='/images/top_logo.jpg' width='150' height='80'>");
        }
        return d("<img src='".$item['thumbnail']."'>");

    }
    public function cb_school($item){
        $productId =$item['id'];
        $m = F::$f->Model_Schoole;
        $data = $m->getMap(array('productid'=>$productId));
        return $data[0]['choolename'];
    }

    public function beforeListTableFootRender(){
        $html = <<<HTML
    <tr class="dark">
        <td colspan="100">
            <input type="checkbox" class="sel-all"/>
            <a class='label label label-info' onclick="exports();">导出所有作品</a>
        </td>
    </tr>
<script language="JavaScript" type="application/javascript">
        function exports(){
            
           location.href = "/advertise/advertise/export";//location.href实现客户端页面的跳转
           
        }
</script>
HTML;
        echo $html;
    }
}