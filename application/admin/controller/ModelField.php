<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-03-29
 * Time: 9:04:07
 * Info: 模型字段管理
 */

namespace app\admin\controller;
use app\admin\library\LibAuth;
use think\Db;
use app\admin\library\Sql;

class ModelField extends Common
{
   
    public $childrenAdminIds;
    public $childrenGroupIds;
    public $modelid;
    public $modeltable;
    public $modelname;
    
    public function __construct()
    {
        parent::__construct();
        $LibAuth = new LibAuth();
        $this->childrenAdminIds= $LibAuth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $LibAuth->getChildrenGroupIds(true);
        $this->modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 1;
        $modeltable = Db::name('model')->where('modelid',input('modelid'))->find();
        $this->modeltable = $modeltable['tablename'];
        $this->modelname = $modeltable['name'];
    }
    
    //字段列表
    public function index()
    {
        $modelid = $this->modelid;
        $data = Db::name('model_field')->where("modelid=0 OR modelid=".$this->modelid)->order('listorder ASC,fieldid ASC')->select();
        $total = count($data);
        return $this->fetch('',['total'=>$total,'data'=>$data]);
    }
    
    //添加字段
    public function add()
    {
        if(input('dosubmit')){
            $param = input('post.');
            if(!preg_match('/^[a-zA-Z]{1}([a-zA-Z0-9]|[_]){0,19}$/', $param['field'])){
                return json(['status'=>0,'msg'=>'字段名不正确！']);
            }
            $files = ['input','textarea','number','datetime','image','images','attachment','select','radio','checkbox','editor', 'editor_mini'];
            if(!in_array($param['fieldtype'], $files)){
                return json(['status'=>0,'msg'=>'非法参数！']);
            }
            $param['issystem'] = 0;
            $param['listorder'] = 1;
            
            if(in_array($param['fieldtype'], ['select','radio','checkbox'])){
                $param['setting'] = array2string(explode('|', rtrim($param['setting'], '|')));
            }elseif($param['fieldtype']=='datetime'){
                $param['setting'] = $param['dateset'];
            }else{
                unset($param['setting']);
            }
            if($param['minlength']) $param['isrequired'] = 1;
            if($param['fieldtype'] == 'textarea' || $param['fieldtype'] == 'images'){
                Sql::sql_add_field_mediumtext($this->modeltable, $param['field']);
            }else if($param['fieldtype'] == 'editor' || $param['fieldtype'] == 'editor_mini'){
                Sql::sql_add_field_text($this->modeltable, $param['field']);
            }else if($param['fieldtype'] == 'number'){
                Sql::sql_add_field_int($this->modeltable, $param['field'], intval($param['defaultvalue']));
                $param['fieldtype'] = 'input';
            }else{
                Sql::sql_add_field($this->modeltable, $param['field'], $param['defaultvalue'], $param['maxlength']);
            }
            $a = Db::name('model_field')->data($param)->strict(false)->insert();
            cache($this->modelid.'_model',null);
            return json(['status'=>1,'msg'=>'添加成功~~~']);
        }else{
            $modelname = $this->modelname;
            return $this->fetch('',['modelname'=>$modelname]);
        }
    }
    
    //编辑字段
    public function edit()
    {
        if(!empty(input('post.dosubmit'))){
            $param = input('post.');
            if(in_array($param['fieldtype'], ['select','radio','checkbox'])){
                $param['setting'] = array2string(explode('|', rtrim($param['setting'], '|')));
            }elseif($param['fieldtype']=='datetime'){
                $param['setting'] = array2string([$param['dateset']]);
            }else{
                unset($param['setting']);
            }
            unset($param['issystem'], $param['modelid'], $param['fieldtype']);
            $param['isrequired'] = $param['minlength'] ? 1 :0;
            if(Db::name('model_field')->data($param)->where('fieldid',$param['fieldid'])->strict(false)->update()){
                cache($this->modelid.'_model',null);
                return json(['status'=>1,'msg'=>'修改成功~~~']);
            }else{
                return json(['status'=>0,'msg'=>'修改失败或者你没做任何修改！！！']);
            }
        } else{
            $data = Db::name('model_field')->where('fieldid',input('fieldid'))->find();
            $data['setting'] =!empty($data['setting'])?implode("|",string2array($data['setting'] )):'';
            $modelname = $this->modelname;
            return $this->fetch('',['modelname'=>$modelname,'data'=>$data]);
        }
    }
    
    //删除字段
    public function delete()
    {
        $fieldid = input('fieldid');
        $data = Db::name('model_field')->field('field,issystem')->where(['fieldid'=>$fieldid])->find();
        if(!$data['issystem']){
            Db::name('model_field')->where('fieldid',$fieldid)->delete();
            cache($this->modelid.'_model',null);
            Sql::sql_del_field($this->modeltable, $data['field']);
            return json(['status'=>1,'msg'=>'删除成功~']);
        }else{
            return json(['status'=>0,'msg'=>'不能删除系统字段！']);
        }
    }
    
    //排序字段
    public function order()
    {
        foreach (input('listorders') as $id => $listorder) {
            Db::name('model_field')->where(['fieldid' => $id])->data(['listorder' => $listorder])->update();
            cache($this->modelid.'_model',null);
        }
        $this->success('排序成功！');
    }
    
    /**
     * 检查字段
     */
    public function public_check_field() {
        if(empty(input('post.field'))){
            return json(0);
        }
        $fields = Db::name($this->modeltable)->getTableFields();
        if(!in_array(input('post.field'), $fields)){
            return json(1);
        }else{
            return json(0);
        }
    }
    
}