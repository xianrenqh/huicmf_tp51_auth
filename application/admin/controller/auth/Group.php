<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/3 0003
 * Time: 10:04
 */

namespace app\admin\controller\auth;
use app\admin\controller\Common;
use app\admin\library\LibAuth;

use think\Db;
use lib\Tree;
use lib\Tree2;
use app\admin\model\AuthGroup;

class Group extends Common
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
    
    /**
     * 角色组
     */
    public function index()
    {
        $group_data = $this->auth->getGroups($this->uid);
        
        $tree = new Tree();
        $tree->icon =
            ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        
        $LibAuth = new LibAuth();
        $role_id = $LibAuth->getChildrenGroupIds(true);
        $data = Db::name('auth_group')->where('id', 'in', $role_id)->select();
        $array = [];
        foreach ($data as $v) {
            $edit_tree =
                '<a title=\'编辑权限\' href=\'javascript:;\' onclick=\'WeAdminShow("编辑权限","' . url(
                    'group_edit',
                    ['id' => $v['id']]
                ) . '",800)\' style=\'text-decoration:none\'  class=\'layui-btn layui-btn-xs layui-btn-normal\'>编辑权限</a>';
            if ( ! check_auth('auth/group_delete')) {
                $del_tree='';
            }else{
                $del_tree =
                    '<a title="删除" href="javascript:;" style="text-decoration:none" data-href="'.url('group_delete',['id'=>$v['id']]).'" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>';
            }
            
            $v['string'] = ($v['id']==$group_data[0]['id'])?'':$edit_tree . $del_tree;
            $v['status'] =
                $v['status'] == 'normal' ? '<span class="layui-btn layui-btn-xs">启用</span>' : '<span class="layui-btn layui-btn-xs layui-bg-red">禁用</span>';
            $v['parentid'] = $v['pid'];
            $array[] = $v;
        }
        $str = "<tr>
					<td>\$id</td>
					<td>\$parentid</td>
					<td>\$spacer\$name</td>
					<td style='text-align: center'>\$status</td>
					<td style='text-align: center'>\$string</td>
				</tr>";
        $tree->init($array);
        $menus = $tree->get_tree($group_data[0]['pid'], $str);
        return $this->fetch('', ['menus' => $menus]);
    }
    
    /**
     * 添加角色组
     */
    public function group_add()
    {
        if ( ! check_auth('auth/group_add')) {
            exit('2222');
        }
        if(input('post.dosubmit')){
            $rulesArr = input('post.rules');
            $rules = '';
            foreach($rulesArr as $v){
                $rules .=$v.",";
            }
            $rules = substr($rules,0,strlen($rules)-1);
            $param = input('post.');
            $param['createtime']=time();
            $param['rules']=$rules;
            $res = Db::name('auth_group')->data($param)->strict(false)->insert();
            if ($res) {
                return json(['status' => 1, 'msg' => '提交成功！']);
            } else {
                return json(['status' => 0, 'msg' => '提交失败！！！']);
            }
        }else{
            $pid = input('pid') ? input('pid') : 0;
            $tree = new Tree();
            
            $LibAuth = new LibAuth();
            $role_id = $LibAuth->getChildrenGroupIds(true);
            $data = Db::name('auth_group')->where('id', 'in', $role_id)->select();
            
            $array = [];
            foreach ($data as $v) {
                $v['selected'] = $v['id'] == $pid ? 'selected' : '';
                $v['parentid'] = $v['pid'];
                $v['title'] = $v['name'];
                $array[] = $v;
            }
            $str = "<option value='\$id' \$selected> \$spacer \$title</option>";
            $tree->init($array);
            $group_data = $this->auth->getGroups($this->uid);
            $select_menus = $tree->get_tree($group_data[0]['pid'], $str);
            
            return $this->fetch('', ['select_menus' => $select_menus,'role_id'=>$this->role_id]);
        }
    }
    
    /**
     * 编辑角色组
     */
    public function group_edit()
    {
        if ( ! check_auth('auth/group_edit')) {
            exit('2222');
        }
        $row = AuthGroup::get(['id' => input('id')]);
        if (!$row) {
            $this->error('记录未找到');
        }
        if (!in_array($row->id, $this->childrenGroupIds)) {
            $this->error('你无权限访问');
        }
        if(input('dosubmit')){
            $groupList = Db::name('auth_group')->where(['status' => 'normal'])->select();
            $params = input('post.');
            $rulesArr = $params['rules'];
            $rulesStr = implode(",",$rulesArr);
            
            //父节点不能是非权限内节点
            if (!in_array($params['pid'], $this->childrenGroupIds)) {
                $this->error('父节点不能是非权限内节点');
            }
            
            // 父节点不能是它自身的子节点或自己本身
            if (in_array($params['pid'], Tree2::instance()->init($groupList)->getChildrenIds($row->id,true))){
                $this->error('父节点不能是它自身的子节点或自己本身');
            }
            
            $params['rules'] = explode(',', $rulesStr);
            
            $parentmodel = model("AuthGroup")->get($params['pid']);
            if (!$parentmodel) {
                $this->error('无法找到父级组节点');
            }
            // 父级别的规则节点
            $parentrules = explode(',', $parentmodel->rules);
            
            // 当前组别的规则节点
            $LibAuth = new LibAuth();
            $currentrules = $LibAuth->getRuleIds();
            $rules = $params['rules'];
            
            // 如果父组不是超级管理员则需要过滤规则节点,不能超过父组别的权限
            $rules = in_array('*', $parentrules) ? $rules : array_intersect($parentrules, $rules);
            // 如果当前组别不是超级管理员则需要过滤规则节点,不能超当前组别的权限
            $rules = in_array('*', $currentrules) ? $rules : array_intersect($currentrules, $rules);
            $params['rules'] = implode(',', $rules);
            if ($params) {
                Db::startTrans();
                try {
                    $row->save($params);
                    $children_auth_groups = Db::name('auth_group')->where('id','in', implode(',',(Tree2::instance()->init($groupList)->getChildrenIds($row->id))))->select();
                    $childparams = [];
                    foreach ($children_auth_groups as $key=>$children_auth_group) {
                        $childparams[$key]['id'] = $children_auth_group['id'];
                        $childparams[$key]['rules'] = implode(',', array_intersect(explode(',', $children_auth_group['rules']), $rules));
                    }
                    model("AuthGroup")->saveAll($childparams);
                    Db::commit();
                    return json(['status' => 1, 'msg' => '修改成功！']);
                }catch (Exception $e){
                    Db::rollback();
                    return json(['status' => 0, 'msg' => '修改失败！！！']);
                }
            }
            $this->error('错错错！！！');
            return;
            
        }else{
            if (input('id')==1) {
                exit('超级管理员角色禁止修改');
            }
            $data_group = Db::name('auth_group')->where('id',input('id'))->find();
            if(input('id')==$this->role_id){
                exit('不允许修改自己的角色信息');
            }
            $pid =$data_group['pid'];
            
            $LibAuth = new LibAuth();
            $role_id = $LibAuth->getChildrenGroupIds(true);
            if(!in_array(input('id'),$role_id)){
                exit('抱歉，你没有权限操作');
            }
            $data = Db::name('auth_group')->where('id', 'in', $role_id)->select();
            $tree = new Tree();
            $array = [];
            
            foreach ($data as $v) {
                $v['selected'] = $v['id'] == $pid ? 'selected' : '';
                $v['parentid'] = $v['pid'];
                $v['title'] = $v['name'];
                $array[] = $v;
            }
            
            $str = "<option value='\$id' \$selected> \$spacer \$title</option>";
            $tree->init($array);
            $group_data = $this->auth->getGroups($this->uid);
            $select_menus = $tree->get_tree($group_data[0]['pid'], $str);
            return $this->fetch('group_edit',['data'=>$data_group,'select_menus'=>$select_menus]);
        }
    }
    
    /**
     * 删除角色组
     */
    public function group_delete()
    {
        if ( ! check_auth('auth/group_delete')) {
            exit('2222');
        }
        if (input('id')) {
            $id = input('id');
            $ids =  [$id];
            $row = AuthGroup::get(['id' => $id]);
            if (!$row) {
                $this->error('记录未找到');
            }
            if (!in_array($row->id, $this->childrenGroupIds)) {
                $this->error('你无权限访问');
            }
            $idArr = [$id];
            $grouplist = AuthGroup::where('id', $id)->select();
            $groupaccessmodel = model('AuthGroupAccess');
            foreach ($grouplist as $k => $v) {
                // 当前组别下有管理员
                $groupone = $groupaccessmodel->get(['group_id' => $v['id']]);
                if ($groupone) {
                    $ids = array_diff($idArr, [$v['id']]);
                    continue;
                }
                // 当前组别下有子组别
                $groupone = AuthGroup::get(['pid' => $v['id']]);
                if ($groupone) {
                    $ids = array_diff($idArr, [$v['id']]);
                    continue;
                }
            }
            if (!$ids) {
                return json(['status'=>0,'msg'=>'你不能删除含有子组和管理员的组']);
            }
            $count = AuthGroup::where('id', 'in', $ids)->delete();
            if ($count) {
                return json(['status'=>1,'msg'=>'删除成功']);
            }
            
        }else{
            $this->error('错错错！！！');
        }
    }
    
    /**
     *  角色组的权限列表，添加调用
     */
    public function group_rule_priv()
    {
        $id = input('pid');
        if($id==1){
            $data = Db::name('auth_rule')->select();
        }else{
            $rules = Db::name('auth_group')->field('rules')->where('id',$id)->find();
            $data = Db::name('auth_rule')->where('id','in',$rules['rules'])->select();
        }
        foreach($data as $k=>$v) {
            $data[$k]['level'] = $this->get_level($v['id'],$data);
        }
        $data=array2level($data);
        $returndata = [
            'code' => 0, 'msg' => '获取成功', 'data' => [
                'list' => $data,
            ],
        ];
        return json($returndata);
    }
    
    
    
    /**
     *  角色组的权限列表，修改调用，
     *  麻痹的， 这里写的好乱。。。  我自己都晕倒了
     */
    public function group_rule_priv2()
    {
        $id = input('pid');
        $gid = input('gid');
        
        $LibAuth = new LibAuth();
        $role_id = $LibAuth->getChildrenGroupIds(false);
        
        $groupList = Db::name('auth_group')->select();
        $childrenList = Tree2::instance()->init($groupList)->getChildren($id, true);
        
        $tree2 = new Tree2();
        
        if (in_array($id, $role_id) && in_array($id, $tree2->instance()->getChildrenIds($gid, true))) {
            return json(['status'=>0,'msg'=>'父组别不能是它的子组别']);
        }
        
        if($id==1){
            $data = Db::name('auth_rule')->select();
        }else{
            $rules = Db::name('auth_group')->field('rules')->where('id',$id)->find();
            $data = Db::name('auth_rule')->where('id','in',$rules['rules'])->select();
        }
        
        foreach($data as $k=>$v) {
            $data[$k]['level'] = $this->get_level($v['id'],$data);
        }
        $getRuleIds = Db::name('auth_group')->field('rules')->where('id',$gid)->find();
        $getRuleIds=explode(",",$getRuleIds['rules']);
        $checkIds=[];
        foreach($getRuleIds as $k=> $v){
            $checkIds[]= intval($v);
        }
        $data=array2level($data);
        $returndata = [
            'status' => 1, 'msg' => '获取成功', 'data' => [
                'list' => $data,
                'checkedId'=> $checkIds
            ],
        ];
        return json($returndata);
    }
    
    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    private function get_level($id, $array=array(), $i=0) {
        foreach($array as $n=>$value){
            if($value['id'] == $id){
                if($value['pid']) return $i;
                $i++;
                return $this->get_level($value['pid'],$array,$i);
            }
        }
    }
    
}