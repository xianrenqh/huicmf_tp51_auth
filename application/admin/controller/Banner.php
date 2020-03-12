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
        $LibAuth = new LibAuth();
        $this->childrenAdminIds= $LibAuth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $LibAuth->getChildrenGroupIds(true);
    }
    
    public function index()
    {
        $db_banner = Db::name('banner');
        $total = $db_banner->count();
        $data = $db_banner->order('typeid ASC,listorder ASC,id DESC')->paginate(10);
        $items = $data->items();
        foreach ($items as $k => $v) {
            $items[$k]['typename'] = $this->get_banner_type($v['typeid']);
        }
        return $this->fetch('banner_list',['total' => $total, 'items' => $items, 'data' => $data]);
    }
    
    public function add()
    {
        return $this->fetch('banner_add');
    }
    
    public function edit()
    {
        $id = input('post.id');
        if (input('post.dosubmit')) {
            if (Db::name('banner')->where('id', Request::param('id'))->strict(false)->update(Request::post())) {
                return_json(array('status' => 1, 'icon' => 1, 'message' => '操作成功~~~'));
            } else {
                return_json(array('status' => 0, 'icon' => 2, 'message' => '操作失败！！！'));
            }
        } else {
            $types = Db::name('banner_type')->select();
            $data = Db::name('banner')->find(input('post.id'));
            //$form_image = $Form->image('image', $data['image']);
            return $this->fetch('banner_edit', ['types' => $types, 'data' => $data]);
        }
    }
    
    public function delete()
    {
    
    }
    
    /**
     * 获取banner分类
     */
    public function get_banner_type($typeid)
    {
        if (!$typeid)
            return '无分类';
        $r = Db::name('banner_type')->find($typeid);
        return $r ? $r['name'] : '';
    }
    
}