<?php
class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(TRUE, '用户');

        $this->data['c_menu'] = 'system';

        $status = Model_User::$STATUS;
        unset($status[Model_User::STATUS_DELETE]);

        $roles = F::$f->Model_Role->getMap();

        $userList = F::$f->Model_User->getMap(array('id' => array('<>' => $this->data['me']['id']),'usertype'=>array('=' => Model_User::TYPE_USER)), TRUE);
        $positionList = F::$f->Model_Position->getMap(array(), TRUE);
        $depList = F::$f->Model_Department->getMap(array(), TRUE);

        $m = F::$f->Model_User;

        $this->_setConfig(array(
            'name' => '成员',
            'ajax' => FALSE,
            'model' => $m,
            'can_create' => has_permission('user.__post'),
            'can_edit' => has_permission('user.__post'),
            'can_delete' => has_permission('user.__post'),
            'delete_alias' => array(
                'field' => 'status',
                'value' => Model_User::STATUS_DELETE,
            ),
            'fields' => array(
                array(
                    'field' => 'username',
                    'label' => '账号',
                    'rules' => 'required|callback_special_username|max_width[50]|unique_row[Model_Member.user.username]',
                    'exp'   => '一般设为名字拼音',
                ),

                array(
                    'field' => 'new_password',
                    'label' => '密码',
                    'type'  => 'password',
                    'rules' => 'safe_password',
                    'exp'   => '密码长度8位以上，包含大小写，数字，特殊字符中三种或以上',
                ),

                array(
                    'field' => 'realname',
                    'label' => '姓名',
                    'rules' => 'required|max_width[50]',
                    'exp'   => '真实姓名',
                ),
                array(
                    'field' => 'mobile',
                    'label' => '手机',
                    'rules' => 'valid_mobile',
                ),
                array(
                    'field' => 'email',
                    'label' => '邮箱',
                    'rules' => 'required|valid_email',
                ),
                array(
                    'field' => 'qq',
                    'label' => 'QQ',
                    'rules' => 'numeric',
                ),
                array(
                    'field' => 'position',
                    'label' => '职位',
                    'rules' => 'required',
                    'type'  => $positionList,
                    'attrs' => 'select2'
                ),
                array(
                    'field' => 'department',
                    'label' => '部门',
                    'rules' => 'required',
                    'type'  => $depList,
                    'attrs' => 'select2'
                ),
                array(
                    'field' => 'higher',
                    'label' => '直属上级',
                    'type'  => $userList,
                    'attrs' => 'select2',
                ),
                array(
                    'field' => 'usertype',
                    'label' => '用户类型',
                    'rules' => 'required',
                    'type' => Model_User::$TYPES,
                ),
                array(
                    'field' => 'roles',
                    'label' => '系统角色',
                    'type' => 'checkbox',
                    'options' => $roles,
                    'rules' => 'required',
                ),
                array(
                    'field' => 'status',
                    'label' => '状态',
                    'rules' => 'required|callback_check_status',
                    'type'  => $status,
                ),
            ),
            'list' => array(
                'keyword' => array(
                    '=' => 'id',
                    'like' => array('realname', 'username'),
                ),
                'where' => array(
                    'status' => Model_User::STATUS_ACTIVE,
                    'usertype' => Model_User::TYPE_USER,
                ),
                'columns' => array(
                    'ID' => 'id',
                    '姓名' => 'realname',
                    '邮箱' => 'cb_email',
                    '电话' => 'cb_mobile',
                    '职位' => 'cb_position',
                    '部门' => 'cb_dep',
                    'QQ' => 'qq',
                    '用户类型' => 'cb_type',
                    '状态' => 'cb_status',
                ),
                'page_size' => 50,
                'sort' => 'usertype ASC, id DESC',
            ),
            'helper' => new MyScoffoldHelper($m, $this->data['me']),
        ));
    }

    public function special_username($account){

        if(preg_match("/[\da-z]+(-[A-Z]{1,3})?$/", $account)){
            return true;
        }else{
            $this->form_validation->set_message('special_username', '用户名只能包含字母，数字，由0-1个由“-”和大写字母组成的后缀结尾');
            return false;
        }
    }

    public function check_status($status)
    {
        if (!isset(Model_User::$STATUS[$status])) {

            $this->form_validation->set_message('check_status', '不存在的状态');

            return FALSE;
        }

        return TRUE;
    }
}

class MyScoffoldHelper extends CommonScaffoldHelper {

    public function cb_email($item){
        if($item['email']){
            return '<a href="mailto:' . $item['email'] . '" title="发送邮件">' . $item['email'] . '</a>';
        }
        return '--';
    }

    public function cb_mobile($item){
        if($item['mobile']){
            return '<a href="tel:' . $item['mobile'] . '" title="拨打电话">' . $item['mobile'] . '</a>';
        }
        return '--';
    }

    public function cb_type($item){
        $types = Model_User::$TYPES;
        return d($types[$item['usertype']], '--');
    }

    public function cb_status($item) {
        $statusName = Model_User::$STATUS[$item['status']];
        $statusStyle = Model_User::$STATUS_STYLE[$item['status']];

        return "<span class='label $statusStyle'>$statusName</span>";
    }

    public function cb_position($item){
        $positions = F::$f->Model_Position->getMap(array(), FALSE);
        return d(@$positions[$item['position']], '--');
    }

    public function cb_dep($item){
        $dep = F::$f->Model_Department->getMap(array(), FALSE);
        return d(@$dep[$item['department']], '--');
    }

    public function onUpdate(&$row, $origin)
    {
        if (isset($row['new_password'])) {
            $password = $row['new_password'];
            unset($row['new_password']);

            if (!empty($password)) {
                $user = new Model_Member($row['username']);
                $row['password'] = $user->getPasswordHash($password);
            }
        }

        if (!empty($row['position']) && !isset($row['department'])) {
            $position = Model_Position::get($row['position']);
            $row['department'] = $position['did'];
        }

        return TRUE;
    }

    public function onCreate(&$row)
    {
        $this->onUpdate($row, NULL);
        //$row['create_people'] = $this->data['id'];
        if (!isset($row['roles'])) {
            $row['roles'] = MY_Controller::ROLE_GUEST;
        }
    }
}
