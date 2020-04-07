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
use think\Db;
use think\facade\View;

class Upload
{
    public $uid;
    public $user_id;
    private $upload_mode;
    public function __construct() {
        $this->uid = session('user_info.uid')?session('user_info.uid'):'0';
        $this->user_id = session('user_id')?session('user_id'):'0';
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
                    $fileInfo = $file->getInfo();
                    
                    //验证是否为图片文件
                    $imagewidth = $imageheight = 0;
                    if (in_array($fileInfo['type'], ['image/gif', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/png', 'image/webp'])) {
                        $imgInfo = getimagesize($fileInfo['tmp_name']);
                        if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                            return json(['msg'=>'上传文件不是有效的图片文件','status'=>0]);
                        }
                        $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
                        $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
                    }
                    
                    $fileurl = $file_path.$filename;
                    $fileinfo=[];
                    $fileinfo['originname']=$info->getInfo()['name'];
                    $fileinfo['filepath']=str_replace($info->getFilename(),"",$fileurl);
                    $fileinfo['filesize']=$getsize;
                    $fileinfo['filename']=$info->getFilename();
                    $fileinfo['fileext']=$info->getExtension();
                    $fileinfo['mimetype'] =$info->getInfo()['type'];
                    $fileinfo['storage'] = 'local';
                    $fileinfo['imagewidth'] = $imagewidth;
                    $fileinfo['imageheight'] = $imageheight;
                    $fileinfo['sha1'] = $file->hash();
                    $fileinfo['url'] = $fileinfo['filepath'].$fileinfo['filename'];
                   
                    $this->add_water(".".$fileinfo['filepath'].$fileinfo['filename']);
                    $this->_att_write($fileinfo);
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
     * 图像裁剪
     */
    public function img_cropper()
    {
        $yzmcms_path = APP_PATH;
        if(input('post.filepath')){
            $x = input('post.x');
            $y = input('post.y');
            $w = input('post.w');
            $h = input('post.h');
            $image = Image::open('.'.input('post.filepath'));
            //$filename = date("ymdhis").rand(100,999);
            $filename = getMillisecond().rand(100,999);
            $filetype = fileext($_POST['filepath']);
            //判断是否存在文件夹，不存在就创建
            $filepath = dir_create(".".get_config('file_path').date('Ymd/',time())."/");
            $newfile = ".".get_config('file_path').date('Ymd/',time()).$filename.".".$filetype;
            $fileinfo = $image->crop($w, $h,$x,$y)->save($newfile);
            $info['url'] = substr_replace($newfile,"",0,1);
            $info['imagewidth'] = $w;
            $info['imageheight'] = $h;
            $info['fileext'] = $filetype;
            $info['filesize'] =filesize($newfile);
            $info['mimetype'] = $fileinfo->mime();
            $info['storage'] = 'local';
            $info['sha1'] = sha1($newfile);
            $this->_att_write($info);
            
            if($fileinfo){
                return json(['status'=>1,'filepath'=>substr_replace($newfile,"",0,1)]);
            }else{
                return json(['status'=>0]);
            }
        }else{
            $filepath = base64_decode(input('get.f'));
            if(strpos($filepath, 'ttp:')) showmsg('请选择本地已存在的图像！', 'stop');
            $spec = isset($_GET['spec']) ? intval($_GET['spec']) : 1;
            $cid = isset($_GET['cid']) ? $_GET['cid'] : 'thumb';
            switch ($spec){
                case 1:
                    $spec = '3 / 2';
                    break;
                case 2:
                    $spec = '4 / 3';
                    break;
                case 3:
                    $spec = '1 / 1';
                    break;
                default:
                    $spec = '3 / 2';
            }
            return View::fetch('upload_test/img_cropper',['filepath'=>$filepath,'spec'=>$spec,'cid'=>$cid]);
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
    
    /**
     * 上传附件写入数据库
     */
    public function _att_write($fileinfo)
    {
        $arr = [];
        $arr['admin_id'] = $this->uid;
        $arr['user_id'] = $this->user_id;
        $arr['url'] = $fileinfo['url'];;
        $arr['imagewidth'] = $fileinfo['imagewidth'];
        $arr['imageheight'] = $fileinfo['imageheight'];
        $arr['imagetype'] = $fileinfo['fileext'];
        $arr['filesize'] = $fileinfo['filesize'];
        $arr['mimetype'] = $fileinfo['mimetype'];
        $arr['extparam'] = !empty($fileinfo['originname'])?array2string(['name'=>$fileinfo['originname']]):'';
        $arr['createtime'] = time();
        $arr['updatetime'] = time();
        $arr['uploadtime'] = time();
        $arr['storage'] = $fileinfo['storage'];
        $arr['sha1'] = $fileinfo['sha1'];
        Db::name('attachment')->data($arr)->insert();
    }
    
    
    }