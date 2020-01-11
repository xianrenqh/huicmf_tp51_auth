<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/1 0001
 * Time: 9:29
 */

return[
    
    //允许登录失败几次
    'login_failure_times'   =>  5,
    
    //登录失败超过N次则后，多久允许重写登录（分钟）
    'login_failure_min'   =>  10,
    
    //登录失败超过N次是否允许重新登录
    'login_failure_retry'   => true,

    //数据库备份文件夹
    'backupDir' => '/databak/',
    
    //不做备份的数据表
    'backupIgnoreTables' => '',

];