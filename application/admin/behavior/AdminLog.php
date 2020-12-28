<?php

namespace app\admin\behavior;

use think\facade\Request;
use app\admin\model\AdminLog as AdminLogModel;

class AdminLog
{

    public function run()
    {
        if (Request::module() == 'admin' && Request::isPost()) {
            AdminLogModel::record();
        }
    }
}
