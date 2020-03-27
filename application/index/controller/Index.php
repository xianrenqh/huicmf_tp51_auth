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
        $site = get_config();
        $seo_title = $site['site_name'];
        $keywords = $site['site_keyword'];
        $description = $site['site_description'];
        include template('index','index');
    }
    
}