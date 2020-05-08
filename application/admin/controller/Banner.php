<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-12
 * Time: 16:31:48
 * Info:
 */

namespace app\admin\controller;

use app\admin\library\LibAuth;
use think\Db;

class Banner extends Common
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
        $db_banner = Db::name('banner');
        $total     = $db_banner->count();
        $data      = $db_banner->order('typeid ASC,listorder ASC,id DESC')->paginate(10);
        $items     = $data->items();
        foreach ($items as $k => $v) {
            $items[$k]['typename'] = $this->get_banner_type($v['typeid']);
        }

        return $this->fetch('banner_list', ['total' => $total, 'items' => $items, 'data' => $data]);
    }

    public function add()
    {
        if (input('dosubmit')) {
            $post_data              = input('post.');
            $post_data['inputtime'] = time();
            $res                    = Db::name('banner')->data($post_data)->strict(false)->insert();
            if ($res == 1) {
                return json(['status' => 1, 'icon' => 1, 'msg' => '操作成功~~~']);
            } else {
                return json(['status' => 0, 'icon' => 2, 'msg' => '操作失败!!!']);
            }

        } else {
            $types = Db::name('banner_type')->select();

            return $this->fetch('banner_add', ['types' => $types]);
        }
    }

    public function edit()
    {
        $id = input('post.id');
        if (input('post.dosubmit')) {
            $update = Db::name('banner')->where('id', $id)->data(input('post.'))->strict(false)->update();
            if ($update == 1) {
                return json(['status' => 1, 'icon' => 1, 'msg' => '操作成功~~~']);
            } else {
                return json(['status' => 0, 'icon' => 2, 'msg' => '操作失败！！！']);
            }
        } else {
            $types = Db::name('banner_type')->select();
            $data  = Db::name('banner')->find(input('post.id'));

            return $this->fetch('banner_edit', ['types' => $types, 'data' => $data]);
        }
    }

    public function delete()
    {
        $res = Db::name('banner')->delete(input('id'));
        if ($res == 1) {
            $this->success('操作成功！！！');
        } else {
            $this->error('操作失败！！！');
        }
    }

    /**
     * 添加banner分类
     */
    public function cat_add()
    {
        $param = input('post.');
        if ( ! empty($param['dosubmit'])) {
            $getname = Db::name('banner_type')->where('name', $param['name'])->find();
            if ($getname) {
                return json(['status' => 0, 'msg' => '分类名称已存在，请重新输入']);
            } else {
                $typeid = Db::name('banner_type')->data($param)->strict(false)->insert();
                switch ($typeid) {
                    case true:
                        $html = "<option value='".$typeid."' selected>".$getname."</option>";

                        return json(array('status' => 1, 'msg' => '操作成功~~，请选择！！', 'html' => $html));
                        break;
                    case false:
                        return json(array('status' => 0, 'msg' => '操作失败~~'));
                }
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * banner分类管理
     */
    public function cat_manage()
    {
        if (input('id')) {
            if (Db::name('banner_type')->delete(input('id'))) {
                return json(['status' => 1, 'msg' => '操作成功']);
            } else {
                return json(['status' => 0, 'msg' => '操作失败']);
            }
        } else {
            $data = Db::name('banner_type')->select();

            return $this->fetch('cat_manage', ['data' => $data]);
        }

    }

    /**
     * 获取banner分类
     */
    public function get_banner_type($typeid)
    {
        if ( ! $typeid) {
            return '无分类';
        }
        $r = Db::name('banner_type')->find($typeid);

        return $r ? $r['name'] : '';
    }

}