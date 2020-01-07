<?php
/**
 * Created by PhpStorm.
 * User: 程序猿
 * Date: 2019-12-04
 * Time: 13:33
 * Info:
 */

return[
    
    // auth配置
    'auth'  => [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'auth_group', // 用户组数据不带前缀表名
        'auth_group_access' => 'auth_group_access', // 用户-用户组关系不带前缀表名
        'auth_rule'         => 'auth_rule', // 权限规则不带前缀表名
        'auth_user'         => 'admin', // 用户信息不带前缀表名
    ],
    
    //不验证的页面（url）,公共页面或者方法，如果方法里包含public_，不需要写，权限自动放行
    'not_check_priv'=>[
        'admin/Index/index',
        'admin/Index/show_menu',
    ]
    
    
];