<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-04-14
 * Time: 15:07:31
 * Info:
 */

namespace lib;
use think\facade\Request;

class Form
{
    
    /**
     * 单选框
     *
     * @param $name name
     * @param $val 默认选中值 如：1
     * @param $array 一维数组 如：array('交易成功', '交易失败', '交易结果未知');
     */
    public static function radio($name, $val = '', $array = array()) {
        $string = '';
        $string .='<div class="layui-input-block" id="status" style="margin-left:0">';
        foreach($array as $value) {
            $checked = trim($val)==trim($value) ? 'checked' : '';
            $string .='<input type="radio" name="'.$name.'" id="'.$name.'_'.$value.'" '.$checked.' value="'.$value.'" title="'.$value.'">';
        }
        $string .='</div>';
        return $string;
    }
    
    /**
     * 下拉选择框
     * @param $name name
     * @param $val 默认选中值 如：1
     * @param $array 一维数组 如：array('交易成功', '交易失败', '交易结果未知');
     * @param $default_option 提示词 如：请选择交易
     */
    public static function select($name, $val = 0, $array = array(), $default_option = '') {
        $string = '<select name="'.$name.'" id="'.$name.'" class="select">';
        if($default_option) $string .= "<option value=''>$default_option</option>";
        if(!is_array($array) || count($array)== 0) return false;
        $ids = array();
        if(isset($val)) $ids = explode(',', $val);
        foreach($array as $value) {
            $selected = in_array($value, $ids) ? 'selected' : '';
            $string .= '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
        }
        $string .= '</select>';
        return $string;
    }
    
    /**
     * 多图上传
     *
     * @param $name name
     * @param $val 默认值
     * @param $n 上传数量
     */
    public static function images($name, $val = '', $n = 20) {
        $string = '';
        $string .= '<fieldset class="layui-elem-field"><legend>图片列表</legend><div class="layui-field-box">您最多可以同时上传 <span style="color:red">'.$n.'</span> 个文件</div><ul id="'.$name.'" class="file_ul">';
        $string .='';
        $string .= '</ul>';
        $string .="</fieldset>";
        $string .="<a class=\"layui-btn\" href=\"javascript:;\" onclick=\"WeAdminShow('上传图片','".url("general.attachment/update_imgs")."','800','500')\">浏览文件</a>";
        $string .='';


        return $string;
    }
    
    
    /**
     * 复选框
     *
     * @param $name name
     * @param $val 默认选中值，多个用 '逗号'分割 如：'1,2'
     * @param $array 一维数组 如：array('交易成功', '交易失败', '交易结果未知');
     */
    public static function checkbox($name, $val = '', $array = []) {
        $string = '';
        $val = trim($val);
        if($val != '') $val = strpos($val, ',') ? explode(',', $val) : [$val];
        $i = 1;
        foreach($array as $value) {
            $value = trim($value);
            $checked = ($val && in_array($value, $val)) ? 'checked' : '';
            $string .= '<label class="layui-form-label" >';
            $string .= '<input type="checkbox" name="'.$name.'[]" id="'.$name.'_'.$i.'" '.$checked.' value="'.$value.'" title="'.$value.'">';
            $string .= '</label>';
            $i++;
        }
        return $string;
    }
    
    /**
     * 日期时间控件
     *
     * @param $name name
     * @param $val 默认值
     * @param $isdatetime 是否显示时分秒
     * @param $loadjs 是否重复加载js，防止页面程序加载不规则导致的控件无法显示
     * @param $attribute 外加属性
     */
    public static function datetime($name, $val = '', $isdatetime = 0, $loadjs = 0, $attribute = '') {
        $string = '';
        if($loadjs || !defined('DATETIME')) {
            define('DATETIME', 1);
            $string .= '<script type="text/javascript" src="/static/lib/laydate/laydate.js"></script>';
        }
        
        $string .= '<input class="layui-input-inline layui-input" value="'.$val.'" name="'.$name.'" id="'.$name.'" '.$attribute.'>';
        $string .= '<script type="text/javascript"> laydate.render({elem: "#'.$name.'",';
        if($isdatetime) $string .= 'istime: true,type: \'datetime\',format: "yyyy-m-d H:m:s",theme: \'#393D49\'';
        $string .= '});</script>';
        return $string;
    }
    
    /**
     * 编辑器
     *
     * @param $name name
     * @param $val 默认值
     * @param $style 样式
     * @param $isload 是否加载js,当该页面加载过编辑器js后，无需重复加载
     */
    public static function editor($name = 'content', $val = '', $style='', $isload=false) {
        $STATIC_LIB=DS."static".DS."lib".DS;
        $style = $style ? $style : 'width:100%;height:400px';
        $string = '';
        if($isload) {
            $string .= '<script type="text/javascript" charset="utf-8" src="'.$STATIC_LIB.'ueditor'.DS.'1.4.3.3'.DS.'ueditor.config.js"></script>
			<script type="text/javascript" charset="utf-8" src="'.$STATIC_LIB.'ueditor'.DS.'1.4.3.3'.DS.'ueditor.all.min.js"> </script>
			<script type="text/javascript" charset="utf-8" src="'.$STATIC_LIB.'ueditor'.DS.'1.4.3.3'.DS.'lang'.DS.'zh-cn'.DS.'zh-cn.js"></script>';
        }
        $string .= '<script id="'.$name.'" type="text/plain" style="'.$style.'" name="'.$name.'">'.$val.'</script>
			<script type="text/javascript"> var ue = UE.getEditor(\''.$name.'\',{serverUrl :\''.url("ueditor/index").'\'}); </script>';
    
        return $string;
    }
    
    
    /**
     * 编辑器-Mini版
     *
     * @param $name name
     * @param $val 默认值
     * @param $style 样式
     * @param $isload 是否加载js,当该页面加载过编辑器js后，无需重复加载
     */
    public static function editor_mini($name = 'content', $val = '', $style='', $isload=false) {
        $STATIC_LIB=DS."static".DS."lib".DS;
        $style = $style ? $style : 'width:100%;height:400px';
        $string = '';
        if($isload) {
            $string .= '<script type="text/javascript" charset="utf-8" src="'.$STATIC_LIB.'ueditor'.DS.'1.4.3.3'.DS.'ueditor.config.js"></script>
			<script type="text/javascript" charset="utf-8" src="'.$STATIC_LIB.'ueditor'.DS.'1.4.3.3'.DS.'ueditor.all.min.js"> </script>
			<script type="text/javascript" charset="utf-8" src="'.$STATIC_LIB.'ueditor'.DS.'1.4.3.3'.DS.'lang'.DS.'zh-cn'.DS.'zh-cn.js"></script>';
        }
        $string .= '<script id="'.$name.'" type="text/plain" style="'.$style.'" name="'.$name.'">'.$val.'</script>
			<script type="text/javascript"> var ue = UE.getEditor("'.$name.'",{
            toolbars:[[ "fullscreen","source","|","undo","redo","|",
            "bold","italic","underline","blockquote","forecolor","|","fontfamily","fontsize","|","simpleupload","link","unlink","emotion","date","time","drafts"]],
            //关闭字数统计
            wordCount:false,
            //关闭elementPath
            elementPathEnabled:false,
            //默认的编辑区域高度
            initialFrameHeight:300,
            serverUrl :\''.url("ueditor/index").'\'
        }); </script>';
        
        return $string;
    }
    
    
}