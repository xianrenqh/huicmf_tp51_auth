<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-27
 * Time: 8:43:19
 * Info:
 */

namespace app\admin\controller;
use app\admin\library\LibAuth;
use think\Db;

class Tag extends Common
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
        $list = Db::name('tag')->order('inputtime desc')->paginate(10);
        $total = Db::name('tag')->count();
        return $this->fetch('',['total'=>$total,'list'=>$list]);
    }
    
    public function add()
    {
        if(input('post.dosubmit')){
            $tags = str_replace('，', ',', strip_tags(input('post.tags')));
            $tags = rtrim($tags, ',');
            $arr = explode(',', $tags);
            foreach ($arr as $val) {
                $tagid = Db::name('tag')->where('tag', $val)->find();
                if ( ! $tagid) {
                    $data = ['tag' => $val, 'total' => 0,'inputtime' => time(),'times'=>0];
                    $tag = Db::name('tag')->data($data)->insert();
                }
            }
            return ['status' => 1, 'msg' => '操作成功'];
        }else{
            return $this->fetch();
        }
    }
    
    public function edit()
    {
        if(input('post.dosubmit')){
            //查询是否存在tag
            $cha = Db::name('tag')->where(['tag'=>input('post.tag')])->where('id','not in',input('post.id'))->find();
            if($cha){
                return json(['status'=>0,'msg'=>'此tag标签已存在，请重新输入']);
            }
            $res = Db::name('tag')->data(['tag'=>input('post.tag'),'inputtime'=>time()])->where('id',input('post.id'))->update();
            if($res){
                return ['status'=>1,'msg'=>'修改成功'];
            }else{
                return ['status'=>0,'msg'=>'修改失败！！！'];
            }
            
        }else{
            $id = input('get.id');
            $data = Db::name('tag')->find($id);
            return $this->fetch('',['data'=>$data]);
        }
    }
    
    public function delete()
    {
        $ids = input('id');
        $del = Db::name('tag')->delete($ids);
        if(is_array($ids)){
            foreach($ids as $i){
                Db::name('tag_content')->where('tagid',$i)->delete();
            }
        }else{
            Db::name('tag_content')->where('tagid',$ids)->delete();
        }
        $this->success('删除成功~~~');
    }
    
    //选择tag
    public function select()
    {
        return $this->fetch('select');
    }
    
    public function select_json()
    {
        $limit = 50;
        if(input('post.dosearch')){
            $res = Db::name('tag')->where('tag','like','%'.input('post.key').'%')->limit($limit)->select();
        }else{
            $res = Db::name('tag')->limit($limit)->select();
        }
        $tags='';
        foreach($res as $v)
        {
            $tags .="<a onclick='set_val(\"".$v['tag']."\")'>#".$v['tag']."</a>";
        }
        return json($tags);
    }
    
}