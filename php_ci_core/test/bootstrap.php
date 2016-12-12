<?php
define('APP_PATH_LOG', dirname(__FILE__) . '/logs');
define('APP_PATH_LIB', implode(';', array(
    dirname(__FILE__) . '/../ad_lib',
)));

require dirname(__FILE__) . '/../bootstrap.php';

sp_load_helper(array('array', 'string', 'date', 'http'));
