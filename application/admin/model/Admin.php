<?php

namespace app\admin\model;

use lib\Random;
use think\Model;
use think\Db;

class Admin extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';

    protected $updateTime = 'updatetime';

    public function saveData($param)
    {
        if (cmf_get_admin_id() != $param['id']) {
            return ['status' => 0, 'msg' => '参数错误！！！'];
            exit;
        }
        if ($param['password'] == '') {
            unset($param['password']);
        } else {
            $Random            = new Random();
            $param['salt']     = $Random->alnum();
            $param['password'] = cmf_password($param['password'], $param['salt']);
        }
        $param['updatetime'] = time();
        $res                 = Db::name('admin')->where('id', $param['id'])->data($param)->strict(false)->update();
        if ($res) {
            return ['status' => 1, 'msg' => '修改成功！！！'];
        } else {
            return ['status' => 0, 'msg' => '修改失败！！！'];
        }
    }

}
