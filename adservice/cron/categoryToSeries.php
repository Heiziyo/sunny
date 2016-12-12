<?php
/**
 * 更新资源到剧集表
 * Date: 2016/7/28
 * Time: 14:42
 */

require dirname(__FILE__) . '/../bootstrap.php';

if (ENV == 'DEVELOPMENT') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    ini_set('track_errors', 1);
} else {
    error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR);
}

class_alias('Factory', 'F');

$where = array(
    'status' => Model_Category::STATUS_ACTIVE
);

$attrs = array(
    'select' => 'id, series',
    'order_by' => 'series ASC, part ASC'
);
$m = F::$f->Model_Series;

$m->execute('TRUNCATE TABLE series');

$c = F::$f->Model_Category;
$rows = $c->select($where, $attrs);
if($rows){
    $tmp = array();
    foreach($rows as $row){
        if(!isset($tmp[$row['series']])) $tmp[$row['series']] = array(
            'name' => $row['series'],
            'res' => array()
        );
        $tmp[$row['series']]['res'][] = $row['id'];
    }

    $tmp = array_map(function($a){
        $item = array($a['name'], '', implode(",", $a['res']), '');
        return "('" . implode("','", $item) . "')";
    },array_values($tmp));

    $tmp = implode(",\n", $tmp);

    $sql = "INSERT INTO `series` (name, icon, res, mem) VALUES \n" . $tmp;

    if($m->execute($sql)){
        echo 'Synchronous data success';
    }else{
        echo 'Synchronous data failure';
    }

}

//更新资源表剧集为ID
$rows = $m->select(array('id, name'));
foreach($rows as $row){
    $c->update(array('series' => $row['name']), array('series' => $row['id']));
}
