<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-31
 * Time: 8:47:16
 * Info: 附件管理
 */

namespace app\admin\controller\general;
use app\admin\controller\Common;
use app\admin\library\LibAuth;
use think\Db;

class Attachment extends Common
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
    
    //查看
    public function index()
    {
        if(input('get.do')){
            $where = "1=1";
            if($this->group_id!=1){
                $where .= " and admin_id in (".implode(",",$this->childrenAdminIds).")";
            }
            $param = input('get.');
            $page = $param['page'];
            $limit = $param['limit'];
            $first = ($page - 1) * $limit;
            if ( !empty($param['key'])) {
                $getkey = $param['key'];
                $picName = $getkey['picName'] ? $getkey['picName'] : "";
                $createtime = $getkey['createtime'] ? $getkey['createtime'] : "";
                
                if ( !empty($createtime)) {
                    $time_first = strtotime(explode(" - ", $createtime)[0]);
                    $time_last = strtotime(explode(" - ", $createtime)[1]) + (60 * 60 * 24 - 1);
                    if ($createtime != '') {
                        $where .= " and createtime>" . $time_first . " and createtime<" . $time_last;
                    }
                }
                if ($picName != '') {
                    $where .= " and url like '%$picName%' ";
                }
                
            }
            $res = Db::name('attachment')->where($where)->limit($first, $limit)->order('id desc')->select();
            for($i=0;$i<count($res);$i++){
                /*if (is_file($res[$i]['url'])) {
                    $res[$i]['is_file'] ='1';
                }else{
                    $res[$i]['is_file'] ='0';
                }*/
                $res[$i]['is_file'] =is_file(".".$res[$i]['url'])?1:0;
                $res[$i]['filesize'] = sizecount($res[$i]['filesize']);
                $res[$i]['createtime'] = date('Y-m-d H:i:s',$res[$i]['createtime'] );
            }
            $return = ["code"    => 0, 'msg' => '获取成功', 'count' => count($res), 'data' => $res];
            return json($return);
        }else{
            return $this->fetch();
        }
    }
    
    //上传
    public function upload()
    {
    
    }
    
    //删除附件
    public function delete()
    {
        if (input('post.ids')) {
            $res = Db::name('attachment')->order('id',input('post.ids'))->find();
            $attachmentFile = ROOT_PATH . '/public' . $res['url'];
            if (is_file($attachmentFile)) {
                @unlink($attachmentFile);
            }
            $del = Db::name('attachment')->delete(input('post.ids'));
            return json(['status'=>1,'msg'=>'附件已删除']);
        }else{
            return json(['status'=>0,'msg'=>'参数错误！']);
        }
    }
    
    //选择图片
    public function select()
    {
        $id =input('id');
        return $this->fetch('',['id'=>$id]);
    }
    
    //多图上传
    public function update_imgs()
    {
        $this->fetch();
    }

}