<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/6 0006
 * Time: 17:26
 * Title：个人资料
 */

namespace app\admin\controller\general;
use app\admin\controller\Common;

class Profile extends Common
{
    
    public function index()
    {
        $data = model('admin')->field('id,username,nickname,email,createtime')->get($this->uid);
        return $this->fetch('',['data'=>$data]);
    }
    
    public function update()
    {
        $param = input('post.');
        $res = model('admin')->saveData($param);
        return json($res);
    }
    
}