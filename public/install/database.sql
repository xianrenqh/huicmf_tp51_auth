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

 Date: 21/03/2020 13:55:44
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
INSERT INTO `hui_admin` VALUES (1, 'admin', 'Admin', 'c09fa70972b70b93163be14a545e23a5', '697a26', '/assets/img/avatar.png', 'admin@admin.com', 0, 1584770131, '127.0.0.1', 1492186163, 1583655724, '62465acf-babd-44d8-96a1-51535702f218', 'normal');

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
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '节点表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_auth_rule
-- ----------------------------
INSERT INTO `hui_auth_rule` VALUES (1, 'file', 0, 'general', '常规管理', 'icon-settings', '0', '0', 1, 1497429920, 1497430169, 11, 'normal');
INSERT INTO `hui_auth_rule` VALUES (2, 'file', 0, 'auth', '权限管理', 'icon-group', '0', '0', 1, 1497429920, 1577703004, 12, 'normal');
INSERT INTO `hui_auth_rule` VALUES (3, 'file', 2, 'auth.admin/index', '管理员管理', 'icon-friendfill', '0', 'Admin0tips', 1, 1497429920, 1578300525, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (4, 'file', 1, 'general.database/index', '数据库管理', 'icon-discoverfill', '0', 'Admin0log0tips', 1, 1497429920, 1578302732, 99, 'normal');
INSERT INTO `hui_auth_rule` VALUES (5, 'file', 2, 'auth.group/index', '角色组', 'icon-group_fill_light', '0', 'Group0tips', 1, 1497429920, 1578381137, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (6, 'file', 2, 'auth.rule/index', '菜单规则', 'icon-round_menu_fill', '0', 'Rule0tips', 1, 1497429920, 1578302765, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (7, 'file', 6, 'auth.rule/rule_switch_field', '更改状态', 'icon-round_text_fill', '0', '0', 0, 0, 1578300629, 0, 'normal');
INSERT INTO `hui_auth_rule` VALUES (8, 'file', 6, 'auth.rule/rule/rule_order', '排序', 'icon-round_text_fill', '0', '0', 0, 1577603451, 1578300640, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (9, 'file', 6, 'auth.rule/rule_add', '添加菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577610841, 1578300663, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (10, 'file', 6, 'auth.rule/rule_edit', '修改菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577756921, 1578300647, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (11, 'file', 6, 'auth.rule/rule_delete', '删除菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577756963, 1578300652, 5, 'normal');
INSERT INTO `hui_auth_rule` VALUES (12, 'file', 5, 'auth.group/group_add', '添加角色组', 'icon-round_text_fill', '0', '0', 0, 1577777043, 1578300224, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (13, 'file', 5, 'auth.group/group_edit', '修改角色组', 'icon-round_text_fill', '0', '0', 0, 1577777099, 1578378879, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (14, 'file', 5, 'auth.group/group_delete', '删除角色组', 'icon-round_text_fill', '0', '0', 0, 1577777115, 1578300240, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (15, 'file', 3, 'auth.admin/admin_add', '添加管理员', 'icon-round_text_fill', '0', '0', 0, 1577943638, 1579256034, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (16, 'file', 3, 'auth.admin/admin_edit', '修改管理员', 'icon-round_text_fill', '0', '0', 0, 1577943650, 1578300545, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (17, 'file', 3, 'auth.admin/admin_delete', '删除管理员', 'icon-round_text_fill', '0', '0', 0, 1577943664, 1578300555, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (18, 'file', 1, 'general.adminlog/index', '管理员日志', 'icon-newsfill', '', '', 1, 1578186256, 1578300731, 98, 'normal');
INSERT INTO `hui_auth_rule` VALUES (19, 'file', 18, 'general.adminlog/detail', '详情', 'icon-round_text_fill', '', '', 0, 1578191905, 1578300751, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (20, 'file', 18, 'general.adminlog/delete', '删除', 'icon-round_text_fill', '', '', 0, 1578191920, 1578300756, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (21, 'file', 1, 'general.profile/index', '个人资料', 'icon-peoplefill', '', '', 1, 1578302691, 1578302998, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (22, 'file', 21, 'general.profile/update', '修改资料', 'icon-round_text_fill', '', '', 0, 1578379925, 1578382485, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (23, 'file', 4, 'general.database/backup', '数据库备份', 'icon-round_text_fill', '', '', 0, 1578382377, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (24, 'file', 4, 'general.database/restore', '数据库还原', 'icon-round_text_fill', '', '', 0, 1578382416, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (25, 'file', 0, 'modules', '模块管理', 'icon-cascades', '', '', 1, 1578529506, 1584759866, 10, 'normal');
INSERT INTO `hui_auth_rule` VALUES (26, 'file', 25, 'link/index', '友情链接管理', 'icon-round_link_fill', '', '', 1, 1578529679, 1578876536, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (27, 'file', 1, 'general.config/index', '系统设置', 'icon-repairfill', '', '', 1, 1578529853, 1578529870, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (28, 'file', 25, 'file/index', '文件管理器', 'icon-form_fill_light', '', '', 1, 1578529983, 1578886060, 99, 'normal');
INSERT INTO `hui_auth_rule` VALUES (29, 'file', 27, 'general.config/update', '修改设置', 'icon-round_text_fill', '', '', 0, 1578530130, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (30, 'file', 27, 'general.config/public_check_ftp', '测试FTP连接', 'icon-round_text_fill', '', '', 0, 1578623211, 1578623224, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (31, 'file', 4, 'general.database/optimize', '优化表', 'icon-round_text_fill', '', '', 0, 1578623434, 1578623499, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (32, 'file', 4, 'general.database/repair', '修复表', 'icon-round_text_fill', '', '', 0, 1578623484, 1578623509, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (33, 'file', 27, 'general.config/save', '保存设置', 'icon-round_text_fill', '', '', 0, 1578726698, 0, 0, 'normal');
INSERT INTO `hui_auth_rule` VALUES (34, 'file', 27, 'general.config/public_mail_test', '发送测试邮件', 'icon-round_text_fill', '', '', 0, 1578726728, 0, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (35, 'file', 28, 'file/edit', '编辑文件', 'icon-round_text_fill', '', '', 0, 1578879148, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (36, 'file', 28, 'file/del', '删除文件', 'icon-round_text_fill', '', '', 0, 1578879186, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (37, 'file', 28, 'file/down', '下载文件', 'icon-round_text_fill', '', '', 0, 1578879207, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (38, 'file', 25, 'upload_test/index', '文件上传测试', 'icon-round_text_fill', '', '', 1, 1579048846, 1579048937, 98, 'normal');
INSERT INTO `hui_auth_rule` VALUES (39, 'file', 26, 'link/add', '添加友情链接', 'icon-round_text_fill', '', '', 0, 1583654505, 1583656187, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (40, 'file', 26, 'link/edit', '修改友情链接', 'icon-round_text_fill', '', '', 0, 1583654520, 1583656192, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (41, 'file', 26, 'link/delete', '删除友情链接', 'icon-round_text_fill', '', '', 0, 1583654534, 1583656196, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (42, 'file', 26, 'link/listorder_edit', '友情链接排序', 'icon-round_text_fill', '', '', 0, 1583654550, 1583656200, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (43, 'file', 25, 'banner/index', '幻灯片管理', 'icon-picfill', '', '', 1, 1584002150, 1584002267, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (44, 'file', 43, 'banner/add', '添加幻灯片', 'icon-round_text_fill', '', '', 0, 1584002283, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (45, 'file', 43, 'banner/edit', '修改幻灯片', 'icon-round_text_fill', '', '', 0, 1584002307, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (46, 'file', 43, 'banner/delete', '删除幻灯片', 'icon-round_text_fill', '', '', 0, 1584002403, 0, 3, 'normal');

-- ----------------------------
-- Table structure for hui_banner
-- ----------------------------
DROP TABLE IF EXISTS `hui_banner`;
CREATE TABLE `hui_banner`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `image` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `url` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `inputtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `listorder` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `typeid` tinyint(2) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1显示0隐藏',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `typeid`(`typeid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for hui_banner_type
-- ----------------------------
DROP TABLE IF EXISTS `hui_banner_type`;
CREATE TABLE `hui_banner_type`  (
  `tid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`tid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for hui_config
-- ----------------------------
DROP TABLE IF EXISTS `hui_config`;
CREATE TABLE `hui_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置类型',
  `title` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置值',
  `fieldtype` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '字段类型',
  `setting` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字段设置',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  `tips` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hui_config
-- ----------------------------
INSERT INTO `hui_config` VALUES (1, 'site_name', 1, '站点名称', 'HuiCmf - 演示站3', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (2, 'site_url', 1, '站点跟网址', 'http://test.xiaohuihui.club/', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (3, 'admin_log', 3, '启用后台管理操作日志', '1', 'radio', '', 1, '');
INSERT INTO `hui_config` VALUES (4, 'site_keyword', 1, '站点关键字', 'huicmf', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (5, 'site_copyright', 1, '网站版权信息', 'Powered By HuiCMF内容管理系统 © 2018-2020 小灰灰工作室', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (6, 'site_beian', 1, '站点备案号', '京ICP备666666号', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (7, 'site_description', 1, '站点描述', '我是描述', 'text', '', 1, '');
INSERT INTO `hui_config` VALUES (8, 'site_code', 1, '统计代码', '', 'text', '', 1, '');
INSERT INTO `hui_config` VALUES (9, 'admin_prohibit_ip', 3, '禁止登录后台的IP', '', 'text', '', 1, '');
INSERT INTO `hui_config` VALUES (10, 'mail_server', 4, 'SMTP服务器', 'smtp.exmail.qq.com', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (11, 'mail_port', 4, 'SMTP服务器端口', '465', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (13, 'mail_user', 4, 'SMTP服务器的用户帐号', '', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (14, 'mail_pass', 4, 'SMTP服务器的用户密码', '', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (15, 'mail_inbox', 4, '收件邮箱地址', '', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (16, 'mail_auth', 4, 'AUTH LOGIN验证', '1', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (17, 'login_code', 3, '后台登录验证码', '0', 'radio', '', 1, '');
INSERT INTO `hui_config` VALUES (18, 'upload_maxsize', 2, '允许上传附件大小', '3048', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (19, 'watermark_enable', 2, '是否开启图片水印', '1', 'radio', '{\"0\":\"否\",\"1\":\"是\"}', 1, '');
INSERT INTO `hui_config` VALUES (20, 'watermark_name', 2, '水印图片名称', 'mark.png', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (21, 'watermark_position', 2, '水印的位置', '9', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (22, 'watermark_touming', 2, '水印透明度', '73', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (23, 'upload_types', 2, '允许上传类型', 'zip|rar|mp3|mp4|jpg|jpeg|png|gif|bmp', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (24, 'upload_mode', 2, '图片上传方式', 'local', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (25, 'ftp_host', 2, 'FTP服务器地址', '45.32.214.79', 'string', ' ', 1, '');
INSERT INTO `hui_config` VALUES (26, 'ftp_port', 2, 'FTP端口', '21', 'string', ' ', 1, '');
INSERT INTO `hui_config` VALUES (27, 'ftp_user', 2, 'FTP账号', 'ftp_com', 'string', ' ', 1, '');
INSERT INTO `hui_config` VALUES (28, 'ftp_pwd', 2, 'FTP密码', 'sJ65wTnhmYPe2k5A', 'string', ' ', 1, '');
INSERT INTO `hui_config` VALUES (29, 'ftp_url', 2, '外链url地址', 'http://222.com', 'string', ' ', 1, '');
INSERT INTO `hui_config` VALUES (30, 'file_path', 2, '文件保存路径', '/uploads/', 'string', ' ', 1, '');

-- ----------------------------
-- Table structure for hui_link
-- ----------------------------
DROP TABLE IF EXISTS `hui_link`;
CREATE TABLE `hui_link`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `typeid` smallint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1首页,2列表页,3内容页',
  `linktype` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0:文字链接,1:logo链接',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `msg` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `listorder` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0未通过,1正常,2未审核',
  `addtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_typeid`(`typeid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
