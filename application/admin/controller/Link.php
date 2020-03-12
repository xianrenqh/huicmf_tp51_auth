<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-02-14
 * Time: 20:01:05
 * Info:
 */

namespace app\admin\controller;
use app\admin\library\LibAuth;
use think\Db;
use app\admin\model\AuthGroup;

class Link extends Common
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
    
    public function index()
    {
        if(!empty(input('do'))){
            $list = Db::name('link')->order('listorder asc,addtime desc')->select();
            for($i=0;$i<count($list);$i++){
                $list[$i]['addtime'] = date("Y-m-d H:i:s",$list[$i]['addtime']);
                $list[$i]['link_status'] = $this->check($list[$i]['id']);
            }
            $total = Db::name('link')->count();
            $data['code'] = 0;
            $data['msg'] = '';
            $data['count'] = $total;
            $data['data'] = $list;
            return json($data);
        }else{
            return $this->fetch();
        }
        
    }
    
    //列表页更改排序
    public function listorder_edit()
    {
        $id = input('post.id');
        $value = input('post.value');
        $update = Db::name('link')->update(['listorder'=>$value,'id'=>$id]);
        if($update){
            return json(['status'=>1,'msg'=>'修改排序成功']);
        }else{
            return json(['status'=>0,'msg'=>'修改失败或者参数错误！！！']);
        }
    }
    
    //修改
    public function edit()
    {
        if(!empty(input('post.dosubmit'))){
            $id = input('post.id');
            $postData = input('post.');
            $postData['addtime']=time();
            $update = Db::name('link')->data($postData)->strict(false)->where('id',$id)->update();
            if($update){
                return json(['status'=>1,'msg'=>'修改成功~~~']);
            }else{
                return json(['status'=>0,'msg'=>'修改失败或者参数错误！！！']);
            }
        }else{
            $data = Db::name('link')->where('id',input('get.id'))->find();
            return $this->fetch('link_edit',['data'=>$data]);
        }
    }
    
    //添加链接
    public function add()
    {
        if ( !check_auth('link/add')) {
            exit('2222');
        }
        if(!empty(input('post.dosubmit'))){
            $postData = input('post.');
            $postData['addtime']=time();
            $data = Db::name('link')->data($postData)->strict(false)->insert();
            if($data){
                return json(['status'=>1,'msg'=>'添加成功~~~']);
            }else{
                return json(['status'=>0,'msg'=>'添加失败！！！']);
            }
        }else{
            return $this->fetch('link_add');
        }
    }
    
    //删除
    public function delete()
    {
         $ids = input('ids');
        if($ids){
            $res = Db::name('link')->delete($ids);
            if($res){
                return json(['status'=>1,'msg'=>'删除成功']);
            }else{
                return json(['status'=>0,'msg'=>'删除失败']);
            }
        }else{
            $this->error('错错错！！！');
        }
    }
    
    //检测url
    public function check($list_id='')
    {
        if($list_id!=''){
            $id = $list_id;
        }else{
            $id = input('id');
        }
        $where = [];
        $where['id'] = ['eq', $id];
        $res = model('Link')->infoData($where);
        if ($res['code'] > 1) {
            return json($res);
        }
        $url = $res['info']['url'];
        $site_url = parse_url(get_config('site_url'));
        $site_url = $site_url['host'];
        $html = cmf_curl_get($url);
        $res = [];
        $res['code'] = 1;
        $res['msg'] = '';
        $msg = '';
        $code = 1;
        
        $ok = ' 友链正常';
        $err = ' 友链异常';
        
        $msg .= '[' . $site_url . ']';
        if (strpos($html, $site_url) !== false) {
            $code = 1;
            $msg .= $ok;
            $link_status = '<span class="layui-badge layui-bg-green"> 正常 </span>';
        } else {
            $code = 101;
            $msg .= $err;
            $link_status = '<span class="layui-badge layui-bg-red"> 异常 </span>';
        }
        if($list_id!=''){
            return $link_status;
        }else{
            $res['msg'] = $msg;
            return json($res);
        }
        
    }
    
}