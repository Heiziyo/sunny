<?php
class BackendController extends ScaffoldController
{
    protected $_permissionTable = array(
        'roles' => array(
            self::ROLE_ADMIN => array(
                '__name' => '管理员',
                '__all',
            ),
        ),
        'public' => array(
            'login', 'logout', 'index'
        ),
    );

    protected $_userModel = NULL;

    public function __construct($checkLogin, $userModel, $enableUserPermission = TRUE)
    {
        $this->_userModel = $userModel;

        parent::__construct($checkLogin, $enableUserPermission);
    }

    protected function _getUserInfo($uid)
    {
        return $this->_userModel->find($uid);
    }

    protected function _getUserExtraData($userInfo)
    {
        if ( ! $userInfo) {
            return NULL;
        }

        return $userInfo['username'];
    }

    protected function _onSessionOk()
    {
        $this->data['myuid'] = $GLOBALS['myuid'] = $this->data['me']['id'];
        parent::_onSessionOk();
    }

    /**
     * 获取用户的角色
     */
    protected function _getUserRole($userInfo)
    {
        return isset($userInfo['roles']) ? $userInfo['roles'] : self::ROLE_ADMIN;
    }

    protected function _login()
    {
        $params = $this->_getPostParams(array(
            'username',
            'password',
        ));

        $username = trim($params['username']);
        $password = trim($params['password']);

        if (empty($username)) {
            return $this->_fail('请输入账号');
        }

        if (empty($password)) {
            return $this->_fail('请输入密码');
        }

        $expire = Config::get('Cookie.Expire', 3600);
        $uid = 0;
        $m = $this->_userModel;
        $isSuperUser = FALSE;

        if ( ! $m->selectCount(array('username' => $username))) {
            $this->_fail('账号不存在');
            return;
        }

        $passwordHash = md5(Config::get('Cookie.Salt') . $username . $password);

        $userInfo = $m->selectOne(array(
            'username' => $username,
            'password' => $passwordHash
        ));

        if (empty($userInfo)) {
            #@todo
            # 尝试下以前的错误版本，更新成正确的数据，一段时间后，该逻辑需要去除
            $oldPasswordHash = md5(Config::get('Cookie.Slat') . $username . $password);

            $userInfo = $m->selectOne(array(
                'username' => $username,
                'password' => $oldPasswordHash
            ));

            if ($userInfo) {
                $m->update(array('id' => $userInfo['id']), array('password' => $passwordHash));
            }
        }

        if (empty($userInfo)) {
            return $this->_fail('密码错误');
        }

        $uid = $userInfo['id'];
        $m->update(array('id' => $uid), array(
            'login_time' => '&/CURRENT_TIMESTAMP'
        ));

        $extraData = $this->_getUserExtraData($userInfo);
        $this->session->setUserID($uid, FALSE, $expire, $extraData);
        $this->_onLogin($userInfo);

        $this->_done();
    }

    protected function _onLogin($userInfo)
    {
        return;
    }
}
