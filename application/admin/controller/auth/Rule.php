<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/3 0003
 * Time: 10:05
 */

namespace app\admin\controller\auth;
use app\admin\controller\Common;

use think\Db;
use lib\Tree;
use think\facade\Cache;

class Rule extends Common
{
    
    
    /**
     * 权限菜单规则
     */
    public function index()
    {
        if ( ! check_auth('auth.rule/index')) {
            exit('抱歉，你没有访问权限！！！');
        }
        
        $tree = new Tree();
        $tree->icon =
            ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        
        if(cache('cache_auth_rule')){
            $data = cache('cache_auth_rule');
        }else{
            $data = Db::name('auth_rule')
                ->order('weigh ASC')
                ->select();
            cache('cache_auth_rule',$data);
        }
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
            if ( !check_auth('auth.rule/rule_delete')) {
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
            Cache::clear();
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
        if ( ! check_auth('auth.rule/rule_add')) {
            exit('2222');
        }
        if (input('post.dosubmit')) {
            $param = input('post.');
            $param['createtime'] = time();
            Db::name('auth_rule')
                ->strict(false)
                ->data($param)
                ->insert();
            
            Cache::clear();
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
        if ( ! check_auth('auth.rule/rule_edit')) {
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
                Cache::clear();
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
        if ( ! check_auth('auth.rule/rule_delete')) {
            exit('2222');
        }
        $id = input('id');
        /*$count = Db::name('auth_rule')->where(['pid'=>$id])->count();
        if($count>0){
            return json(['status'=>0,'msg'=>'删除失败，该菜单下有子菜单！不允许删除']);
        }*/
        $res = Db::name('auth_rule')->where('id',$id)->whereOr('pid',$id)->delete();
        if($res){
            Cache::clear();
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
        if ( ! check_auth('auth.rule/rule_add')) {
            error2('您没有权限操作');
        }
        foreach (input('listorders') as $id => $listorder) {
            Db::name('auth_rule')->where(['id' => $id])->update(['weigh' => $listorder]);
        }
        Cache::clear();
        $this->success('操作成功！', 'index', 1, 2);
    }
    
    
}