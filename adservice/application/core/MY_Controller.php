<?php

class MY_Controller extends BackendController
{
    const ROLE_SUBADMIN = 2;
    const ROLE_SALE = 3;
    const ROLE_SALE_ZHU = 4;
    const ROLE_MEDIA = 5;
    const ROLE_MEMBER = 6;
    const ROLE_GUEST = 7;
    const ROLE_INTERCON = 8;
    const PAGE_SIZE = 10;

    protected $user = NULL;
    protected $checkLogin = TRUE;
    public $other = array();
    public $commonAction = array(
        'index', 'login', 'logout', 'forget', 'report_reporting'
    );

    public $_menus = array(
        '投放管理' => array(
            'css' => 'top_nav_01',
            'url' => '/advertise/campaign',
            'name' => 'advertise',
            'permission' => TRUE,
            'sub' => array(
                '投放列表' => array(
                    'url' => '/advertise/campaign',
                    'name' => 'campaign',
                    'permission' => TRUE,
                ),
                '广告主' => array(
                    'url' => '/advertise/advertisers',
                    'name' => 'advertisers',
                    'permission' => TRUE,
                ),
                '订单列表' => array(
                    'url' => '/advertise/orders',
                    'name' => 'orders',
                    'permission' => TRUE,
                ),
                '素材列表' => array(
                    'url' => '/advertise/units',
                    'name' => 'units',
                    'permission' => TRUE,
                ),

            ),
        ),

        '媒体管理' => array(
            'css' => 'top_nav_01',
            'url' => '/media/media',
            'name' => 'media',
            'permission' => TRUE,
            'sub' => array(
                '媒体列表' => array(
                    'url' => '/media/media',
                    'name' => 'media',
                    'permission' => TRUE,
                ),
                '广告位列表' => array(
                    'url' => '/media/adlist',
                    'name' => 'adlist',
                    'permission' => TRUE,
                )

            ),
        ),
        '报表管理' => array(
            'css' => 'top_nav_05',
            'url' => '/report/reporting',
            'name' => 'report',
            'permission' => TRUE,
            'sub' => array(
                '实时概况' => array(
                    'url' => '/report/reporting',
                    'name' => 'reporting',
                    'permission' => TRUE,
                ),
                '广告效果' => array(
                    'url' => '/report/campaigns',
                    'name' => 'campaigns',
                    'permission' => TRUE,
                    'sub' => array(
                        '投放报表' => array(
                            'url' => '/report/campaigns',
                            'name' => 'campaigns',
                            'permission' => TRUE,
                        ),
                        '地域报表' => array(
                            'url' => '/report/geo',
                            'name' => 'geo',
                            'permission' => TRUE,
                        ),
                        '设备报表' => array(
                            'url' => '/report/device',
                            'name' => 'device',
                            'permission' => TRUE,
                        ),
                        '频次报表' => array(
                            'url' => '/report/grp',
                            'name' => 'grp',
                            'permission' => TRUE,
                        ),
                        /*                        '分时报表' => array(
                                                    'url' => '/report/time',
                                                    'name' => 'time',
                                                    'permission' => TRUE,
                                                )*/

                    )
                ),
                '媒体效果' => array(
                    'url' => '/report/publication',
                    'name' => 'publication',
                    'permission' => TRUE,
                    'sub' => array(
                        '媒体报表' => array(
                            'url' => '/report/publication',
                            'name' => 'publication',
                            'permission' => TRUE,
                        ),
                        '流量库存管理' => array(
                            'url' => '/report/inventory',
                            'name' => 'inventory',
                            'permission' => TRUE,
                        ),
                    )

                )
            ),
        ),

        '系统管理' => array(
            'css' => 'top_nav_05',
            'url' => '/system/user',
            'name' => 'system',
            'permission' => TRUE,
            'sub' => array(
                '账号管理' => array(
                    'url' => '/system/user',
                    'name' => 'user',
                    'permission' => TRUE,
                ),
                '角色管理' => array(
                    'url' => '/system/role',
                    'name' => 'role',
                    'permission' => TRUE,
                ),
                '操作日志' => array(
                    'url' => '/system/oplog',
                    'name' => 'oplog',
                    'permission' => TRUE,
                ),
            ),
        ),
    );

    public function __construct($checkLogin = TRUE, $pageTitle = '')
    {
        $optionsAry['"plugin_sum"'] = '{"group":"日期"}';
        $this->checkLogin = $checkLogin;
        parent::__construct($checkLogin, F::$f->Model_User, FALSE);

        if (!empty($this->data['myuid'])) {
            if ($this->data['me']['status'] != Model_User::STATUS_ACTIVE) {
                Session::getInstance()->clearUserID();
                redirect('/login');
            }
        }

        $this->_setModuleDir('');

        $this->load->helper('view');

        $this->data['c_menu'] = $this->router->class;

        $this->data['c_submenu'] = implode('.', array(
            trim($this->router->directory, '/'),
            $this->router->class,
            $this->router->method
        ));

        $this->data['breadLine'] = array();

        if ($pageTitle) {
            if (is_array($pageTitle)) {
                $breadLine = $pageTitle;
                $pageTitle = array_pop($breadLine);

                $this->data['breadLine'] = $breadLine;
            }

            $this->_setPageTitle($pageTitle);

            $pageUrl = '/' . implode('/', array_filter(array(
                    trim($this->router->directory, '/'),
                    $this->router->class
                )));

            $this->_setBreadLine($pageTitle, $pageUrl);
        }

        $this->isSuperAdmin = FALSE;
        $data = &$this->data;
        $data['CURRENT_GROUP'] = trim($this->router->directory, '/');//当前分组
        $data['CURRENT_MOUDLE'] = $this->router->class;//当前模块
        $data['CURRENT_ACTION'] = $this->router->method;//当前动作

        //当前模块权限判断
        $this->canOperateDir = $this->checkCanOperateDir();

        //判断目录权限
        $data['_menus'] = $this->checkMenu();
    }

    protected function _setConfig($config)
    {
        if(!isset($config['can_create'])){
            $config['can_create'] = $this->havePrivilege('acCreate');
        }
        if(!isset($config['can_edit'])){
            $config['can_edit'] = $this->havePrivilege('acEdit');
        }
        if(!isset($config['can_delete'])){
            $config['can_delete'] = $this->havePrivilege('acDel');
        }
        $this->_config = $config;

        if(!isset($this->_config['list']['hide_op_column'])){
            $this->_config['list']['hide_op_column'] = $this->checkColumn($this->other);
        }
        $this->_initConfig();
    }
    /*
     *此方法主要验证list的操作这一列的显示
     *$other是指除了删除和编辑按钮外，还有别的按钮需要操作这一列
     */
    protected function checkColumn($other = array()){
        if(is_array($other) && !empty($other)){
            foreach ($other as $val ){
                if ($this->havePrivilege($val)){
                    return false;
                }
            }
        }
        $default = !($this->_config['can_delete'] || $this->_config['can_edit']);
        return $default;
    }

    protected function checkMenu($menus = NULL)
    {

        if (!$menus) $menus = $this->_menus;
        foreach ($menus as &$menu) {
            if (!$this->hasMenuPermission($menu['name'])) {
                $menu['permission'] = FALSE;
            }
            $subMenus = &$menu['sub'];
            foreach ($subMenus as &$sub) {
                if (!$this->hasSubMenuPermission(implode('_', array($menu['name'], $sub['name'])))) {
                    $sub['permission'] = FALSE;
                }
                if (!empty($sub['sub'])) {
                    $subSubMenus = &$sub['sub'];
                    foreach ($subSubMenus as &$child) {
                        if (!$this->hasSubMenuPermission(implode('_', array($menu['name'], $sub['name'], $child['name'])))) {
                            $child['permission'] = FALSE;
                        }
                    }
                }
            }
        }

        return $menus;
    }

    protected function checkCanOperateDir()
    {

        $superAdmin = Config::get('SuperAdmin');

        $user = $this->data['me'];
        $g = trim($this->router->directory, '/');
        $m = $this->router->class;

        if (!$this->data['me']) {
            if ($m != 'login' && $this->checkLogin) {
                $this->_onPermissionDeny('对不起，您没有登录');
            } else {
                $user['username'] = '';
            }
        }

        if (in_array($user['username'], $superAdmin)) {
            $this->isSuperAdmin = TRUE;
            return TRUE;
        } else {
            $roleModel = F::$f->Model_Role;
            if (!in_array($m, $this->commonAction)) {

                $privileges = $roleModel->select(array('id' => explode(',', $user['roles'])), array('select' => 'privilege'));
                $l1 = $l2 = $l2Tmp = $l3 = array();
                foreach ($privileges as $privilege) {
                    $privilege = json_decode($privilege['privilege'], TRUE);
                    foreach ($privilege as $p) {
                        if ($p['level'] === 0) {
                            $l1[$p['aid']] = TRUE;
                        } else if ($p['level'] === 1) {
                            $l2[$p['aid']] = TRUE;
                        } else if ($p['level'] === 2) {
                            //对于二级子菜单的特殊处理
                            if ($p['isDefault']) {
                                $l3[$p['aid']] = TRUE;
                            }else {
                                $l2[$p['aid']] = TRUE;
                                $aid = explode('_', $p['aid']);
                                if (count($aid) == 3) {
                                    $l2Tmp[implode('_', array($aid[0], $aid[2]))] = TRUE;
                                }
                            }
                        }
                    }
                }

                //针对多级模块的权限处理
                if (!empty($this->commonAction)) {
                    $commonActions = $this->commonAction;
                    $l1Tmp = $l2tmp = array();
                    foreach ($commonActions as $action) {
                        $actions = explode('_', $action);
                        if (count($actions) > 1) {
                            $l1Tmp[$actions[0]] = TRUE;
                            $l2tmp[$action] = TRUE;
                        }
                    }

                    if (!empty($l1Tmp) && !empty($l2tmp)) {
                        $l1 = array_merge($l1, $l1Tmp);
                        $l2 = array_merge($l2, $l2tmp);
                    }
                }

                $this->data['privilege'] = array(
                    'l1' => $l1,
                    'l2' => $l2,
                    'l3' => $l3
                );


                if (isset($l1[$g])) {
                    $gm = implode('_', array($g, $m));
                    if (isset($l2[$gm]) || isset($l2Tmp[$gm])) {
                        return TRUE;
                    }
                }

            } else {
                return TRUE;
            }

            //找当前权限内模块，顺位第一位
            foreach ($l2 as $key => $val) {
                $key = explode('_', $key);
                if ($key[0] == $g) {
                    if (count($key) == 2) {
                        $url = "/$g/" . $key[1];
                    } else {
                        $url = "/$g/" . $key[2];
                    }
                    redirect($url);
                }
            }

            $this->_onPermissionDeny();
        }
    }

    protected function hasMenuPermission($action)
    {
        if (!$action) {
            return FALSE;
        }
        $data = $this->data;
        $p = d(@$data['privilege']['l1'], array());
        if ($this->isSuperAdmin) {
            return TRUE;
        }

        if (isset($p[$action])) {
            return TRUE;
        }

        return FALSE;
    }

    protected function hasSubMenuPermission($action)
    {
        if (!$action) {
            return FALSE;
        }
        $data = $this->data;
        $p = d(@$data['privilege']['l2'], array());
        if ($this->isSuperAdmin) {
            return TRUE;
        }

        if (isset($p[$action])) {
            return TRUE;
        }

        return FALSE;
    }

    protected function havePrivilege($action)
    {

        if (!$action) return FALSE;

        $data = $this->data;
        $g = $data['CURRENT_GROUP'];
        $m = $data['CURRENT_MOUDLE'];
        $p = d(@$data['privilege']['l3'], array());

        if ($this->isSuperAdmin) {
            return TRUE;
        }

        if (isset($p[implode('_', array($g, $m, $action))])) {
            return TRUE;
        }

        return FALSE;

    }

    protected function _setBreadLine($name, $url = '')
    {
        $this->data['breadLine'][] = array($name, $url);
    }

    protected function _getUserInfo($uid)
    {
        return Model_User::get($uid);
    }

    protected function paramsFormatDate(&$params)
    {
        $formats = array('start_date', 'end_date', 'date');
        foreach ($params as $key => $val) {
            if (in_array($key, $formats)) {
                $params[$key] = Model_Schedule::stampToDate($val);
            }
        }
    }

    protected function getParseNodesByCpm(&$nodes, $cpm)
    {

        $tmp = array();
        if ($cpm >= 35 && $cpm < 45) {
            $tmp[] = $nodes[0];
        } else if ($cpm < 35) {
            $tmp[] = $nodes[0];
            $tmp[] = $nodes[1];
        } else {
            $tmp[] = $nodes[0];
        }
        $nodes = $tmp;
    }

    protected function getAuditingNodes($id, $people, $nodes, $type = 'customer', $cpm = NULL)
    {
        $logInfo = json_encode(func_get_args());

        if (empty($nodes)) return '';
        $nodes = explode(',', $nodes);

        if ($type == 'orders') {
            if ($cpm) $this->getParseNodesByCpm($nodes, $cpm);
        }

        $tmp = array();
        $index = 1;
        foreach ($nodes as $node) {
            $isNode = FALSE;
            if ($node == Model_ApprovalChain::DEP_MANAGER) {
                $user = F::$f->Model_User->get($people);
                $dep = F::$f->Model_Department->get($user['department']);
                if ($dep['manager_user_id'] && $people != $dep['manager_user_id']) {
                    $isNode = TRUE;
                    $tmp[$index] = array($dep['manager_user_id']);
                } else {
                    log_message('Auditing ' . $type . 'get nodes INFO:未找到部门领导，或者部门领导是自己(' . @$dep['manager_user_id'] . '),参数：' . $logInfo, LOG_ERR);
                }
            } else if ($node == Model_ApprovalChain::AREA_MANAGER) {
                $sale = F::$f->Model_Sales->selectOne(array('uid' => $people));
                if ($sale['team_leader'] && $people != $sale['team_leader']) {
                    $isNode = TRUE;
                    $tmp[$index] = array($sale['team_leader']);
                } else {
                    log_message('Auditing ' . $type . 'get nodes INFO:未找到团队主管，或者团队主管是自己(' . @$sale['team_leader'] . '),参数：' . $logInfo, LOG_ERR);
                }
            } else if ($node == Model_ApprovalChain::ASSISTANT) {
                //老板助理，这里这个ID可能要改
                $where = array(
                    'status' => Model_User::STATUS_ACTIVE,
                    'position' => Model_Position::getAssistantId()
                );
                $rows = F::$f->Model_User->select($where);
                if ($rows) {
                    $isNode = TRUE;
                    $allAssistant = array_get_column($rows, 'id');
                    if (!in_array($people, $allAssistant)) {
                        $tmp[$index] = $allAssistant;
                    } else {
                        log_message('Auditing ' . $type . ' get nodes INFO:自己就是老板助理,参数：' . $logInfo, LOG_ERR);
                    }
                } else {
                    log_message('Auditing ' . $type . 'get nodes INFO:未找到老板助理,参数：' . $logInfo, LOG_ERR);
                }
            } else {
                $isNode = TRUE;
                $tmp[$index] = array($node);
            }

            if ($isNode) {
                $index++;
            }
        }
        return $tmp;
    }

    public function getAuditData($type = 'customer')
    {
        $userId = $this->data['me']['id'];
        $workNodeModel = F::$f->Model_WorkNode;

        $option = array(
            'operator' => $userId,
            'handler_result' => Model_Customer::STATUS_WAITING,
            'type' => $type
        );

        $attr = array(
            'select' => 'belong'
        );

        $list = $workNodeModel->select($option, $attr);

        $ids = array();
        if ($list) {
            $ids = array_unique(array_get_column($list, 'belong'));
        }

        return $ids;
    }

    public function checkNodeAvailable($type = 'customer', $params)
    {
        if ($type == 'customer') {
            $info = '客户';
            $data = Model_Customer::get($params['id']);
        } else if ($type == 'orders') {
            $info = '订单';
            $data = Model_Orders::get($params['id']);
        }
        if (!$data) {
            log_message('Auditing ' . $type . ' Error:没有需要审批的对应 ' . $info . ',参数：' . json_encode($params), LOG_ERR);
            $this->_fail('没有需要审批的对应' . $info . ',请告知管理员核查');
        }

        $where = array(
            'belong' => $params['id'],
            'handler_result' => Model_Customer::STATUS_WAITING,
            'type' => $type
        );

        $nodes = F::$f->Model_WorkNode->select($where);

        if (!$nodes) {
            log_message('Auditing ' . $type . ' Error:未找到对应审批节点,参数：' . json_encode($params) . ',类型:' . $type, LOG_ERR);
            $this->_fail('未找到对应审批节点,请告知管理员核查');
        }

        if (count($nodes) > 1) {
            log_message('Auditing Error:当前待审批节点多余一个,参数：' . json_encode($params) . ',类型:' . $type, LOG_ERR);
            //$this->_fail('当前待审批节点多余一个，请告知管理员核查');
            $_node = NULL;
            $_otherNodes = array();
            foreach ($nodes as $node) {
                if ($node['operator'] == $this->data['me']['id']) {
                    $_node = $node;
                } else {
                    $_otherNodes[] = $node;
                }
            }
            if ($_node) {
                //删除其他节点
                foreach ($_otherNodes as $node) {
                    F::$f->Model_WorkNode->delete(array('id' => $node['id']));
                }
                return $_node;
            } else {
                log_message('Auditing ' . $type . ' Error:多个节点，不是当前用户审批,参数：' . json_encode($params) . ',类型:' . $type . '，用户：' . $this->data['me']['id'], LOG_ERR);
                $this->_fail('当前不是您审批，请告知管理员核查');
            }
        } else {
            if ($nodes[0]['operator'] != $this->data['me']['id']) {
                log_message('Auditing ' . $type . ' Error:不是当前用户审批,参数：' . json_encode($params) . ',类型:' . $type . '，用户：' . $this->data['me']['id'], LOG_ERR);
                $this->_fail('当前不是您审批，请告知管理员核查');
            }
            return $nodes[0];
        }

    }

    public function noticeNextNode($type = 'customer', $id, $curNode)
    {
        $curNodeIndex = $curNode['next_node'];
        if ($type == 'customer') {
            $info = '客户';
            $m = F::$f->Model_Customer;
            $data = Model_Customer::get($id);
        } else if ($type == 'orders') {
            $info = '订单';
            $m = F::$f->Model_Orders;
            $data = Model_Orders::get($id);
        }

        //当前已经是最后一个节点了。
        if ($curNode['next_node'] == '-1') {
            $m->update(array('id' => $id), array('auditing_status' => Model_Customer::STATUS_PASS));
            //通知发起人
            $this->sendCustomerMail($id, array($data['create_people']), 'PASS', $type);
            $this->_succ('操作成功');
        }

        $audUser = json_decode($data['auditing_user']);
        $nodes = get_object_vars($audUser);
        if (!isset($nodes[$curNode['next_node']])) {
            log_message('Auditing Error:下一审批节点不存在,参数：' . json_encode(func_get_args()) . ',类型:' . $type . '，用户：' . $this->data['me']['id'], LOG_ERR);
            $this->_fail('下一审批节点不存在，请告知管理员核查');
        }

        $nextNodes = $nodes[$curNode['next_node']];
        $receive = array();
        foreach ($nextNodes as $node) {
            $insertNode = array(
                'belong' => $id,
                'operator' => $node,
                'operator_tmp' => $node,
                'current_node' => $curNode['next_node'],
                'next_node' => isset($nodes[intval($curNode['next_node']) + 1]) ? intval($curNode['next_node']) + 1 : -1,
                'handler_result' => Model_Customer::STATUS_WAITING,
                'handler_mem' => '',
                'type' => $type
            );
            $receive[] = $node;
            F::$f->Model_WorkNode->insert($insertNode);
        }

        //统一发邮件发邮件
        if ($receive) {
            $receive = array_unique($receive);
            $this->sendCustomerMail($id, $receive, 'NOTICE', $type);
        }

        $this->_succ('操作成功');

    }

    public function denyCurAuditing($type = 'customer', $id, $curNode)
    {
        if ($type == 'customer') {
            $m = F::$f->Model_Customer;
            $data = Model_Customer::get($id);
        } else if ($type == 'orders') {
            $m = F::$f->Model_Orders;
            $data = Model_Orders::get($id);
        }
        $m->update(array('id' => $id), array('auditing_status' => Model_Customer::STATUS_DENT));
        $this->sendCustomerMail($id, array($data['create_people']), 'DENY', $type);
        $this->_succ('操作成功');
    }

    public function sendCustomerMail($id, $reviver, $ac, $dac)
    {
        $ac = strtoupper($ac);
        $dac = strtolower($dac);

        if ($dac == 'customer') {
            $type = '客户';
            $customer = Model_Customer::get($id);
            $mailContent = "名称：{$customer['name']},直客品牌：{$customer['brand']}";
            $host = getServerHost() . '/customer/receive_customer';
            $host1 = getServerHost() . '/customer/customer';
        } else if ($dac == 'orders') {
            $type = '订单';
            $order = Model_Orders::get($id);
            $campaign = Model_Campaign::get($order['campaign_id']);
            $customer = Model_Customer::get($campaign['customer_id']);
            $mailContent = "直客品牌：{$customer['brand']},活动名称：{$campaign['name']}";;
            $host = getServerHost() . '/orders/receive_orders';
            $host1 = getServerHost() . '/orders/orders';
        }
        $user = Model_User::get($customer['create_people']);

        switch ($ac) {
            case 'NOTICE':
                $content = $this->FLOW_MAIL_NOTICE_CONTENT;
                $content = str_replace('{type}', $type, $content);
                $content = str_replace('{puser}', $user['realname'], $content);
                $content = str_replace('{content}', $mailContent, $content);
                $content = preg_replace('/{host}/', $host, $content);
                break;
            case 'DENY':
                $content = $this->FLOW_MAIL_DENY_CONTENT;
                $content = str_replace('{type}', $type, $content);
                $content = str_replace('{time}', $customer['create_time'], $content);
                $content = str_replace('{puser}', $user['realname'], $content);
                $content = str_replace('{content}', $mailContent, $content);
                $content = preg_replace('/{host}/', $host1, $content);
                break;
            case 'PASS':
                $content = $this->FLOW_MAIL_PASS_CONTENT;
                $content = str_replace('{type}', $type, $content);
                $content = str_replace('{time}', $customer['create_time'], $content);
                $content = str_replace('{puser}', $user['realname'], $content);
                $content = str_replace('{content}', $mailContent, $content);
                $content = preg_replace('/{host}/', $host1, $content);
                break;
        }

        if ($content && $reviver) {
            $where = array(
                'id' => $reviver
            );
            $rows = F::$f->Model_User->select($where);
            if ($rows) {
                $rows = array_filter(array_get_column($rows, 'email'));
                //配合测试
                $rows = $this->getTestUserEmail($rows);

                sendMail($rows, $this->subject, $content);
            }
        }
    }

    public function getTestUserEmail($rows)
    {
        if (in_array(ENV, array('DEVELOPMENT', 'TEST'))) {
            $user = Model_User::get($this->data['me']['id']);
            $count = count($rows);
            $tmp = array();
            for ($i = 0; $i < $count; $i++) {
                $tmp[] = $user['email'];
            }
            return $tmp;
        }

        return $rows;
    }

    public function createUserSortKey($key)
    {
        $user = $this->data['me'];
        return implode('_', array($key, $user['id'], $user['username']));
    }

    public function dealSortField($mem_key)
    {

        $cacheSort = Cache_Memcache::sGet($mem_key);
        if ($cacheSort) {
            $cacheSort = json_decode($cacheSort, TRUE);
        } else {
            $cacheSort = array();
        }

        $data = array_merge($_GET, $_POST);
        $activeSort = NULL;
        $activeSortKey = NULL;
        if ($data) {
            foreach ($data as $key => $val) {
                if (strpos($key, 'sort_') !== FALSE) {
                    $key = substr($key, strlen('sort_'));
                    $cacheSort[$key] = $val;
                    $activeSort = array(
                        $key => $val
                    );
                    $activeSortKey = $key;
                }
            }
        }

        if ($cacheSort) {

            if (count($cacheSort) > 1 && isset($activeSort)) {
                unset($cacheSort[$activeSortKey]);
                $cacheSort = $activeSort + $cacheSort;
            }

            $sortAry = '';
            foreach ($cacheSort as $key => $val) {
                $sortAry[] = $key . ' ' . $val;
            }
            Cache_Memcache::sSet($mem_key, json_encode($cacheSort), SORT_EXP);
            return implode(',', $sortAry);
        }
    }

    public function checkUploadFileName()
    {
        $file = d(@$_FILES['files']['name'], '');
        if ($file) {
            if (preg_match("/[\!\@\#\$\%\^\&\*\+\=\~\`\*\%\(\)]+/i", $file)) {
                $err = '文件名包含特殊字符“!、@、#、$、%、^、&、*、+、=、~、`、*、%、(、)”';
                $this->_fail($err);
            }
        } else {
            $this->_fail('没有上传任何文件');
        }
    }

    public function uploadFile($isReturn = FALSE)
    {

        $this->checkUploadFileName();

        $subDir = date('/Y/m/d');
        $savePath = APP_PATH_SOURCE . Config::get('Upload.Dir') . $subDir;
        $upload = new FlowUploadUtil($savePath);
        if (!$upload->save()) {
            $err = $upload->getError(false);
            $this->_fail($err);
        }

        $filePath = $subDir . '/' . $upload->newName;

        if ($isReturn) {
            return $filePath;
        } else {
            $this->_succ('ok', $filePath);
        }
    }

    public function getMemcache()
    {
        $mem = Cache_Memcache::getInstance('default');
        _dump($mem);
    }


    /**
     * 日期时间转时间戳
     * @author  chenyu 2016-10-19
     */
    public function unixTime($date)
    {
        return preg_match('/-/', $date) ? strtotime($date) : $date;
    }

}

class CommonScaffoldHelper extends ScaffoldHelper
{

    public function beforeSearchBtnRender(&$config)
    {
        if (!isset($config['list']['columns'])) {
            return '';
        }

        $usrSel = array();
        if (isset($_GET['showFields'])) {
            $usrSel = $_GET['showFields'];
            if (!is_array($usrSel)) {
                $usrSel = preg_split('/,/', $usrSel);
            }
        }
        $columns = $config['list']['columns'];

        $tmp = array();
        $contents = array();
        foreach ($columns as $key => $col) {
            preg_match('@^\{(.+?)\}(.+)$@', $key, $ma);
            if (isset($ma[2])) {
                $columnName = $ma[2];
            } else {
                $columnName = $key;
            }

            $col = explode('|', $col);
            if (empty($usrSel)) {
                if (isset($col[1])) {
                    if ($col[1] == 'show') {
                        $checked = 'show';
                    } else if ($col[1] == 'hide') {
                        $checked = 'hide';
                    }
                } else {
                    $checked = 'show';
                }

            } else {
                if (in_array($key, $usrSel)) {
                    $checked = 'show';
                } else {
                    $checked = 'hide';
                }
            }

            $tmp[$key] = $col[0];
            if ($key !== '__checkbox__') {
                preg_match('@^(.+)(\(.+?\))@', $columnName, $ma);

                if ($ma) {
                    $columnName = $ma[1];
                }

                $contents[$key] = array(
                    $col[0], $checked, $columnName
                );
            }

        }

        $config['list']['columns'] = $tmp;
        $config['list']['columnSel'] = $contents;
    }

    public function _beforeSearchBtnRender(&$config)
    {

        if (!isset($config['list']['columns'])) {
            return '';
        }

        $usrSel = array();
        if (isset($_GET['showFields'])) {
            $usrSel = $_GET['showFields'];
        }

        $columns = $config['list']['columns'];

        $config['list']['columns'] = $columns;

        $content = '';
        $tmp = array();
        foreach ($columns as $key => $col) {
            preg_match('@^\{(.+?)\}(.+)$@', $key, $ma);
            if (isset($ma[2])) {
                $columnName = $ma[2];
            } else {
                $columnName = $key;
            }

            $col = explode('|', $col);
            if (empty($usrSel)) {
                if (isset($col[1])) {
                    if ($col[1] == 'show') {
                        $tmp[$key] = $col[0];
                        $checked = 'checked';
                    } else if ($col[1] == 'hide') {
                        $checked = '';
                    }
                } else {
                    $checked = 'checked';
                    $tmp[$key] = $col[0];
                }

            } else {
                if (in_array($key, $usrSel)) {
                    $tmp[$key] = $col[0];
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
            }

            $content .= '<label>
                <input name="showFields[]" type="checkbox" ' . $checked . ' value="' . $key . '">
                <span>' . $columnName . '</span>
            </label>';
        }

        $config['list']['columns'] = $tmp;

        $html = <<<HTML
     <div class="panel">
        <div class="panel-body">
            <span class="mr10">可选字段:</span>
            {$content}
        </div>
     </div>
HTML;

        //echo $html;
    }

    function editLink($config, $item, $title = '编辑')
    {
        if (@$config['can_edit'] !== FALSE && $this->canEdit($item) !== FALSE) {
            return '<a class="" href="/' . $config['controller_directory'] . $config['controller']
            . '/edit?' . $config['primary_key'] . '=' . $item[$config['primary_key']]
            . '&redirect_uri=' . urlencode(get_self_full_url())
            . '"><img src="/images/edit.png" width="21" height="18" style="margin-top:7px;"></a>';
        } else {
            return '';
        }
    }

    function deleteLink($config, $item, $title = '删除')
    {
        if (@$config['can_delete'] !== FALSE && $this->canDelete($item) !== FALSE) {
            return '<a href="javascript:;" class="del-btn" rel="'
            . $item[$config['primary_key']] . '"><img src="/images/del.png" width="21" height="18" style="margin-top:7px;"></a>';
        } else {
            return '';
        }
    }


    /**
     * @param $item
     * @author chenyu 2016-10-19
     */
    public function cb_update_time($item)
    {
        return $this->formTime($item['update_time']);
    }


    /**
     * @param $item
     * @author chenyu 2016-10-19
     */
    public function cb_start_time($item)
    {
        return $this->formTime($item['start_time']);
    }

    /**
     * @param $item
     * @author chenyu 2016-10-19
     */
    public function cb_end_time($item)
    {
        return $this->formTime($item['end_time']);
    }


    /**
     * 字符串时间格式化
     * @author chenyu 2016-10-18
     */
    public function formTime($time)
    {
        return date('Y-m-d H:i', $time);
    }


}