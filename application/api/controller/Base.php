<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-09-30
 * Time: 17:35:09
 * Info:
 */

namespace app\api\controller;

use think\App;
use think\exception\HttpResponseException;
use think\Response;

class Base
{

    protected $app;

    public function __construct(App $app = null)
    {
        // 用户验证初始化
        $this->_initUser();

        // 控制器初始化
        $this->initialize();
    }

    private function _initUser()
    {

    }

    // 初始化
    protected function initialize()
    {

    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param array $header 发送的Header信息
     * @return void
     */
    protected function success($msg = '', $data = '', array $header = [])
    {
        $code   = 200;
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        $type                                   = $this->getResponseType();
        $header['Access-Control-Allow-Origin']  = '*';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With,Content-Type,XX-Device-Type,XX-Token,XX-Api-Version,XX-Wxapp-AppId';
        $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        $response                               = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息,若要指定错误码,可以传数组,格式为['code'=>您的错误码,'msg'=>'您的错误消息']
     * @param mixed $data 返回的数据
     * @param array $header 发送的Header信息
     * @return void
     */
    protected function error($msg = '', $data = '', array $header = [])
    {
        $code = 0;
        if (is_array($msg)) {
            $code = $msg['code'];
            $msg  = $msg['msg'];
        }
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        $type                                   = $this->getResponseType();
        $header['Access-Control-Allow-Origin']  = '*';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With,Content-Type,XX-Device-Type,XX-Token,XX-Api-Version,XX-Wxapp-AppId';
        $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        $response                               = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }

    /**
     * 获取当前的response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType()
    {
        return 'json';
    }

}