<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-28
 * Time: 8:15:35
 * Info:
 */

namespace app\index\controller;

class Error
{

    public function _empty()
    {
        return fetch(Env::get('extend_path').'tpl/404.html');
    }
}