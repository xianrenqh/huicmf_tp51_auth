/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50725
 Source Host           : localhost:3306
 Source Schema         : 1055tp5_test

 Target Server Type    : MySQL
 Target Server Version : 50725
 File Encoding         : 65001

 Date: 05/01/2020 17:25:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for hui_admin
-- ----------------------------
DROP TABLE IF EXISTS `hui_admin`;
CREATE TABLE `hui_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '失败次数',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_admin
-- ----------------------------
INSERT INTO `hui_admin` VALUES (1, 'admin', 'Admin', 'fffe69302908dafc83cecc5686608ac7', 'zsmQ5v', '/assets/img/avatar.png', 'admin@fastadmin.net', 0, 1578182645, '127.0.0.1', 1492186163, 1578215522, '62465acf-babd-44d8-96a1-51535702f218', 'normal');

-- ----------------------------
-- Table structure for hui_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `hui_admin_log`;
CREATE TABLE `hui_admin_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '内容',
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员日志表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for hui_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `hui_auth_group`;
CREATE TABLE `hui_auth_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父组别',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规则ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_auth_group
-- ----------------------------
INSERT INTO `hui_auth_group` VALUES (1, 0, '超级管理员', '*', 1490883540, 149088354, 'normal');

-- ----------------------------
-- Table structure for hui_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `hui_auth_group_access`;
CREATE TABLE `hui_auth_group_access`  (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员ID',
  `group_id` int(10) UNSIGNED NOT NULL COMMENT '级别ID',
  UNIQUE INDEX `uid_group_id`(`uid`, `group_id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_auth_group_access
-- ----------------------------
INSERT INTO `hui_auth_group_access` VALUES (1, 1);

-- ----------------------------
-- Table structure for hui_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `hui_auth_rule`;
CREATE TABLE `hui_auth_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否为菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `weigh`(`weigh`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '节点表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_auth_rule
-- ----------------------------
INSERT INTO `hui_auth_rule` VALUES (1, 'file', 0, 'general', '常规管理', 'icon-settings', '0', '0', 1, 1497429920, 1497430169, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (2, 'file', 0, 'auth', '权限管理', 'icon-group', '0', '0', 1, 1497429920, 1577703004, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (3, 'file', 2, 'auth/admin', '管理员管理', 'icon-friendfill', '0', 'Admin0tips', 1, 1497429920, 1577703009, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (4, 'file', 1, 'general/database', '数据库管理', 'icon-subscription_light', '0', 'Admin0log0tips', 1, 1497429920, 1578017454, 99, 'normal');
INSERT INTO `hui_auth_rule` VALUES (5, 'file', 2, 'auth/group', '角色组', 'icon-group_fill_light', '0', 'Group0tips', 1, 1497429920, 1578014716, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (6, 'file', 2, 'auth/rule', '菜单规则', 'icon-sort', '0', 'Rule0tips', 1, 1497429920, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (7, 'file', 6, 'auth/rule/rule_switch_field', '更改状态', 'icon-round_text_fill', '0', '0', 0, 0, 1578018438, 0, 'normal');
INSERT INTO `hui_auth_rule` VALUES (8, 'file', 6, 'auth/rule/rule_order', '排序', 'icon-round_text_fill', '0', '0', 0, 1577603451, 1578018446, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (9, 'file', 6, 'auth/rule/rule_add', '添加菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577610841, 1578018458, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (10, 'file', 6, 'auth/rule/rule_edit', '修改菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577756921, 1578018464, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (11, 'file', 6, 'auth/rule/rule_delete', '删除菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577756963, 1578018451, 5, 'normal');
INSERT INTO `hui_auth_rule` VALUES (12, 'file', 5, 'auth/group/group_add', '添加角色组', 'icon-round_text_fill', '0', '0', 0, 1577777043, 1578018415, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (13, 'file', 5, 'auth/group/group_edit', '修改角色组', 'icon-round_text_fill', '0', '0', 0, 1577777099, 1578018422, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (14, 'file', 5, 'auth/group/group_delete', '删除角色组', 'icon-round_text_fill', '0', '0', 0, 1577777115, 1578018428, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (15, 'file', 3, 'auth/admin/admin_add', '添加管理员', 'icon-round_text_fill', '0', '0', 0, 1577943638, 1578018368, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (16, 'file', 3, 'auth/admin/admin_edit', '修改管理员', 'icon-round_text_fill', '0', '0', 0, 1577943650, 1578018400, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (17, 'file', 3, 'auth/admin/admin_delete', '删除管理员', 'icon-round_text_fill', '0', '0', 0, 1577943664, 1578018408, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (18, 'file', 1, 'general/adminlog', '管理员日志', 'icon-newsfill', '', '', 1, 1578186256, 1578216053, 98, 'normal');
INSERT INTO `hui_auth_rule` VALUES (19, 'file', 18, 'general/adminlog/detail', '详情', 'icon-round_text_fill', '', '', 0, 1578191905, 1578216036, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (20, 'file', 18, 'general/adminlog/delete', '删除', 'icon-round_text_fill', '', '', 0, 1578191920, 0, 2, 'normal');

SET FOREIGN_KEY_CHECKS = 1;
