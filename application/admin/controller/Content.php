<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-04-03
 * Time: 9:59:31
 * Info:
 */

namespace app\admin\controller;
use app\admin\library\LibAuth;
use think\Db;

class Content extends Common
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
        if(input('post.do')==1){
            $param = input('post.');
            $page = $param['page'];
            $limit = $param['limit'];
            $first = ($page - 1) * $limit;
            $where = "1=1";
            if ( !empty($param['key'])) {
            
            }
            $modelid = !empty($param['modelid'])?$param['modelid']:1;
            $tableName = $this->get_model($modelid)->{'tablename'};
            $field = "id,catid,click,flag,inputtime,userid,username,updatetime,url,title,thumb,status,is_push";
            $list = Db::name($tableName)->where($where)->field($field)->limit($first, $limit)->order('updatetime DESC,id DESC')->select();
            for($i=0;$i<count($list);$i++){
                $flag = '';
                foreach(explode(",",$list[$i]['flag']) as $v){
                    $flag .= "<span class='we-red'>[".$this->getFlagName($v)."]</span> ";
                }
                $list[$i]['title'] =  "<a href='".$list[$i]['url']."' target='_blank'>".$list[$i]['title']."</a> <i class=\"layui-icon layui-icon-picture\" style='color:#1E9FFF'></i> ".$flag;
                $list[$i]['updatetime'] = date("Y-m-d H:i:s",$list[$i]['updatetime']);
                $list[$i]['username'] = $this->getUserName($list[$i]['userid'])->{'username'};
                $list[$i]['catname'] = $this->getTypeName($list[$i]['catid'])->{'name'};
            }
            $total = Db::name($tableName)->count();
            $data['code'] = 0;
            $data['msg'] = '';
            $data['count'] = $total;
            $data['data'] = $list;
            return json($data);
        }else{
            return $this->fetch('content_list');
        }
    }
    
    //根据modelid获取tableName以及中文Nabe
    private function get_model($modelid){
        $res = Db::name('model')->field("name,tablename")->where('modelid',$modelid)->find();
        if($res){
            return array2object($res);
        }else{
            return array2object(['name'=>'空','tablename'=>'空']);
        }
    }
    
    //根据uid获取userName
    private function getUserName($uid){
        $res = Db::name('admin')->field('username,nickname')->where('id',$uid)->find();
        if($res){
            return array2object($res);
        }else{
            return array2object(['username'=>'空','nickname'=>'空']);
        }
    }
    
    //根据分类id获取分类Name
    private function getTypeName($catid){
        $res = Db::name('category')->field('id,name')->where('id',$catid)->find();
        if($res){
            return array2object($res);
        }else{
            return array2object(['id'=>'空','name'=>'空']);
        }
    }
    
    //根据flagId获取flagName
    private function getFlagName($flagid)
    {
        switch ($flagid){
            case 1:
                $flagName = "置顶";
                break;
            case 2:
                $flagName = "头条";
                break;
            case 3:
                $flagName = "特荐";
                break;
            case 4:
                $flagName = "推荐";
                break;
            case 5:
                $flagName = "热点";
                break;
            case 6:
                $flagName = "幻灯";
                break;
            case 7:
                $flagName = "跳转";
                break;
            default:
                $flagName = '';
                break;
        }
        return $flagName;
    }
    
}