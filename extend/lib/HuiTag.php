<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2020-01-17
 * Time: 15:33:22
 * Info:
 */

namespace lib;
use think\Db;

class HuiTag
{
    
    
    /**
     * 分页显示
     * @param $string
     */
    public function pages() {
        /*if(!is_object($this->page)) return '';
        //当前页：$this->page->getpage();
        return '<span class="pageinfo">共<strong>'.$this->page->total().'</strong>页<strong>'.$this->total.'</strong>条记录</span>'.$this->page->getfull();*/
    }
    
    /**
     * 友情链接标签
     * @param $data
     */
    public function link($data) {
        $field = isset($data['field']) ? $data['field'] : '*';
        $where = 'status = 1';
        $order = isset($data['order']) ? $data['order'] : 'listorder ASC, id DESC';
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        if(isset($data['where'])){
            $where = $data['where'];
        }else{
            $where .= isset($data['thumb']) ? " AND logo != ''" : '';
        }
        return Db::name('link')->where($where)->order($order)->limit($limit)->select();
    }
    
    
    /**
     * 自定义SQL标签
     * @param $data
     */
    public function get($data) {
        if(!isset($data['sql'])) return false;
        $sql = $data['sql'];
        $limit = isset($data['limit']) ? $data['limit'] : '20';
        if(isset($data['page'])){
        
        }
        $sql = $sql.' LIMIT '.$limit;
        return Db::query($sql);
    }
    
}