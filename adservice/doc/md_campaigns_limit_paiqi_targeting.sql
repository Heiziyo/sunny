/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : ad_service

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-11-10 16:20:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `md_campaign_limit`
-- ----------------------------
DROP TABLE IF EXISTS `md_campaign_limit`;
CREATE TABLE `md_campaign_limit` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `cap_type` varchar(100) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `total_amount_left` int(11) DEFAULT NULL,
  `last_refresh` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_campaign_limit
-- ----------------------------
INSERT INTO `md_campaign_limit` VALUES ('7', '1', '3', '800', '8531', null);

-- ----------------------------
-- Table structure for `md_campaign_paiqi`
-- ----------------------------
DROP TABLE IF EXISTS `md_campaign_paiqi`;
CREATE TABLE `md_campaign_paiqi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `num_origin` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_campaign_paiqi
-- ----------------------------
INSERT INTO `md_campaign_paiqi` VALUES ('73', '1', '2016-11-02', '800');
INSERT INTO `md_campaign_paiqi` VALUES ('74', '1', '2016-11-03', '833');
INSERT INTO `md_campaign_paiqi` VALUES ('75', '1', '2016-11-04', '833');
INSERT INTO `md_campaign_paiqi` VALUES ('76', '1', '2016-11-05', '833');
INSERT INTO `md_campaign_paiqi` VALUES ('77', '1', '2016-11-06', '600');
INSERT INTO `md_campaign_paiqi` VALUES ('78', '1', '2016-11-07', '800');
INSERT INTO `md_campaign_paiqi` VALUES ('79', '1', '2016-11-08', '400');
INSERT INTO `md_campaign_paiqi` VALUES ('80', '1', '2016-11-09', '900');
INSERT INTO `md_campaign_paiqi` VALUES ('81', '1', '2016-11-10', '833');
INSERT INTO `md_campaign_paiqi` VALUES ('82', '1', '2016-11-11', '833');
INSERT INTO `md_campaign_paiqi` VALUES ('83', '1', '2016-11-12', '833');
INSERT INTO `md_campaign_paiqi` VALUES ('84', '1', '2016-11-13', '833');

-- ----------------------------
-- Table structure for `md_campaign_targeting`
-- ----------------------------
DROP TABLE IF EXISTS `md_campaign_targeting`;
CREATE TABLE `md_campaign_targeting` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `targeting_type` varchar(100) NOT NULL,
  `targeting_code` varchar(100) NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `s1` (`campaign_id`,`targeting_type`,`targeting_code`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_campaign_targeting
-- ----------------------------
INSERT INTO `md_campaign_targeting` VALUES ('33', '1', 'geo', 'CNAH00');
INSERT INTO `md_campaign_targeting` VALUES ('34', '1', 'geo', 'CNAHAQ');
INSERT INTO `md_campaign_targeting` VALUES ('35', '1', 'geo', 'CNBJ00');
INSERT INTO `md_campaign_targeting` VALUES ('31', '1', 'placement', '223');
INSERT INTO `md_campaign_targeting` VALUES ('32', '1', 'placement', '225');

-- ----------------------------
-- Table structure for `md_campaigns`
-- ----------------------------
DROP TABLE IF EXISTS `md_campaigns`;
CREATE TABLE `md_campaigns` (
  `campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_owner` int(11) NOT NULL,
  `campaign_status` varchar(100) DEFAULT '',
  `campaign_type` varchar(100) DEFAULT '',
  `campaign_name` varchar(100) NOT NULL,
  `campaign_desc` text,
  `campaign_start` date NOT NULL,
  `campaign_end` date NOT NULL,
  `campaign_creationdate` varchar(100) DEFAULT '',
  `campaign_networkid` varchar(100) NOT NULL DEFAULT '',
  `campaign_priority` int(11) DEFAULT '0',
  `country_target` varchar(1) DEFAULT '',
  `publication_target` varchar(1) NOT NULL DEFAULT '',
  `channel_target` varchar(1) NOT NULL DEFAULT '',
  `device_target` varchar(1) NOT NULL DEFAULT '',
  `device_type_target` varchar(1) NOT NULL DEFAULT '',
  `video_target` varchar(1) NOT NULL DEFAULT '',
  `pattern_target` varchar(1) NOT NULL DEFAULT '',
  `quality_target` varchar(1) DEFAULT '' COMMENT '品质定向，1、全部，2、指定品质',
  `brand_target` varchar(1) NOT NULL DEFAULT '',
  `creative_show_rule` varchar(10) DEFAULT '',
  `belong_to_advertiser` int(11) DEFAULT '0',
  `campaign_display_way` int(11) DEFAULT '0',
  `total_amount` int(11) DEFAULT '0',
  `campaign_class` int(1) NOT NULL DEFAULT '0',
  `campaign_hash` varchar(100) DEFAULT '',
  `del_flg` int(1) NOT NULL DEFAULT '0',
  `order_id` int(10) unsigned NOT NULL COMMENT '订单id',
  `time_target` int(11) DEFAULT '16777215' COMMENT '投放时间段',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`campaign_id`),
  KEY `campaign_hash` (`campaign_hash`),
  KEY `time_target` (`time_target`),
  KEY `campaign_name` (`campaign_name`),
  KEY `campaign_time` (`campaign_start`,`campaign_end`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_campaigns
-- ----------------------------
INSERT INTO `md_campaigns` VALUES ('1', '0', '', '', '测试1', '测试1', '2016-11-02', '2016-11-13', '1478138261', '', '3', '2', '', '2', '', '', '', '', '', '', '1', '29', '3', '9331', '0', '', '0', '15', '4722757', '1478138261', '1478241661');
