<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-01-15
 * Time: 9:03:09
 * Info:
 */

namespace app\admin\controller;
use think\Image;

class Upload extends Common
{
    
    private $upload_mode;
    public function __construct() {
        $this->upload_mode = get_config('upload_mode');
    }
    
    //附件上传
    public function index()
    {
        $option = [];
        $option['allowtype'] = $this->_get_upload_types();
        $file_path =get_config('file_path');
        switch ($this->upload_mode){
            
            //上传到本地
            case "local":
                $file = request()->file('Filedata');
                $getsize = $file->getSize();
                $info = $file
                    ->validate(['size'=>get_config('upload_maxsize')*1024,'ext'=>$option['allowtype']])
                    ->move(".".$file_path);
                if($info){
                    $filename=str_replace('\\','/',$info->getSaveName());
                    $fileurl = $file_path.$filename;
                    $fileinfo=[];
                    $fileinfo['originname']=$info->getInfo()['name'];
                    $fileinfo['filepath']=str_replace($info->getFilename(),"",$fileurl);
                    $fileinfo['filesize']=$getsize;
                    $fileinfo['filename']=$info->getFilename();
                    $fileinfo['fileext']=$info->getExtension();
                    $this->add_water(".".$fileinfo['filepath'].$fileinfo['filename']);
                    return json(['status'=>1,'src'=>$fileurl,'filetype'=>$info->getExtension(),'title'=>$info->getFilename(),'msg'=>$fileurl]);
                }else{
                    return json(['msg'=>$file->getError(),'status'=>0]);
                }
                break;
                
            //上传到FTP
            case "Ftp":
                
                break;
        }
    }
    
    
    /**
     * 上传框
     */
    public function upload_box()
    {
    
    }
    
    /**
     * 获取上传类型
     */
    private function _get_upload_types()
    {
        
        $arr = explode('|', get_config('upload_types'));
        $allow = array('gif', 'jpg', 'png', 'jpeg', 'zip', 'rar', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt', 'csv', 'mp4', 'avi', 'wmv', 'rmvb', 'flv', 'mp3', 'wma', 'wav', 'amr', 'ogg');
        foreach ($arr as $key => $val) {
            if (!in_array($val, $allow))
                unset($arr[$key]);
        }
        return $arr;
    }
    
    //获取上传文件后缀
    private function get_file_ext($file_name)
    {
        $temp_arr = explode(".", $file_name);
        $file_ext = array_pop($temp_arr);
        $file_ext = trim($file_ext);
        $file_ext = strtolower($file_ext);
        return $file_ext;
    }
    
    //添加水印
    private function add_water($imginfo)
    {
        //获取水印配置
        if(get_config('watermark_enable')){
            $waterpic = "./static/water/".get_config('watermark_name');
            $pic_url =$imginfo;
            $image=Image::open($pic_url);
            $image->water($waterpic,get_config('watermark_position'),get_config('watermark_touming'))->save($pic_url);
        }else{
            return false;
        }
    }
    
    
}