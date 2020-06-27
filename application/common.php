<?php
/*
 * 用户自定义公共函数
 */
use lib\HuiTpl;

/**
 * 返回带协议的域名
 */
function cmf_get_domain()
{
    return request()->domain();
}

/**
 * 判断是否为手机访问
 * @return  boolean
 */
function cmf_is_mobile()
{
    if (PHP_SAPI != 'cli') {
        static $cmf_is_mobile;

        if (isset($cmf_is_mobile)) {
            return $cmf_is_mobile;
        }
    }

    $cmf_is_mobile = request()->isMobile();

    return $cmf_is_mobile;
}

/**
 * 判断是否为微信访问
 * @return boolean
 */
function cmf_is_wechat()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }

    return false;
}

/**
 * 判断是否为Android访问
 * @return boolean
 */
function cmf_is_android()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
        return true;
    }

    return false;
}

/**
 * 判断是否为ios访问
 * @return boolean
 */
function cmf_is_ios()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
        return true;
    }

    return false;
}

/**
 * 判断是否为iPhone访问
 * @return boolean
 */
function cmf_is_iphone()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
        return true;
    }

    return false;
}

/**
 * 判断是否为iPad访问
 * @return boolean
 */
function cmf_is_ipad()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
        return true;
    }

    return false;
}

/**
 * 获取系统配置信息
 *
 * @param $key 键值，可为空，为空获取整个数组
 *
 * @return array|string
 */
function get_config($key = '')
{
    if (cache('configs')) {
        $configs = cache('configs');
    } else {
        $data    = Db::name('config')->where('status', 1)->select();
        $configs = array();
        foreach ($data as $val) {
            $configs[$val['name']] = $val['value'];
        }
        cache('configs', $configs);
    }
    if ( ! $key) {
        return $configs;
    } else {
        return array_key_exists($key, $configs) ? $configs[$key] : '';
    }
}

/**
 * 修改config的函数
 *
 * @param $string 配置名 ，字符串
 * @param $arr2   配置前缀，数组
 * @param $arr3   数据变量，数组
 *
 * @return bool 返回状态
 */
function setconfig($filename, $pat, $rep)
{
    if (is_array($pat) and is_array($rep)) {
        for ($i = 0; $i < count($pat); $i++) {
            $pats[$i] = '/\''.$pat[$i].'\'(.*?),/';
            $reps[$i] = "'".$pat[$i]."'"."=>"."'".$rep[$i]."',";
        }
        $fileurl = ROOT_PATH.'config/'.$filename.'.php';
        $string  = file_get_contents($fileurl); //加载配置文件
        $string  = preg_replace($pats, $reps, $string); // 正则查找然后替换
        file_put_contents($fileurl, $string); // 写入配置文件

        return true;
    } else {
        return false;
    }
}


/**
 * 模板调用
 *
 * @param $module
 * @param $template
 *
 * @return unknown_type
 */
function template($module = '', $template = 'index')
{
    if ( ! $module) {
        $module = 'index';
    }
    $template_c    = Env::get('runtime_path').$module.DIRECTORY_SEPARATOR;
    $template_path = ! defined('MODULE_THEME') ? Env::get('app_path').$module.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.get_config('site_theme').DIRECTORY_SEPARATOR : Env::get('app_path').$module.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.MODULE_THEME.DIRECTORY_SEPARATOR;;
    $filename = $template.'.html';
    $tplfile  = $template_path.$filename;
    if ( ! is_file($tplfile)) {
        showmsg(str_replace(Env::get('root_path'), "", $tplfile).' 模板不存在！', 'stop');
    }
    if ( ! is_dir(Env::get('runtime_path').$module.DIRECTORY_SEPARATOR)) {
        @mkdir(Env::get('runtime_path').$module.DIRECTORY_SEPARATOR, 0777, true);
    }
    $template   = md5($template_path.$template);
    $template_c = $template_c.$template.'.tpl.php';
    if ( ! is_file($template_c) || filemtime($template_c) < filemtime($tplfile)) {
        $HuiTPL  = new HuiTpl();
        $compile = $HuiTPL->tpl_replace(@file_get_contents($tplfile));
        file_put_contents($template_c, $compile);
    }

    return $template_c;
}

//数组去重并排序
if ( ! function_exists('assoc_unique')) {
    function assoc_unique($arr, $key)
    {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {
                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }
        sort($arr);

        return $arr;
    }
}
