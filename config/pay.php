<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-09
 * Time: 14:47:39
 * Info: 支付配置（微信，支付宝。调用方式：config('pay.wxpay.appid')）
 */
return [
    'wxpay'=>[
        'mchid' => '1557478971',            //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        'appid' =>  'wxc72aa7912f3c5715',   //微信支付申请对应的公众号的APPID
        'apiKey' => '6hnAAcdrddecSgh9KB3542PvXLuI852a',  //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        'appKey'=>'4b128d4ba0af9f248833ab6b1fad0ebb'    //微信支付申请对应的公众号的APP Key
    ],
    
    'alipay'=>[
    
    ]
];