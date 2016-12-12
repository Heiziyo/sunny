<?php
class Model_Member
{
    public $username;
    public $info;

    private static $SALT = 'xiushi!';

    public function __construct($info) 
    {
        if (is_array($info)) {
            $this->info = $info;
            $this->username = $info['username'];
        } else {
            $this->username = $info;
            $this->info = Factory::$f->Model_User->selectOne(array('username' => $info));
        }
    }

    public function update($upt)
    {
        if (empty($this->info)) {

            return FALSE;
        }

        return Factory::$f->Model_User->update(array(
            'id' => $this->info['id'],
        ), $upt);
    }

    public function isActive()
    {
        return $this->info && $this->info['status'] == Model_User::STATUS_ACTIVE;
    }

    public function exists()
    {
        return !empty($this->info);
    }

    public function getPasswordHash($password)
    {
        $originHash = $password;  

        if (!preg_match('@^[a-z0-9]{32}$@', $password)) {
            $originHash = md5($password);
        }

        $passwordHash = md5(self::$SALT . $this->username . $originHash);

        return $passwordHash;
    }

    public function setTmpPassword()
    {
        $tmpPassword = rand_str(6);

        $passwordHash = md5(self::$SALT . $this->username . md5($tmpPassword));

        if (Cache_Memcache::sSet($this->_getTmpPasswordKey(), $passwordHash, 600)) {
            return $tmpPassword;
        }

        return FALSE;
    }

    public function checkPassword($password) 
    {
        if (empty($password) || !$this->exists()) {

            return FALSE;
        }

        $originHash = $password;  

        if (!preg_match('@^[a-z0-9]{32}$@', $password)) {
            $originHash = md5($password);
        }

        $passwordHash = md5(self::$SALT . $this->username . $originHash);

        if ($this->info['password'] == $passwordHash) {
            
            return TRUE;
        }
        
        return FALSE;
    }
}