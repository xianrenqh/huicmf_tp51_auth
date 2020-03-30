<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-28
 * Time: 15:10:41
 * Info: 站点模型管理
 */

namespace app\admin\controller;
use app\admin\library\LibAuth;
use app\admin\library\Sql;
use think\Db;
use think\facade\Cache;

class Sitemodel extends Common
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
        $list = Db::name('model')->paginate(100);
        $total = Db::name('model')->count();
        return $this->fetch('',['data'=>$list,'total'=>$total]);
    }
    
    public function add()
    {
        if(input('post.dosubmit')){
            if(empty(input('post.name'))) return json(['status'=>0,'msg'=>'模型名称不能为空！']);
            if(empty(input('post.tablename'))) return json(['status'=>0,'msg'=>'表名称不能为空！']);
            $model = Db::name('model');
            if($this->table_exists(config('database.prefix').input('post.tablename'))) return json(['status'=>0,'msg'=>'表名已存在！']);
            $param = input('post.');
            $param['issystem'] = $_POST['type'] = 0;
            $param['inputtime'] = time();
            $model->strict(false)->data($param)->insert();
            Sql::sql_create_siteModel(input('post.tablename'));
            Cache::rm('modelinfo');
            return json(['status'=>1,'msg'=>'添加成功~~~']);
        }else{
            return $this->fetch();
        }
    }
    
    public function edit()
    {
        if(input('post.dosubmit')){
            $param = input('post.');
            $param['inputtime'] = time();
            $res = Db::name('model')->data($param)->strict(false)->where('modelid',$param['modelid'])->update();
            if($res){
                Cache::rm('modelinfo');
                return json(['status'=>1,'msg'=>'操作成功~']);
            }else{
                return json(['status'=>0,'msg'=>'操作失败！']);
            }
        }else{
            $modelid = input('get.modelid')?input('get.modelid'):0;
            $data = Db::name('model')->where('modelid',$modelid)->find();
            return $this->fetch('',['data'=>$data]);
        }
    }
    
    public function delete()
    {
        $modelid = input('modelid');
        if(in_array($modelid, [1,2,3])) $this->error('不能删除系统模型！');
        $model = Db::name('model');
        $r = $model->field('tablename')->where(['modelid'=>$modelid])->find();
        if($r) Sql::sql_delete($r['tablename']);
        $model->where('modelid',$modelid)->delete(); //删除model信息
        Db::name('model_field')->where('modelid',$modelid)->delete(); //删除字段
        Cache::rm('modelinfo');
        return json(['status'=>1,'msg'=>'删除成功~~~']);
    }
    
    
    //导出模型
    public function export()
    {
        $modelid = input('modelid')?input('modelid'):0;
        $data = Db::name('model')->where('modelid',$modelid)->find();
        if(!$data) showmsg('参数错误！', 'stop');
        $arr['model_data'] = $data;
        $field_data = Db::name('model_field')->where('modelid',$modelid)->select();
        $arr['model_field_data'] = $field_data;
        $res = array2string($arr);
        return download($res, $data['tablename']."."."model", true);
    }
    
    //导入模型
    public function import()
    {
        if(input('file.Filedata')){
            $data_import = (input('file.Filedata')->getInfo());
            if(empty($data_import['name'])) return json(['status'=>0,'msg'=>'请上传文件！']);
            if(fileext($data_import['name']) != 'model') return json(['status'=>0,'msg'=>'上传文件类型错误！']);
            $data_import_tmp = @file_get_contents($data_import['tmp_name']);
            if(empty($data_import_tmp)) return json(['status'=>0,'msg'=>'上传文件数据为空！']);
            $model_import_data = string2array($data_import_tmp);
            if(!is_array($model_import_data))  return json(['status'=>0,'message'=>'解析文件数据错误！']);
            $model_data = $model_import_data['model_data'];
            
            $modelid = Db::name('model')->where(['tablename'=>$model_data['tablename']])->value('modelid');
            $model_arr =[];
            $model_arr['name'] = htmlspecialchars($model_data['name']);
            $model_arr['description'] = htmlspecialchars($model_data['description']);
            $model_arr['setting'] = $model_data['setting'];
            $model_arr['inputtime'] = intval($model_data['inputtime']);
            $model_arr['disabled'] = intval($model_data['disabled']);
            $model_arr['type'] = intval($model_data['type']);
            $model_arr['sort'] = intval($model_data['sort']);
            $model_arr['issystem'] = intval($model_data['issystem']);
      
            //更新模型
            if($modelid){
                Db::name('model')->where('modelid',$modelid)->data($model_arr)->update();
            }else{
                $model_arr['tablename'] = htmlspecialchars($model_data['tablename']);
                Sql::sql_create_siteModel($model_data['tablename']);
                $modelid = Db::name('model')->data($model_arr)->insert();
            }
            
            //更新模型字段
            $model_field_data = $model_import_data['model_field_data'];
            foreach($model_field_data as $val){
                $fieldid = Db::name('model_field')->where(['modelid' => $modelid, 'field' => $val['field']])->value('fieldid');
                $arr = [];
                $arr['modelid'] = $modelid;
                $arr['name'] = htmlspecialchars($val['name']);
                $arr['tips'] = htmlspecialchars($val['tips']);
                $arr['minlength'] = intval($val['minlength']);
                $arr['maxlength'] = intval($val['maxlength']);
                $arr['errortips'] = htmlspecialchars($val['errortips']);
                $arr['fieldtype'] = htmlspecialchars($val['fieldtype']);
                $arr['defaultvalue'] = htmlspecialchars($val['defaultvalue']);
                $arr['setting'] = $val['setting'];
                $arr['isrequired'] = intval($val['isrequired']);
                $arr['issystem'] = intval($val['issystem']);
                $arr['isunique'] = intval($val['isunique']);
                $arr['isadd'] = intval($val['isadd']);
                $arr['listorder'] = intval($val['listorder']);
                $arr['disabled'] = intval($val['disabled']);
                $arr['type'] = intval($val['type']);
                $arr['status'] = intval($val['status']);
                if($fieldid){
                    Db::name('model_field')->data($arr)->where('fieldid',$fieldid)->update();
                }else{
                    $arr['field'] = htmlspecialchars($val['field']);
                    Db::name('model_field')->data($arr)->insert();
                    $this->_add_field($arr, $model_data['tablename']);
                }
            }
            Cache::rm('modelinfo');
            return json(['code'=>0,'status'=>1,'msg'=>'导入成功！']);
        }else{
            return $this->fetch();
        }
    }
    
    /**
     * 检查表是否存在
     * @param $table 表名
     * @return boolean
     */
    final public function table_exists($table){
        $isTable=db()->query('SHOW TABLES LIKE '."'".$table."'");
        return $isTable;
    }
    
    //添加模型字段
    private function _add_field($data, $table){
        if($data['fieldtype'] == 'textarea' || $data['fieldtype'] == 'images'){
            Sql::sql_add_field_mediumtext($table, $data['field']);
        }else if($data['fieldtype'] == 'editor' || $data['fieldtype'] == 'editor_mini'){
            Sql::sql_add_field_text($table, $data['field']);
        }else if($data['fieldtype'] == 'number'){
            Sql::sql_add_field_int($table, $data['field'], intval($data['defaultvalue']));
            $data['fieldtype'] = 'input';
        }else{
            Sql::sql_add_field($table, $data['field'], $data['defaultvalue'], $data['maxlength']);
        }
    }
    
    
}