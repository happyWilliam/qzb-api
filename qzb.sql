/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : qzb

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-08-27 23:46:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for fee_records
-- ----------------------------
DROP TABLE IF EXISTS `fee_records`;
CREATE TABLE `fee_records` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` smallint(5) unsigned NOT NULL,
  `type` tinyint(2) unsigned NOT NULL COMMENT '1-充值，2-打球扣费，3-购买商品，4-其它',
  `operator_id` smallint(3) NOT NULL,
  `operate_time` datetime NOT NULL,
  `last_balance` float(5,2) NOT NULL,
  `after_balance` float(5,2) NOT NULL,
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fee_records
-- ----------------------------
INSERT INTO `fee_records` VALUES ('1', '1', '1', '1', '2017-08-18 15:00:00', '0.00', '100.00', '会费充值');

-- ----------------------------
-- Table structure for fee_type
-- ----------------------------
DROP TABLE IF EXISTS `fee_type`;
CREATE TABLE `fee_type` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `min_num` tinyint(1) DEFAULT NULL COMMENT '最小参加人数',
  `max_num` tinyint(3) DEFAULT NULL COMMENT '最多参加人数 ',
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fee_type
-- ----------------------------
INSERT INTO `fee_type` VALUES ('1', '会员制', '4', '6', '男-25元/人，女-20元/人，最少4人，最多6人');
INSERT INTO `fee_type` VALUES ('2', 'AA制', '4', '8', '总费用/人数，最少4人，最多8人');
INSERT INTO `fee_type` VALUES ('3', '其它', null, null, '费用、人数限制和管理员协商');

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `login_name` varchar(30) NOT NULL,
  `pwd` varchar(30) NOT NULL,
  `real_name` varchar(30) DEFAULT NULL,
  `mobile` varchar(12) DEFAULT NULL,
  `balance` float(5,2) NOT NULL DEFAULT '0.00',
  `gender` tinyint(1) NOT NULL COMMENT '0-女，1-男，2-未知',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0-停用，1-启用',
  `del_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-未删除，1-已删除',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of member
-- ----------------------------
INSERT INTO `member` VALUES ('1', 'huiling', '013895666698', '钟惠玲', '13895666698', '100.00', '0', '1', '0', '0000-00-00 00:00:00');
INSERT INTO `member` VALUES ('2', 'lyj', '013895666698', '李玉娟', '13895666699', '0.00', '0', '1', '0', '0000-00-00 00:00:00');
INSERT INTO `member` VALUES ('3', 'wy002', 'wy123', '王二', '1333333333', '0.00', '0', '1', '1', '2017-08-23 23:36:43');

-- ----------------------------
-- Table structure for participant
-- ----------------------------
DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `mobile` varchar(12) DEFAULT NULL,
  `sign_member_id` smallint(5) unsigned NOT NULL,
  `program_id` smallint(5) unsigned NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-女，1-男',
  `member_id` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of participant
-- ----------------------------
INSERT INTO `participant` VALUES ('1', '张三', '13692115085', '1', '1', '1', null);
INSERT INTO `participant` VALUES ('2', '李四', '13698885458', '1', '1', '1', null);
INSERT INTO `participant` VALUES ('3', '惠玲', '13695448958', '1', '1', '0', '1');

-- ----------------------------
-- Table structure for program
-- ----------------------------
DROP TABLE IF EXISTS `program`;
CREATE TABLE `program` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `participant_ids` varchar(300) DEFAULT NULL COMMENT '参与者id，关联participant表',
  `imgs` varchar(300) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `address` varchar(255) NOT NULL,
  `fee_type` varchar(30) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-活动已取消，1-活动报名中，2-活动报名截止，3-活动费用已核对，4-活动已结束',
  `field_num` tinyint(2) NOT NULL DEFAULT '1',
  `charge_user_id` tinyint(3) NOT NULL,
  `fee_type_id` tinyint(2) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL,
  `creator_id` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of program
-- ----------------------------
INSERT INTO `program` VALUES ('1', '2017-08-19羽毛球活动', '2017-08-19羽毛球活动', '1,2,3', 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=1877976885,3975167324&fm=26&gp=0.jpg', '2017-08-19 10:00:00', '2017-08-19 12:00:00', '深圳市龙华区民治民兴工业区民治羽毛球馆', 'fee_type_1', '1', '1', '1', '1', '0000-00-00 00:00:00', '0');
INSERT INTO `program` VALUES ('2', '2017-09-02羽毛球活动22', '2017-09-02羽毛球活动22', null, null, '2017-08-26 10:00:00', '2017-08-26 12:00:00', '深圳市龙华区民治民兴工业区民治羽毛球馆', '1', '1', '1', '1', '1', '2017-08-27 20:42:30', '1');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `login_name` varchar(30) NOT NULL,
  `pwd` varchar(30) NOT NULL,
  `real_name` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL COMMENT '0-停用，1-启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'wy', 'sq123456', '王益', '13692110607', '0');
