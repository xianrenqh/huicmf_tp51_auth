<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-22
 * Time: 8:35:45
 * Info:
 */

namespace app\admin\controller;

use app\admin\controller\Common;
use think\Db;
use app\admin\library\LibAuth;

class Pay extends Common
{

    public $childrenAdminIds;

    public $childrenGroupIds;

    public function __construct()
    {
        parent::__construct();
        $LibAuth                = new LibAuth();
        $this->childrenAdminIds = $LibAuth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $LibAuth->getChildrenGroupIds(true);
    }

    public function index()
    {
        $data = Db::name('pay_mode')->paginate(10);

        return $this->fetch('', ['data' => $data]);
    }

    public function edit()
    {
        if (input('dosubmit')) {
            $param = input('post.');
            $data  = ['desc'    => $param['desc'],
                      'config'  => array2string($param['config']),
                      'enabled' => $param['enabled']
            ];
            $res   = Db::name('pay_mode')->where('id', $param['id'])->data($data)->update();
            if ($res == 1) {
                return json(['status' => 1, 'msg' => '保存成功']);
            } else {
                return json(['status' => 0, 'msg' => '保存失败或者你没有做任何修改！！']);
            }
        } else {
            $id             = input('get.id');
            $data           = Db::name('pay_mode')->where('id', $id)->find();
            $config         = string2array($data['config']);
            $data['config'] = $config;

            return $this->fetch($data['template'], ['data' => $data]);
        }
    }

}