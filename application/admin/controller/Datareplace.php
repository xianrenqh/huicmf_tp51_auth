<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-31
 * Time: 14:55:11
 * Info:
 */

namespace app\admin\controller;
use app\admin\library\LibAuth;
use think\Db;

class Datareplace extends Common
{
    public $childrenAdminIds;
    public $childrenGroupIds;
    
    public function __construct()
    {
        parent::__construct();
        $LibAuth = new LibAuth();
        $this->childrenAdminIds= $LibAuth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $LibAuth->getChildrenGroupIds(true);
    }
    
    public function index()
    {
        if(input('action') && input('action')=="getfields"){
            $exptable = input('exptable');
            $tableName = Db::query("select COLUMN_NAME from information_schema.COLUMNS where table_name = '$exptable' and TABLE_SCHEMA='".config('database.database')."'");
            $tableNames='';
            foreach($tableName as $v){
                $tableNames .='<a href=\'javascript:pf("'.$v['COLUMN_NAME'].'")\'><u>'.$v['COLUMN_NAME'].'</u></a>' ;
            }
            return json(['names'=>$tableNames]);
        }else{
            $exptable_list = Db::query("SHOW TABLE STATUS");
            if($exptable_list==''){
                echo "<font>找不到你所指定的数据库！ </font><br>";
            }
            return $this->fetch('',['exptable_list'=>$exptable_list]);
        }
    }
    
    //执行sql
    public function dosql()
    {
        $param = input('post.');
        if( !captcha_check(input('code') )){
            return json(['status'=>1001,'msg'=>'验证码输出错误，请重新输入！！！']);
        }
        $condition = empty($condition) ? '' : " WHERE $condition ";
        $exptable = $param['exptable'];
        $rpfield = $param['rpfield'];
        $rpstring = $param['rpstring'];
        $tostring = $param['tostring'];
        $rs1 = Db::query(" UPDATE `$exptable` SET $rpfield=REPLACE($rpfield,'$rpstring','$tostring') ");
        $rs3= Db::query("OPTIMIZE TABLE `$exptable`");
        if($rs1!=''){
            return json(['status'=>200,'message'=>'成功完成数据替换！','icon'=>1]);
        }else{
            return json(['status'=>201,'message'=>'数据替换失败！','icon'=>2]);
        }
    }
    
}