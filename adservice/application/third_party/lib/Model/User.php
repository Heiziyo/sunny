<?php
class Model_User extends Model_Handler
{


    public static $STATUS = array(
        self::STATUS_ACTIVE => '正常',
        self::STATUS_DELETE => '删除',
        self::STATUS_PAUSE => '停用'
    );

    public static $STATUS_STYLE = array(
        self::STATUS_ACTIVE => 'label-success',
        self::STATUS_DELETE => '',
    );

    const TYPE_USER  = 0;
    const TYPE_AD = 1;

    public static $TYPES = array(
        self::TYPE_USER => '公司员工',
        self::TYPE_AD => '广告主'
    );

    public function __construct()
    {
        parent::__construct('user', 'ad_service');
    }

    public static function get($ids, $useCache = TRUE)
    {
        return self::_get(__CLASS__, $ids, $useCache);
    }

    public  function getMap($option = array(), $isSelect = FALSE, $appends = FALSE, $default = 0){
        $defaultOption = array(
            'status' => Model_User::STATUS_ACTIVE,
            'usertype' => Model_User::TYPE_USER
        );
        if(empty($option)){
            $option = $defaultOption;
        }else{
            $option = array_merge($defaultOption, $option);
        }

        $attr = array('select' => 'id, realname');

        $userMap   = array();
        if($isSelect){
            $userMap = array($default => '请选择');
        }

        if($appends && is_array($appends)){
            $userMap = array_merge($userMap, $appends);
        }

        $users = $this->select($option, $attr);
        if($users) {
            foreach ($users as $user) {
                $userMap[$user['id']] = $user['realname'];
            }
        }

        return $userMap;
    }

    public function getSelfCustomer($id){
        $item = self::get($id);
        $ids = array($id);
        if(!empty($item['rel_user']) && $id != $item['rel_user']){
            $ids[] = $item['rel_user'];
        }

        $where = array(
            'usertype'=> Model_User::TYPE_CUSTOMER,
            'create_people' => $ids
        );

        return $this->getMap($where, TRUE);
    }

    public function getSubordinates($id, $isActive = TRUE, $andMe = FALSE){
        if($isActive) {
            $where = array(
                'higher' => $id,
                'status' => Model_User::STATUS_ACTIVE,
            );
        }else{
            $where = array(
                'higher' => $id,
            );
        }

        $rows = $this->select($where, array('select' => 'id'));
        $tmp = array();
        if($rows){
            $tmp = array_get_column($rows, 'id');
        }
        if($andMe){
            array_unshift($tmp, $id);
        }

        return array_unique($tmp);
    }
}