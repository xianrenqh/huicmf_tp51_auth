<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-01-10
 * Time: 8:10:26
 * Info:
 */

namespace app\admin\controller\general;
use app\admin\controller\Common;
use think\Db;
use lib\FtpLib;
use lib\PhpMail;
use think\facade\Cache;

class Config extends  Common
{
    
    public function index()
    {
        if(cache('cache_configs')){
            $data = cache('cache_configs');
        }else{
            $datalist = Db::name('config')->select();
            $data= array_column($datalist,'value','name');
            cache('cache_configs',$data);
        }
        return $this->fetch('',['data'=>$data]);
    }
    
    
    /**
     * 保存配置信息
     */
    public function save()
    {
        if (input('post.dosubmit')) {
            foreach (input('post.') as $key => $value) {
                $arr[$key] = $value;
                $value = htmlspecialchars($value);
                Db::name('config')->strict(false)->where(['name' => $key])->update(['value' => $value]);
                Cache::clear();
            }
            return json(['message' => "保存成功", 'icon' => 2]);
        }
    }
    
    
    //测试ftp连接
    public function public_check_ftp()
    {
        $post_data = input();
        $ftp_conn =  new FtpLib();
        if($ftp_conn->connect()=='-1'){
            return json(['code'=>1001,'msg'=>'FTP服务器连接失败! 请检查服务器地址和端口','icon'=>2]);
        }elseif ($ftp_conn->connect()=='-2'){
            return json(['code'=>1001,'msg'=>'FTP服务器登录失败','icon'=>2]);
        }elseif ($ftp_conn->connect()=='-3'){
            return json(['code'=>1001,'msg'=>'FTP模块不支持，请先开启！！','icon'=>2]);
        }
        else{
            return json(['code'=>1,'msg'=>'FTP连接成功','icon'=>1]);
        }
    }
    
    /*
* 发送测试邮件
*/
    public function public_mail_test()
    {
        $mail_to =input('mail_to');
        $phpmail = new PhpMail();
        $mail_title = "【测试】这是一封测试邮件";
        $mail_body = "<h2>这是一封测试邮件，测试是否发送成功。</h2><br>发送时间：".date("Y-m-d H:i:s",time());
        $sendmail = $phpmail->email($mail_to,$mail_title,$mail_body);
        return $sendmail;
    }
    
}