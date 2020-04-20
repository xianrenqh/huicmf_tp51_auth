<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-24
 * Time: 13:39:09
 * Info:
 */

namespace app\admin\controller;
use app\admin\controller\Common;
use app\admin\library\LibAuth;
use think\Db;
use lib\Tree2;

class Category extends Common
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
        if(!empty(input('post.do'))){
            $list = Db::name('category')->order('id asc,weigh asc,pid asc')->select();
            for($i=0;$i<count($list);$i++){
                $list[$i]['nickname'] = ($list[$i]['nickname']==''&& $list[$i]['pc_link']!='')?$list[$i]['pc_link']:$list[$i]['nickname'];
            }
            $data = [
                'code'=>0,
                'data'=>$list,
                'count'=>count($list),
                'is'=>true,
                'tips'=>'操作成功'
            ];
            
            return json($data);
        }else{
            return $this->fetch("");
        }
     
    }
    
    //添加栏目
    public function add()
    {
        if(input('post.dosubmit')){
            $param = input('post.');
            $param['createtime'] = time();
            $res = Db::name('category')->data($param)->strict(false)->insert();
            if($res==1){
                return json(['status'=>1,'msg'=>'添加成功~~~']);
            }else{
                return json(['status'=>0,'msg'=>'添加失败！！！']);
            }
        }else{
            $pid = input('pid') ? input('pid') : 0;
            $type = input('type') ? input('type') : 1;
            $select_cate = $this->select_cate($pid);
            $get_model = $this->getModel();
            if($type==1){
                $category_temp = $this->select_template('category_temp', 'category_');
                $list_temp = $this->select_template('list_temp', 'list_');
                $show_temp = $this->select_template('show_temp', 'show_');
                return $this->fetch('add',['type'=>$type,'select_cate'=>$select_cate,'category_temp'=>$category_temp,'list_temp'=>$list_temp,'show_temp'=>$show_temp,'get_model'=>$get_model]);
            }elseif ($type==2){
                $page_temp = $this->select_template('page_temp', 'page_');
                return $this->fetch('page_add',['type'=>$type,'page_temp'=>$page_temp,'select_cate'=>$select_cate]);
            }else{
                return $this->fetch('link_add',['type'=>$type,'select_cate'=>$select_cate]);
            }
            
        }
    }
    
    
    public function edit()
    {
        if(input('post.dosubmit')){
            $param = input('post.');
            $param['updatetime'] = time();
            //查询是否有子级，有子级的话调整栏目不孕系调整到子级栏目下
            $groupList = Db::name('category')->select();
            if (in_array($param['pid'], Tree2::instance()->init($groupList)->getChildrenIds($param['id'],true))){
                return json(['status'=>0,'msg'=>'父节点不能是它自身的子节点或自己本身']);
            }
            $res = Db::name('category')->data($param)->strict(false)->update();
            if($res==1){
                return json(['status'=>1,'msg'=>'修改成功~~~']);
            }else{
                return json(['status'=>0,'msg'=>'修改失败！！！']);
            }
        }else{
            $data = Db::name('category')->where('id',input('id'))->find();
            $select_cate = $this->select_cate($data['pid']);
            $type = input('type');
            $get_model = Db::name('model')->field('name')->where('modelid',$data['modelid'])->find();
            if($type==1){
                $category_temp = $this->select_template('category_temp', 'category_');
                $list_temp = $this->select_template('list_temp', 'list_');
                $show_temp = $this->select_template('show_temp', 'show_');
                return $this->fetch('edit',['data'=>$data,'select_cate'=>$select_cate,'category_temp'=>$category_temp,'list_temp'=>$list_temp,'show_temp'=>$show_temp,'get_model'=>$get_model]);
            }elseif ($type==2){
                $page_temp = $this->select_template('page_temp', 'page_');
                return $this->fetch('page_edit',['data'=>$data,'select_cate'=>$select_cate,'page_temp'=>$page_temp]);
            }else{
                return $this->fetch('link_edit',['data'=>$data,'select_cate'=>$select_cate]);
            }
            
        }
    }

    
    public function delete()
    {
        $id = intval(input('post.id'));
        //查询是否有子级，如果有子级不允许删除
        $cha_soncate = Db::name('category')->where('pid',$id)->find();
        if($cha_soncate){
            return json(['status'=>0,'msg'=>'存在子级无法删除，请先删除子级栏目','icon'=>2]);
        }else{
            $del = Db::name('category')->delete($id);
            if($del==1){
                $count = Db::name('category')->count();
                return json(['status'=>1,'msg'=>'删除成功~~~','icon'=>1,'count'=>$count]);
            }else{
                return json(['status'=>0,'msg'=>'参数错误！！！','icon'=>2]);
            }
        }
    }
    
    public function order()
    {
        $weigh = input('post.weigh');
        $id = input('post.id');
        $arr = array_combine($id,$weigh);
        foreach($arr as $id=>$w){
            $up = Db::name('category')->where(['id' => $id])->update(['weigh' => intval($w)]);
        }
       return json(['status'=>1,'msg'=>'排序成功']);
    }
    
    private function select_cate($pid)
    {
        $cateList = Db::name('category')->order('id ASC,weigh ASC')->select();
        $select_cate = Tree2::instance()->init($cateList)->getTree(0,'<option value=@id @selected @disabled>@spacer@name</option>',$pid,'','','');
        return $select_cate;
    }
    
    //获取模型select
    private function getModel()
    {
        $res = Db::name('model')->field('modelid,name,tablename')->where('disabled',0)->select();
        return $res;
    }
    
    /**
     * 模板选择
     *
     * @param $style  风格
     * @param $pre 模板前缀
     */
    private function select_template($style, $pre = '') {
        $files = glob(APP_PATH.'index'.DS.'view'.DS.get_config('site_theme').DS.$pre.'*.html');
        $files = @array_map('basename', $files);
        $templates = array();
        if(is_array($files)) {
            foreach($files as $file) {
                $key = substr($file, 0, -5);
                $templates[$key] = $file;
            }
        }
        $tem_style = APP_PATH.'index'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.get_config('site_theme').DIRECTORY_SEPARATOR.'config.php';
        if(is_file($tem_style)){
            $templets = require($tem_style);
            return array_merge($templates, $templets[$style]);
        }else{
            return $templates;
        }
    }
    
}