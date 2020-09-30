<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-10
 * Time: 15:11:19
 * Info:
 */

namespace app\api\controller;

use app\member\model\BaseUser;
use think\facade\Request;

use think\Db;

header('Content-type:text/html; Charset=utf-8');

/**
 * @title HuiCMF-API
 * @desc API接口
 * Class APi
 * @package app\api\controller
 */
class Index extends Base
{

    /**
     * 获取标签列表
     */
    public function index()
    {
        return json(['msg' => '恭喜您,API访问成功!']);
    }

    /**
     * @title 友情链接
     * @url /api.php/index/link
     * @header string MX-device-type 设备类型 空 必须
     * @header string MX-Admin-Type 管理类型 空 必须
     * @header string MX-Token 用户token 空 必须
     * @json {"code":200,"msg":"获取成功","data":[]}
     * @method POST
     * @code 200 成功
     * @code 0 失败
     * @return int code 状态码 （具体参见状态码说明）
     * @return string msg 提示信息
     */
    public function link()
    {
        $order = "id desc";
        $first = ! (empty(input('post.page'))) ? input('post.page') : 1;
        $limit = ! (empty(input('post.limit'))) ? input('post.limit') : 10;
        $list  = Db::name('link')->where(['status' => 1])->order($order)->limit($first - 1, $limit)->select();
        $this->success('获取成功', $list);
    }

}