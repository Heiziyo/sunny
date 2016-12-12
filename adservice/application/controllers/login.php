<?php
class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(FALSE);

        $this->_setModuleDir('');

        $this->load->helper('captcha');
    }
    
    public function captcha()
    {
        return create_captcha();
    }

    public function relogin()
    {

        Session::getInstance()->clearUserID();

        unset($_SESSION['lock_screen']);

        redirect('/login?redirect_uri=' . urlencode(d(@$_SESSION['last_page'], '/')));
    }

    public function index()
    {

    	# 进行登录验证
        if (check_form_hash('login')) {
            $this->_login();
        }
        
        # ticket 登录
        if (isset($_GET['ticket'])) {
            $this->_loginWithTicket();
        }

        if ($this->_needCaptcha()) {
            $this->data['showCaptcha'] = TRUE;
        }

        # 显示登录页面
        //$this->_view('login');
        $this->load->view('login');
    }
    
    protected function _processRedirectUri(&$redirectUri) 
    {
        if (preg_match('@/login@', $redirectUri)) {
            $redirectUri = '/';
        }
    }

    protected function _needCaptcha($set = NULL)
    {
        $key = 'member_need_captcha';
        
        if (is_null($set)) {
            return !empty($_SESSION[$key]);
        }

        $_SESSION[$key] = $set;

        return $set;
    }

    protected function _login()
    {
        $params = $this->_getPostParams(array(
            'username',
            'password',
            'captcha',
        ), array(
            'remember' => 'BOOL'
        ));

        $username = trim($params['username']);
        $password = trim($params['password']);
        $remember = $params['remember'];

        if (empty($username)) {

            return $this->_fail('请输入账号');
        }
        
        if (empty($password)) {

            return $this->_fail('请输入密码');
        }

        if ($this->_needCaptcha()) {
            if (empty($params['captcha'])) {

                return $this->_fail('请输入验证码');
            } else if (!validate_captcha($params['captcha'])) {

                return $this->_fail('验证码错误');
            } 
        }

        $expire = Config::get('Cookie.Expire', 3600);

        if ($remember) {
            $expire = 86400 * 7;
        }

        $uid = 0;
        $m = $this->_userModel;

        $user = new Model_Member($username);

        if ( ! $user->exists()) {
            
            if (@++$_SESSION['auth_fail'] > 3) 
            {
                $this->_needCaptcha(TRUE);
            }

            return $this->_fail('账号不存在');
        }

        if (!$user->checkPassword($password)) {

            if (@++$_SESSION['auth_fail'] > 3) {

                $this->_needCaptcha(TRUE);
            }

            return $this->_fail('密码错误');
        }
        
        if (!$user->isActive()) {
            
            return $this->_fail('该账号已停用');
        }

        $this->_needCaptcha(FALSE);

        $userInfo = $user->info;
        $uid = $userInfo['id'];
        $upt = array(
            'login_time' => '&/CURRENT_TIMESTAMP',
            'login_ip' => get_client_ip(),
        );

        $m->update(array('id' => $uid), $upt);
        
        $extraData = $this->_getUserExtraData($userInfo);
        $this->session->setUserID($uid, $remember, $expire, $extraData);
        $this->_onLogin($userInfo);
        $this->data['myuid'] = $uid;
        $this->data['me'] = $userInfo;

        $this->_dispatch();
    }


    protected function _loginWithTicket()
    {
        $ticket = $_GET['ticket'];
        
        $userInfo = Hz_Auth::getUserInfoByTicket($ticket);

        if (empty($userInfo)) {

            return $this->_fail('TICKET错误');
        }

        $uid = $userInfo['id'];
        $upt = array(
            'login_time' => '&/CURRENT_TIMESTAMP',
            'login_ip' => get_client_ip(),
        );

        F::$f->Model_User->update(array('id' => $uid), $upt);
        
        $expire = Config::get('Cookie.Expire', 3600);
        $extraData = $this->_getUserExtraData($userInfo);
        $this->session->setUserID($uid, FALSE, $expire, $extraData);
        $this->data['myuid'] = $uid;
        $this->data['me'] = $userInfo;

        $this->_dispatch();
    }

    private function _dispatch()
    {
        param_request(array(
            'appid' => 'UINT',
            'redirect_uri' => 'STRING',
        ));

        if (!empty($GLOBALS['req_appid']) && !empty($GLOBALS['req_redirect_uri'])) {

            $redirectUri = $GLOBALS['req_redirect_uri'];

            if (!preg_match('@^https?://@', $redirectUri)) {
                $redirectUri = '/';
            }

            redirect($redirectUri);

            return;
        }

        $this->_done();
    }
}