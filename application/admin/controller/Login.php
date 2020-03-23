<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2019/12/28 0028
 * Time: 21:03
 */

namespace app\admin\controller;
use lib\Random;
use think\App;
use think\Controller;
use think\captcha\Captcha;
use think\Db;
use think\Request;
use think\facade\{Cookie,Session,Cache};
use app\admin\model\AdminLog;


class Login extends Controller
{
    protected $request;
    public $logined = false; //登录状态
    public function __construct(Request $request) {
        $this->request = $request;
    }
    
    public function index()
    {
        $url = $this->request->get('url', 'index/index');
        if ($this->isLogin()) {
            $this->success("你已经登录，无需重复登录",$url);
        }
    
        $captcha = new Captcha();
        if(input('post.dosubmit')){
            if(get_config('login_code')){
                if( !captcha_check(input('code') )){
                    return json(['status'=>1001,'msg'=>'验证码输出错误，请重新输入！！！']);
                }
            }
            $user_info = Db::name('admin')->where('username',input('post.username'))->find();
            if(empty($user_info['username'])){
                return json(['status'=>1002,'msg'=>'用户名或密码错误！']);
            }
            
            $login_failure_retry = config('huiadmin.login_failure_retry');
            $login_failure_times = config('huiadmin.login_failure_times');
            $login_failure_min = config('huiadmin.login_failure_min');
            if( $login_failure_retry && $user_info['loginfailure'] >=$login_failure_times && (time()-$user_info['updatetime'])< $login_failure_min*60 )
            {
                return json(['status'=>1002,'msg'=>'密码错误次数超过'.$login_failure_times.'次，请'.$login_failure_min.'分钟之后重试！']);
            }
            
            $pass = md5(md5(input('post.password')) . $user_info['salt']);
            if($user_info['password']!==$pass){
                Db::name('admin')->where('username',input('post.username'))->setInc('loginfailure');
                return ['status'=>1002,'msg'=>'用户名或密码错误！！！'];
            }else{
                AdminLog::setTitle('登录');
                if($user_info['status']!='normal'){
                    return ['status'=>1003,'msg'=>'该用户已被禁止访问'];
                }else{
                    $token = Random::uuid();
                    $user_session_info = [
                        'uid'        => $user_info['id'],
                        'username'  => $user_info['username'],
                        'nickname'  => $user_info['nickname'],
                        'logintime'=> $user_info['logintime'],
                        'loginip'  => $user_info['loginip'],
                        'token' => $token,
                    ];
                    Cache::clear();
                    session('user_info', $user_session_info);
                    $data = ['loginip'=>ip(),'loginfailure'=>0,'logintime'=>time(),'token'=>$token];
                    Db::name('admin')->where('username',input('post.username'))->data($data)->update();
                    return['status'=>1,'msg'=>'登录成功，正在跳转~~~'];
                }
            }
        }else{
            //获取微信用户信息
            $appid = '';
            $appKey = '';
            return $this->fetch();
        }
    }
    
    //自定义验证码类
    public function verify()
    {
        $captcha = new Captcha(config('captcha.'));
        return $captcha->entry();
    }
    
    /**
     * 检测是否登录
     *
     * @return boolean
     */
    public function isLogin()
    {
        if (session('user_info.uid')) {
            return true;
        }
    }
    
    /**
     * 退出
     */
    public function logout()
    {
        Session::clear();
        Cookie::clear();
        $this->success('退出成功！', 'login/index');
    }
    

    
    /**
     *获取bing背景图
     */
    public function getbing_bgpic(){
        $idx = input('idx');
        $api = "https://cn.bing.com/HPImageArchive.aspx?format=js&idx=$idx&n=1";
        $data = self::object2array(json_decode(self::get_url($api)));
        $pic_url = $data['images'][0]->{'url'}; //获取数据里的图片地址
        if($pic_url){
            $images_url  ="https://cn.bing.com/".$pic_url;      //如果图片地址存在，则输出图片地址
        }else{
            $images_url="https://s1.ax1x.com/2018/12/10/FGbI81.jpg";     //否则输入一个自定义图
        }
        header("Location: $images_url");    //header跳转
        
    }
    private  function get_url($url)
    {
        $ch = curl_init();
        $header[] = "";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
    private function object2array($object) {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        }
        else {
            $array = $object;
        }
        return $array;
    }
    
}