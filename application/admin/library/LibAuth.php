<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2019/12/31 0031
 * Time: 14:31
 */

namespace app\admin\library;
use lib\Tree2;
use think\Db;
use app\admin\model\Admin;

class LibAuth extends \lib\Auth
{
    private $auth;
    private $uid;
    public function __construct()
    {
        //parent::__construct();
        $this->auth = \lib\Auth::instance();
        $this->uid =session('user_info.uid');
    }
    
    
    public function getRuleIds($uid = null)
    {
        return $this->auth->getRuleIds($uid);
    }
    public function isSuperAdmin()
    {
        return in_array('*', $this->getRuleIds($this->uid)) ? true : false;
    }
    
    /**
     * 取出当前管理员所拥有权限的管理员
     * @param boolean $withself 是否包含自身
     * @return array
     */
    public function getChildrenAdminIds($withself = false)
    {
        $childrenAdminIds = [];
        if (!$this->isSuperAdmin()) {
            $groupIds = $this->getChildrenGroupIds(false);
            $authGroupList = \app\admin\model\AuthGroupAccess::
            field('uid,group_id')
                ->where('group_id', 'in', $groupIds)
                ->select();
            foreach ($authGroupList as $k => $v) {
                $childrenAdminIds[] = $v['uid'];
            }
        }else{
            $childrenAdminIds = Admin::column('id');
        }
        
        if ($withself) {
            if (!in_array($this->uid, $childrenAdminIds)) {
                $childrenAdminIds[] = $this->uid;
            }
        } else {
            $childrenAdminIds = array_diff($childrenAdminIds, [$this->uid]);
        }
    
        return $childrenAdminIds;
        
    }
    
    /**
     * 取出当前管理员所拥有权限的分组
     * @param boolean $withself 是否包含当前所在的分组
     * @return array
     */
    public function getChildrenGroupIds($withself = false)
    {
        $groups = $this->auth->getGroups($this->uid);
        $groupIds = [];
        foreach ($groups as $k => $v) {
            $groupIds[] = $v['id'];
        }
        $originGroupIds = $groupIds;
        foreach ($groups as $k => $v) {
            if (in_array($v['pid'], $originGroupIds)) {
                $groupIds = array_diff($groupIds, [$v['id']]);
                unset($groups[$k]);
            }
        }
        $groupList = Db::name('auth_group')->where(['status' => 'normal'])->select();
        $objList = [];
        foreach ($groups as $k => $v) {
            if ($v['rules'] === '*') {
                $objList = $groupList;
                break;
            }
            
            // 取出包含自己的所有子节点
            
            $childrenList = Tree2::instance()->init($groupList)->getChildren($v['id'], true);
           
            $obj = Tree2::instance()->init($childrenList)->getTreeArray($v['pid']);
            $objList = array_merge($objList, Tree2::instance()->getTreeList($obj));
        }

        $childrenGroupIds = [];
        foreach ($objList as $k => $v) {
            $childrenGroupIds[] = $v['id'];
        }
        
        if (!$withself) {
            $childrenGroupIds = array_diff($childrenGroupIds, $groupIds);
        }
        return $childrenGroupIds;
    }
    
    
}