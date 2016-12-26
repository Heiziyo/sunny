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
            '作品' => 'cb_getimg|show',
            '作者名称' => 'cb_name|show',
            '指导老师' => 'teachername|show',
            '学校' => 'cb_school|show',
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
                'columns' => $column,
                'page_size' => 10,
                'sort' => d($sortFields, 'id DESC'),
            ),
            'helper' => new MyScoffoldHelper($m),
        ));

    }

    public function export(){
        param_get(
            array(
                'id' => 'STRING',
            ),'', $params, array()
        );
        $ids = $params['_GET']['id'];
        $m = F::$f->Model_Advertiser;
        $DATA = $m->select(array('id' => explode(',',$ids)),array('select'=>'id'));
        $title = array('ID');
        Export::exportExcel($title,$DATA,'作品列表','advertise.xlsx');
    }


    //参赛作品
    public function competition(){
        $this->data['c_menu'] = 'advertise';
        $this->data['get_param']=$_GET;


    }
    //获奖作品
    public function winning(){



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
        $m = F::$f->Model_schoole;
        $data = $m->getMap(array('productid'=>$productId));
        return $data[0]['choolename'];
    }

    public function beforeListTableFootRender(){
        $html = <<<HTML
    <tr class="dark">
        <td colspan="100">
            <input type="checkbox" class="sel-all"/>
            <a class='label label label-info' onclick="exports();">导出</a>
        </td>
    </tr>
<script language="JavaScript" type="application/javascript">
        function exports(){
            var chk_value =[]; 
            $('input[class="sel-item"]:checked').each(function(){ 
            chk_value.push($(this).val()); 
            }); 
            if(chk_value == ''){
                alert('请选择需要导出的数据！');
                return false;
            }
           location.href = "/advertise/advertise/export?id="+chk_value;//location.href实现客户端页面的跳转
           
        }
</script>
HTML;
        echo $html;
    }

    public function beforeSearchFormRender(){
        $html =<<<SCREEN
        
    <tr class="dark">
        <td colspan="100">
           省：<select>
                
           </select>
        </td>
        <td colspan="100">
           市：<select>
           
           </select>
        </td>
        <td colspan="100">
           区：<select>
           
           </select>
        </td>
        <td colspan="100">
           学校：<select>
           
           </select>
        </td>
    </tr>


SCREEN;


        echo $html;
        
        
        
        



    }



}