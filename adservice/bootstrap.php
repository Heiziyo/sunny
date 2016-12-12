<?php

function error_report($errno, $msg, $smsMsg = '')
{
    log_message('error_report:'.$msg, LOG_ERR);
}

/*
 * 用于测试输出
 */
function _dump($obj){
    echo "<pre>";
    var_dump($obj);
    echo "</pre>";
}

function relation_array_unshift(&$res, $target){
    foreach($target as $key => $val){
        $res[$key] = $val;
    }
}

function dr()
{
    $args = func_get_args();
    foreach ($args as $arg) {
        if (isset($arg)) {
            return $arg;
        }
    }
    return $arg;
}

function real_isset($val){
    if($val === NULL || $val === ''){
        return FALSE;
    }
    return TRUE;
}

function sendMail($receiver, $subject, $content)
{
    log_message(json_encode($receiver) . ", $subject, $content", LOG_DEBUG);

    $mailer = new Mail_Sendmail();

    return $mailer->send($receiver, $subject, $content);
}

function getServerHost(){
    return $_SERVER['HTTP_HOST'];
}

function dateDiff($start, $end){
    $differenceFormat = '%a';
    $datetime1 = date_create($start);
    $datetime2 = date_create($end);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->format($differenceFormat) + 1;
}

define('APP_PATH', dirname(__FILE__));
define('APP_PATH_SOURCE', dirname(__FILE__) . '/www');
define('APP_PATH_LOG', dirname(__FILE__) . '/application/logs');
define('APP_PATH_CONF', dirname(__FILE__) . '/application/third_party/conf');

define('APP_PATH_LIB', implode(';', array(
    dirname(__FILE__) . '/application/third_party',
    dirname(__FILE__) . '/application/third_party/lib',
)));

define('IS_WIN', DIRECTORY_SEPARATOR != '/');
define('DEFAULT_DB_CLUSTER_ID', 'ad_service');
//APP版本
define('APP_VERSION', 1);

require_once(dirname(__FILE__) . '/../php_ci_core/bootstrap.php');

//读写主库
//Db_Model::setForceReadOnMater();

sp_load_helper(array('array', 'http', 'parameter', 'string', 'date'));

Session::$config = array(
    'cookieName' => substr(ENV, 0, 1) . '_session'
);

if (in_array(ENV, array('DEVELOPMENT', 'TEST'))) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    ini_set('track_errors', 1);
} else {
    error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR);
}
