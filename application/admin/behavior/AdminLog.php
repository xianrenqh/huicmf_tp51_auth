<?php

namespace app\admin\behavior;

use think\facade\Request;

class AdminLog
{

    public function run()
    {
        if (Request::module() == 'admin' && Request::isPost()) {
            \app\admin\model\AdminLog::record();
        }
    }
}
