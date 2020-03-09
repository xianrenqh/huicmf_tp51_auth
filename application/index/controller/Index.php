<?php

namespace app\index\controller;

class Index
{
    public function index()
    {
        $title = "测试模板";
        include template('index','index');
    }
}