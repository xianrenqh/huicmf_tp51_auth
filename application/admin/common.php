<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2019/12/29 0029
 * Time: 15:20
 */
use lib\Auth;

/*错误页。不跳转*/
function error2($msg){
    echo "<h2 align='center'>".$msg."</h2>";exit;
}

/**
 * 按钮权限验证
 */
function check_auth($rule_name=''){
    $Auth = Auth::instance();
    return $Auth->check($rule_name,session('user_info.uid'));
}


/**
 * 数组层级缩进转换
 * @param array $array 源数组
 * @param int   $pid
 * @param int   $level
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
 * 转换字节数为其他单位
 * @param	string	$filesize	字节大小
 * @return	string	返回大小
 */
function sizecount($filesize) {
    if ($filesize >= 1073741824) {
        $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
    } elseif ($filesize >= 1048576) {
        $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
    } elseif($filesize >= 1024) {
        $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
    } else {
        $filesize = $filesize.' Bytes';
    }
    return $filesize;
}

/**
 * 根据key删除数组中指定元素
 * @param  array  $arr  数组
 * @param  string/int  $key  键（key）
 * @return array
 */
function array_remove_by_key($arr, $key){
    if(!array_key_exists($key, $arr)){
        return $arr;
    }
    $keys = array_keys($arr);
    $index = array_search($key, $keys);
    if($index !== FALSE){
        array_splice($arr, $index, 1);
    }
    
    return $arr;
}


/**
 * 构建层级（树状）数组
 * @param array  $array          要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
 * @param string $pid_name       父级ID的字段名
 * @param string $child_key_name 子元素键名
 * @return array|bool
 */
function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children')
{
    $counter = array_children_count($array, $pid_name);
    if (!isset($counter[0]) || $counter[0] == 0) {
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
                $array =  array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
            }
        }
        $counter =  array_children_count($array, $pid_name);
    }
    return $tree;
}
/**
 * 把元素插入到对应的父元素$child_key_name字段
 * @param        $parent
 * @param        $pid
 * @param        $child
 * @param string $child_key_name 子元素键名
 * @return mixed
 */
function array_child_append($parent, $pid, $child, $child_key_name)
{
    foreach ($parent as &$item) {
        if ($item['id'] == $pid) {
            if (!isset($item[$child_key_name])) {
                $item[$child_key_name] = [];
            }
            
            $item[$child_key_name][] = $child;
        }
    }
    return $parent;
}
/**
 * 子元素计数器
 * @param array $array
 * @param int   $pid
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
 * @param   string $path     路径
 * @param   string $exts     扩展名
 * @param   array  $list     增加的文件列表
 * @return  array  所有满足条件的文件
 */
function dir_path($path) {
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') $path = $path . '/';
    return $path;
}

/**
 * 删除目录及目录下面的所有文件
 *
 * @param   string $dir      路径
 * @return  bool   如果成功则返回 TRUE，失败则返回 FALSE
 */
function dir_delete($dir) {
    $dir = dir_path($dir);
    if (!is_dir($dir)) return FALSE;
    $list = glob($dir.'*');
    foreach($list as $v) {
        is_dir($v) ? dir_delete($v) : @unlink($v);
    }
    return @rmdir($dir);
}


/**
 * 文件下载
 * @param $filepath 文件路径
 * @param $filename 文件名称
 */

function file_down($filepath, $filename = '') {
    if(!$filename) $filename = basename($filepath);
    if(is_ie()) $filename = rawurlencode($filename);
    $filetype = fileext($filename);
    $filesize = sprintf("%u", filesize($filepath));
    if(ob_get_length() !== false) @ob_end_clean();
    header('Pragma: public');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
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
 * @param $str
 * @param bool $is true：其它编码转utf8   false：utf8转gb2312
 * @return string
 */
function gbk_utf($str, $is = true)
{
    $encode = mb_detect_encoding($str, ['ASCII', 'UTF-8', 'GB2312', 'GBK','CP936']);
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

function is_ie() {
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false)) return false;
    if(strpos($useragent, 'msie ') !== false) return true;
    return false;
}

/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

// 转换大小
function changeSize($size,$num = 0)
{
    $type = ['B','KB','MB','GB','TB'];
    $i = 0;
    for ($i; $size >= 1024; $i++){
        $size /= 1024;
    }
    return round($size,$num).$type[$i];// round四舍五入取值$num位小数
}

// 显示图片
function getpic($file,$height = 30)
{
    //$file = mb_convert_encoding(urldecode($file),'GB2312','UTF-8');
    $server = PHP_OS;
    //防止 有些中文windows或linux乱码
    if($server == "Linux"){
        $file = urldecode($file);// 有些会不支持iconv 换mb_convert_encoding函数
    }elseif($server == "WINNT"){
        $file = iconv('UTF-8','GB2312',urldecode($file));// 有些会不支持iconv 换mb_convert_encoding函数
    }else{
        $file = urldecode($file);
    }
    if($fileinfo = @getimagesize($file)){
        $filecontent = file_get_contents($file);
        $_b64 = chunk_split(base64_encode($filecontent));
        $pic = 'data:'.$fileinfo['mime'].';base64,'.$_b64;
        return "<img src='{$pic}' height='{$height}.px'>";
    }
    return '';
}