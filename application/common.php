<?php
/*
 * 用户自定义公共函数
 */


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


