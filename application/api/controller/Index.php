<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-10
 * Time: 15:11:19
 * Info:
 */

namespace app\api\controller;
use think\Db;

header('Content-type:text/html; Charset=utf-8');
class Index
{
    
    /**
     * @api {post} api/index/link 01、获取友情链接列表
     * @apiName link
     * @apiGroup index
     * @apiVersion 1.0.0
     * @apiDescription  获取友情链接列表
     * @apiParam {String}  key key，第几页
     * @apiParam {String}  [page=1] 分页，第几页
     * @apiParam {int} [limit=10] 期望分页返回的数据页数量.
     *
     * @apiSuccessExample Success-Response://这里的JSON可以不用格式化，因为apidoc.js会自动格式化,但是最好格式化放在这里，方便别人看.
     *  {
     *    "status": 200,
     *    "msg": "success",
     *    "data": [{
     *       "id": 3,
     *       "typeid": 1,
     *       "linktype": 0,
     *       "name": "新浪",
     *       "url": "http:\/\/www.sina.com.cn",
     *       "logo": "",
     *       "msg": "",
     *       "username": "新浪",
     *       "email": "",
     *       "listorder": 2,
     *       "status": 1,
     *       "addtime": 1583827200
     *    }]
     *  }
     */
    public function link()
    {
        $order = "id desc";
        $first = !(empty(input('post.page')))?input('post.page'):1;
        $limit = !(empty(input('post.limit')))?input('post.limit'):10;
        $list = Db::name('link')->where(['status'=>1])->order($order)->limit($first-1,$limit)->select();
        if($list){
            return json(['status'=>200,'msg'=>'success','data'=>$list]);
        }else{
            return json(['status'=>201,'msg'=>'error']);
        }
    }
    
    
}