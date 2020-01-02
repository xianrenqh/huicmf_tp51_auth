<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/1 0001
 * Time: 9:29
 */

return[
    
    //是否开启后台登录验证码
    'login_captcha'         => false,
    
    //允许登录失败几次
    'login_failure_times'   =>  5,
    
    //登录失败超过N次则后，多久允许重写登录（分钟）
    'login_failure_min'   =>  10,
    
    //登录失败超过N次是否允许重新登录
    'login_failure_retry'   => true,
    
    
];