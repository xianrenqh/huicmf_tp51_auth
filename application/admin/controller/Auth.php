<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2019/12/29 0029
 * Time: 8:20
 * Name：权限管理页面处理方法
 */

namespace app\admin\controller;

use think\Db;
use lib\Tree;
use lib\Tree2;
use lib\Random;
use app\admin\library\LibAuth;
use app\admin\model\Admin;
use app\admin\model\AuthGroup;


class Auth extends Common
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
     * 权限菜单规则
     */
    public function rule()
    {
        if ( ! check_auth('auth/rule')) {
            exit('抱歉，你没有访问权限！！！');
        }
        
        $tree = new Tree();
        $tree->icon =
            ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $data = Db::name('auth_rule')
            ->order('weigh ASC')
            ->select();
        $array = [];
        foreach ($data as $v) {
            $add_tree =
                '<a title=\'增加菜单\' href=\'javascript:;\' onclick=\'WeAdminShow("增加子菜单","' . url(
                    'rule_add',
                    ['pid' => $v['id']]
                ) . '",800)\' style=\'text-decoration:none\'  class=\'layui-btn layui-btn-xs\'>增加子类</a>';
            $edit_tree =
                '<a title=\'编辑菜单\' href=\'javascript:;\' onclick=\'WeAdminShow("编辑菜单","' . url(
                    'rule_edit',
                    ['id' => $v['id'], 'pid' => $v['pid']]
                ) . '",800)\' style=\'text-decoration:none\'  class=\'layui-btn layui-btn-xs layui-btn-normal\'>编辑</a>';
            if ( !check_auth('rule/rule_delete')) {
                $del_tree='';
            }else{
                $del_tree =
                    '<a title="删除" href="javascript:;" style="text-decoration:none" data-href="'.url('rule_delete',['id'=>$v['id']]).'" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>';
            }
            $v['string'] = $add_tree . $edit_tree . $del_tree;
            $v['parentid'] = $v['pid'];
            $checked = $v['status'] == 'normal' ? "checked" : '';
            $v['status'] =
                '<input type="checkbox" lay-skin="switch" lay-filter="switchStatus" lay-text="ON|OFF" ' . $checked . ' data-href="' . url(
                    'rule_switch_field',
                    ['id' => $v['id']]
                ) . '">';
            $v['icon'] = '<i class="iconfont ' . $v['icon'] . '"></i>';
            $v['ismenu'] =
                $v['ismenu'] == 1 ? '<span class="layui-badge layui-bg-green"> 是 </span>' : '<span class="layui-badge layui-bg-cyan"> 否 </span>';
            $array[] = $v;
        }
        $str = "<tr>
					<td><input name='listorders[\$id]' type='text' value='\$weigh' class='input-text listorder'></td>
					<td>\$id</td>
					<td style='text-align: center'>\$ismenu</td>
					<td>\$spacer\$title</td>
					<td style='text-align:center'>\$icon</td>
					<td>\$name</td>
					<td style='text-align: center'>\$status</td>
					<td style='text-align:center'>\$string</td>
				</tr>";
        $tree->init($array);
        $menus = $tree->get_tree(0, $str);
        
        return $this->fetch('', ['menus' => $menus]);
    }
    
    /**
     * 权限菜单列表更改状态
     */
    public function rule_switch_field()
    {
        $status = input('val') == 1 ? 'normal' : 'hidden';
        if ($this->auth->check('auth/rule_switch_field', $this->uid)) {
            $res = Db::name('auth_rule')
                ->data(['status' => $status])
                ->where('id', input('id'))
                ->update();
        } else {
            $res = 0;
        }
        if ($res) {
            return json(['status' => 1, 'msg' => '状态更改成功', 'reload' => 1]);
        } else {
            return json(
                ['status' => 0, 'msg' => '状态更改失败或者你没有权限', 'reload' => 0]
            );
        }
    }
    
    /**
     * 添加菜单
     */
    public function rule_add()
    {
        if ( ! check_auth('auth/rule_add')) {
            exit('2222');
        }
        if (input('post.dosubmit')) {
            $param = input('post.');
            $param['createtime'] = time();
            Db::name('auth_rule')
                ->strict(false)
                ->data($param)
                ->insert();
            
            return json(['status' => 1, 'msg' => '添加成功！']);
        } else {
            $pid = input('pid') ? input('pid') : 0;
            $tree = new Tree();
            $data = Db::name('auth_rule')
                ->order('weigh ASC,id DESC')
                ->select();
            $array = [];
            foreach ($data as $v) {
                $v['selected'] = $v['id'] == $pid ? 'selected' : '';
                $v['parentid'] = $v['pid'];
                $array[] = $v;
            }
            $str = "<option value='\$id' \$selected> \$spacer \$title</option>";
            $tree->init($array);
            $select_menus = $tree->get_tree(0, $str);
            
            return $this->fetch('rule_add', ['select_menus' => $select_menus]);
        }
    }
    
    /**
     * 编辑菜单
     */
    public function rule_edit()
    {
        if ( ! check_auth('auth/rule_edit')) {
            exit('2222');
        }
        
        if (input('post.dosubmit')) {
            $param = input('post.');
            $param['updatetime'] = time();
            $update = Db::name('auth_rule')
                ->where('id', input('post.id'))
                ->data($param)
                ->strict(false)
                ->update();
            if ($update) {
                return json(['status' => 1, 'msg' => '修改成功！']);
            } else {
                return json(['status' => 0, 'msg' => '修改失败！！！']);
            }
        } else {
            $id = input('id');
            $pid = input('pid') ? input('pid') : 0;
            $tree = new Tree();
            $data = Db::name('auth_rule')
                ->order('weigh ASC,id DESC')
                ->select();
            $array = [];
            foreach ($data as $v) {
                $v['selected'] = $v['id'] == $pid ? 'selected' : '';
                $v['parentid'] = $v['pid'];
                $array[] = $v;
            }
            $str = "<option value='\$id' \$selected> \$spacer \$title</option>";
            $tree->init($array);
            $select_menus = $tree->get_tree(0, $str);
            $data = Db::name('auth_rule')
                ->where('id', $id)
                ->find();
            
            return $this->fetch(
                'rule_edit',
                ['select_menus' => $select_menus, 'data' => $data]
            );
        }
    }
    
    /**
     * 删除
     */
    public function rule_delete()
    {
        if ( ! check_auth('auth/rule_delete')) {
            exit('2222');
        }
        $id = input('id');
        /*$count = Db::name('auth_rule')->where(['pid'=>$id])->count();
        if($count>0){
            return json(['status'=>0,'msg'=>'删除失败，该菜单下有子菜单！不允许删除']);
        }*/
        $res = Db::name('auth_rule')->where('id',$id)->whereOr('pid',$id)->delete();
        if($res){
            return json(['status'=>1,'msg'=>'操作成功~~~']);
        }else{
            return json(['status'=>0,'msg'=>'操作失败！！！']);
        }
    }
    
    /**
     * 权限菜单排序  weigh
     */
    public function rule_order()
    {
        if ( ! check_auth('auth/rule_add')) {
            error2('您没有权限操作');
        }
        foreach (input('listorders') as $id => $listorder) {
            Db::name('auth_rule')->where(['id' => $id])->update(['weigh' => $listorder]);
        }
        $this->success('操作成功！', 'rule', 1, 2);
    }
    
    
    /**
     * 角色组
     */
    public function group()
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
                    '<a title="删除" href="javascript:;" onclick=\'WeAdminDel("' . url(
                        'group_delete',
                        ['id' => $v['id']]
                    ) . '")\'style="text-decoration:none" data-href="'.url('group_delete',['id'=>$v['id']]).'" class="layui-btn layui-btn-xs layui-btn-danger ">删除</a>';
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
            
            /*$data = Db::name('auth_group')
                ->field('id,pid,name')
                ->order('id ASC')
                ->select();*/
            
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
            $rulesArr = input('post.rules');
            $rules = '';
            foreach($rulesArr as $v){
                $rules .=$v.",";
            }
            $rules = substr($rules,0,strlen($rules)-1);
            $param = input('post.');
            $param['updatetime']=time();
            $param['rules']=$rules;
            if($param['save']){
                $res = Db::name('auth_group')->where('id',input('id'))->data($param)->strict(false)->update();
                if ($res) {
                    return json(['status' => 1, 'msg' => '修改成功！']);
                } else {
                    return json(['status' => 0, 'msg' => '修改失败！！！']);
                }
            }else{
                return json(['status' => 0, 'msg' => '父组别不能是它子组别及本身！！！']);
            }
            
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
    }
    
    /**
     * 管理员管理
     */
    public function admin()
    {
        
        if(input('post.do')){
            $where = "1=1";
            $page = input('post.page');
            $limit = input('post.limit');
            $first = ($page - 1) * $limit;
            $field = "id,username,nickname,email,logintime,loginip,status";
    
            $getkey = input('post.key');
            $username = $getkey['username'] ? $getkey['username'] : "";
            //$roles = $getkey['roles'] ? $getkey['roles'] : "";
            $status = $getkey['isuse'] == '' ? '' : $getkey['isuse'];
            if ($username != '') {
                $where .= " and username like '%$username%' ";
            }
            if ($status == 'normal' || $status == 'hidden') {
                $where .= " and status ='$status' ";
            }
            $list = Db::name('admin')->field($field)
                ->where($where)
                ->where('id', 'in', $this->childrenAdminIds)
                ->limit("$first,$limit")
                ->order('id asc')
                ->select();
            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['logintime'] = $list[$i]['logintime'] == 0 ? '' : date("Y-m-d H:i:s", $list[$i]['logintime']);
                $group_list = $this->auth->getGroups($list[$i]['id']);
                $group_list1 ='';
                foreach ($group_list as $k=>$v){
                    $group_list1 .= $v['name'].',';
                }
                $list[$i]['usergroup'] = substr($group_list1,0,strlen($group_list1)-1);
            }
            $total = Db::name('admin')->count();
            $data['code'] = 0;
            $data['msg'] = '';
            $data['count'] = $total;
            $data['data'] = $list;
            return json($data);
        }else{
            return $this->fetch();
        }
    }
    
    /**
     * 添加管理员
     */
    public function admin_add()
    {
        return $this->fetch();
    }
    
    /**
     * 编辑管理
     */
    public function admin_edit()
    {
        $row = Admin::get(['id' => input('id')]);
        if (!$row) {
            $this->error('记录未找到');
        }
        if (!in_array($row->id, $this->childrenAdminIds)) {
            $this->error('你无权限访问');
        }
        $Random = new Random();
        if(input('post.dosubmit')){
            $param = input('post.');
            $param['updatetime'] = time();
            if($param['password']!=''){
                $param['salt']=$Random->alnum();
                $param['password'] = md5(md5($param['password']) . $param['salt']);
            }else{
                unset($param['password']);
            }
            $res1 = Db::name('admin')->data($param)->where('id',$param['id'])->strict(false)->update();
            $group_ids = $param['group'];
            $del = Db::name('auth_group_access')->where('uid',$param['id'])->delete();
            $group_ids = explode(",",$group_ids);
            $data2 = '';
            foreach ($group_ids as $k=>$v){
                $data2[] = ['uid'=>$param['id'],'group_id'=>$v];
            }
            $insert = Db::name('auth_group_access')->insertAll($data2);
            if($res1){
                return json(['status'=>1,'msg'=>'修改成功']);
            }else{
                return json(['status'=>0,'msg'=>'修改失败']);
            }
        }else{
            $data111 = Db::name('admin')->where('id',input('get.id'))->find();
            $group_ids = Db::name('auth_group_access')->where('uid',input('id'))->select();
            $group_idss =[];
            foreach($group_ids as $k=>$v){
                $group_idss[]= ($v['group_id']);
            }
            $scope =  implode(',',$group_idss);
            $data111['group_ids']=$scope;
    
            $group_names = $this->get_auth_group_name($scope);
            $data111['group_names']=$group_names;
            $hidden = $this->uid==$data111['id']?1:0;
            return $this->fetch('',['data'=>$data111,'hidden'=>$hidden]);
        }
    }
    
    /**
     * 点击选择组别
     */
    public function change_auth_group()
    {
        if(input('do')){
            $data = Db::name('auth_group')
                ->where(['status'=>'normal'])
                ->where('id','in',$this->childrenGroupIds)
                ->select();
            $group_ids = Db::name('auth_group_access')->where('uid',input('id'))->select();
            $group_idss =[];
            foreach($group_ids as $k=>$v){
                $group_idss[]= ($v['group_id']);
            }
            foreach($data as $k=>$v) {
                $data[$k]['level'] = $this->get_level($v['id'],$data);
                //$data[$k]['level'] = array2level($data,$v['pid'],1);
                $data[$k]['title'] = $data[$k]['name'];
            }
            $start_pid = $data[0]['pid'];
            $data=array2level($data,$start_pid);
           
            $returndata = [
                'code' => 0, 'msg' => '获取成功','start_pid'=>$start_pid, 'data' => [
                    'list' => $data,
                    'checkedId'=> $group_idss
                ],
            ];
            return json($returndata);
        }else{
            $id = input('id');
            return $this->fetch('',['id'=>$id]);
        }
    }
    
    
    /**
     * 根据group_id 获取name
     */
    public function get_auth_group_name($ppp='')
    {
        if($ppp){
            $param = explode(",",$ppp);
        }else{
            $param = explode(",",input('post.arr'));
        }
        $group_name ='';
        foreach($param as $k=>$v){
            $res = Db::name('auth_group')->field('name')->where('id',$v)->find();
            $group_name.=$res['name'].',';
        }
        $group_name = substr($group_name,0,strlen($group_name)-1);
        if($ppp){
            return $group_name;
        }else{
            return json($group_name);
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