<?php
/**
 * 全局配制接口文件
 *
 * 所有与环境相关的配制都集中放在一个配制文件中
 * config_development.php 开发环境的配制文件
 * config_production.php 线上环境的配制文件
 * config_test.php 测试环境的配制文件
 *
 * 程序根据当前服务器的hostname自动读取当前环境的配制文件
 *
 **/
class Config
{
    private static $CONFIG = array();

    /**
     * 添加配制数组
     *
     * @param $config array
     * @return void
     */
    public static function add($config)
    {
        self::$CONFIG = self::_merge($config, self::$CONFIG);
    }

    private static function _merge($source, $target)
    {
        foreach ($source as $key => $val) {
            if ( ! is_array($val) || ! isset($target[$key])) {
                $target[$key] = $val;
            } else {
                $target[$key] = self::_merge($val, $target[$key]);
            }
        }
        return $target;
    }

    public static function set($key, $val)
    {
        $config = &self::$CONFIG;
        $segments = explode('.', $key);
        $key = array_pop($segments);
        foreach ($segments as $segment) {
            if ( ! isset($config[$segment])) {
                $config[$segment] = array();
            }
            $config = &$config[$segment];
        }
        $config[$key] = $val;
    }

    /**
     * 获取一个配制值
     *
     * @param string $key 配制名, 可包含多级，用 "." 分隔
     * @param string $default default NULL,默认值
     * @return mixed
     */
    public static function get($key, $default = NULL)
    {
        $config = self::$CONFIG;

        $path = explode('.', $key);
        foreach ($path as $key) {
            $key = trim($key);
            if (empty($config) || !isset($config[$key])) {
                return $default;
            }
            $config = $config[$key];
        }

        return $config;
    }

    /**
     * Alias of method get
     */
    public static function g($key, $default = NULL)
    {
        return self::get($key, $default);
    }
}

// 所有环境、所有应用的公共配制
Config::add(array(
    'Cookie' => array(
        'HashMethod' => 'md5',
        'Salt' => '66623cbed0b094dc14ffae2ad7ec105a',
        'Session' => 'session',
    ),
    /*
     * SMTPServer = smtp.exmail.qq.com
SMTPServerPort = 25
SMTPUserMail = macus.liang@joyplus.tv
SMTPUser = macus.liang@joyplus.tv
SMTPUserPassword = Joyplus315315L
     */
    'mail' => array(
        'host' => 'smtp.exmail.qq.com',
        'username' => 'macus.liang@joyplus.tv',
        'password' => 'Joyplus315315L',
        'name' => '秀视智能',
        'sign' => '
<br/>
<div>本邮件为系统自动发送，请勿回复。</div>
<div>-------------------------------------------------------------</div>
<p>秀视智能（上海）有限公司</p>
<p>地址：上海市长宁区中山西路1055号Soho中山广场A座1204室</p>
<p>电话：021-60318881</p>
',
    ),
    'member' => array(
        'liangfen' => array(
            'qq'     => '18905974',
            'mobile' => '18601707630',
            'email'  => '18905974@qq.com',
        ),
        'lihongwei' => array(
            'qq' => '460605928',
            'mobile' => '13816196249',
            'email' => '460605928@qq.com'
        )
    ),
));

$global_config_files = array(
    'DEVELOPMENT' => 'production',
    'TEST'        => 'test',
    'PRODUCTION'  => 'production',
);

if (!defined('ENV')) {
    $hostname = php_uname('n');
    $devHostnames = array('USER-PC', 'USER-20160322YW');//开发机
    $testHostnames = array('USER-20160322YW');//测试机

    if (in_array($hostname, $devHostnames)) {
        define('ENV', 'DEVELOPMENT');
    } else if (in_array($hostname, $testHostnames)) {
        define('ENV', 'TEST');
    } else {
        //域名二次判断
        if(getLocalHost() == 'kjtest.joyplus.tv'){
            define('ENV', 'TEST');
        }else {
            define('ENV', 'PRODUCTION');
        }
    }
}

function getLocalHost(){
    return isset($_SERVER['HTTP_HOST']) ?  $_SERVER['HTTP_HOST'] : '';
}

$global_config_file = 'config_'.$global_config_files[ENV].'.php';

//公共的针对不同环境配制文件
require dirname(__FILE__) . DS . $global_config_file;

//每个应用独立的配制文件
if (defined('APP_PATH_CONF')) {
    if (file_exists(APP_PATH_CONF . DS . 'config.php')) {
        require APP_PATH_CONF . DS . 'config.php';
    }

    if (file_exists(APP_PATH_CONF . DS . $global_config_file)) {
        require APP_PATH_CONF . DS . $global_config_file;
    }

    if (file_exists(APP_PATH_CONF . DS . 'constants.php')) {
        require APP_PATH_CONF . DS . 'constants.php';
    }
}

require dirname(__FILE__) . DS . 'constants.php';

/**
 * 分库hash函数
 *
 */
function partition_16_by_md5_hash ($objId)
{
    return hexdec(substr(md5($objId), 0, 2)) % 16 + 1;
}

function partition_256_by_md5_hash ($objId)
{
    return hexdec(substr(md5($objId), 0, 2)) + 1;
}

function partition_by_last_3_digits ($objId)
{
    return intval(substr($objId, - 3, 3), 10);
}

function partition_1 ($objId)
{
    return 1;
}
