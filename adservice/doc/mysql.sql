
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `password` char(32) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(50) DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT NULL,
  `login_ip` varchar(50) DEFAULT NULL,
  `position` varchar(30) DEFAULT '0' COMMENT 'position',
  `customerposition` varchar(20) DEFAULT NULL COMMENT '广告主联系人职位名称',
  `department` int(10) DEFAULT '0' COMMENT '部门id',
  `qq` varchar(20) DEFAULT NULL COMMENT '用户qq',
  `higher` int(10) DEFAULT '0' COMMENT '用户的上级uid',
  `roles` varchar(50) DEFAULT '7' COMMENT '默认为访客',
  `usertype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0,自己公司的人，2广告主',
  `usertype_id` int(11) NOT NULL COMMENT '对应客户公司（代理）的id',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表';

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `brand` varchar(255) NOT NULL COMMENT '品牌',
  `industry` int(11) NOT NULL,
  `customer_ids` varchar(100) DEFAULT '' COMMENT '联系人',
  `discount` decimal(2,2) DEFAULT '0.00',
  `city` varchar(2000) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `create_people` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`brand`,`create_people`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='广告主';
ALTER TABLE `user` ADD UNIQUE (`username`);

CREATE TABLE IF NOT EXISTS `md_block_mac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac_address` varchar(100) NOT NULL,
  `create_time` date NOT NULL,
  `update_time` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `md_block_ip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_start` int(11) unsigned DEFAULT '0',
  `ip_end` int(11) unsigned DEFAULT '0',
  `create_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `md_lov` (
  `key` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  `value` varchar(50) NOT NULL,
  `description` text,
  KEY `key` (`key`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_campaigns` (
  `campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_owner` int(11) NOT NULL,
  `campaign_status` varchar(100) DEFAULT NULL,
  `campaign_type` varchar(100) DEFAULT NULL,
  `campaign_name` varchar(100) NOT NULL,
  `campaign_desc` text,
  `campaign_start` date NOT NULL,
  `campaign_end` date NOT NULL,
  `campaign_creationdate` varchar(100) DEFAULT NULL,
  `campaign_networkid` varchar(100) NOT NULL,
  `campaign_priority` int(11) DEFAULT NULL,
  `country_target` varchar(1) DEFAULT NULL,
  `publication_target` varchar(1) NOT NULL,
  `channel_target` varchar(1) NOT NULL,
  `device_target` varchar(1) NOT NULL,
  `device_type_target` varchar(1) NOT NULL,
  `video_target` varchar(1) NOT NULL,
  `pattern_target` varchar(1) NOT NULL,
  `quality_target` varchar(1) DEFAULT NULL,
  `brand_target` varchar(1) NOT NULL,
  `creative_show_rule` varchar(10) DEFAULT NULL,
  `belong_to_advertiser` int(11) DEFAULT NULL,
  `campaign_display_way` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `frequency` int(11) NOT NULL DEFAULT '3',
  `campaign_class` int(1) NOT NULL,
  `campaign_hash` varchar(100) DEFAULT NULL,
  `del_flg` int(1) NOT NULL DEFAULT '0',
  `time_target` int(11) DEFAULT '16777215',
  PRIMARY KEY (`campaign_id`),
  KEY `campaign_hash` (`campaign_hash`),
  KEY `time_target` (`time_target`),
  KEY `campaign_name` (`campaign_name`),
  KEY `campaign_time` (`campaign_start`,`campaign_end`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_campaign_targeting` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `targeting_type` varchar(100) NOT NULL,
  `targeting_code` varchar(100) NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `s1` (`campaign_id`,`targeting_type`,`targeting_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_ad_units` (
  `adv_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `unit_hash` varchar(100) NOT NULL,
  `adv_type` varchar(100) DEFAULT NULL,
  `adv_status` varchar(100) DEFAULT NULL,
  `adv_click_url` varchar(500) DEFAULT NULL,
  `adv_click_url_type` varchar(100) DEFAULT 'openurl',
  `adv_click_opentype` varchar(100) NOT NULL DEFAULT '',
  `adv_chtml` varchar(500) DEFAULT NULL,
  `adv_mraid` varchar(1) NOT NULL DEFAULT '',
  `adv_bannerurl` varchar(1000) NOT NULL DEFAULT '',
  `adv_impression_tracking_url` varchar(500) DEFAULT NULL,
  `adv_impression_tracking_url_iresearch` varchar(500) DEFAULT NULL,
  `adv_impression_tracking_url_admaster` varchar(500) DEFAULT NULL,
  `adv_impression_tracking_url_nielsen` varchar(500) DEFAULT NULL,
  `adv_name` varchar(100) NOT NULL DEFAULT '',
  `adv_clickthrough_type` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_extension` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_extension_2` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_extension_3` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_url` varchar(1000) DEFAULT '',
  `adv_creative_url_2` varchar(1000) DEFAULT '',
  `adv_creative_url_3` varchar(1000) DEFAULT '',
  `creative_time` int(11) DEFAULT '0',
  `custom_file_name` varchar(100) DEFAULT '' COMMENT '客户端原始文件名',
  `custom_file_name_2` varchar(100) DEFAULT '' COMMENT '客户端原始文件名_开机1',
  `custom_file_name_3` varchar(100) DEFAULT '' COMMENT '客户端原始文件名_开机2',
  `file_hash_1` varchar(100) DEFAULT '',
  `file_hash_2` varchar(100) DEFAULT '',
  `adv_height` varchar(100) DEFAULT '',
  `adv_width` varchar(100) DEFAULT '',
  `creativeserver_id` varchar(100) NOT NULL DEFAULT '',
  `creative_unit_type` varchar(20) DEFAULT '',
  `creative_weight` int(11) DEFAULT '5',
  `adv_start` date DEFAULT NULL,
  `adv_end` date DEFAULT NULL,
  `create_material` text,
  `xml` varchar(1000) DEFAULT '',
  `play_method` int(11) NOT NULL DEFAULT '0',
  `del_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adv_id`),
  KEY `campaign_id` (`campaign_id`,`adv_status`),
  KEY `campaign_id_width` (`adv_height`,`adv_width`),
  KEY `unit_hash` (`unit_hash`),
  KEY `adv_time` (`adv_start`,`adv_end`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='创意';

CREATE TABLE IF NOT EXISTS `md_reporting` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `time_stamp` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `day` varchar(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `year` varchar(100) NOT NULL,
  `publication_id` int(11) DEFAULT '0',
  `zone_id` int(11) DEFAULT '0',
  `campaign_id` int(11) DEFAULT '0',
  `creative_id` int(11) DEFAULT '0',
  `network_id` varchar(100) DEFAULT NULL,
  `total_requests` int(11) DEFAULT '0',
  `total_requests_sec` int(11) DEFAULT '0',
  `total_impressions` int(11) DEFAULT '0',
  `total_clicks` int(11) DEFAULT '0',
  `hours` varchar(10) NOT NULL,
  `province_code` varchar(30) DEFAULT NULL,
  `city_code` varchar(30) DEFAULT NULL,
  `report_hash` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`entry_id`,`date`),
  KEY `reporting_select` (`publication_id`,`zone_id`,`campaign_id`,`creative_id`,`date`,`hours`),
  KEY `report_hash` (`report_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
PARTITION BY RANGE  COLUMNS(`date`)
(PARTITION p01 VALUES LESS THAN ('2016-11-01') ENGINE = InnoDB,
 PARTITION p02 VALUES LESS THAN ('2016-12-01') ENGINE = InnoDB,
 PARTITION p03 VALUES LESS THAN ('2017-01-01') ENGINE = InnoDB,
 PARTITION p04 VALUES LESS THAN ('2017-02-01') ENGINE = InnoDB,
 PARTITION p05 VALUES LESS THAN ('2017-03-01') ENGINE = InnoDB,
 PARTITION p06 VALUES LESS THAN ('2017-04-01') ENGINE = InnoDB,
 PARTITION p07 VALUES LESS THAN ('2017-05-01') ENGINE = InnoDB,
 PARTITION p08 VALUES LESS THAN ('2017-06-01') ENGINE = InnoDB,
 PARTITION p09 VALUES LESS THAN ('2017-07-01') ENGINE = InnoDB,
 PARTITION p10 VALUES LESS THAN ('2017-08-01') ENGINE = InnoDB,
 PARTITION p11 VALUES LESS THAN ('2017-09-01') ENGINE = InnoDB,
 PARTITION p12 VALUES LESS THAN ('2017-10-01') ENGINE = InnoDB,
 PARTITION p13 VALUES LESS THAN ('2017-11-01') ENGINE = InnoDB,
 PARTITION p14 VALUES LESS THAN ('2017-12-01') ENGINE = InnoDB,
 PARTITION p15 VALUES LESS THAN ('2018-01-01') ENGINE = InnoDB,
 PARTITION p16 VALUES LESS THAN ('2018-02-01') ENGINE = InnoDB,
 PARTITION p17 VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB);

 CREATE TABLE IF NOT EXISTS `md_publications` (
  `inv_id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` varchar(100) NOT NULL,
  `inv_status` varchar(100) NOT NULL,
  `inv_type` varchar(100) DEFAULT NULL,
  `inv_name` varchar(100) NOT NULL,
  `inv_description` varchar(100) DEFAULT NULL,
  `inv_address` varchar(100) DEFAULT NULL,
  `inv_defaultchannel` varchar(100) DEFAULT NULL,
  `md_lastrequest` varchar(100) DEFAULT NULL,
  `del_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`inv_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_campaign_paiqi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `num_origin` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_devices` (
  `device_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_type` varchar(3) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `device_movement` varchar(100) DEFAULT NULL,
  `device_brands` varchar(10) DEFAULT NULL,
  `device_quality` int(11) DEFAULT NULL,
  `business_id` varchar(10) DEFAULT 'MDADV',
  `del_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`device_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_device_package_matrix` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `package_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_zones` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `publication_id` int(11) NOT NULL DEFAULT '0',
  `zone_hash` varchar(100) NOT NULL,
  `zone_name` varchar(100) NOT NULL,
  `zone_type` varchar(100) NOT NULL,
  `zone_width` varchar(100) DEFAULT NULL,
  `zone_height` varchar(100) DEFAULT NULL,
  `zone_refresh` varchar(100) NOT NULL,
  `zone_channel` varchar(100) DEFAULT NULL,
  `zone_lastrequest` varchar(100) DEFAULT NULL,
  `zone_description` varchar(500) DEFAULT NULL,
  `mobfox_backfill_active` varchar(100) DEFAULT NULL,
  `mobfox_min_cpc_active` varchar(100) DEFAULT NULL,
  `min_cpc` decimal(5,3) DEFAULT '0.000',
  `min_cpm` decimal(5,3) DEFAULT '0.000',
  `backfill_alt_1` varchar(100) DEFAULT NULL,
  `backfill_alt_2` varchar(100) DEFAULT NULL,
  `backfill_alt_3` varchar(100) DEFAULT NULL,
  `zone_typeint` int(11) DEFAULT NULL,
  `del_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`entry_id`),
  KEY `publication_id` (`publication_id`),
  KEY `zone_hash` (`zone_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `md_ad_unit_device` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `adv_id` varchar(100) NOT NULL,
  `equipment_key` varchar(100) NOT NULL,
  `equipment_sn` varchar(100) DEFAULT NULL,
  `zone_hash` varchar(100) NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `adv_id` (`adv_id`),
  KEY `zone_hash` (`zone_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- toby
-- 2016-10-19
-- --------------------------------------------
CREATE TABLE `privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moudle_code` varchar(20) DEFAULT NULL,
  `moudle_name` varchar(30) DEFAULT NULL,
  `controller_code` varchar(20) DEFAULT NULL,
  `controller_name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=Innodb DEFAULT CHARSET=utf8;

CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `privilege` text,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `role` ADD UNIQUE (`name`);
ALTER TABLE  `privilege` ADD  `status` INT NOT NULL DEFAULT  '0' AFTER  `controller_name`;

-- toby
-- 2016-10-19 4:04
INSERT INTO `privilege` (`id`, `moudle_code`, `moudle_name`, `controller_code`, `controller_name`, `status`) VALUES
(1, 'system', '系统管理', 'user', '账号管理', 0),
(2, 'system', '系统管理', 'system', '角色管理', 0),
(3, 'media', '媒体管理', 'media', '媒体列表', 0),
(4, 'media', '媒体管理', 'adlist', '广告列表', 0),
(5, 'customer', '广告主管理', 'customer', '广告主列表', 0),
(6, 'advertise', '投放管理', 'advertisers', '广告主', 0),
(7, 'advertise', '投放管理', 'campaign', '广告主', 0),
(8, 'advertise', '投放管理', 'orders', '订单列表', 0),
(9, 'advertise', '投放管理', 'units', '素材列表', 0);


-- --------------------------------------------------------

--
-- 20161024 ycm 表的结构 `md_ad_units`
--

CREATE TABLE IF NOT EXISTS `md_ad_units` (
  `adv_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `unit_hash` varchar(100) NOT NULL,
  `adv_type` varchar(100) DEFAULT NULL,
  `adv_status` int(2) DEFAULT '1',
  `adv_click_url` varchar(500) DEFAULT NULL,
  `adv_click_url_type` varchar(100) DEFAULT 'openurl',
  `adv_click_opentype` varchar(100) NOT NULL DEFAULT '',
  `adv_chtml` varchar(500) DEFAULT NULL,
  `adv_mraid` varchar(1) NOT NULL DEFAULT '',
  `adv_bannerurl` varchar(1000) NOT NULL DEFAULT '',
  `adv_impression_tracking_url` varchar(500) DEFAULT NULL,
  `adv_impression_tracking_url_iresearch` varchar(500) DEFAULT NULL,
  `adv_impression_tracking_url_admaster` varchar(500) DEFAULT NULL,
  `adv_impression_tracking_url_nielsen` varchar(500) DEFAULT NULL,
  `adv_name` varchar(100) NOT NULL DEFAULT '',
  `adv_clickthrough_type` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_extension` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_extension_2` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_extension_3` varchar(100) NOT NULL DEFAULT '',
  `adv_creative_url` varchar(1000) DEFAULT '',
  `adv_creative_url_2` varchar(1000) DEFAULT '',
  `adv_creative_url_3` varchar(1000) DEFAULT '',
  `creative_time` int(11) DEFAULT '0',
  `custom_file_name` varchar(100) DEFAULT '' COMMENT '客户端原始文件名',
  `custom_file_name_2` varchar(100) DEFAULT '' COMMENT '客户端原始文件名_开机1',
  `custom_file_name_3` varchar(100) DEFAULT '' COMMENT '客户端原始文件名_开机2',
  `file_hash_1` varchar(100) DEFAULT '',
  `file_hash_2` varchar(100) DEFAULT '',
  `adv_height` varchar(100) DEFAULT '',
  `adv_width` varchar(100) DEFAULT '',
  `creativeserver_id` varchar(100) NOT NULL DEFAULT '',
  `creative_unit_type` varchar(20) DEFAULT '',
  `creative_weight` int(11) DEFAULT '5',
  `adv_start` date DEFAULT NULL,
  `adv_end` date DEFAULT NULL,
  `del_flg` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adv_id`),
  KEY `campaign_id` (`campaign_id`,`adv_status`),
  KEY `campaign_id_width` (`adv_height`,`adv_width`),
  KEY `unit_hash` (`unit_hash`),
  KEY `adv_time` (`adv_start`,`adv_end`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='创意' AUTO_INCREMENT=2566 ;

-- 2016-10-26
ALTER TABLE  `privilege` ADD  `controller_sub` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER  `controller_name`;

-- 2016-11-02
-- lihongwei
alter table  `privilege` add unique (`moudle_code`,`controller_code`);


-- Table structure for `md_device_reports_num`
-- ----------------------------
DROP TABLE IF EXISTS `md_device_reports_num`;
CREATE TABLE `md_device_reports_num` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) DEFAULT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `publication_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `province_code` varchar(10) NOT NULL,
  `city_code` varchar(10) NOT NULL,
  `date` date DEFAULT NULL,
  `n1` int(11) DEFAULT '0',
  `n2` int(11) DEFAULT '0',
  `n3` int(11) DEFAULT '0',
  `n4` int(11) DEFAULT '0',
  `n5` int(11) DEFAULT '0',
  `n6` int(11) DEFAULT '0',
  `n7` int(11) DEFAULT '0',
  `n8` int(11) DEFAULT '0',
  `n9` int(11) DEFAULT '0',
  `n10` int(11) DEFAULT '0',
  `n11` int(11) DEFAULT '0',
  `n12` int(11) DEFAULT '0',
  `n13` int(11) DEFAULT '0',
  `n14` int(11) DEFAULT '0',
  `n15` int(11) DEFAULT '0',
  `n16` int(11) DEFAULT '0',
  `n17` int(11) DEFAULT '0',
  `n18` int(11) DEFAULT '0',
  `n19` int(11) DEFAULT '0',
  `n20` int(11) DEFAULT '0',
  `n21` int(11) DEFAULT '0',
  `n22` int(11) DEFAULT '0',
  `n23` int(11) DEFAULT '0',
  `n24` int(11) DEFAULT '0',
  `n25` int(11) DEFAULT '0',
  `n26` int(11) DEFAULT '0',
  `n27` int(11) DEFAULT '0',
  `n28` int(11) DEFAULT '0',
  `n29` int(11) DEFAULT '0',
  `n30` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `reports_num_select` (`campaign_id`,`ad_id`,`publication_id`,`zone_id`,`province_code`,`city_code`,`date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- 2016-11-18
-- 李红卫
-- 视图sql和原视图sql有改动
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER
  VIEW `vw_region_device_report` AS
    SELECT SUM(`md_device_reports`.`impression`) AS `total_impression`,
           SUM(`md_device_reports`.`uv`) AS `total_uv`,
           SUM(`md_device_reports`.`exposure`) AS `total_exposure`,
           `md_device_reports`.`province_code` AS `province_code`,
           `md_device_reports`.`campaign_id` AS `campaign_id`,
           `md_device_reports`.`ad_id` AS `ad_id`,
           `md_device_reports`.`date` AS `date` 
      FROM `md_device_reports`
  GROUP BY `md_device_reports`.`province_code`,`md_device_reports`.`date`,`md_device_reports`.`ad_id`
  GROUP BY SUM(`md_device_reports`.`impression`) DESC;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER 
  VIEW `vw_publication_device_report` AS
    SELECT `md_device_reports`.`campaign_id` AS `campaign_id`,
           `md_device_reports`.`zone_id` AS `zone_id`,
           `md_publications`.`inv_name` AS `publication_name`,
           `md_zones`.`zone_name` AS `zone_name`,
           `md_campaigns`.`campaign_name` AS `campaign_name`,
           SUM(`md_device_reports`.`impression`) AS `total_impression`,
           SUM(`md_device_reports`.`uv`) AS `total_uv`,
           `md_device_reports`.`date` AS `date` 
     FROM  `md_device_reports` LEFT JOIN `md_publications` ON `md_device_reports`.`publication_id` = `md_publications`.`inv_id`
LEFT JOIN `md_zones` ON `md_device_reports`.`zone_id` = `md_zones`.`entry_id`
LEFT JOIN `md_campaigns` ON `md_device_reports`.`campaign_id` = `md_campaigns`.`campaign_id`
 GROUP BY `md_device_reports`.`zone_id`,`md_device_reports`.`campaign_id`,`md_device_reports`.`date`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER 
  VIEW `vw_campaign_device_report` AS
    SELECT `cam`.`campaign_id` AS `campaign_id`,
           `cam`.`campaign_name` AS `campaign_name`,
           `cam`.`campaign_start` AS `campaign_start`,
           `cam`.`campaign_end` AS `campaign_end`,
           SUM(`r`.`impression`) AS `impression`,
           SUM(`r`.`exposure`) AS `exposure`,
           SUM(`r`.`uv`) AS `uv` 
      FROM `md_campaigns` AS `cam`
 LEFT JOIN `md_device_reports` `r` ON `cam`.`campaign_id` = `r`.`campaign_id`
     WHERE `cam`.`del_flg` <> 1
      AND  `r`.`date` >= `cam`.`campaign_start`
      AND  `r`.`date` <= `cam`.`campaign_end`
 GROUP BY  `cam`.`campaign_id`;



-- ----------------------------
-- Table structure for `data_version`
-- ----------------------------
DROP TABLE IF EXISTS `data_version`;
CREATE TABLE `data_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_name` varchar(50) DEFAULT NULL,
  `table_name` varchar(50) NOT NULL,
  `object_id` varchar(50) NOT NULL,
  `origin_data` text,
  `op_uid` int(11) DEFAULT NULL,
  `op_username` varchar(100) DEFAULT '',
  `op_login_time` varchar(50) DEFAULT NULL,
  `op_login_ip` varchar(50) DEFAULT NULL,
  `change_data` text,
  `change_fields` text,
  `op_type` varchar(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `op_title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `version_id` (`db_name`),
  KEY `table_name` (`table_name`,`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=229 DEFAULT CHARSET=utf8;



ALTER TABLE `md_ad_units` CHANGE `adv_status` `adv_status` INT(2) NULL DEFAULT '1'