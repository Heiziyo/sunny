<?php
class Role extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(TRUE, '角色');

        $this->data['c_menu'] = 'system';

        $m = F::$f->Model_Role;

        $this->_setConfig(array(
            'name' => '角色',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => $this->havePrivilege('acCreate'),
            'can_edit' => $this->havePrivilege('acEdit'),
            'can_delete' => $this->havePrivilege('acDel'),
            'delete_alias' => array(
                'field' => 'status',
                'value' => Model_User::STATUS_DELETE,
            ),
            'fields' => array(
                array(
                    'field' => 'name',
                    'label' => '名称',
                    'rules' => 'required|max_width[50]|unique_row[ad_service.role.name]',
                ),
                array(
                    'field' => 'privilege',
                    'label' => '权限',
                    'rules' => 'required',
                ),
            ),
            'list' => array(
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('name'),
                ),
                'columns' => array(
                    '名称' => 'name',
                    '权限' => 'cb_privilege',
                ),
                'page_size' => 10,
                'sort' => 'id ASC',
            ),
            'helper' => new MyScoffoldHelper($m, $this->data['me']),
        ));

    }

    public function synMenu(){
        $menus = $this->_menus;
        $m = F::$f->Model_Privilege;

        if(!empty($menus)){
            foreach($menus as $key => $menu){
                $name = $menu['name'];
                if(!empty($menu['sub'])){
                    $children = $menu['sub'];
                    foreach($children as $subKey => $child){
                        $cChildren = '';
                        if(!empty($child['sub'])){
                            foreach($child['sub'] as $cSubKey => $cChildren){
                                $child['sub'][$cSubKey]['realName'] = $cSubKey;
                            }

                            $cChildren = array_values($child['sub']);
                            $cChildren = json_encode($cChildren);
                        }

                        $insert = array(
                            'moudle_code' => $name,
                            'moudle_name' => $key,
                            'controller_code' => $child['name'],
                            'controller_name' => $subKey,
                            'controller_sub' => $cChildren
                        );

                        $replace = $insert;
                        unset($replace['moudle_code']);
                        unset($replace['controller_code']);

                        $m->insertReplace($insert, $replace);
                    }
                }
            }
        }

        $this->_succ('ok');
    }
}

class MyScoffoldHelper extends CommonScaffoldHelper {

    public function afterSearchFormRender(){
        $html = <<<HTML
        <a class="ml20 btn-primary btn" onclick="synMenu();">同步目录</a>
        <script type="text/javascript">
            function synMenu(){
                do_ajax('/system/role/synMenu', {id:1}, function(){
                    popup_msg('同步成功', 'success');
                });
            }
        </script>
HTML;
        echo $html;
    }

    public function cb_privilege($itme){
        $privilege =  json_decode($itme['privilege'], TRUE);
        $html = '';
        $allPrivilege = json_encode(F::$f->Model_Privilege->getCheckNodes($privilege));
        if($privilege){
            $html .= <<<HTML
            <ul id="priTree{$itme['id']}" class="ztree"></ul>
            <script type="text/javascript">
                var priSetting = {
                    check: {
                        enable: false
                    },
                    data: {
                        simpleData: {
                            enable: true
                        }
                    }
                },
                allPrivilege = $allPrivilege;
                zTreeObj = $.fn.zTree.init($("#priTree{$itme['id']}"), priSetting, allPrivilege);
            </script>
HTML;

        }
        return $html;
    }

}