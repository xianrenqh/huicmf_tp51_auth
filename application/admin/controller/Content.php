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
use lib\Tree2;
use lib\Random;

class Content extends Common
{

    public $childrenAdminIds;

    public $childrenGroupIds;

    public function __construct()
    {
        parent::__construct();
        $LibAuth                = new LibAuth();
        $this->childrenAdminIds = $LibAuth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $LibAuth->getChildrenGroupIds(true);
    }

    public function index()
    {
        $getModel = $this->getModel()[0]['modelid'];
        if (input('get.do') == 1) {
            $param = input();
            $page  = $param['page'];
            $limit = $param['limit'];
            $first = ($page - 1) * $limit;
            $where = "1=1";
            $order = "updatetime DESC,id DESC";
            if ( ! empty($param['key'])) {
                $getkey     = $param['key'];
                $updatetime = $getkey['updatetime'] ? $getkey['updatetime'] : "";
                if ( ! empty($updatetime)) {
                    $time_first = strtotime(explode(" - ", $updatetime)[0]);
                    $time_last  = strtotime(explode(" - ", $updatetime)[1]) + (60 * 60 * 24 - 1);
                    if ($updatetime != '') {
                        $where .= " and updatetime>".$time_first." and updatetime<".$time_last;
                    }
                }
                if (isset($getkey["flag"]) && $getkey["flag"] != '0') {
                    $where .= ' AND FIND_IN_SET('.intval($getkey["flag"]).',flag)';
                }
                if (isset($getkey["status"]) && $getkey["status"] != '99') {
                    $where .= ' AND status = '.intval($getkey["status"]);
                }
                $type     = $getkey['type'];
                $searinfo = $getkey['searinfo'] ? $getkey['searinfo'] : "";
                if ($searinfo != '') {
                    switch ($type) {
                        case 1:
                            $where .= " and title like '%".$searinfo."%'";
                            break;
                        case 2:
                            $uid = $this->getUserId($searinfo)->{'id'};
                            if ($uid != '') {
                                $where .= " and userid=".$uid;
                            } else {
                                $where .= " and userid=''";
                            }
                            break;
                        case 3:
                            $where .= " and id=".$searinfo;
                            break;
                        default:
                            $where .= " and title like '%".$searinfo."%'";
                            break;
                    }
                }
            }
            if ( ! empty($param['field']) && ! empty($param['order'])) {
                $order = $param['field']." ".$param['order'];
            }
            $modelid   = ! empty($param['modelid']) ? $param['modelid'] : $getModel;
            $tableName = $this->get_model($modelid)->{'tablename'};
            $field     = "id,catid,click,flag,inputtime,userid,username,updatetime,url,title,thumb,status,is_push";
            $list      = Db::name($tableName)->where($where)->field($field)->limit($first,
                $limit)->order($order)->select();
            for ($i = 0; $i < count($list); $i++) {
                $flag = '';
                foreach (explode(",", $list[$i]['flag']) as $v) {
                    if ($v != '') {
                        $flag .= "<span class='we-red'>[".$this->getFlagName($v)."]</span> ";
                    }
                }
                $list[$i]['title']      = "<a href='".$list[$i]['url']."' target='_blank'>".$list[$i]['title']."</a> <i class=\"layui-icon layui-icon-picture\" style='color:#1E9FFF'></i> ".$flag;
                $list[$i]['updatetime'] = date("Y-m-d H:i:s", $list[$i]['updatetime']);
                $list[$i]['username']   = $this->getUserName($list[$i]['userid'])->{'username'};
                $list[$i]['catname']    = $this->getTypeName($list[$i]['catid'])->{'name'};
            }
            $total           = Db::name($tableName)->where($where)->count();
            $data['code']    = 0;
            $data['msg']     = '';
            $data['count']   = $total;
            $data['data']    = $list;
            $data['modelid'] = $modelid;

            return json($data);
        } else {
            $getModel = $this->getModel();

            return $this->fetch('content_list', ['getModel' => $getModel, 'getModel' => $getModel]);
        }
    }

    //添加
    public function add()
    {
        if (input('post.dosubmit')) {
            $param = input('post.');
            $param['inputtime']=time();
            //这里随便写点，测试添加数据的
            $insert = Db::name('article')->strict(false)->insert($param);
            return json(['status' => 1,'msg'=>'ok']);
        }
        $param       = input('get.');
        $modelid     = ! empty($param['modelid']) ? $param['modelid'] : 1;
        $tableName   = $this->get_model($modelid)->{'tablename'};
        $select_cate = $this->select_cate(0, $modelid);
        $randNum     = Random::nozero(2);
        $ContentForm = new ContentForm($modelid);
        $string      = $ContentForm->content_add();

        return $this->fetch('content_add', ['select_cate' => $select_cate, 'randNum' => $randNum, 'string' => $string]);
    }

    //编辑
    public function edit()
    {

    }

    //删除
    public function delete()
    {
        $ids     = input('post.ids');
        $modelid = input('post.modelid');
        //如果是数组，批量删除
        if (is_array($ids)) {

        } else {
            //删除单条
        }
    }

    //属性操作
    public function attribute_operation()
    {

    }

    //栏目option树结构
    private function select_cate($pid, $modelid)
    {
        $cateList = Db::name('category')->field("id,pid,modelid,type,name")->where([
            'type'    => 1,
            'modelid' => $modelid
        ])->order('id ASC,weigh ASC')->select();
        $pppidstr = '';
        foreach ($cateList as $v) {
            $pppid    = Tree2::instance()->init($cateList)->getParentsIds($v['id']);
            $pppidstr .= (implode(",", $pppid)).",";
        }
        $cateIddd    = unique($pppidstr);
        $select_cate = Tree2::instance()->init($cateList)->getTree(0,
            '<option value=@id @selected @disabled>@spacer@name</option>', $pid, $cateIddd, '', '');

        return $select_cate;
    }

    //获取模型select
    private function getModel()
    {
        $res = Db::name('model')->field('modelid,name,tablename')->where('disabled', 0)->select();

        return $res;
    }

    //根据modelid获取tableName以及中文Nabe
    private function get_model($modelid)
    {
        $res = Db::name('model')->field("name,tablename")->where('modelid', $modelid)->find();
        if ($res) {
            return array2object($res);
        } else {
            return array2object(['name' => '空', 'tablename' => '空']);
        }
    }

    //根据uid获取userName
    private function getUserName($uid)
    {
        $res = Db::name('admin')->field('username,nickname')->where('id', $uid)->find();
        if ($res) {
            return array2object($res);
        } else {
            return array2object(['username' => '空', 'nickname' => '空']);
        }
    }

    //根据username查询uid
    private function getUserId($username)
    {
        $res = Db::name('admin')->field('id,username')->where('username', $username)->find();
        if ($res) {
            $res['code'] = 1;

            return array2object($res);
        } else {
            return array2object(['code' => 0, 'id' => '']);
        }
    }

    //根据分类id获取分类Name
    private function getTypeName($catid)
    {
        $res = Db::name('category')->field('id,name')->where('id', $catid)->find();
        if ($res) {
            return array2object($res);
        } else {
            return array2object(['id' => '空', 'name' => '空']);
        }
    }

    //根据flagId获取flagName
    private function getFlagName($flagid)
    {
        switch ($flagid) {
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