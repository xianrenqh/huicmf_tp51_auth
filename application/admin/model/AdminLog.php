<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/5 0005
 * Time: 9:58
 */

namespace app\admin\model;

use app\admin\library\LibAuth;
use think\Model;
use think\Db;
use lib\Auth;
use think\Loader;
use think\facade\Request;

class AdminLog extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    
    protected $childrenAdminIds = [];
    protected $childrenGroupIds = [];
    protected $uid;
    
    //自定义日志标题
    protected static $title = '';
    //自定义日志内容
    protected static $content = '';
    
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->uid = $this->uid = session('user_info.uid');
        $LibAuth = new LibAuth();
        $this->childrenAdminIds = $LibAuth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $LibAuth->getChildrenGroupIds(false);
    }
    
    public static function setTitle($title)
    {
        self::$title = $title;
    }
    
    public static function setContent($content)
    {
        self::$content = $content;
    }
    
    /**
     * @param $param
     * 写入日志
     * @return array
     */
    public static function record($title = '')
    {
    
        $controllername = Loader::parseName(Request::controller());
        $actionname = strtolower(Request::action());
        $path = str_replace('.', '/', $controllername) . '/' . $actionname;
        //$auth = Auth::instance();
        $LibAuth = new LibAuth();
        $admin_id = session('user_info') ? session('user_info.uid') : 0;
        $username = session('user_info') ? session('user_info.username') : 0;
        $content = self::$content;
        if (!$content) {
            $content = request()->param('', null, 'trim,strip_tags,htmlspecialchars');
            foreach ($content as $k => $v) {
                if (is_string($v) && strlen($v) > 200 || stripos($k, 'password') !== false) {
                    unset($content[$k]);
                }
            }
        }
        $title = self::$title;
        if(!$title){
            $title = [];
            $breadcrumb = $LibAuth->getBreadCrumb($path);
            foreach ($breadcrumb as $k => $v) {
                $title[] = $v['title'];
            }
            $title = implode(' ', $title);
        }
        if(config('huiadmin.open_adminlog')){
            self::create(
                [
                    'title'     => $title,
                    'content'   => !is_scalar($content) ? json_encode($content) : $content,
                    'url'       => substr(request()->url(), 0, 1500),
                    'admin_id'  => $admin_id,
                    'username'  => $username,
                    'useragent' => substr(request()->server('HTTP_USER_AGENT'), 0, 255),
                    'ip'        => ip()
                ]
            );
        }
    }
    
    public function selectData($param)
    {
        $where = "1=1";
        $page = $param['page'];
        $limit = $param['limit'];
        $first = ($page - 1) * $limit;
        
        if ( ! empty($param['key'])) {
            $getkey = $param['key'];
            $username = $getkey['username'] ? $getkey['username'] : "";
            $title = $getkey['title'] ? $getkey['title'] : "";
            $createtime = $getkey['createtime'] ? $getkey['createtime'] : "";
            if ( ! empty($createtime)) {
                $time_first = strtotime(explode(" - ", $createtime)[0]);
                $time_last = strtotime(
                                 explode(" - ", $createtime)[1]
                             ) + (60 * 60 * 24 - 1);
                if ($createtime != '') {
                    $where .= " and createtime>" . $time_first . " and createtime<" . $time_last;
                }
            }
            if ($username != '') {
                $where .= " and username='$username' ";
            }
            if ($title != '') {
                $where .= " and title like '%$title%' ";
            }
        }
        
        $list = Db::name('admin_log')
            ->where($where)
            ->where('admin_id', 'in', $this->childrenAdminIds)
            ->limit($first, $limit)
            ->order('id desc')
            ->select();
        for ($i = 0; $i < count($list); $i++) {
            $list[$i]['createtime'] =
                date("Y-m-d H:i:s", $list[$i]['createtime']);
        }
        $count = Db::name('admin_log')
            ->where($where)
            ->where('admin_id', 'in', $this->childrenAdminIds)
            ->count();
        
        return ['count' => $count, 'data' => $list, 'status' => 1];
    }
    
    public function delData($ids)
    {
        $childrenGroupIds = $this->childrenGroupIds;
        $idsss = Db::name('auth_group_access')
            ->where('group_id', 'in', $childrenGroupIds)
            ->column('uid');
        $adminList = Db::name('admin_log')
            ->where('id', 'in', $ids)
            ->where('admin_id', 'in', $idsss)
            ->select();
        if ($adminList) {
            $deleteIds = [];
            foreach ($adminList as $k => $v) {
                $deleteIds[] = $v['id'];
            }
            $deleteIds = array_values(array_diff($deleteIds, [$this->uid]));
            if ($deleteIds) {
                $del = Db::name('admin_log')
                    ->delete($deleteIds);
                
                return ['status' => 1, 'msg' => '删除成功！！！'];
            } else {
                return ['status' => 0, 'msg' => '删除失败'];
            }
        } else {
            return ['status' => 0, 'msg' => '不能越权删除以及删除自己！！！'];
        }
    }
    
    /**
     * @param $id
     * 查看详情
     */
    public function detailData($id)
    {
        $row =Db::name('admin_log')->find($id);
        if (!$row) {
            return ['status'=>0,'msg'=>'记录未找到！！！'];
        }
        if (!in_array($row['admin_id'], $this->childrenAdminIds)) {
            return ['status'=>0,'msg'=>'你无权限访问！！！'];
        }
        
        $data = Db::name('admin_log')->where('id',$id)->where('admin_id', 'in', $this->childrenAdminIds)->find();
        if($data){
            return ['status'=>1,'msg'=>'获取成功','data'=>$data];
        }else{
            return ['status'=>0,'msg'=>'参数错误！！！'];
        }
    }
    
}