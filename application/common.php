<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * 获取请求ip
 * @return ip地址
 */
function ip() {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}

/**
 * 根据IP获取请求地区（太平洋IP库）
 * @param $ip
 * @return 所在位置
 */
function get_address($ip){
    if($ip == '127.0.0.1') return '本地地址';
    $content = @file_get_contents('http://whois.pconline.com.cn/ipJson.jsp?ip='.$ip.'&json=true');
    $content=iconv('GB2312', 'UTF-8', $content);
    $arr = json_decode($content, true);
    if(is_array($arr)&& $arr['regionCode']==0){
        return $arr['addr'];
    }else{
        return '未知';
    }
}


/**
 * 获取系统配置信息
 * @param $key 键值，可为空，为空获取整个数组
 * @return array|string
 */
function get_config($key = '')
{
    if(cache('configs')){
        $configs= cache('configs');
    }else{
        $data = Db::name('config')->where('status',1)->select();
        $configs = array();
        foreach($data as $val){
            $configs[$val['name']] = $val['value'];
        }
        cache('configs',$configs);
    }
    if(!$key){
        return $configs;
    }else{
        return array_key_exists($key, $configs) ? $configs[$key] : '';
    }
}

/**
 * 修改config的函数
 * @param $string 配置名 ，字符串
 * @param $arr2 配置前缀，数组
 * @param $arr3 数据变量，数组
 * @return bool 返回状态
 */
function setconfig($filename , $pat, $rep)
{
    if (is_array($pat) and is_array($rep)) {
        for ($i = 0; $i < count($pat); $i++) {
            $pats[$i] = '/\'' . $pat[$i] . '\'(.*?),/';
            $reps[$i] = "'". $pat[$i]. "'". "=>" . "'".$rep[$i] ."',";
        }
        $fileurl = ROOT_PATH.'config/'.$filename.'.php' ;
        $string = file_get_contents($fileurl); //加载配置文件
        $string = preg_replace($pats, $reps, $string); // 正则查找然后替换
        file_put_contents($fileurl, $string); // 写入配置文件
        return true;
    } else {
        return false;
    }
}
