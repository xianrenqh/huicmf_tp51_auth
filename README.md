![](https://box.kancloud.cn/5a0aaa69a5ff42657b5c4715f3d49221) 

Huicmf 3.0 —— 念念不忘 必有回響，你值得信赖的CMF
===============

### 此项目不再更新，请使用tp6版本的：
https://github.com/xianrenqh/huicmf_tp6

[![Total Downloads](https://poser.pugx.org/xianrenqh/huicmf_tp51/downloads)](https://packagist.org/packages/xianrenqh/huicmf_tp51)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/xianrenqh/huicmf_tp51/license)](https://packagist.org/packages/xianrenqh/huicmf_tp51)

HuiCMF3.0基于ThinkPHP5.1框架进行二次开发，其主要特性包括：

 + 使用Auth进行权限控制
 + （二开Auth，控制器方法名包含public_，放行权限，不做验证）
 + 角色组继承父级角色，权限继承父级权限
 + 做个推送测试


> HuiCMF3.0的运行环境要求PHP7.0以上。

## 安装
###1、
使用git进行安装
~~~
git clone https://github.com/xianrenqh/huicmf_tp51_auth.git
~~~

之后使用composer update进行更新

启动服务
###2、
composer create-project xianrenqh/huicmf_tp51_aut
composer require xianrenqh/huicmf_tp51_auth
之后使用composer update进行更新

## 代码里已用到的缓存名称
cache_auth_rule  
cache_auth_rules  
menu_list_(管理员id)
cache_configs

## 在线手册

## api接口文档生成说明（apidoc）
系统api接口文档 生成 依赖第三方工具apidoc  如果你要使用则必须先安装apidoc  
先安装node.js  如下链接下载安装  
64 位安装包下载地址 :   
https://nodejs.org/dist/v12.13.1/node-v12.13.1-x64.msi  
安装完成以后 开始安装 apidoc 

命令行：npm install apidoc -g

安装成功以后 开始生成接口文档  

1、进入api目录 注意不要在其他目录生成（api目录默认会有apidoc.json 配置文件）  
  
2、执行生成命令：  
apidoc -i ./ -o ../../public/doc    
(执行目录为api目录 生成文档路径为 public/doc)



+ [完全开发手册](https://www.kancloud.cn/manual/thinkphp5_1/content)
+ [升级指导](https://www.kancloud.cn/manual/thinkphp5_1/354155) 


## 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─module_name        模块目录
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行定义文件
│  ├─common.php         公共函数文件
│  └─tags.php           应用行为扩展定义文件
│
├─config                应用配置目录
│  ├─module_name        模块配置目录
│  │  ├─database.php    数据库配置
│  │  ├─cache           缓存配置
│  │  └─ ...            
│  │
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─log.php            日志配置
│  ├─session.php        Session配置
│  ├─template.php       模板引擎配置
│  └─trace.php          Trace配置
│
├─route                 路由定义目录
│  ├─route.php          路由定义
│  └─...                更多
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

> 可以使用php自带webserver快速测试
> 切换到根目录后，启动命令：php think run

## 命名规范

`ThinkPHP5`遵循PSR-2命名规范和PSR-4自动加载规范，并且注意如下规范：

### 目录和文件

*   目录不强制规范，驼峰和小写+下划线模式均支持；
*   类库、函数文件统一以`.php`为后缀；
*   类的文件名均以命名空间定义，并且命名空间的路径和类库文件所在路径一致；
*   类名和类文件名保持一致，统一采用驼峰法命名（首字母大写）；

### 函数和类、属性命名

*   类的命名采用驼峰法，并且首字母大写，例如 `User`、`UserType`，默认不需要添加后缀，例如`UserController`应该直接命名为`User`；
*   函数的命名使用小写字母和下划线（小写字母开头）的方式，例如 `get_client_ip`；
*   方法的命名使用驼峰法，并且首字母小写，例如 `getUserName`；
*   属性的命名使用驼峰法，并且首字母小写，例如 `tableName`、`instance`；
*   以双下划线“__”打头的函数或方法作为魔法方法，例如 `__call` 和 `__autoload`；

### 常量和配置

*   常量以大写字母和下划线命名，例如 `APP_PATH`和 `THINK_PATH`；
*   配置参数以小写字母和下划线命名，例如 `url_route_on` 和`url_convert`；

### 数据表和字段

*   数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 `think_user` 表和 `user_name`字段，不建议使用驼峰和中文作为数据表字段命名。

## 参与开发

请参阅 [ThinkPHP5 核心框架包]

## 版权信息

ThinkPHP遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2006-2018 by ThinkPHP (http://thinkphp.cn)

All rights reserved。

ThinkPHP® 商标和著作权所有者为上海顶想信息科技有限公司。

更多细节参阅 [LICENSE.txt](LICENSE.txt)
