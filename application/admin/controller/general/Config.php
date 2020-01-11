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

class Config extends  Common
{
    
    public function index()
    {
        $datalist = Db::name('config')->select();
        $data= array_column($datalist,'value','name');
        return $this->fetch('',['data'=>$data]);
    }
    
    
    /**
     * 保存配置信息
     */
    public function save()
    {
    
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
    
}