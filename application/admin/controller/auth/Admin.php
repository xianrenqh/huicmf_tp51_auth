<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/3 0003
 * Time: 10:05
 */

namespace app\admin\controller\auth;

use app\admin\controller\Auth;
use app\admin\controller\Common;
use lib\Random;
use think\Db;
use lib\Tree;
use lib\Tree2;
use app\admin\library\LibAuth;
use app\admin\model\Admin as ModelAdmin;
use app\admin\model\AuthGroup;
use think\facade\Cache;

class Admin extends Common
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

    /**
     * 管理员管理
     */
    public function index()
    {

        if (input('get.do')) {
            $where = "1=1";
            $page  = input('get.page');
            $limit = input('get.limit');
            $first = ($page - 1) * $limit;
            $field = "id,username,nickname,email,logintime,loginip,status";

            $getkey   = input('get.key');
            $username = $getkey['username'] ? $getkey['username'] : "";
            $roles    = $getkey['roles'] ? $getkey['roles'] : "";
            $status   = $getkey['isuse'] == '' ? '' : $getkey['isuse'];

            $LibAuth = new LibAuth();
            $role_id = $LibAuth->getChildrenGroupIds(true);
            if ( ! empty($roles)) {
                if (in_array($roles, $role_id)) {
                    $uids = Db::name('auth_group_access')->alias('a')->field('uid')->join('auth_group g',
                            'g.id = a.group_id')->where('a.group_id', $roles)->select();

                    $cha_ids = '';
                    foreach ($uids as $v) {
                        $cha_ids .= $v['uid'].',';
                    }
                    $cha_idsArr = substr($cha_ids, 0, strlen($cha_ids) - 1);
                } else {
                    $this->error('参数错误');
                }
            }

            if ($username != '') {
                $where .= " and username like '%$username%' ";
            }
            if ($status == 'normal' || $status == 'hidden') {
                $where .= " and status ='$status' ";
            }
            if ($roles != '') {
                $where .= " and id in ($cha_idsArr) ";
            }
            $list = Db::name('admin')->field($field)->where($where)->where('id', 'in',
                    $this->childrenAdminIds)->limit("$first,$limit")->order('id asc')->select();
            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['logintime'] = $list[$i]['logintime'] == 0 ? '' : date("Y-m-d H:i:s", $list[$i]['logintime']);
                $group_list            = $this->auth->getGroups($list[$i]['id']);
                $group_list1           = '';
                foreach ($group_list as $k => $v) {
                    $group_list1 .= $v['name'].',';
                }
                $list[$i]['usergroup'] = substr($group_list1, 0, strlen($group_list1) - 1);
            }
            $total         = Db::name('admin')->count();
            $data['code']  = 0;
            $data['msg']   = '';
            $data['count'] = $total;
            $data['data']  = $list;

            return json($data);
        } else {

            $pid  = input('pid') ? input('pid') : 0;
            $tree = new Tree();

            $LibAuth = new LibAuth();
            $role_id = $LibAuth->getChildrenGroupIds(true);
            $data    = Db::name('auth_group')->where('id', 'in', $role_id)->select();

            $array = [];
            foreach ($data as $v) {
                $v['selected'] = $v['id'] == $pid ? 'selected' : '';
                $v['parentid'] = $v['pid'];
                $v['title']    = $v['name'];
                $array[]       = $v;
            }
            $str = "<option value='\$id' \$selected> \$spacer \$title</option>";
            $tree->init($array);
            $group_data   = $this->auth->getGroups($this->uid);
            $select_menus = $tree->get_tree($group_data[0]['pid'], $str);

            return $this->fetch('', ['select_menus' => $select_menus]);
        }
    }

    /**
     * 添加管理员
     */
    public function admin_add()
    {
        if (input('post.dosubmit')) {
            $param = input('post.');
            //查看是否有重复会员登录名
            $cha = ModelAdmin::where('username', $param['username'])->find();
            if ($cha) {
                return json(['status' => 0, 'msg' => '已存在此登录名，请更换']);
            }
            $salt                = Random::alnum();
            $param['salt']       = $salt;
            $param['password']   = cmf_password($param['password'],$salt);
            $param['createtime'] = time();
            $ins_id              = Db::name('admin')->strict(false)->insertGetId($param);
            $group_ids           = explode(",", $param['group']);
            $data2               = [];
            foreach ($group_ids as $k => $v) {
                $data2[] = ['uid' => $ins_id, 'group_id' => $v];
            }
            $insert_group = Db::name('auth_group_access')->insertAll($data2);

            return json(['status' => 1, 'msg' => '操作成功~~~']);
        } else {
            return $this->fetch();
        }
    }

    /**
     * 编辑管理
     */
    public function admin_edit()
    {
        $row = ModelAdmin::get(['id' => input('id')]);
        if ( ! $row) {
            $this->error('记录未找到');
        }
        if ( ! in_array($row->id, $this->childrenAdminIds)) {
            $this->error('你无权限访问');
        }
        $Random = new Random();
        if (input('post.dosubmit')) {
            $param               = input('post.');
            $param['updatetime'] = time();
            if ($param['password'] != '') {
                $param['salt']     = $Random->alnum();
                $param['password'] = cmf_password($param['password'],$param['salt']);
            } else {
                unset($param['password']);
            }
            $res1      = Db::name('admin')->data($param)->where('id', $param['id'])->strict(false)->update();
            $group_ids = $param['group'];
            $del       = Db::name('auth_group_access')->where('uid', $param['id'])->delete();
            $group_ids = explode(",", $group_ids);
            $data2     = [];
            foreach ($group_ids as $k => $v) {
                $data2[] = ['uid' => $param['id'], 'group_id' => $v];
            }
            $insert = Db::name('auth_group_access')->insertAll($data2);
            if ($res1) {
                return json(['status' => 1, 'msg' => '修改成功']);
            } else {
                return json(['status' => 0, 'msg' => '修改失败']);
            }
        } else {
            $data111    = Db::name('admin')->where('id', input('get.id'))->find();
            $group_ids  = Db::name('auth_group_access')->where('uid', input('id'))->select();
            $group_idss = [];
            foreach ($group_ids as $k => $v) {
                $group_idss[] = ($v['group_id']);
            }
            $scope                = implode(',', $group_idss);
            $data111['group_ids'] = $scope;

            $group_names            = $this->public_get_auth_group_name($scope);
            $data111['group_names'] = $group_names;
            $hidden                 = $this->uid == $data111['id'] ? 1 : 0;

            return $this->fetch('', ['data' => $data111, 'hidden' => $hidden]);
        }
    }

    /**
     * 删除管理员
     */
    public function admin_delete()
    {
        if ( ! check_auth('auth.admin/admin_delete')) {
            exit('2222');
        }
        $ids = input('ids');
        if ($ids) {
            $ids = array_intersect($this->childrenAdminIds, array_filter(explode(',', $ids)));
            // 避免越权删除管理员
            $childrenGroupIds = $this->childrenGroupIds;
            $idsss            = Db::name('auth_group_access')->where('group_id', 'in',
                $childrenGroupIds)->column('uid');
            $adminList        = ModelAdmin::where('id', 'in', $ids)->where('id', 'in', $idsss)->select();
            if ($adminList) {
                $deleteIds = [];
                foreach ($adminList as $k => $v) {
                    $deleteIds[] = $v->id;
                }
                $deleteIds = array_values(array_diff($deleteIds, [$this->uid]));
                if ($deleteIds) {
                    ModelAdmin::destroy($deleteIds);
                    model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();

                    return json(['status' => 1, 'msg' => '删除成功！！！']);
                } else {
                    return json(['status' => 0, 'msg' => '不能越权删除以及删除自己']);
                }
            }

        } else {
            $this->error('错错错！！！');
        }
    }

    /**
     * 点击选择组别
     */
    public function public_change_auth_group()
    {
        if (input('do')) {
            $data       = Db::name('auth_group')->where(['status' => 'normal'])->where('id', 'in',
                    $this->childrenGroupIds)->select();
            $group_ids  = Db::name('auth_group_access')->where('uid', input('id'))->select();
            $group_idss = [];
            foreach ($group_ids as $k => $v) {
                $group_idss[] = ($v['group_id']);
            }
            foreach ($data as $k => $v) {
                $data[$k]['level'] = $this->public_get_level($v['id'], $data);
                //$data[$k]['level'] = array2level($data,$v['pid'],1);
                $data[$k]['title'] = $data[$k]['name'];
            }
            $start_pid = $data[0]['pid'];
            $data      = array2level($data, $start_pid);

            $returndata = [
                'code'      => 0,
                'msg'       => '获取成功',
                'start_pid' => $start_pid,
                'data'      => [
                    'list'      => $data,
                    'checkedId' => $group_idss
                ],
            ];

            return json($returndata);
        } else {
            $id = input('id');

            return $this->fetch('change_auth_group', ['id' => $id]);
        }
    }

    /**
     * 根据group_id 获取name
     */
    public function public_get_auth_group_name($ppp = '')
    {
        if ($ppp) {
            $param = explode(",", $ppp);
        } else {
            $param = explode(",", input('post.arr'));
        }
        $group_name = '';
        foreach ($param as $k => $v) {
            $res        = Db::name('auth_group')->field('name')->where('id', $v)->find();
            $group_name .= $res['name'].',';
        }
        $group_name = substr($group_name, 0, strlen($group_name) - 1);
        if ($ppp) {
            return $group_name;
        } else {
            return json($group_name);
        }

    }

    /**
     * 获取菜单深度
     *
     * @param $id
     * @param $array
     * @param $i
     */
    private function public_get_level($id, $array = array(), $i = 0)
    {
        foreach ($array as $n => $value) {
            if ($value['id'] == $id) {
                if ($value['pid']) {
                    return $i;
                }
                $i++;

                return $this->public_get_level($value['pid'], $array, $i);
            }
        }
    }

}