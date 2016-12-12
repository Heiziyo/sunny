<?php
Config::add(array(
    'db_physical' => array( //physical master-slave shard configuration
        0 => array(
            'write' => array(
                'host' => '127.0.0.1',
                'port' => 3306
            ),
            'read' => array(
                'host' => '127.0.0.1',
                'port' => 3306
            ),
            'db_user' => 'root',
            'db_pwd' => 'point9*'
        ),
    ),
    'db_cluster' => array(),
    'db_singles' => array(
        'ad_joy' => array(
            'map' => 0,
            'db_name' => 'ad_joy'
        ),
        'addatakj' => array(
            'map' => 0,
            'db_name' => 'addatakj'
        ),
        'ad_business' => array(
            'map' => 0,
            'db_name' => 'ad_business'
        ),
        'ad_service' => array(
            'map' => 0,
            'db_name' => 'ad_service'
        ),
    ),
    'cache_physical' => array(
        0 => array(
            'host' => '127.0.0.1',
            'port' => '11211'
        ),
    ),
    'cache_cluster' => array(
        'default' => array(0)
    ),
    'redis_physical' => array(
        0 => array(
            'host' => '127.0.0.1',
            'port' => '6379'
        )
    ),
    'redis_single' => array(
        #0.0, 第一个0指映射到的redis_physical的配置，第二个0指选择redis的0号数据库
        #默认redis分16个数据库，即0-15
        'default' => '0.0',
        'stat' => '0.0',
        'rt_adx' => '0.0',
        'rt_uplog' => '0.0',
    ),
));
