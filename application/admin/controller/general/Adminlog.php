<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/5 0005
 * Time: 9:15
 */

namespace app\admin\controller\general;

use app\admin\controller\Common;
use app\admin\library\LibAuth;

class Adminlog extends Common
{

    public function index()
    {
        if (input('get.do')) {
            $res    = model('admin_log')->selectData(input('get.'));
            $return = ["code" => 0, 'msg' => '获取成功', 'count' => $res['count'], 'data' => $res['data']];

            return json($return);
        } else {
            return $this->fetch();
        }
    }

    /**
     * 创建日志
     *
     */
    public function add()
    {
        //$res = model('admin_log')->record();
    }

    /**
     * 删除
     */
    public function delete()
    {
        $ids = input('ids');
        if ($ids) {
            $res = model('admin_log')->delData($ids);

            return json($res);
        } else {
            $this->error('错错错！！！');
        }
    }

    /**
     * 查看详情
     */
    public function detail()
    {
        if ( ! check_auth('general.adminlog/detail')) {
            exit('2222');
        }
        $id  = input('get.id');
        $res = model('admin_log')->detailData($id);
        if ($res['status'] == 1) {
            $data = $res['data'];
        } else {
            $this->error($res['msg']);
        }

        return $this->fetch('', ['data' => $data]);
    }

}