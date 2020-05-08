<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-04-14
 * Time: 13:31:24
 * Info:
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use lib\Form;

class ContentForm extends Controller
{

    public $modelid;

    function __construct($modelid)
    {
        $this->modelid = $modelid;
    }

    public function content_add()
    {
        $modelinfo = $this->get_modelinfo();
        $string    = cache($this->modelid.'_model_string');
        if ($string === false) {
            $string = '';
            foreach ($modelinfo as $val) {
                $fieldtype = $val['fieldtype'];
                if ($fieldtype == 'input' || $fieldtype == 'number') {
                    $errortips = ! empty($val['errortips']) ? $val['errortips'] : '必填项不能为空';
                    $required  = $val['isrequired'] ? ' required" errortips="'.$errortips : '';
                    $string    .= $this->tag_start($val['name'],
                            $val['isrequired']).'<input type="text" value="'.$val['defaultvalue'].'" name="'.$val['field'].'" autocomplete="off" class="layui-input'.$required.'" placeholder="'.$val['tips'].'">'.$this->tag_end();
                } elseif ($fieldtype == 'textarea') {
                    $errortips = ! empty($val['errortips']) ? $val['errortips'] : '必填项不能为空';
                    $required  = $val['isrequired'] ? ' required" errortips="'.$errortips : '';
                    $string    .= $this->tag_start($val['name'],
                            $val['isrequired']).'<textarea name="'.$val['field'].'" class="layui-textarea'.$required.'" placeholder="'.$val['tips'].'" onKeyUp="textarealength(this,'.$val['maxlength'].')">'.$val['defaultvalue'].'</textarea><p class="textarea-numberbar"><em class="textarea-length">0</em>/'.$val['maxlength'].'</p>'.$this->tag_end();
                } elseif ($fieldtype == 'select') {
                    $string .= $this->tag_start($val['name']).'<span class="select-box">'.Form::select($val['field'],
                            $val['defaultvalue'], string2array($val['setting'])).'</span>'.$this->tag_end();
                } elseif ($fieldtype == 'radio' || $fieldtype == 'checkbox') {
                    $string .= $this->tag_start($val['name']).Form::$fieldtype($val['field'], $val['defaultvalue'],
                            string2array($val['setting'])).$this->tag_end();
                } elseif ($fieldtype == 'datetime') {
                    $string .= $this->tag_start($val['name']).Form::datetime($val['field'], '',
                            string2array($val['setting'])[0]).$this->tag_end();
                } else {
                    $string .= $this->tag_start($val['name']).Form::$fieldtype($val['field']).$this->tag_end();
                }
            }
            //cache($this->modelid.'_model_string', $string);
        }

        return ($string);
    }

    public function tag_start($tip, $tips_name = '0')
    {
        $tips_span = $tips_name ? '<span class="we-red">*</span>' : '';

        return '<div class="layui-form-item"><label class="layui-form-label">'.$tips_span.$tip.'：</label><div class="layui-input-block">';
    }

    public function tag_end()
    {
        return '</div></div>';
    }

    public function get_modelinfo()
    {
        $modelinfo = cache($this->modelid.'_model');
        if ($modelinfo === false) {
            if ( ! Db::name('model')->where(['modelid' => $this->modelid])->find()) {
                showmsg('模型不存在！');
            }
            $modelinfo = Db::name('model_field')->where(['modelid'  => $this->modelid,
                                                         'disabled' => 0
            ])->order('listorder ASC')->select();
            cache($this->modelid.'_model', $modelinfo);
            cache($this->modelid.'_model_string', null);
        }

        return $modelinfo;
    }

}
