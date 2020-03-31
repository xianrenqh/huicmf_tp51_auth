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

 Date: 31/03/2020 14:19:39
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
INSERT INTO `hui_admin` VALUES (1, 'admin', 'Admin', 'c09fa70972b70b93163be14a545e23a5', '697a26', '/assets/img/avatar.png', 'admin@admin.com', 0, 1585633261, '127.0.0.1', 1492186163, 1583655724, '3de16292-debd-4980-95a0-4c7d7f49ec4f', 'normal');

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
-- Table structure for hui_article
-- ----------------------------
DROP TABLE IF EXISTS `hui_article`;
CREATE TABLE `hui_article`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `userid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nickname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `title` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `seo_title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `inputtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `keywords` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `click` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `copyfrom` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `thumb` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `flag` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '1置顶,2头条,3特荐,4推荐,5热点,6幻灯,7跳转',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `system` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `groupids_view` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读权限',
  `readpoint` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读收费',
  `is_push` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否百度推送',
  `test` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`, `listorder`) USING BTREE,
  INDEX `catid`(`catid`, `status`) USING BTREE,
  INDEX `userid`(`userid`, `status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hui_article
-- ----------------------------
INSERT INTO `hui_article` VALUES (1, 2, 1, 'yzmcms', '袁志蒙', 'YZMPHP轻量级开源框架2.0', 'YZMPHP轻量级开源框架2.0_YzmCMS - 演示站', 1526387722, 1577414952, 'PHP', '简介：YZMPHP是一款免费开源的轻量级PHP框架，框架完全采用面向对象的设计思想，并且是基于MVC的三层设计模式。具有部署和应用及为简单、效...', 103, '<p><img src=\"/uploads/ueditor/image/20191122/1574408124438686.jpg\" title=\"YZMPHP轻量级开源框架2.0\" alt=\"YZMPHP轻量级开源框架2.0\"/></p><p><strong>简介:</strong></p><p>YZMPHP是一款免费开源的轻量级PHP框架，框架完全采用面向对象的设计思想，并且是基于MVC的三层设计模式。具有部署和应用及为简单、效率高、速度快，扩展性和可维护性都很好等特点。</p><p>2016年12月19日完成框架的1.0版本，经过近两年的磨炼与成长，今日发布YZMPHP 2.0版本，该框架已经被多家公司企业采用和认可，是一款简单强大的PHP框架。上手快、框架源码简单明了结构清析，使得项目开发更加容易和方便，使用YZMPHP框架适合开发BBS、电子商城、SNS、CMS、Blog、企业门户等任何的中小型系统！</p><p><br/></p><p><strong>特点：</strong></p><p>简洁、高效、轻量级、高性能</p><p>软件环境：Apache/Nginx/IIS</p><p>PHP：支持PHP5.2至7.2之间的所有版本</p><p><br/></p><p><strong>YZMPHP 2.0更新日志：</strong></p><p>1.新增：框架命令模式,可自定义或新增命令;</p><p>2.新增：缓存类型配置，支持类型:file/redis/memcache;</p><p>3.新增：系统URL路由映射重写;</p><p>4.新增：DB类库事务处理;</p><p>5.新增：支持切换和链接其他数据库;</p><p>6.新增：DB类库多种操作数据库方法;</p><p>7.新增：Nginx支持PATHINFO模式配置;</p><p>8.新增：系统函数库多种方法;</p><p>9.新增：支持捕捉致命错误;</p><p>10.优化：数据对象单例模式;</p><p>11.优化：支持join多表链接查询;</p><p>12.修复：框架漏洞一枚;</p><p>本次更新优化内容包括但不限于以上所列举的项！</p><p><br/></p>', '原创', '/uploads/201911/22/191122105427318.jpg', 'http://yzmcms2.cn/index/index/show/catid/2/id/1.html', '', 1, 1, 10, 0, 0, 0, '');
INSERT INTO `hui_article` VALUES (2, 2, 1, 'yzmcms', '袁志蒙', 'YzmCMS v5.4正式版发布', 'YzmCMS v5.4正式版发布_YzmCMS - 演示站', 1571500800, 1577350500, '文章,PHP', '产品说明：YzmCMS是一款轻量级开源内容管理系统，它采用OOP（面向对象）方式自主开发的框架。基于PHP+Mysql架构，并采用MVC框架式开发的一...', 107, '<p><strong style=\"color: red;\">产品说明：</strong></p><p>YzmCMS是一款轻量级开源内容管理系统，它采用OOP（面向对象）方式自主开发的框架。基于PHP+Mysql架构，并采用MVC框架式开发的一款高效开源的内容管理系统，可运行在Linux、Windows、MacOSX、Solaris等各种平台上。</p><p>它可以让您不需要任何专业技术轻松搭建您需要的网站，操作简单，很容易上手，快捷方便的后台操作让您10分钟就会建立自己的爱站。在同类产品的比较中，YzmCMS更是凸显出了体积轻巧、功能强大、源码简洁、系统安全等特点，无论你是做企业网站、新闻网站、个人博客、门户网站、行业网站、电子商城等，它都能完全胜任，而且还提供了非常方便的二次开发体系，是一款全能型的建站系统！</p><p><br/></p><p>下载地址：<a href=\"http://www.yzmcms.com/xiazai/\" target=\"_blank\" style=\"color:blue\">官方下载</a></p>', '原创', '/uploads/201911/22/191122082530443.jpg', 'http://yzmcms2.cn/index/index/show/catid/2/id/2.html', '', 1, 1, 10, 0, 0, 0, '');

-- ----------------------------
-- Table structure for hui_attachment
-- ----------------------------
DROP TABLE IF EXISTS `hui_attachment`;
CREATE TABLE `hui_attachment`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片帧数',
  `filesize` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `mimetype` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '附件表' ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_auth_group
-- ----------------------------
INSERT INTO `hui_auth_group` VALUES (1, 0, '超级管理员', '*', 1490883540, 149088354, 'normal');
INSERT INTO `hui_auth_group` VALUES (2, 1, '总编', '56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,73,74,75,76,71,72,25,26,39,40,41,42,43,44,45,46,54,55,28,35,36,37,38,1,21,22,27,33,29,30,34,49,51,52,53', 1585633358, NULL, 'normal');
INSERT INTO `hui_auth_group` VALUES (3, 2, '发布人员', '56,57,58,59,61,62,63,64,25,26,39,40,42,43,44,45,38,1,21,22,49,51,52', 1585633454, NULL, 'normal');

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
) ENGINE = InnoDB AUTO_INCREMENT = 82 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '节点表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_auth_rule
-- ----------------------------
INSERT INTO `hui_auth_rule` VALUES (1, 'file', 0, 'general', '常规管理', 'icon-settings', '0', '0', 1, 1497429920, 1497430169, 11, 'normal');
INSERT INTO `hui_auth_rule` VALUES (2, 'file', 0, 'auth', '权限管理', 'icon-group', '0', '0', 1, 1497429920, 1577703004, 21, 'normal');
INSERT INTO `hui_auth_rule` VALUES (3, 'file', 2, 'auth.admin/index', '管理员管理', 'icon-friendfill', '0', 'Admin0tips', 1, 1497429920, 1578300525, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (4, 'file', 1, 'general.database/index', '数据库管理', 'icon-discoverfill', '0', 'Admin0log0tips', 1, 1497429920, 1578302732, 98, 'normal');
INSERT INTO `hui_auth_rule` VALUES (5, 'file', 2, 'auth.group/index', '角色组', 'icon-group_fill_light', '0', 'Group0tips', 1, 1497429920, 1578381137, 5, 'normal');
INSERT INTO `hui_auth_rule` VALUES (6, 'file', 2, 'auth.rule/index', '菜单规则', 'icon-round_menu_fill', '0', 'Rule0tips', 1, 1497429920, 1578302765, 6, 'normal');
INSERT INTO `hui_auth_rule` VALUES (7, 'file', 6, 'auth.rule/rule_switch_field', '更改状态', 'icon-round_text_fill', '0', '0', 0, 0, 1578300629, 7, 'normal');
INSERT INTO `hui_auth_rule` VALUES (8, 'file', 6, 'auth.rule/rule/rule_order', '排序', 'icon-round_text_fill', '0', '0', 0, 1577603451, 1578300640, 8, 'normal');
INSERT INTO `hui_auth_rule` VALUES (9, 'file', 6, 'auth.rule/rule_add', '添加菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577610841, 1578300663, 9, 'normal');
INSERT INTO `hui_auth_rule` VALUES (10, 'file', 6, 'auth.rule/rule_edit', '修改菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577756921, 1578300647, 10, 'normal');
INSERT INTO `hui_auth_rule` VALUES (11, 'file', 6, 'auth.rule/rule_delete', '删除菜单规则', 'icon-round_text_fill', '0', '0', 0, 1577756963, 1578300652, 11, 'normal');
INSERT INTO `hui_auth_rule` VALUES (12, 'file', 5, 'auth.group/group_add', '添加角色组', 'icon-round_text_fill', '0', '0', 0, 1577777043, 1578300224, 12, 'normal');
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
INSERT INTO `hui_auth_rule` VALUES (28, 'file', 25, 'file/index', '文件管理器', 'icon-form_fill_light', '', '', 1, 1578529983, 1578886060, 98, 'normal');
INSERT INTO `hui_auth_rule` VALUES (29, 'file', 27, 'general.config/update', '修改设置', 'icon-round_text_fill', '', '', 0, 1578530130, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (30, 'file', 27, 'general.config/public_check_ftp', '测试FTP连接', 'icon-round_text_fill', '', '', 0, 1578623211, 1578623224, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (31, 'file', 4, 'general.database/optimize', '优化表', 'icon-round_text_fill', '', '', 0, 1578623434, 1578623499, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (32, 'file', 4, 'general.database/repair', '修复表', 'icon-round_text_fill', '', '', 0, 1578623484, 1578623509, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (33, 'file', 27, 'general.config/save', '保存设置', 'icon-round_text_fill', '', '', 0, 1578726698, 0, 0, 'normal');
INSERT INTO `hui_auth_rule` VALUES (34, 'file', 27, 'general.config/public_mail_test', '发送测试邮件', 'icon-round_text_fill', '', '', 0, 1578726728, 0, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (35, 'file', 28, 'file/edit', '编辑文件', 'icon-round_text_fill', '', '', 0, 1578879148, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (36, 'file', 28, 'file/del', '删除文件', 'icon-round_text_fill', '', '', 0, 1578879186, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (37, 'file', 28, 'file/down', '下载文件', 'icon-round_text_fill', '', '', 0, 1578879207, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (38, 'file', 25, 'upload_test/index', '文件上传测试', 'icon-round_text_fill', '', '', 1, 1579048846, 1579048937, 99, 'normal');
INSERT INTO `hui_auth_rule` VALUES (39, 'file', 26, 'link/add', '添加友情链接', 'icon-round_text_fill', '', '', 0, 1583654505, 1583656187, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (40, 'file', 26, 'link/edit', '修改友情链接', 'icon-round_text_fill', '', '', 0, 1583654520, 1583656192, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (41, 'file', 26, 'link/delete', '删除友情链接', 'icon-round_text_fill', '', '', 0, 1583654534, 1583656196, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (42, 'file', 26, 'link/listorder_edit', '友情链接排序', 'icon-round_text_fill', '', '', 0, 1583654550, 1583656200, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (43, 'file', 25, 'banner/index', '幻灯片管理', 'icon-picfill', '', '', 1, 1584002150, 1584002267, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (44, 'file', 43, 'banner/add', '添加幻灯片', 'icon-round_text_fill', '', '', 0, 1584002283, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (45, 'file', 43, 'banner/edit', '修改幻灯片', 'icon-round_text_fill', '', '', 0, 1584002307, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (46, 'file', 43, 'banner/delete', '删除幻灯片', 'icon-round_text_fill', '', '', 0, 1584002403, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (49, 'file', 1, 'general.config/user_config', '自定义配置', 'icon-settings_light', '', '', 1, 1584774186, 1584774296, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (51, 'file', 49, 'general.config/user_config_add', '添加自定义配置', 'icon-round_text_fill', '', '', 0, 1584774803, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (52, 'file', 49, 'general.config/user_config_edit', '修改自定义配置', 'icon-round_text_fill', '', '', 0, 1584774864, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (53, 'file', 49, 'general.config/user_config_delete', '删除自定义配置', 'icon-round_text_fill', '', '', 0, 1584774947, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (54, 'file', 25, 'pay/index', '支付模块', 'icon-rechargefill', '', '', 1, 1584837226, 0, 97, 'normal');
INSERT INTO `hui_auth_rule` VALUES (55, 'file', 54, 'pay/edit', '编辑支付模块', 'icon-round_text_fill', '', '', 0, 1584857404, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (56, 'file', 0, 'content/init', '内容管理', 'icon-calendar', '', '', 1, 1585021064, 1585021210, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (57, 'file', 56, 'category/index', '分类管理', 'icon-ticket_fill', '', '', 1, 1585021152, 1585028819, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (58, 'file', 57, 'category/add', '添加分类', 'icon-round_text_fill', '', '', 0, 1585028951, 1585447788, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (59, 'file', 57, 'category/edit', '修改分类', 'icon-round_text_fill', '', '', 0, 1585028964, 1585028971, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (60, 'file', 57, 'category/delete', '删除分类', 'icon-round_text_fill', '', '', 0, 1585028985, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (61, 'file', 57, 'category/order', '排序分类', 'icon-round_text_fill', '', '', 0, 1585029001, 0, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (62, 'file', 56, 'tag/index', 'TAG管理', 'icon-tagfill', '', '', 1, 1585269162, 0, 11, 'normal');
INSERT INTO `hui_auth_rule` VALUES (63, 'file', 62, 'tag/add', '添加tag', 'icon-round_text_fill', '', '', 0, 1585270667, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (64, 'file', 62, 'tag/edit', '修改tag', 'icon-round_text_fill', '', '', 0, 1585270680, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (65, 'file', 62, 'tag/delete', '删除tag', 'icon-round_text_fill', '', '', 0, 1585270695, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (66, 'file', 56, 'sitemodel/index', '模型管理', 'icon-taoxiaopu', '', '', 1, 1585379423, 0, 12, 'normal');
INSERT INTO `hui_auth_rule` VALUES (67, 'file', 66, 'sitemodel/add', '添加模型', 'icon-round_text_fill', '', '', 0, 1585379779, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (68, 'file', 66, 'sitemodel/edit', '修改模型', 'icon-round_text_fill', '', '', 0, 1585379795, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (69, 'file', 66, 'sitemodel/delete', '删除模型', 'icon-round_text_fill', '', '', 0, 1585379814, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (70, 'file', 66, 'model_field/index', '模型字段管理', 'icon-round_text_fill', '', '', 0, 1585379836, 1585450303, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (71, 'file', 66, 'sitemodel/export', '导出模型', 'icon-round_text_fill', '', '', 0, 1585379857, 0, 5, 'normal');
INSERT INTO `hui_auth_rule` VALUES (72, 'file', 66, 'sitemodel/import', '导入模型', 'icon-round_text_fill', '', '', 0, 1585379875, 0, 6, 'normal');
INSERT INTO `hui_auth_rule` VALUES (73, 'file', 70, 'model_field/add', '添加模型字段', 'icon-round_text_fill', '', '', 0, 1585450323, 0, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (74, 'file', 70, 'model_field/edit', '修改模型字段', 'icon-round_text_fill', '', '', 0, 1585450344, 0, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (75, 'file', 70, 'model_field/delete', '删除模型字段', 'icon-round_text_fill', '', '', 0, 1585450360, 0, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (76, 'file', 70, 'model_field/order', '排序模型字段', 'icon-round_text_fill', '', '', 0, 1585450420, 0, 4, 'normal');
INSERT INTO `hui_auth_rule` VALUES (77, 'file', 25, 'banner/cat_manage', '幻灯分类管理（包含删除）', 'icon-round_text_fill', '', '', 0, 1585633690, 1585633861, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (78, 'file', 77, 'banner/cat_add', '添加幻灯分类', 'icon-round_text_fill', '', '', 0, 1585633735, NULL, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (79, 'file', 1, 'general.attachment/index', '附件管理', 'icon-picfill', '', '', 1, 1585634129, NULL, 11, 'normal');
INSERT INTO `hui_auth_rule` VALUES (80, 'file', 79, 'general.attachment/delete', '删除附件', 'icon-round_text_fill', '', '', 0, 1585634150, NULL, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (81, 'file', 56, 'content/index', '内容管理', 'icon-edit', '', '', 1, 1585634694, 1585635304, 1, 'normal');

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
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of hui_banner_type
-- ----------------------------
INSERT INTO `hui_banner_type` VALUES (1, '首页');

-- ----------------------------
-- Table structure for hui_category
-- ----------------------------
DROP TABLE IF EXISTS `hui_category`;
CREATE TABLE `hui_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `modelid` int(3) DEFAULT NULL COMMENT '模型类型',
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `flag` set('hot','index','recommend') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `image` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `seo_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'seo标题',
  `seo_keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `seo_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '自定义名称',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '状态',
  `category_template` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '频道页模板',
  `list_template` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '列表页模板',
  `show_template` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '内容页模板',
  `pc_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '链接',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `weigh`(`weigh`, `id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '分类表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_category
-- ----------------------------
INSERT INTO `hui_category` VALUES (1, 0, 1, '1', '新闻中心', 'xinwenzhongxin', '', '', '', '', '', '', 1585379114, 0, 0, '1', 'category_article', 'list_article', 'show_article', '');

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
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hui_config
-- ----------------------------
INSERT INTO `hui_config` VALUES (1, 'site_name', 1, '站点名称', 'HuiCmf - 演示站3', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (2, 'site_url', 1, '站点跟网址', 'http://test.xiaohuihui.club/', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (3, 'admin_log', 3, '启用后台管理操作日志', '0', 'radio', '', 1, '');
INSERT INTO `hui_config` VALUES (4, 'site_keyword', 1, '站点关键字', 'huicmf', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (5, 'site_copyright', 1, '网站版权信息', 'Powered By HuiCMF内容管理系统 © 2018-2020 小灰灰工作室', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (6, 'site_beian', 1, '站点备案号', '京ICP备666666号', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (7, 'site_description', 1, '站点描述', '我是描述', 'text', '', 1, '');
INSERT INTO `hui_config` VALUES (8, 'site_code', 1, '统计代码', '', 'text', '', 1, '');
INSERT INTO `hui_config` VALUES (9, 'admin_prohibit_ip', 3, '禁止登录后台的IP', '', 'text', '', 1, '');
INSERT INTO `hui_config` VALUES (10, 'mail_server', 4, 'SMTP服务器', 'smtp.exmail.qq.com', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (11, 'mail_port', 4, 'SMTP服务器端口', '465', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (12, 'site_theme', 1, '模板风格', 'default', 'textarea', '', 1, '');
INSERT INTO `hui_config` VALUES (13, 'mail_user', 4, 'SMTP服务器的用户帐号', '', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (14, 'mail_pass', 4, 'SMTP服务器的用户密码', '', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (15, 'mail_inbox', 4, '收件邮箱地址', '', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (16, 'mail_auth', 4, 'AUTH LOGIN验证', '1', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (17, 'login_code', 3, '后台登录验证码', '2', 'radio', '', 1, '');
INSERT INTO `hui_config` VALUES (18, 'upload_maxsize', 2, '允许上传附件大小', '3048', 'string', '', 1, '');
INSERT INTO `hui_config` VALUES (19, 'watermark_enable', 2, '是否开启图片水印', '0', 'radio', '{\"0\":\"否\",\"1\":\"是\"}', 1, '');
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
INSERT INTO `hui_config` VALUES (31, 'site_qq', 99, '站长QQ', '123456', 'textarea', '', 1, '');
INSERT INTO `hui_config` VALUES (32, 'JY_captcha_id', 3, '极验验证码ID', '48a6ebac4ebc6642d68c217fca33eb4d', 'textarea', ' ', 1, '');
INSERT INTO `hui_config` VALUES (33, 'JY_captcha_key', 3, '极验验证码KEY', '4f1c085290bec5afdc54df73535fc361', 'textarea', ' ', 1, '');

-- ----------------------------
-- Table structure for hui_download
-- ----------------------------
DROP TABLE IF EXISTS `hui_download`;
CREATE TABLE `hui_download`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `userid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nickname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `title` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `seo_title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `inputtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `keywords` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `click` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `copyfrom` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `thumb` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `flag` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '1置顶,2头条,3特荐,4推荐,5热点,6幻灯,7跳转',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `system` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `groupids_view` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读权限',
  `readpoint` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读收费',
  `is_push` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否百度推送',
  `down_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '下载地址',
  `copytype` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '授权形式',
  `systems` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '平台',
  `language` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '语言',
  `version` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '版本',
  `filesize` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件大小',
  `classtype` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '软件类型',
  `stars` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '评分等级',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`, `listorder`) USING BTREE,
  INDEX `catid`(`catid`, `status`) USING BTREE,
  INDEX `userid`(`userid`, `status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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

-- ----------------------------
-- Table structure for hui_model
-- ----------------------------
DROP TABLE IF EXISTS `hui_model`;
CREATE TABLE `hui_model`  (
  `modelid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `tablename` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `setting` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `inputtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `items` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `disabled` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `issystem` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`modelid`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hui_model
-- ----------------------------
INSERT INTO `hui_model` VALUES (1, '文章模型', 'article', '文章模型', '', 1466393786, 0, 0, 0, 0, 1);
INSERT INTO `hui_model` VALUES (2, '产品模型', 'product', '产品模型', '', 1585386661, 0, 0, 0, 0, 1);
INSERT INTO `hui_model` VALUES (3, '下载模型', 'download', '下载模型', '', 1585386669, 0, 1, 0, 0, 1);

-- ----------------------------
-- Table structure for hui_model_field
-- ----------------------------
DROP TABLE IF EXISTS `hui_model_field`;
CREATE TABLE `hui_model_field`  (
  `fieldid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `field` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `tips` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `css` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `minlength` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `maxlength` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `errortips` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `fieldtype` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `defaultvalue` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `setting` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `isrequired` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `issystem` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `isunique` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `isadd` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `listorder` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `disabled` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`fieldid`) USING BTREE,
  INDEX `modelid`(`modelid`, `disabled`) USING BTREE,
  INDEX `field`(`field`, `modelid`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 39 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hui_model_field
-- ----------------------------
INSERT INTO `hui_model_field` VALUES (1, 0, 'title', '标题', '', '', 1, 100, '请输入标题', 'input', '', '', 1, 1, 0, 1, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (2, 0, 'seo_title', 'SEO标题', '', '', 0, 100, '', 'input', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (3, 0, 'catid', '栏目', '', '', 1, 10, '请选择栏目', 'select', '', '', 1, 1, 0, 1, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (4, 0, 'thumb', '缩略图', '', '', 0, 100, '', 'image', '', '', 0, 1, 0, 1, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (5, 0, 'keywords', '关键词', '', '', 0, 50, '', 'input', '', '', 0, 1, 0, 1, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (6, 0, 'description', '摘要', '', '', 0, 255, '', 'textarea', '', '', 0, 1, 0, 1, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (7, 0, 'inputtime', '发布时间', '', '', 1, 10, '', 'datetime', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (8, 0, 'updatetime', '更新时间', '', '', 1, 10, '', 'datetime', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (9, 0, 'copyfrom', '来源', '', '', 0, 30, '', 'input', '', '', 0, 1, 0, 1, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (10, 0, 'url', 'URL', '', '', 1, 100, '', 'input', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (11, 0, 'userid', '用户ID', '', '', 1, 10, '', 'input', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (12, 0, 'username', '用户名', '', '', 1, 30, '', 'input', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (13, 0, 'nickname', '昵称', '', '', 0, 30, '', 'input', '', '', 0, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (14, 0, 'template', '模板', '', '', 1, 50, '', 'select', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (15, 0, 'content', '内容', '', '', 1, 999999, '', 'editor', '', '', 1, 1, 0, 1, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (16, 0, 'click', '点击数', '', '', 1, 10, '', 'input', '0', '', 0, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (17, 0, 'tag', 'TAG', '', '', 0, 50, '', 'checkbox', '', '', 0, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (18, 0, 'readpoint', '阅读收费', '', '', 1, 5, '', 'input', '0', '', 0, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (19, 0, 'groupids_view', '阅读权限', '', '', 1, 10, '', 'checkbox', '1', '', 0, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (20, 0, 'status', '状态', '', '', 1, 2, '', 'checkbox', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (21, 0, 'flag', '属性', '', '', 1, 16, '', 'checkbox', '', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (22, 0, 'listorder', '排序', '', '', 1, 5, '', 'input', '1', '', 1, 1, 0, 0, 0, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (23, 2, 'brand', '品牌', '', '', 0, 30, '', 'input', '', '', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (24, 2, 'standard', '型号', '', '', 0, 30, '', 'input', '', '', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (25, 2, 'yieldly', '产地', '', '', 0, 50, '', 'input', '', '', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (26, 2, 'pictures', '产品图集', '', '', 0, 1000, '', 'images', '', '', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (27, 2, 'price', '单价', '请输入单价', '', 1, 10, '单价不能为空', 'input', '', '', 1, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (28, 2, 'unit', '价格单位', '', '', 1, 10, '', 'select', '', '{\\\"0\\\":\\\"\\\\u4ef6\\\",\\\"1\\\":\\\"\\\\u65a4\\\",\\\"2\\\":\\\"KG\\\",\\\"3\\\":\\\"\\\\u5428\\\",\\\"4\\\":\\\"\\\\u5957\\\"}', 1, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (29, 2, 'stock', '库存', '库存量必须为数字', '', 1, 5, '库存不能为空', 'input', '99999', '', 1, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (30, 3, 'down_url', '下载地址', '', '', 1, 100, '下载地址不能为空', 'attachment', '', '', 1, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (31, 3, 'copytype', '授权形式', '', '', 0, 20, '', 'select', '', '{\\\"0\\\":\\\"\\\\u514d\\\\u8d39\\\\u7248\\\",\\\"1\\\":\\\"\\\\u6b63\\\\u5f0f\\\\u7248\\\",\\\"2\\\":\\\"\\\\u5171\\\\u4eab\\\\u7248\\\",\\\"3\\\":\\\"\\\\u8bd5\\\\u7528\\\\u7248\\\",\\\"4\\\":\\\"\\\\u6f14\\\\u793a\\\\u7248\\\",\\\"5\\\":\\\"\\\\u6ce8\\\\u518c\\\\u7248\\\",\\\"6\\\":\\\"\\\\u7834\\\\u89e3\\\\u7248\\\"}', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (32, 3, 'systems', '平台', '', '', 1, 30, '', 'select', '', '{\\\"0\\\":\\\"Windows\\\",\\\"1\\\":\\\"Linux\\\",\\\"2\\\":\\\"MacOS\\\"}', 1, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (33, 3, 'language', '语言', '', '', 0, 20, '', 'select', '', '{\\\"0\\\":\\\"\\\\u7b80\\\\u4f53\\\\u4e2d\\\\u6587\\\",\\\"1\\\":\\\"\\\\u7e41\\\\u4f53\\\\u4e2d\\\\u6587\\\",\\\"2\\\":\\\"\\\\u82f1\\\\u6587\\\",\\\"3\\\":\\\"\\\\u591a\\\\u56fd\\\\u8bed\\\\u8a00\\\",\\\"4\\\":\\\"\\\\u5176\\\\u4ed6\\\\u8bed\\\\u8a00\\\"}', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (34, 3, 'version', '版本', '', '', 1, 15, '版本号不能为空', 'input', '', '', 1, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (35, 3, 'filesize', '文件大小', '', '', 0, 10, '', 'input', '', '', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (36, 3, 'classtype', '软件类型', '', '', 1, 30, '', 'radio', '', '{\\\"0\\\":\\\"\\\\u56fd\\\\u4ea7\\\\u8f6f\\\\u4ef6\\\",\\\"1\\\":\\\"\\\\u56fd\\\\u5916\\\\u8f6f\\\\u4ef6\\\",\\\"2\\\":\\\"\\\\u6c49\\\\u5316\\\\u8865\\\\u4e01\\\",\\\"3\\\":\\\"\\\\u7a0b\\\\u5e8f\\\\u6e90\\\\u7801\\\",\\\"4\\\":\\\"\\\\u5176\\\\u4ed6\\\"}', 1, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (37, 3, 'stars', '评分等级', '', '', 0, 20, '', 'radio', '', '{\\\"0\\\":\\\"\\\\u4e00\\\\u661f\\\",\\\"1\\\":\\\"\\\\u4e8c\\\\u661f\\\",\\\"2\\\":\\\"\\\\u4e09\\\\u661f\\\",\\\"3\\\":\\\"\\\\u56db\\\\u661f\\\",\\\"4\\\":\\\"\\\\u4e94\\\\u661f\\\"}', 0, 0, 0, 1, 1, 0, 0, 1);
INSERT INTO `hui_model_field` VALUES (38, 1, 'test', '测试字段', '测试字段1', '', 0, 100, '', 'textarea', '0', '', 0, 0, 0, 0, 1, 0, 0, 1);

-- ----------------------------
-- Table structure for hui_pay_mode
-- ----------------------------
DROP TABLE IF EXISTS `hui_pay_mode`;
CREATE TABLE `hui_pay_mode`  (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `desc` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `version` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `action` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '支付调用方法',
  `template` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hui_pay_mode
-- ----------------------------
INSERT INTO `hui_pay_mode` VALUES (1, '【官方】支付宝', 'alipay.png', '支付宝新版在线支付插件，要求PHP版本&gt;=5.5 .', '{\\\"app_id\\\":\\\"2018021002172596\\\",\\\"merchant_private_key\\\":\\\"MIIEowIBAAKCAQEApPbtJv5Xsd9+\\\\/U8UJqZnAfVt76x0UQ631hjMKZ9mVn5rA4aAKypEuxupnmLsUX61tVIGXWd+bLh0fGJEOL9VGzDypneilqJ4maS9g\\\\/htZZq8o8w6oXpEWFPBvaT46y0hI+QN9g34JefK00UvyrVrE1EzjRPCt\\\\/gSZLWRgrA8tBAmfXtgzEJg+OMtDjhftAX1XfpvZ0i\\\\/07g2a+ZI0KjOvnr9GEW6LH0agUOwe5QIaH\\\\/vjo+9A2tvI4Lto1q\\\\/AKzDOjyAaGgwiadTG\\\\/wOXwENrVGcgOtSgkiftpi55rlJLy1sxA3GGZl4TPZ6qbYYzD2CuRoN3kcupXFEjRLmFNJnbQIDAQABAoIBAFmTqBVLeU0iIm\\\\/kd\\\\/nA1CBxKCBEUekOB+9fCcX0Q3rmLK\\\\/+YiyOSEId9E4IQ3DBUGRERSaFI1ZgFwjPx2HVK56XRrv0LLqFQN2VYj9+L9FDY\\\\/nB1XiHzwLzgDm9klkJ6Xv2w0oALeZPZoiwghIdzyXvKwIJX+vL7hj3qyr\\\\/Dgdr+mZ+PMr0n23EgKCR1ihwBY\\\\/vpvpPeJoucgKp3JoXJunRtR7yfptt5UY0qeW7e1JiTEt3V9eAehxNUa2\\\\/sBh2LWgU91oJ8NPJ3a8sZfydTJ734Icdi\\\\/hItpPxMl5AhhVvImeU8B4L7NMVztX9u+S0V1oLT+cIZ08YOXb+ltNEkdUCgYEA00M9T8K2DUchiaRI+Fl+3XTTnS8qBteVc4TA7DHs9Cm1U+aK\\\\/fdy\\\\/18NaICEtT2UbhtZB+Q+oL\\\\/x8TGEBusO7KmvUCO3JXPE0RMwsSIDvN1oMJ+AAj\\\\/4jnDVI9Es6ChkExrz1JdBAoDLfvY+m+woqPwbCczifJRvrdI7qimlWUMCgYEAx+XSDVEZrAWxODiMKYlmcQTGD2bAzZcJOtAZM+ApZgXYLYJjJDaeU2p60khhrWB43Os6Ty6Nf6nQ+xlXCHq+JU3qBsoui2LO\\\\/tJ10tFzI3nWGlkQMOrl4EAXAF\\\\/HwLz4lbcPzXKTqFNYoJ3cMxZG0TB\\\\/55mpBK6JLXpoLhJqGY8CgYEAj9x1396jwh7yA5zP4+5tqbhocd0wLUCZEPURusW0qf\\\\/M7rlLZO4gbS35H3SE6jZo4SHWWr1euHB8\\\\/NXPJjbjDt3t+BCaIvLWz9jVi4myKeZLkPDMZrvRMo47VgyXG\\\\/CFVU1BRT9Kkb3K1UYyFFOYzJoAf4f5owQhmKhU4OdDJakCgYBnP2MhEbSKz13bxglPIvLQiUCrj80h41MXF4kM7Ek1susXhnMirztTpnnNxyj8XgnPQYgagdcAC3wvVcVIUe2IYxfvbdpgkCaOI7JLs3ce6b0WWs4sFgeprjCzNsV73Z8f5S+6U+XEWVg7jY3ArU7imYRW4V0VV\\\\/jujnzk3woCLQKBgBrMZ1cf3dj50w\\\\/oDGQfAHpwCPStmn9ECq0Rw9y0kutJSs+0xRLEOPYVue2kA5bY3bFjBFZPCcOojvYF8rJKkUGHHN420DckDiTviWz+71AzvPSDnOL+dyVMi1Ab4EY8tboD+bj2ebTIjWwBS4bwNGhejMVbVUzBLcoUtfLMkaf7\\\",\\\"alipay_public_key\\\":\\\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApPbtJv5Xsd9+\\\\/U8UJqZnAfVt76x0UQ631hjMKZ9mVn5rA4aAKypEuxupnmLsUX61tVIGXWd+bLh0fGJEOL9VGzDypneilqJ4maS9g\\\\/htZZq8o8w6oXpEWFPBvaT46y0hI+QN9g34JefK00UvyrVrE1EzjRPCt\\\\/gSZLWRgrA8tBAmfXtgzEJg+OMtDjhftAX1XfpvZ0i\\\\/07g2a+ZI0KjOvnr9GEW6LH0agUOwe5QIaH\\\\/vjo+9A2tvI4Lto1q\\\\/AKzDOjyAaGgwiadTG\\\\/wOXwENrVGcgOtSgkiftpi55rlJLy1sxA3GGZl4TPZ6qbYYzD2CuRoN3kcupXFEjRLmFNJnbQIDAQAB\\\"}', 1, '1.0', 'alipay', 'alipay');
INSERT INTO `hui_pay_mode` VALUES (2, '【官方】微信', 'wechat.png', '微信支付提供公众号支付、APP支付、扫码支付、刷卡支付等支付方式。', '{\\\"mch_id\\\":\\\"1557478971\\\",\\\"app_id\\\":\\\"wxc72aa7912f3c5715\\\",\\\"app_secret\\\":\\\"6hnAAcdrddecSgh9KB3542PvXLuI852a\\\",\\\"key\\\":\\\"4b128d4ba0af9f248833ab6b1fad0ebb\\\"}', 1, '1.0', 'wechat', 'wechat');

-- ----------------------------
-- Table structure for hui_tag
-- ----------------------------
DROP TABLE IF EXISTS `hui_tag`;
CREATE TABLE `hui_tag`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `total` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `inputtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `times` int(10) DEFAULT NULL COMMENT '次数',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tag`(`tag`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for hui_tag_content
-- ----------------------------
DROP TABLE IF EXISTS `hui_tag_content`;
CREATE TABLE `hui_tag_content`  (
  `modelid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `catid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `aid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tagid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  INDEX `tag_index`(`modelid`, `aid`) USING BTREE,
  INDEX `tagid_index`(`tagid`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for hui_test
-- ----------------------------
DROP TABLE IF EXISTS `hui_test`;
CREATE TABLE `hui_test`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `userid` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nickname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `title` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `seo_title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `inputtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `keywords` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `click` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `copyfrom` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `thumb` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `flag` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '1置顶,2头条,3特荐,4推荐,5热点,6幻灯,7跳转',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `system` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `groupids_view` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读权限',
  `readpoint` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读收费',
  `is_push` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否百度推送',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`, `listorder`) USING BTREE,
  INDEX `catid`(`catid`, `status`) USING BTREE,
  INDEX `userid`(`userid`, `status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
