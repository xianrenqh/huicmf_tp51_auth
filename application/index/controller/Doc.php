<?php

namespace app\index\controller;

use xianrenqh\apidoc\ApiDoc;
use think\Controller;

class Doc
{

    public function index()
    {
        $config = [
            'class'         => [
                'app\\api\\controller\\Index',
            ], // 要生成文档的类
            'filter_method' => ['__construct', '_empty'], // 要过滤的方法名称
        ];
        $api    = new \xianrenqh\apidoc\BootstrapApiDoc($config);
        $doc    = $api->getHtml();
        exit($doc);
    }

}