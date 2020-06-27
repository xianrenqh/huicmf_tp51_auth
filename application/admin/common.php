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

// 后台应用公共文件-函数

use lib\HuiTpl;
use lib\Auth;
use lib\GetImgSrc;

/*错误页。不跳转*/
function error2($msg)
{
    echo "<h2 align='center'>".$msg."</h2>";
    exit;
}

/**
 * 按钮权限验证
 */
function check_auth($rule_name = '')
{
    $Auth = Auth::instance();

    return $Auth->check($rule_name, cmf_get_admin_id());
}

/**
 * 数组层级缩进转换
 *
 * @param array $array 源数组
 * @param int   $pid
 * @param int   $level
 *
 * @return array
 */
function array2level($array, $pid = 0, $level = 1)
{
    static $list = [];
    foreach ($array as $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $list[]     = $v;
            array2level($array, $v['id'], $level + 1);
        }
    }

    return $list;
}

/**
 * CMF密码加密方法
 *
 * @param string $pw       要加密的原始密码
 * @param string $authCode 加密字符串,salt
 *
 * @return string
 */
function cmf_password($pw, $authCode = '')
{
    $result = md5(md5($pw).$authCode);

    return $result;
}

/**
 * 获取当前登录的管理员ID
 * @return int
 */
function cmf_get_admin_id()
{
    $user_id = ! empty(session('user_info.uid')) ? session('user_info.uid') : 0;

    return $user_id;
}

/**
 * 转换字节数为其他单位
 *
 * @param string $filesize 字节大小
 *
 * @return    string    返回大小
 */
function sizecount($filesize)
{
    if ($filesize >= 1073741824) {
        $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
    } elseif ($filesize >= 1048576) {
        $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
    } elseif ($filesize >= 1024) {
        $filesize = round($filesize / 1024 * 100) / 100 .' KB';
    } else {
        $filesize = $filesize.' Bytes';
    }

    return $filesize;
}

/**
 * 根据key删除数组中指定元素
 *
 * @param array $arr 数组
 * @param string/int  $key  键（key）
 *
 * @return array
 */
function array_remove_by_key($arr, $key)
{
    if ( ! array_key_exists($key, $arr)) {
        return $arr;
    }
    $keys  = array_keys($arr);
    $index = array_search($key, $keys);
    if ($index !== false) {
        array_splice($arr, $index, 1);
    }

    return $arr;
}

/**
 * 构建层级（树状）数组
 *
 * @param array  $array          要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
 * @param string $pid_name       父级ID的字段名
 * @param string $child_key_name 子元素键名
 *
 * @return array|bool
 */
function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children')
{
    $counter = array_children_count($array, $pid_name);
    if ( ! isset($counter[0]) || $counter[0] == 0) {
        return $array;
    }
    $tree = [];
    while (isset($counter[0]) && $counter[0] > 0) {
        $temp = array_shift($array);
        if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
            array_push($array, $temp);
        } else {
            if ($temp[$pid_name] == 0) {
                $tree[] = $temp;
            } else {
                $array = array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
            }
        }
        $counter = array_children_count($array, $pid_name);
    }

    return $tree;
}

/**
 * 将数组转为tree树形结构函数
 *
 * @param        $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int    $root
 *
 * @return array
 */
function listToTree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
{
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent           =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }

    return $tree;
}

/**
 * 把元素插入到对应的父元素$child_key_name字段
 *
 * @param        $parent
 * @param        $pid
 * @param        $child
 * @param string $child_key_name 子元素键名
 *
 * @return mixed
 */
function array_child_append($parent, $pid, $child, $child_key_name)
{
    foreach ($parent as &$item) {
        if ($item['id'] == $pid) {
            if ( ! isset($item[$child_key_name])) {
                $item[$child_key_name] = [];
            }

            $item[$child_key_name][] = $child;
        }
    }

    return $parent;
}

/**
 * 子元素计数器
 *
 * @param array $array
 * @param int   $pid
 *
 * @return array
 */
function array_children_count($array, $pid)
{
    $counter = [];
    foreach ($array as $item) {
        $count = isset($counter[$item[$pid]]) ? $counter[$item[$pid]] : 0;
        $count++;
        $counter[$item[$pid]] = $count;
    }

    return $counter;
}

/**
 * 列出目录下所有文件
 *
 * @param string $path 路径
 * @param string $exts 扩展名
 * @param array  $list 增加的文件列表
 *
 * @return  array  所有满足条件的文件
 */
function dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') {
        $path = $path.'/';
    }

    return $path;
}

/**
 * 删除目录及目录下面的所有文件
 *
 * @param string $dir 路径
 *
 * @return  bool   如果成功则返回 TRUE，失败则返回 FALSE
 */
function dir_delete($dir)
{
    $dir = dir_path($dir);
    if ( ! is_dir($dir)) {
        return false;
    }
    $list = glob($dir.'*');
    foreach ($list as $v) {
        is_dir($v) ? dir_delete($v) : @unlink($v);
    }

    return @rmdir($dir);
}

/**
 * 文件下载
 *
 * @param $filepath 文件路径
 * @param $filename 文件名称
 */

function file_down($filepath, $filename = '')
{
    if ( ! $filename) {
        $filename = basename($filepath);
    }
    if (is_ie()) {
        $filename = rawurlencode($filename);
    }
    $filetype = fileext($filename);
    $filesize = sprintf("%u", filesize($filepath));
    if (ob_get_length() !== false) {
        @ob_end_clean();
    }
    header('Pragma: public');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Encoding: none');
    header('Content-type: '.$filetype);
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-length: '.$filesize);
    readfile($filepath);
    exit;
}

/**
 * gb2312转为utf-8
 *
 * @param      $str
 * @param bool $is true：其它编码转utf8   false：utf8转gb2312
 *
 * @return string
 */
function gbk_utf($str, $is = true)
{
    $encode = mb_detect_encoding($str, ['ASCII', 'UTF-8', 'GB2312', 'GBK', 'CP936']);
    // return $encode;
    if ($encode == 'UTF-8' && $is) {
        return $str;
    } elseif ($is == false && PHP_OS != 'Linux') {
        return iconv($encode, 'GB2312', $str);
    } else {
        return iconv($encode, 'UTF-8', $str);
    }
}

/**
 * IE浏览器判断
 */

function is_ie()
{
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if ((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false)) {
        return false;
    }
    if (strpos($useragent, 'msie ') !== false) {
        return true;
    }

    return false;
}

/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 *
 * @return 扩展名
 */
function fileext($filename)
{
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

// 转换大小
function changeSize($size, $num = 0)
{
    $type = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i    = 0;
    for ($i; $size >= 1024; $i++) {
        $size /= 1024;
    }

    return round($size, $num).$type[$i];// round四舍五入取值$num位小数
}

// 显示图片
function getpic($file, $height = 30)
{
    //$file = mb_convert_encoding(urldecode($file),'GB2312','UTF-8');
    $server = PHP_OS;
    //防止 有些中文windows或linux乱码
    if ($server == "Linux") {
        $file = urldecode($file);// 有些会不支持iconv 换mb_convert_encoding函数
    } elseif ($server == "WINNT") {
        $file = iconv('UTF-8', 'GB2312', urldecode($file));// 有些会不支持iconv 换mb_convert_encoding函数
    } else {
        $file = urldecode($file);
    }
    if ($fileinfo = @getimagesize($file)) {
        $filecontent = file_get_contents($file);
        $_b64        = chunk_split(base64_encode($filecontent));
        $pic         = 'data:'.$fileinfo['mime'].';base64,'.$_b64;

        return "<img src='{$pic}' height='{$height}.px'>";
    }

    return '';
}

/**
 * 字符截取
 *
 * @param $string    要截取的字符串
 * @param $length    截取长度
 * @param $dot       截取之后用什么表示
 * @param $code      编码格式，支持UTF8/GBK
 */
function str_cut($string, $length, $dot = '...', $code = 'utf-8')
{
    $strlen = strlen($string);
    if ($strlen <= $length) {
        return $string;
    }
    $string = str_replace(array(
        ' ',
        '&nbsp;',
        '&amp;',
        '&quot;',
        '&#039;',
        '&ldquo;',
        '&rdquo;',
        '&mdash;',
        '&lt;',
        '&gt;',
        '&middot;',
        '&hellip;'
    ), array('∵', ' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if ($code == 'utf-8') {
        $length = intval($length - strlen($dot) - $length / 3);
        $n      = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn  = 2;
                $n   += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn  = 3;
                $n   += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn  = 4;
                $n   += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn  = 5;
                $n   += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn  = 6;
                $n   += 6;
                $noc += 2;
            } else {
                $n++;
            }
            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
        $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(
            ' ',
            '&amp;',
            '&quot;',
            '&#039;',
            '&ldquo;',
            '&rdquo;',
            '&mdash;',
            '&lt;',
            '&gt;',
            '&middot;',
            '&hellip;'
        ), $strcut);
    } else {
        $dotlen      = strlen($dot);
        $maxi        = $length - $dotlen - 1;
        $current_str = '';
        $search_arr  = array('&', ' ', '"', "'", '“', '”', '—', '<', '>', '·', '…', '∵');
        $replace_arr = array(
            '&amp;',
            '&nbsp;',
            '&quot;',
            '&#039;',
            '&ldquo;',
            '&rdquo;',
            '&mdash;',
            '&lt;',
            '&gt;',
            '&middot;',
            '&hellip;',
            ' '
        );
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key         = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
    }

    return $strcut.$dot;
}

/**
 * 安全过滤函数
 *
 * @param $string
 *
 * @return string
 */
function safe_replace($string)
{
    $string = str_replace('%20', '', $string);
    $string = str_replace('%27', '', $string);
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);
    $string = str_replace('\\', '', $string);

    return $string;
}

function public_gethtml($ftype = '', $val = '', $setting = '')
{
    $fieldtype = $ftype ? $ftype : (isset($_POST['fieldtype']) && is_string($_POST['fieldtype']) ? safe_replace($_POST['fieldtype']) : 'textarea');
    if ($fieldtype == 'textarea') {
        echo '<textarea name="value" class="layui-textarea"  placeholder="例如：214243830">'.$val.'</textarea>';
    } elseif (in_array($fieldtype, array('select', 'radio'))) {
        if ($val) {
            echo \lib\Form::$fieldtype('value', $val, string2array($setting));
        } else {
            echo '<textarea name="setting" class="layui-textarea"  placeholder="选项用“|”分开，如“男|女|人妖”"></textarea> &nbsp;<input type="text" name="value" class="layui-input" style="width:180px" placeholder="默认值用配置值填写">';
        }
    } elseif ($fieldtype == 'image' || $fieldtype == 'attachment') {
        echo \lib\Form::$fieldtype('value', $val);
    } else {
        echo '<textarea name="value" class="layui-textarea"  placeholder="例如：214243830">'.$val.'</textarea>';
    }
}

/**
 * 获取请求ip
 * @return ip地址
 */
function ip()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],
            'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
}

/**
 * 根据IP获取请求地区（太平洋IP库）
 *
 * @param $ip
 *
 * @return 所在位置
 */
function get_address($ip)
{
    if ($ip == '127.0.0.1') {
        return '本地地址';
    }
    $content = @file_get_contents('http://whois.pconline.com.cn/ipJson.jsp?ip='.$ip.'&json=true');
    $content = iconv('GB2312', 'UTF-8', $content);
    $arr     = json_decode($content, true);
    if (is_array($arr) && $arr['regionCode'] == 0) {
        return $arr['addr'];
    } else {
        return '未知';
    }
}

/**
 * 数组去重
 */
function getArray($arr)
{
    $count = count($arr);
    $arrs  = array();
    for ($i = 0; $i < $count; $i++) {
        $a = $arr[$i];
        unset($arr[$i]);
        if ( ! in_array($a, $arr)) {
            $arrs[] = $a;
        }
    }

    return $arrs;
}

//字符串去重
function unique($str)
{
    //字符串中，需要去重的数据是以数字和“，”号连接的字符串，如$str,explode()是用逗号为分割，变成一个新的数组，见打印
    $arr  = explode(',', $str);
    $arr  = array_unique($arr);//内置数组去重算法
    $data = implode(',', $arr);
    $data = trim($data, ',');//trim — 去除字符串首尾处的空白字符（或者其他字符）,假如不使用，后面会多个逗号

    return $data;//返回值，返回到函数外部
}

/**
 * 创建目录
 *
 * @param string $path 路径
 * @param string $mode 属性
 *
 * @return  string 如果已经存在则返回true，否则为flase
 */
function dir_create($path, $mode = 0777)
{
    if (is_dir($path)) {
        return true;
    }
    $ftp_enable = 0;
    $path       = dir_path($path);
    $temp       = explode('/', $path);
    $cur_dir    = '';
    $max        = count($temp) - 1;
    for ($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i].'/';
        if (@is_dir($cur_dir)) {
            continue;
        }
        @mkdir($cur_dir, 0777, true);
        @chmod($cur_dir, 0777);
    }

    return is_dir($path);
}

/**
 * 创建文件操作
 * @method create_file
 *
 * @param str $filename 文件名
 *
 * @return boolean          true|false
 */
function create_file($filename)
{
    if (file_exists($filename)) {
        return false;
    }
    // 检测目录是否存在，不存在则创建
    if ( ! file_exists(dirname($filename))) {
        mkdir(dirname($filename), 0777, true);   //true是指是否创建多级目录
    }
    if (file_put_contents($filename, '') !== false) {   // ''是指创建的文件中的内容是空的
        return true;
    }
    return false;
}

/*
返回13位的时间戳
*/
function getMillisecond()
{
    list($t1, $t2) = explode(' ', microtime());

    return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

/**
 * 返回经addslashes处理过的字符串或数组
 *
 * @param $string 需要处理的字符串或数组
 *
 * @return mixed
 */
function new_addslashes($string)
{
    if ( ! is_array($string)) {
        return addslashes($string);
    }
    foreach ($string as $key => $val) {
        $string[$key] = new_addslashes($val);
    }

    return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 *
 * @param $string 需要处理的字符串或数组
 *
 * @return mixed
 */
function new_stripslashes($string)
{
    if ( ! is_array($string)) {
        return stripslashes($string);
    }
    foreach ($string as $key => $val) {
        $string[$key] = new_stripslashes($val);
    }

    return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 *
 * @param $obj 需要处理的字符串或数组
 *
 * @return mixed
 */
function new_html_special_chars($string)
{
    if ( ! is_array($string)) {
        return htmlspecialchars($string, ENT_QUOTES, 'utf-8');
    }
    foreach ($string as $key => $val) {
        $string[$key] = new_html_special_chars($val);
    }

    return $string;
}

/**
 * 转义 javascript 代码标记
 *
 * @param $str
 *
 * @return mixed
 */
function trim_script($str)
{
    if (is_array($str)) {
        foreach ($str as $key => $val) {
            $str[$key] = trim_script($val);
        }
    } else {
        $str = preg_replace('/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str);
        $str = preg_replace('/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str);
        $str = preg_replace('/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str);
        $str = str_replace('javascript:', 'javascript：', $str);
    }

    return $str;
}

/**
 * 将字符串转换为数组
 *
 * @param string $data 字符串
 *
 * @return    array    返回数组格式，如果，data为空，则返回空数组
 */
function string2array($data)
{
    $data = trim($data);
    if ($data == '') {
        return array();
    }

    if (strpos($data, '{\\') === 0) {
        $data = stripslashes($data);
    }
    $array = json_decode($data, true);

    return $array;
}

/**
 * 将数组转换为字符串
 *
 * @param array $data       数组
 * @param bool  $isformdata 如果为0，则不使用new_stripslashes处理，可选参数，默认为1
 *
 * @return    string    返回字符串，如果，data为空，则返回空
 */
function array2string($data, $isformdata = 1)
{
    if ($data == '' || empty($data)) {
        return '';
    }

    if ($isformdata) {
        $data = new_stripslashes($data);
    }
    if (version_compare(PHP_VERSION, '5.3.0', '<')) {
        return addslashes(json_encode($data));
    } else {
        return addslashes(json_encode($data, JSON_FORCE_OBJECT));
    }
}

// CurlPOST数据提交-----------------------------------------
function cmf_curl_get($url, $heads = array(), $cookie = '')
{
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT,
        'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36');

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if ( ! empty($cookie)) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if (count($heads) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $heads);
    }
    $response = @curl_exec($ch);
    if (curl_errno($ch)) {//出错则显示错误信息
        //print curl_error($ch);die;
    }
    curl_close($ch); //关闭curl链接

    return $response;//显示返回信息
}

/**
 *  提示信息页面跳转
 *
 * @param string $msg       消息提示信息
 * @param string $gourl     跳转地址
 * @param int    $limittime 限制时间
 *
 * @return    void
 */
function showmsg($msg, $gourl = '', $limittime = '3')
{
    $gourl = empty($gourl) ? HTTP_REFERER : $gourl;
    $stop  = $gourl != 'stop' ? false : true;
    include(Env::get('root_path').'extend'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'message.tpl');
    if (config('app.app_debug')) {
        exit;
    }
}

/**
 * 获取模板主题列表
 *
 * @param string $m 模块
 *
 * @return array
 */
function get_theme_list($m = 'index')
{
    $theme_list = array();
    $list       = glob(APP_PATH.$m.DS.'view'.DS.'*', GLOB_ONLYDIR);
    foreach ($list as $v) {
        $theme_list[] = basename($v);
    }

    return $theme_list;
}

/**
 * 将数组转换为对象
 *
 * @param array $data 数组
 *
 * @return 返回对象（object）
 */
function array2object($array)
{
    if (is_array($array)) {
        $obj = new StdClass();
        foreach ($array as $key => $val) {
            $obj->$key = $val;
        }
    } else {
        $obj = $array;
    }

    return $obj;
}

/**
 * 获取远程图片并把它保存到本地, 确定您有把文件写入本地服务器的权限
 *
 * @param string $content   文章内容
 * @param string $targeturl 可选参数，对方网站的网址，防止对方网站的图片使用"/upload/1.jpg"这样的情况
 *
 * @return string $content 处理后的内容
 */
function grab_image($content)
{
    $srcArr = GetImgSrc::srcList($content, 1, 0);
    dump($srcArr);
}