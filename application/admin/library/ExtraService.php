<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/4 0004
 * Time: 16:58
 */

namespace app\admin\library;


class ExtraService
{
    /*
     * 初始化数据备份
     */
    public static function backupConfig(){
        $filearr['file_name']="backup";
        $filearr['file_content']=array();
        self::writeConfig($filearr);
    }
    
    /*
    *生成php 配置文件
    *@param array $filearr['file_name']文件名称 $filearr['file_content'] key=>$val 数组类型
    *@param bool $mode 1 追加 0 重新生成
    */
    public static function writeConfig($filearr)
    {
        $filename = $filearr['file_name'] . '.php';
        $content = "<?php\n";
        $content .= "return " . var_export($filearr['file_content'], true) . ";\n?>";
        $filepath = \Env::get('config_path'). $filename;
        @file_put_contents($filepath, $content); //配置文件的地址
    }
    
}