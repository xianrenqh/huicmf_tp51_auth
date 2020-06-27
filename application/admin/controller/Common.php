<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2019/12/27 0027
 * Time: 17:24
 */

namespace app\admin\controller;

use app\admin\library\LibAuth;
use app\ApplicationName\controller\View;
use think\Controller;
use lib\Auth;
use think\facade\Request;
use think\Loader;

class Common extends Controller
{

    public $auth;

    public $uid;

    public $role_id;

    public $group_id;

    public $breadcrumb;

    public static $ip;

    public function __construct()
    {
        parent::__construct();
        self::$ip = ip();
        self::check_ip();
        $controllername = Loader::parseName(Request::controller());
        $actionname     = strtolower(Request::action());
        $path           = str_replace('.', '/', $controllername).'/'.$actionname;

        //初始化判断用户是否已经登陆
        $this->uid = cmf_get_admin_id();
        if ( ! $this->uid) {
            $this->error('请先登陆系统！', 'login/index');
        } else {
            $this->auth = Auth::instance();
            self::not_check_priv();
            $getGroups      = $this->auth->getGroups($this->uid);
            $this->role_id  = ! empty($getGroups[0]['id']) ? $getGroups[0]['id'] : '';
            $groups         = $this->auth->getGroups($this->uid);
            $this->group_id = $groups[0]['group_id'];
        }

        // 设置面包屑导航数据
        $LibAuth    = new LibAuth();
        $breadcrumb = $LibAuth->getBreadCrumb($path);
        array_pop($breadcrumb);
        $this->breadcrumb = $breadcrumb;

    }

    //不做验证的
    final private function not_check_priv()
    {
        $allow = config('auth.not_check_priv');
        $route = request()->module().'/'.request()->controller().'/'.request()->action();
        if ( ! in_array($route, $allow)) {
            self::check_priv();
        }
    }

    //权限判断
    final private function check_priv()
    {
        $module     = Request::module();
        $controller = Request::controller();
        $action     = Request::action();
        if ($module == 'admin') {
            $rule1 = $controller.'/'.$action;
        } else {
            $rule1 = $module.'/'.$controller.'/'.$action;
        }
        if ( ! $this->auth->check($rule1, $this->uid)) {
            //error2 ('您没有权限操作');
            //return json_encode(['status'=>0,'msg'=>'您没有权限操作','reload'=>0]);
            $this->error('您没有权限操作！！！');
        }
    }

    //后台IP禁止判断
    final private function check_ip()
    {
        $admin_prohibit_ip = get_config('admin_prohibit_ip');
        if ( ! $admin_prohibit_ip) {
            return true;
        }
        $arr = explode(',', $admin_prohibit_ip);
        foreach ($arr as $val) {
            //是否是IP段
            if (strpos($val, '*')) {
                if (strpos(self::$ip, str_replace('.*', '', $val)) !== false) {
                    error2("你在IP禁止段内,禁止访问！~~~");
                }
            } else {
                //不是IP段,用绝对匹配
                if (self::$ip == $val) {
                    error2("IP地址绝对匹配,禁止访问！~~~");
                }
            }
        }
    }

}