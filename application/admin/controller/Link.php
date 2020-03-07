<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-02-14
 * Time: 20:01:05
 * Info:
 */

namespace app\admin\controller;
use think\Db;

class Link extends Common
{
    public function index()
    {
        if(!empty(input('do'))){
            $list = Db::name('link')->order('addtime desc')->select();
            for($i=0;$i<count($list);$i++){
                $list[$i]['addtime'] = date("Y-m-d H:i:s",$list[$i]['addtime']);
            }
            $total = Db::name('link')->count();
            $data['code'] = 0;
            $data['msg'] = '';
            $data['count'] = $total;
            $data['data'] = $list;
            return json($data);
        }else{
            
            return $this->fetch();
        }
        
    }
    
}