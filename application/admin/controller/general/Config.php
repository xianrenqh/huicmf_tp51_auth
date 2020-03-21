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
    
    
    /**
     * 测试ftp连接
     */
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
    
    
    /**
     * 送测试邮件
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
    
    /**
     * 自定义配置
     */
    public function user_config()
    {
        $data = Db::name('config')->where('type', '99')->paginate(10);
        $total = Db::name('config')->where('type', '99')->count();
        return $this->fetch('',['data'=>$data,'total'=>$total]);
    }
    
    /**
     * 添加自定义配置
     */
    public function user_config_add()
    {
        if(input('post.dosubmit')){
            $param = input('post.');
            //查询数据库是否存在配置名称name
            $cha = Db::name('config')->where('name',$param['name'])->find();
            if(is_array($cha)){
                return json(['status'=>0,'msg'=>'配置名称已存在，请修改']);
            }
            if($param['fieldtype']=="radio" || $param['fieldtype']=="select"  ){
                $setting = array2string(explode('|', rtrim($param['setting'][$param['fieldtype']], '|')));
            }else{
                $setting = "";
            }
            $data = [
                'name'=>$param['name'],
                'fieldtype'=>$param['fieldtype'],
                'type'=>99,
                'title'=>$param['title'],
                'status'=>1,
                'value'=>$param['value'][$param['fieldtype']],
                'setting'=>$setting
            ];
            $insert = Db::name('config')->data($data)->strict(false)->insert();
            Cache::set('cache_configs', null);
            if ($insert) {
                return json(['status'=>1,'msg'=>'操作成功~~~']);
            } else {
                return json(['status'=>0,'msg'=>'操作失败！！！']);
            }
        }else{
            return $this->fetch();
        }
    }
    
    /**
     * 修改自定义配置
     */
    public function user_config_edit()
    {
        if(input('dosubmit')){
            $param = input('post.');
            $data = Db::name('config')->where('id',$param['id'])->find();
            if($data['fieldtype']=='radio' || $data['fieldtype']=='select'){
                $setting = string2array($data['setting']);
                $data = ['title'=>$param['title'],'value'=>$setting[$param['value']],'status'=>$param['status']];
            }else{
                $data = ['title'=>$param['title'],'value'=>$param['value'],'status'=>$param['status']];
            }
            
            $update_id = Db::name('config')->where('id', input('post.id'))->data($data)->strict(false)->update();
            Cache::set('cache_configs', null);
            if ($update_id) {
                return json(['status'=>1,'msg'=>'修改成功~~~']);
            }else{
                return json(['status'=>0,'msg'=>'修改失败或者你没有做任何修改！！！']);
            }
        }else{
            $data = Db::name('config')->where('id',input('id'))->find();
            if($data['fieldtype']=='radio'){
                $setting = string2array($data['setting']);
                $setting_data = "";
                foreach($setting as $k=>$v){
                    $checked = $data['value']==$v?"checked":'';
                    $setting_data.= '<input type="radio" name="value" value="'.$k.'" title="'.$v.'" '.$checked.'>';
                }
            }
            elseif($data['fieldtype']=='select'){
                $setting = string2array($data['setting']);
                $setting_data = "";
                foreach($setting as $k=>$v){
                    $selected = $data['value']==$v?"selected":'';
                    $setting_data .='<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                }
            }
            else{
                $setting_data='';
            }
            return $this->fetch('',['data'=>$data,'setting_data'=>$setting_data]);
        }
    }
    
    /**
     * 删除自定义配置
     */
    public function user_config_del()
    {
        $res = Db::name('config')->where('id','in',input('id'))->delete();
        Cache::set('cache_configs', null);
        $this->success('操作成功');
    }
    
    /*
* 根据字段类型获取html
*/
    public function public_gethtml($ftype = '', $val = '', $setting = '')
    {
        public_gethtml($ftype, $val, $setting);
    }
    
}