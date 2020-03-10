<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-10
 * Time: 14:22:06
 * Info:
 */
namespace app\index\controller;
class Index{
    
    public function index()
    {
        $title = "测试模板";
        include template('index','index');
    }
    
}