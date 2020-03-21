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

 Date: 21/03/2020 19:19:48
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
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员日志表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of hui_admin_log
-- ----------------------------
INSERT INTO `hui_admin_log` VALUES (1, 1, 'admin', '/admin.php/general.database/backup.html', '常规管理 数据库备份', '{\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584770329);
INSERT INTO `hui_admin_log` VALUES (2, 1, 'admin', '/admin.php/general.database/optimize.html', '常规管理 优化表', '{\"table\":[\"hui_admin\",\"hui_admin_log\",\"hui_auth_group\",\"hui_auth_group_access\",\"hui_auth_rule\",\"hui_banner\",\"hui_banner_type\",\"hui_config\",\"hui_link\"]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584770528);
INSERT INTO `hui_admin_log` VALUES (3, 1, 'admin', '/admin.php/general.database/repair.html', '常规管理 修复表', '{\"table\":[\"hui_admin\",\"hui_admin_log\",\"hui_auth_group\",\"hui_auth_group_access\",\"hui_auth_rule\",\"hui_banner\",\"hui_banner_type\",\"hui_config\",\"hui_link\"]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584770529);
INSERT INTO `hui_admin_log` VALUES (4, 1, 'admin', '/admin.php/link/index.html', '友情链接管理', '{\"page\":\"1\",\"limit\":\"10\",\"do\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584770602);
INSERT INTO `hui_admin_log` VALUES (5, 1, 'admin', '/admin.php/link/index.html', '友情链接管理', '{\"page\":\"1\",\"limit\":\"10\",\"do\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584770636);
INSERT INTO `hui_admin_log` VALUES (6, 1, 'admin', '/admin.php/general.config/public_check_ftp.html', '常规管理 测试FTP连接', '{\"ftp_host\":\"45.32.214.79\",\"ftp_port\":\"21\",\"ftp_user\":\"ftp_com\",\"ftp_pwd\":\"sJ65wTnhmYPe2k5A\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584772725);
INSERT INTO `hui_admin_log` VALUES (7, 1, 'admin', '/admin.php/general.config/save.html', '常规管理 保存设置', '{\"site_name\":\"HuiCmf - \\u6f14\\u793a\\u7ad93\",\"site_url\":\"http:\\/\\/test.xiaohuihui.club\\/\",\"site_keyword\":\"huicmf\",\"site_copyright\":\"Powered By HuiCMF\\u5185\\u5bb9\\u7ba1\\u7406\\u7cfb\\u7edf \\u00a9 2018-2020 \\u5c0f\\u7070\\u7070\\u5de5\\u4f5c\\u5ba4\",\"site_beian\":\"\\u4eacICP\\u5907666666\\u53f7\",\"site_code\":\"\",\"admin_prohibit_ip\":\"\",\"admin_log\":\"1\",\"login_code\":\"0\",\"mail_server\":\"smtp.exmail.qq.com\",\"mail_port\":\"465\",\"mail_user\":\"\",\"mail_pass\":\"\",\"mail_inbox\":\"\",\"mail_to\":\"\",\"upload_maxsize\":\"3048\",\"upload_types\":\"zip|rar|mp3|mp4|jpg|jpeg|png|gif|bmp\",\"watermark_enable\":\"1\",\"watermark_name\":\"mark.png\",\"watermark_position\":\"9\",\"watermark_touming\":\"73\",\"upload_mode\":\"local\",\"file_path\":\"\\/uploads\\/\",\"ftp_host\":\"45.32.214.79\",\"ftp_port\":\"21\",\"ftp_user\":\"ftp_com\",\"ftp_pwd\":\"sJ65wTnhmYPe2k5A\",\"ftp_url\":\"http:\\/\\/222.com\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584772725);
INSERT INTO `hui_admin_log` VALUES (8, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"1\",\"pid\":\"1\",\"title\":\"\\u81ea\\u5b9a\\u4e49\\u914d\\u7f6e\",\"name\":\"general.config\\/user_config\",\"icon\":\"icon-settings_light\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"3\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774186);
INSERT INTO `hui_admin_log` VALUES (9, 1, 'admin', '/admin.php/auth.rule/rule_edit.html', '权限管理 修改菜单规则', '{\"ismenu\":\"1\",\"pid\":\"1\",\"title\":\"\\u81ea\\u5b9a\\u4e49\\u914d\\u7f6e\",\"name\":\"general.config\\/user_config\",\"icon\":\"icon-settings_light\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"3\",\"id\":\"49\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774297);
INSERT INTO `hui_admin_log` VALUES (10, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"1\",\"pid\":\"49\",\"title\":\"\\u6d4b\\u8bd5\",\"name\":\"general.config\\/user_config\",\"icon\":\"icon-round_text_fill\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774594);
INSERT INTO `hui_admin_log` VALUES (11, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"1\",\"pid\":\"49\",\"title\":\"\\u6d4b\\u8bd5\",\"name\":\"general.config\\/user_config\",\"icon\":\"icon-round_text_fill\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774602);
INSERT INTO `hui_admin_log` VALUES (12, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"1\",\"pid\":\"49\",\"title\":\"\\u6d4b\\u8bd5\",\"name\":\"general.config\\/user_config\",\"icon\":\"icon-round_text_fill\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774635);
INSERT INTO `hui_admin_log` VALUES (13, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"1\",\"pid\":\"49\",\"title\":\"asdf\",\"name\":\"general.config\\/user_config\",\"icon\":\"icon-round_text_fill\",\"condition\":\"asdf\",\"status\":\"normal\",\"weigh\":\"0\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774648);
INSERT INTO `hui_admin_log` VALUES (14, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"1\",\"pid\":\"49\",\"title\":\"asdf\",\"name\":\"general.config\\/user_config\",\"icon\":\"icon-round_text_fill\",\"condition\":\"asdf\",\"status\":\"normal\",\"weigh\":\"0\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774725);
INSERT INTO `hui_admin_log` VALUES (15, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"0\",\"pid\":\"49\",\"title\":\"\\u6dfb\\u52a0\\u81ea\\u5b9a\\u4e49\\u914d\\u7f6e\",\"name\":\"general.config\\/user_config_add\",\"icon\":\"icon-round_text_fill\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774811);
INSERT INTO `hui_admin_log` VALUES (16, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"0\",\"pid\":\"49\",\"title\":\"\\u4fee\\u6539\\u81ea\\u5b9a\\u4e49\\u914d\\u7f6e\",\"name\":\"general.config\\/user_config_edit\",\"icon\":\"icon-round_text_fill\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"2\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774864);
INSERT INTO `hui_admin_log` VALUES (17, 1, 'admin', '/admin.php/auth.rule/rule_add.html', '权限管理 添加菜单规则', '{\"ismenu\":\"0\",\"pid\":\"49\",\"title\":\"\\u5220\\u9664\\u81ea\\u5b9a\\u4e49\\u914d\\u7f6e\",\"name\":\"general.config\\/user_config_delete\",\"icon\":\"icon-round_text_fill\",\"condition\":\"\",\"status\":\"normal\",\"weigh\":\"3\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584774947);
INSERT INTO `hui_admin_log` VALUES (18, 1, 'admin', '/admin.php/general.database/backup.html', '常规管理 数据库备份', '{\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584775136);
INSERT INTO `hui_admin_log` VALUES (19, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776112);
INSERT INTO `hui_admin_log` VALUES (20, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"attachment\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776115);
INSERT INTO `hui_admin_log` VALUES (21, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"radio\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776116);
INSERT INTO `hui_admin_log` VALUES (22, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"select\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776117);
INSERT INTO `hui_admin_log` VALUES (23, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776120);
INSERT INTO `hui_admin_log` VALUES (24, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776301);
INSERT INTO `hui_admin_log` VALUES (25, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776426);
INSERT INTO `hui_admin_log` VALUES (26, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776586);
INSERT INTO `hui_admin_log` VALUES (27, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776605);
INSERT INTO `hui_admin_log` VALUES (28, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776631);
INSERT INTO `hui_admin_log` VALUES (29, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"attachment\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776633);
INSERT INTO `hui_admin_log` VALUES (30, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776656);
INSERT INTO `hui_admin_log` VALUES (31, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776664);
INSERT INTO `hui_admin_log` VALUES (32, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776806);
INSERT INTO `hui_admin_log` VALUES (33, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776873);
INSERT INTO `hui_admin_log` VALUES (34, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"attachment\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584776878);
INSERT INTO `hui_admin_log` VALUES (35, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"textarea\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584777974);
INSERT INTO `hui_admin_log` VALUES (36, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584777975);
INSERT INTO `hui_admin_log` VALUES (37, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"textarea\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778024);
INSERT INTO `hui_admin_log` VALUES (38, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"select\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778026);
INSERT INTO `hui_admin_log` VALUES (39, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"radio\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778030);
INSERT INTO `hui_admin_log` VALUES (40, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"attachment\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778032);
INSERT INTO `hui_admin_log` VALUES (41, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"textarea\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778128);
INSERT INTO `hui_admin_log` VALUES (42, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"image\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778135);
INSERT INTO `hui_admin_log` VALUES (43, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"attachment\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778136);
INSERT INTO `hui_admin_log` VALUES (44, 1, 'admin', '/admin.php/general.config/public_gethtml.html', '常规管理', '{\"fieldtype\":\"radio\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584778137);
INSERT INTO `hui_admin_log` VALUES (45, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"1\",\"fieldtype\":\"textarea\",\"value\":\"\",\"setting\":\"\",\"title\":\"11\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584779395);
INSERT INTO `hui_admin_log` VALUES (46, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"1\",\"fieldtype\":\"radio\",\"value\":\"\",\"setting\":\"\",\"title\":\"11\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584779411);
INSERT INTO `hui_admin_log` VALUES (47, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"1\",\"fieldtype\":\"textarea\",\"value\":\"\",\"setting\":\"\",\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584779500);
INSERT INTO `hui_admin_log` VALUES (48, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"1\",\"fieldtype\":\"textarea\",\"value\":\"\",\"setting\":\"\",\"title\":\"1\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584779525);
INSERT INTO `hui_admin_log` VALUES (49, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"1\",\"fieldtype\":\"textarea\",\"value\":\"\",\"setting\":\"\",\"title\":\"1\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584779540);
INSERT INTO `hui_admin_log` VALUES (50, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"1\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"11222\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":\"\",\"title\":\"33\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584779639);
INSERT INTO `hui_admin_log` VALUES (51, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"1\",\"fieldtype\":\"radio\",\"value\":{\"textarea\":\"11222\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\\u7537\",\"select\":\"\"},\"setting\":\"\",\"title\":\"33\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584779659);
INSERT INTO `hui_admin_log` VALUES (52, 1, 'admin', '/admin.php/upload/index.html', '', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584784393);
INSERT INTO `hui_admin_log` VALUES (53, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"asfs\",\"fieldtype\":\"image\",\"value\":{\"textarea\":\"\",\"image\":\"\\/uploads\\/20200321\\/a7c0b569b8a7cedec3ed6b3799f0577f.png\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"asf\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584784469);
INSERT INTO `hui_admin_log` VALUES (54, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"asf_asfa\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584784581);
INSERT INTO `hui_admin_log` VALUES (55, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"asf_\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584784586);
INSERT INTO `hui_admin_log` VALUES (56, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"_ASDFSAD\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584784589);
INSERT INTO `hui_admin_log` VALUES (57, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"qq\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584784634);
INSERT INTO `hui_admin_log` VALUES (58, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"qq\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785072);
INSERT INTO `hui_admin_log` VALUES (59, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"qq\",\"fieldtype\":\"radio\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"123\",\"select\":\"\"},\"setting\":{\"radio\":\"\\u7537|\\u5973\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785091);
INSERT INTO `hui_admin_log` VALUES (60, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"qq\",\"fieldtype\":\"radio\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\\u7537\",\"select\":\"\"},\"setting\":{\"radio\":\"\\u7537|\\u5973\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785097);
INSERT INTO `hui_admin_log` VALUES (61, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"qq\",\"fieldtype\":\"radio\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\\u7537\",\"select\":\"\"},\"setting\":{\"radio\":\"\\u7537|\\u5973\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785253);
INSERT INTO `hui_admin_log` VALUES (62, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"mail_port\",\"fieldtype\":\"radio\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\\u7537\",\"select\":\"\"},\"setting\":{\"radio\":\"\\u7537|\\u5973\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785531);
INSERT INTO `hui_admin_log` VALUES (63, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"mail_port\",\"fieldtype\":\"radio\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\\u7537\",\"select\":\"\"},\"setting\":{\"radio\":\"\\u7537|\\u5973\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785536);
INSERT INTO `hui_admin_log` VALUES (64, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"mail_port1\",\"fieldtype\":\"radio\",\"value\":{\"textarea\":\"123456789\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\\u7537\",\"select\":\"\"},\"setting\":{\"radio\":\"\\u7537|\\u5973\",\"select\":\"\"},\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785564);
INSERT INTO `hui_admin_log` VALUES (65, 1, 'admin', '/admin.php/upload/index.html', '', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785979);
INSERT INTO `hui_admin_log` VALUES (66, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"imagessss\",\"fieldtype\":\"image\",\"value\":{\"textarea\":\"\",\"image\":\"\\/uploads\\/20200321\\/095d3341acfc5557ad1af2028920662a.png\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\\u56fe\\u56fe\\u56fe\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584785983);
INSERT INTO `hui_admin_log` VALUES (67, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"sasf\",\"fieldtype\":\"select\",\"value\":{\"textarea\":\"\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\\u5b58\\u50a8\"},\"setting\":{\"radio\":\"\",\"select\":\"\\u788d\\u4e8b\\u6cd5\\u5e08|\\u963f\\u65af\\u987f\\u53d1|\\u55ef\\u55ef|\\u5b58\\u50a8\"},\"title\":\"\\u963f\\u65af\\u987f\\u53d1\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584787086);
INSERT INTO `hui_admin_log` VALUES (68, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"qq\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"12345566\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"qq\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584787577);
INSERT INTO `hui_admin_log` VALUES (69, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"mail_port1\",\"value\":{\"textarea\":\"\\u7537\",\"image\":\"\\u7537\",\"attachment\":\"\\u7537\"},\"mail_port1\":\"1\",\"title\":\"QQ\",\"status\":\"0\",\"dosubmit\":\"1\",\"id\":\"32\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584787714);
INSERT INTO `hui_admin_log` VALUES (70, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"sasf\",\"value\":\"\\u5b58\\u50a8\",\"setting\":\"3\",\"title\":\"\\u963f\\u65af\\u987f\\u53d1\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"34\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584788429);
INSERT INTO `hui_admin_log` VALUES (71, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"mail_port1\",\"value\":\"1\",\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"32\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584788888);
INSERT INTO `hui_admin_log` VALUES (72, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"mail_port1\",\"value\":\"1\",\"title\":\"QQ\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"32\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584788903);
INSERT INTO `hui_admin_log` VALUES (73, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"qq\",\"value\":\"12345566\",\"title\":\"qq\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"35\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789030);
INSERT INTO `hui_admin_log` VALUES (74, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"qq\",\"value\":\"12345566\",\"title\":\"qq\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"35\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789038);
INSERT INTO `hui_admin_log` VALUES (75, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"qq\",\"value\":\"12345566\",\"title\":\"qq\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"35\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789072);
INSERT INTO `hui_admin_log` VALUES (76, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"qq\",\"value\":\"123455662\",\"title\":\"qq\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"35\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789095);
INSERT INTO `hui_admin_log` VALUES (77, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"qq\",\"value\":\"123455662\",\"title\":\"qq\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"35\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789098);
INSERT INTO `hui_admin_log` VALUES (78, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"sasf\",\"value\":\"0\",\"title\":\"\\u963f\\u65af\\u987f\\u53d1\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"34\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789122);
INSERT INTO `hui_admin_log` VALUES (79, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"sasf\",\"value\":\"0\",\"title\":\"\\u963f\\u65af\\u987f\\u53d1\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"34\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789156);
INSERT INTO `hui_admin_log` VALUES (80, 1, 'admin', '/admin.php/general.config/user_config_edit.html', '常规管理 修改自定义配置', '{\"name\":\"sasf\",\"value\":\"1\",\"title\":\"\\u963f\\u65af\\u987f\\u53d1\",\"status\":\"1\",\"dosubmit\":\"1\",\"id\":\"34\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789161);
INSERT INTO `hui_admin_log` VALUES (81, 1, 'admin', '/admin.php/general.config/user_config_del.html', '常规管理', '{\"id\":[\"32\",\"33\",\"34\",\"35\"]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789192);
INSERT INTO `hui_admin_log` VALUES (82, 1, 'admin', '/admin.php/general.config/user_config_del.html', '常规管理', '{\"id\":[\"32\",\"33\",\"34\",\"35\"]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789231);
INSERT INTO `hui_admin_log` VALUES (83, 1, 'admin', '/admin.php/general.config/user_config_del.html', '常规管理', '{\"id\":[\"32\",\"33\",\"34\",\"35\"]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789285);
INSERT INTO `hui_admin_log` VALUES (84, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"asf\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"asf\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789293);
INSERT INTO `hui_admin_log` VALUES (85, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"asf\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"asfa\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789382);
INSERT INTO `hui_admin_log` VALUES (86, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"asfasf\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"asfa\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789388);
INSERT INTO `hui_admin_log` VALUES (87, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"asfas\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"asfas\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789411);
INSERT INTO `hui_admin_log` VALUES (88, 1, 'admin', '/admin.php/general.config/user_config_del.html', '常规管理', '{\"id\":[\"38\"]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789436);
INSERT INTO `hui_admin_log` VALUES (89, 1, 'admin', '/admin.php/general.config/user_config_del.html', '常规管理', '{\"id\":[\"38\"]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789438);
INSERT INTO `hui_admin_log` VALUES (90, 1, 'admin', '/admin.php/general.config/user_config_add.html', '常规管理 添加自定义配置', '{\"name\":\"afas\",\"fieldtype\":\"textarea\",\"value\":{\"textarea\":\"fasffa\",\"image\":\"\",\"attachment\":\"\",\"radio\":\"\",\"select\":\"\"},\"setting\":{\"radio\":\"\",\"select\":\"\"},\"title\":\"\",\"status\":\"1\",\"dosubmit\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1584789485);

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
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '节点表' ROW_FORMAT = Compact;

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
INSERT INTO `hui_auth_rule` VALUES (49, 'file', 1, 'general.config/user_config', '自定义配置', 'icon-settings_light', '', '', 1, 1584774186, 1584774296, 3, 'normal');
INSERT INTO `hui_auth_rule` VALUES (51, 'file', 49, 'general.config/user_config_add', '添加自定义配置', 'icon-round_text_fill', '', '', 0, 1584774803, NULL, 1, 'normal');
INSERT INTO `hui_auth_rule` VALUES (52, 'file', 49, 'general.config/user_config_edit', '修改自定义配置', 'icon-round_text_fill', '', '', 0, 1584774864, NULL, 2, 'normal');
INSERT INTO `hui_auth_rule` VALUES (53, 'file', 49, 'general.config/user_config_delete', '删除自定义配置', 'icon-round_text_fill', '', '', 0, 1584774947, NULL, 3, 'normal');

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
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

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
INSERT INTO `hui_config` VALUES (31, 'pay_mode', 5, '支付方式', '1', 'string', '', 1, '');

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
