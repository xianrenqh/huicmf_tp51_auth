<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2019/12/27 0027
 * Time: 17:24
 */

namespace app\admin\controller;
use think\Controller;
use lib\Auth;
use think\facade\Request;

class Common extends Controller
{
    public $auth;
    public $uid;
    public $role_id;
    public $group_id;
    public function __construct(){
        //初始化判断用户是否已经登陆
        $this->uid =session('user_info.uid');
        if(!$this->uid){
            $this->error('请先登陆系统！','login/index');
        }else{
            $this->auth = Auth::instance();
            self::not_check_priv();
            $getGroups = $this->auth->getGroups($this->uid);
            $this->role_id = $getGroups[0]['id'];
            $groups = $this->auth->getGroups($this->uid);
            $this->group_id = $groups[0]['group_id'];
        }
        
    }
    
    
    /**
     * 不做验证的
     */
    final private function not_check_priv()
    {
        $allow = config('auth.not_check_priv');
        $route = request()->module() . '/' . request()->controller() . '/' . request()->action();
        if(!in_array($route, $allow)){
            self::check_priv();
        }
    }
    
    /**
     * 权限判断
     */
    final private function check_priv() {
        $module = Request::module();
        $controller = Request::controller();
        $action = Request::action();
        $rule1 = $module . '/' . $controller . '/' . $action;
        if(!$this->auth->check($rule1, $this->uid))
        {
            //error2 ('您没有权限操作');
            return json_encode(['status'=>0,'msg'=>'您没有权限操作','reload'=>0]);
        }
    }
    
    

    
}