<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020/1/3 0003
 * Time: 9:55
 */

namespace app\admin\controller\general;
use app\admin\controller\Common;
use app\admin\library\Backup;
use think\Db;
use think\facade\Debug;
use think\Exception;
use think\exception\PDOException;
use ZipArchive;
use database\Backup as LibBackup;

class Database extends Common
{
    protected $noNeedRight = ['backuplist'];
    
    public function index()
    {
        $list = Db::query("SHOW TABLE STATUS");
        for($i=0;$i<count($list);$i++){
            $list[$i]['Data_length']=sizecount($list[$i]['Data_length']);
        }
        if(input('do')) {
            $return = ["code"    => 0, 'msg' => '获取成功', 'count' => count($list), 'data' => $list];
            return json($return);
        }else{
            return $this->fetch('',['data'=>$list]);
        }
    }
    
    /**
     * SQL查询
     */
    public function query()
    {
        $do_action = input('do_action');
        
        echo '<style type="text/css">
            xmp,body{margin:0;padding:0;line-height:18px;font-size:12px;font-family:"Helvetica Neue", Helvetica, Microsoft Yahei, Hiragino Sans GB, WenQuanYi Micro Hei, sans-serif;}
            hr{height:1px;margin:5px 1px;background:#e3e3e3;border:none;}
            </style>';
        if ($do_action == '') {
            exit(__('Invalid parameters'));
        }
        
        $tablename = $this->request->post("tablename/a");
        
        if (in_array($do_action, array('doquery', 'optimizeall', 'repairall'))) {
            $this->$do_action();
        } elseif (count($tablename) == 0) {
            exit(__('Invalid parameters'));
        } else {
            foreach ($tablename as $table) {
                $this->$do_action($table);
                echo "<br />";
            }
        }
    }
    
    /**
     * 备份数据
     */
    /**
     * 备份
     */
    public function backup()
    {
        $DataBase = config('database.');
        $BackUp = config('huiadmin.');
        $backupDir = ROOT_PATH ."public".DS. $BackUp['backupDir'];
        if(input('post.dosubmit')){
            try {
                $backup = new Backup($DataBase['hostname'], $DataBase['username'], $DataBase['database'], $DataBase['password'], $DataBase['hostport']);
                $backup->setIgnoreTable($BackUp['backupIgnoreTables'])->backup($backupDir);
            }catch (Exception $e){
                return json(['message'=>$e->getMessage(),'status'=>0]);
            }
            return json(['message'=>'备份成功','status'=>1]);
        }
        return;
    }
    
    /**
     * 数据还原
     *
     */
    public function restore()
    {
        $BackUp = config('huiadmin.');
        $backupDir = ROOT_PATH ."public".DS. $BackUp['backupDir'];
        $file = input('file');
        if($file){
            /*if (!preg_match("/^backup\-([a-z0-9_\-]+)\.zip$/i", $file)) {
                $this->error('未知参数');
            }*/
            try {
                $dir = RUNTIME_PATH . 'database' . DS;
                if (!is_dir($dir)) {
                    mkdir($dir, 0755);
                }
                $file = $backupDir.$file;
                if (class_exists('ZipArchive')) {
                    $zip = new ZipArchive;
                    if ($zip->open($file) !== true) {
                        throw new Exception('无法打开备份文件');
                    }
                    if (!$zip->extractTo($dir)) {
                        $zip->close();
                        throw new Exception('无法解压备份文件');
                    }
                    $zip->close();
                    $filename = basename($file);
                    $sqlFile = $dir . str_replace('.zip', '.sql', $filename);
                    if (!is_file($sqlFile)) {
                        throw new Exception('未找到SQL文件');
                    }
                    $filesize = filesize($sqlFile);
                    $list = Db::query('SELECT @@global.max_allowed_packet');
                    if (isset($list[0]['@@global.max_allowed_packet']) && $filesize >= $list[0]['@@global.max_allowed_packet']) {
                        Db::execute('SET @@global.max_allowed_packet = ' . ($filesize + 1024));
                        //throw new Exception('备份文件超过配置max_allowed_packet大小，请修改Mysql服务器配置');
                    }
                    $sql = file_get_contents($sqlFile);
                    if(preg_match('/.*;$/', trim($sql))){
                        try {
                            $sqlArr = array_filter(explode(";\n\n",trim($sql)));
                            foreach($sqlArr as $k=>$v){
                                $res = Db::execute($v);
                            }
                            $this->success('还原成功！！！');
                        }catch (Exception $e){
                            $this->error($e->getMessage());
                        }
                    } else{
                        return 0;
                    }
                    
                }
                
            }catch (Exception $e) {
                $this->error($e->getMessage());
            }catch (PDOException $e) {
                $this->error($e->getMessage());
            }
        }else{
            $this->error('错错错！！！');
        }
    }
    
    //数据类连接
    public static function connect()
    {
        return Db::connect();
    }
    
    /**
     * 备份列表
     */
    public function databack_list()
    {
        $BackUp = config('huiadmin.');
        $backupDir = ROOT_PATH ."public".DS. $BackUp['backupDir'];
        $backuplist = [];
        foreach (glob($backupDir . "*.zip") as $filename) {
            $time = filemtime($filename);
            $backuplist[$time] = [
                    'file' => str_replace($backupDir, '', $filename),
                    'date' => date("Y-m-d H:i:s", $time),
                    'size' => sizecount(filesize($filename))
                ];
            
        }
        krsort($backuplist);
        return $this->fetch('',['data'=>$backuplist]);
    }
    
    /**
     * 备份文件删除
     */
    public function delete()
    {
        $BackUp = config('huiadmin.');
        $backupDir = ROOT_PATH ."public".DS. $BackUp['backupDir'];
        $file =$backupDir.input('file');
        if(file_exists($file)){
            unlink($file);
            return json(['msg'=>'删除成功','status'=>1]);
        }else{
            return json(['msg'=>'删除失败','status'=>0]);
        }
    }
    
    /**
     * 下载备份文件
     */
    public function download()
    {
        $BackUp = config('huiadmin.');
        $backupDir = ROOT_PATH ."public".DS. $BackUp['backupDir'];
        $file =input('file');
        if(file_exists($backupDir.$file)){
            file_down($backupDir.$file,$file);
        }
    }
    
    public function viewinfo()
    {
        $name = input('table');
        $row = Db::query("SHOW CREATE TABLE `{$name}`");
        $row = array_values($row[0]);
        $info = $row[1];
        echo "<style>pre {display: block;font-family: Monaco,Menlo,Consolas,\"Courier New\",monospace;padding: 9.5px;margin-bottom: 10px;font-size: 12px;line-height: 20px;word-break: break-all;word-wrap: break-word;white-space: pre;white-space: pre-wrap;background-color: #f5f5f5;border: 1px solid #ccc;border-radius: 4px;color: #333;}</style>";
        echo "<pre>{$info};</pre>";
    }
    
    public function viewdata()
    {
        $name = input('table');
        $row = Db::query("SHOW CREATE TABLE `{$name}`");
        $sqlquery = "SELECT * FROM `{$name}`";
        $this->doquery($sqlquery);
    }
    
    /**
     * 优化表
     */
    public function optimize()
    {
        $name = input('table');
        if(is_array($name)){
            foreach ($name as $key => $row) {
                Db::execute("OPTIMIZE TABLE `{$row}`");
            }
            $return = ['message'=>'全部优化成功','icon'=>1];
        }else{
            if (Db::execute("OPTIMIZE TABLE `{$name}`")) {
                $return = ['message'=>'优化表 ['.$name.'] 成功','icon'=>1];
            } else {
                $return =['message'=>'优化表 ['.$name.'] 失败','icon'=>2];
            }
        }
        
        return json($return);
    }
    
    /**
     * 修复表
     */
    public function repair()
    {
        $name = input('table');
        if(is_array($name)){
            foreach ($name as $key => $row) {
                Db::execute("REPAIR TABLE `{$row}`");
            }
            $return = ['message'=>'全部修复成功','icon'=>1];
        }else{
            if (Db::execute("REPAIR TABLE `{$name}`")) {
                $return = ['message'=>'修复表 ['.$name.'] 成功','icon'=>1];
            } else {
                $return = ['message'=>'修复表 ['.$name.'] 成功','icon'=>1];
            }
        }
        
        return json($return);
    }
    
    private function doquery($sql = null)
    {
        $sqlquery = $sql ? $sql : $this->request->post('sqlquery');
        if ($sqlquery == '') {
            exit(__('SQL can not be empty'));
        }
        $sqlquery = str_replace("\r", "", $sqlquery);
        $sqls = preg_split("/;[ \t]{0,}\n/i", $sqlquery);
        $maxreturn = 100;
        $r = '';
        foreach ($sqls as $key => $val) {
            if (trim($val) == '') {
                continue;
            }
            $val = rtrim($val, ';');
            $r .= "SQL：<span style='color:green;'>{$val}</span> ";
            if (preg_match("/^(select|explain)(.*)/i ", $val)) {
                Debug::remark("begin");
                $limit = stripos(strtolower($val), "limit") !== false ? true : false;
                $count = Db::execute($val);
                if ($count > 0) {
                    $resultlist = Db::query($val . (!$limit && $count > $maxreturn ? ' LIMIT ' . $maxreturn : ''));
                } else {
                    $resultlist = [];
                }
                Debug::remark("end");
                $time = Debug::getRangeTime('begin', 'end', 4);
                
                $usedseconds =  "用时 ".$time." 秒<br />";
                if ($count <= 0) {
                    $r .= '返回结果为空';
                } else {
                    $r .=('共有'.$count.'条记录!').(!$limit && $count > $maxreturn ?',最大返回'.$maxreturn.'条':'');
                    //$r .= (__('Total:%s', $count) . (!$limit && $count > $maxreturn ? ',' . ""__('Max output:%s', $maxreturn) : ""));
                }
                $r = $r . ',' . $usedseconds;
                $j = 0;
                foreach ($resultlist as $m => $n) {
                    $j++;
                    if (!$limit && $j > $maxreturn) {
                        break;
                    }
                    $r .= "<hr/>";
                    $r .= "<font color='red'> 记录" . $j . "</font><br />";
                    foreach ($n as $k => $v) {
                        $r .= "<font color='blue'>{$k}：</font>{$v}<br/>\r\n";
                    }
                }
            } else {
                Debug::remark("begin");
                $count = Db::execute($val);
                Debug::remark("end");
                $time = Debug::getRangeTime('begin', 'end', 4);
                $r .= __('Query affected %s rows and took %s seconds', $count, $time) . "<br />";
            }
        }
        echo $r;
    }
    
    
    
    /***************************************************************************************
     *  这是第二种备份还原方案，引用了 extend/database里面的数据库操作类
     */
    public function test000000000()
    {
        $Data = new LibBackup();
        $list = $Data->systemExport();  //备份
        $list = $Data->import('1578137315');    //还原
        dump($list);
    }
    
}