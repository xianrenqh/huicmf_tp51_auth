<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2019/12/26 0026
 * Time: 10:59
 */

namespace app\admin\controller;

use think\Db;
use think\facade\Cache;

class Index extends Common
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 后台首页
     */
    public function index()
    {
        return $this->fetch('index', ['info' => session('user_info')]);
    }
    
    /**
     * 显示后台菜单
     */
    public function show_menu()
    {
        if (cache('menu_list_' . $this->role_id)) {
            $menu_list = cache('menu_list_' . $this->role_id);
        } else {
            //如果有超级管理员权限的话，
            if ($this->group_id == 1) {
                $menu_list = Db::name('auth_rule')
                    ->where(['ismenu' => 1, 'pid' => 0, 'status' => 'normal'])
                    ->order('weigh ASC')
                    ->select();
                for ($i = 0; $i < count($menu_list); $i++) {
                    $menu_list[$i]['icon'] = $menu_list[$i]['icon'];
                    $child[$i]['url'] = url($menu_list[$i]['name']);
                }
                foreach ($menu_list as $key => $value) {
                    $child = Db::name('auth_rule')
                        ->where(
                            ['pid' => $value['id'], 'ismenu' => 1, 'status' => 'normal']
                        )
                        ->order('weigh ASC')
                        ->select();
                    for ($i = 0; $i < count($child); $i++) {
                        $child[$i]['url'] = url($child[$i]['name']);
                    }
                    
                    if ($child) {
                        $menu_list[$key]['children'] = $child;
                    } else {
                        unset($menu_list[$key]);
                    }
                }
            } else {
                $u_group = $this->auth->getGroups($this->uid);
                $u_group = $u_group[0]['rules'];
                $menu_list2 = Db::name('auth_rule')
                    ->where("id in($u_group) and ismenu=1")
                    ->order('weigh ASC')
                    ->select();
                
                for ($i = 0; $i < count($menu_list2); $i++) {
                    $menu_list2[$i]['url'] = url($menu_list2[$i]['name']);
                }
                $menu_list = (array2tree($menu_list2));
                foreach ($menu_list as $k2 => $v2) {
                    if (empty($v2['children'])) {
                        unset($menu_list[$k2]);
                    }
                }
            }
            cache('menu_list_' . $this->role_id, $menu_list);
        }
        
        return json(['status' => 0, 'msg' => 'ok', 'data' => $menu_list]);
    }
    
    /**
     * 右侧公共welcome
     */
    public function public_welcome()
    {
        return $this->fetch('welcome');
    }
    
    public function public_welcome_xiugai()
    {
        return $this->fetch('welcome_xiugai');
    }
    
    //清除所有缓存文件
    public function public_clear($type = '')
    {
        Cache::clear();
        $path = env('RUNTIME_PATH');
        //如果是目录则继续
        if ( ! is_dir($path)) {
            return json(['status' => 0, 'message' => 'runtime目录不存在']);
        }
        $p = scandir($path);
        $arr = config('huiadmin.runtime_dir');
        foreach ($p as $val) {
            if ( ! in_array($val, $arr)) {
                continue;
            }
            if ( ! is_dir($path . $val)) {
                continue;
            }
            $dir = $path . $val . '/';
            //先删除目录下的文件：
            $dh = opendir($dir);
            while ($file = readdir($dh)) {
                if ($file != "." && $file != "..") {
                    $fullpath = $dir . $file;
                    if ( ! is_dir($fullpath)) {
                        @unlink($fullpath);
                    } else {
                        dir_delete($fullpath);
                    }
                }
            }
            closedir($dh);
            @rmdir($path . $val . '/');
        }
        if ($type != 'index') {
            return json(
                ['status' => 1, 'message' => '清除缓存成功', 'url' => url('index')]
            );
        }
    }
    
    /**
     * 图标列表
     */
    public function public_checkicon()
    {
        return $this->fetch('public_checkicon');
    }
    
    
}