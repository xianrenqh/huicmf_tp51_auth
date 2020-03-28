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
use lib\HuiTpl;

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
 * 数组去重
 */
function getArray($arr){
    $count = count($arr);
    $arrs = array();
    for ($i=0;$i<$count;$i++){
        $a = $arr[$i];
        unset($arr[$i]);
        if(!in_array($a,$arr)){
            $arrs[] = $a;
        }
    }
    return $arrs;
}

/**
 * 创建目录
 *
 * @param   string $path  路径
 * @param   string $mode  属性
 * @return  string 如果已经存在则返回true，否则为flase
 */
function dir_create($path, $mode = 0777) {
    if(is_dir($path)) return TRUE;
    $ftp_enable = 0;
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for($i=0; $i<$max; $i++) {
        $cur_dir .= $temp[$i].'/';
        if (@is_dir($cur_dir)) continue;
        @mkdir($cur_dir, 0777,true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}

/*
返回13位的时间戳
*/
function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
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


/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
    if(!is_array($string)) return addslashes($string);
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
}


/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
    if(!is_array($string)) return stripslashes($string);
    foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
    return $string;
}


/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
    if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,'utf-8');
    foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
    return $string;
}


/**
 * 转义 javascript 代码标记
 *
 * @param $str
 * @return mixed
 */
function trim_script($str) {
    if(is_array($str)){
        foreach ($str as $key => $val){
            $str[$key] = trim_script($val);
        }
    }else{
        $str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
        $str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
        $str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
        $str = str_replace ( 'javascript:', 'javascript：', $str );
    }
    return $str;
}


/**
 * 将字符串转换为数组
 *
 * @param	string	$data	字符串
 * @return	array	返回数组格式，如果，data为空，则返回空数组
 */
function string2array($data) {
    $data = trim($data);
    if($data == '') return array();
    
    if(strpos($data, '{\\')===0) $data = stripslashes($data);
    $array=json_decode($data,true);
    return $array;
}


/**
 * 将数组转换为字符串
 *
 * @param	array	$data		数组
 * @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
 * @return	string	返回字符串，如果，data为空，则返回空
 */
function array2string($data, $isformdata = 1) {
    if($data == '' || empty($data)) return '';
    
    if($isformdata) $data = new_stripslashes($data);
    if (version_compare(PHP_VERSION,'5.3.0','<')){
        return addslashes(json_encode($data));
    }else{
        return addslashes(json_encode($data,JSON_FORCE_OBJECT));
    }
}

// CurlPOST数据提交-----------------------------------------
function cmf_curl_get($url,$heads=array(),$cookie='')
{
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36');
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if(!empty($cookie)){
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if(count($heads)>0){
        curl_setopt ($ch, CURLOPT_HTTPHEADER , $heads );
    }
    $response = @curl_exec($ch);
    if(curl_errno($ch)){//出错则显示错误信息
        //print curl_error($ch);die;
    }
    curl_close($ch); //关闭curl链接
    return $response;//显示返回信息
}


/**
 *  提示信息页面跳转
 *
 * @param     string  $msg      消息提示信息
 * @param     string  $gourl    跳转地址
 * @param     int     $limittime  限制时间
 * @return    void
 */
function showmsg($msg, $gourl, $limittime='3') {
    $gourl = empty($gourl) ? HTTP_REFERER : $gourl;
    $stop = $gourl!='stop' ? false : true;
    include(Env::get('root_path').'extend'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'message.tpl');
    if(config('app.app_debug')){
        exit;
    }
}

/**
 * 获取模板主题列表
 * @param string $m 模块
 * @return array
 */
function get_theme_list($m = 'index'){
    $theme_list = array();
    $list = glob(APP_PATH.$m.DS.'view'.DS.'*', GLOB_ONLYDIR);
    foreach($list as $v){
        $theme_list[] = basename($v);
    }
    return $theme_list;
}

/**
 * 模板调用
 *
 * @param $module
 * @param $template
 * @return unknown_type
 */
function template($module = '', $template = 'index')
{
    if(!$module) $module = 'index';
    $template_c = Env::get('runtime_path').$module.DIRECTORY_SEPARATOR;
    $template_path = !defined('MODULE_THEME') ? Env::get('app_path').$module.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.get_config('site_theme').DIRECTORY_SEPARATOR : Env::get('app_path').$module.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.MODULE_THEME.DIRECTORY_SEPARATOR;;
    $filename = $template.'.html';
    $tplfile = $template_path.$filename;
    if(!is_file($tplfile)) {
        showmsg(str_replace(Env::get('root_path'),"",$tplfile).' 模板不存在！','stop');
    }
    if(!is_dir(Env::get('runtime_path').$module.DIRECTORY_SEPARATOR)){
        @mkdir(Env::get('runtime_path').$module.DIRECTORY_SEPARATOR, 0777, true);
    }
    $template = md5($template_path.$template);
    $template_c = $template_c.$template.'.tpl.php';
    if(!is_file($template_c) || filemtime($template_c) < filemtime($tplfile)) {
        $HuiTPL = new HuiTpl();
        $compile = $HuiTPL->tpl_replace(@file_get_contents($tplfile));
        file_put_contents($template_c, $compile);
    }
    return $template_c;
}

