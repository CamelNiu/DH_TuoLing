<?php
namespace app\index\model;
use think\Model;
use think\Db;
use app\index\model\CommonModel;
class BlogModel extends CommonModel
{
    public function index_blog_list($limit = 15)
    {
        $blog_info_list = $this->get_blog_latest_publish($limit);
        return $blog_info_list;
    }
    public function blog_list()
    {
        list(,,$blog_list) = $this->get_blog_info();
        array_multisort(array_column($blog_list,'bloginfo_createtime'),SORT_DESC,$blog_list);
        list(,,$class_list) = $this->get_class_info();
        $blog_list = array_map(function($v)use($class_list){$v['class_title'] = $class_list[$v['class_id']]['class_title'];return $v;},$blog_list);
        return $blog_list;
    }
    public function blog_list_index_class($class_id)
    {
        list(,,$class_list) = $this->get_class_info();
        $class_list_cur = $class_list[$class_id];
        list(,,$blog_list) = $this->get_blog_info();
        $blog_list_cur = array_filter($blog_list,function($v)use($class_id){
            return ($v['class_id'] == $class_id)?true:false;
        });
        $blog_list_cur = array_map(function($v)use($class_list_cur){
            $v['class_title'] = $class_list_cur['class_title'];
            return $v;
        },$blog_list_cur);
        array_multisort(array_column($blog_list_cur,'bloginfo_createtime'),SORT_DESC,$blog_list_cur);
        return $blog_list_cur;
    }
    public function blog_list_index_label($label_id)
    {
        list(,,$blog_label_list) = $this->get_blog_label_info();
        $blog_id_list = $blog_label_list['label_blog_key'][$label_id]['bloginfo_id'];
        list(,,$blog_list) = $this->get_blog_info();
        $blog_list = array_map(function($v)use($blog_list){
            $v = $blog_list[$v];
            return $v;
        },$blog_id_list);
        list(,,$class_list) = $this->get_class_info();
        $blog_list = array_map(function($v)use($class_list){$v['class_title'] = $class_list[$v['class_id']]['class_title'];return $v;},$blog_list);
        return $blog_list;
    }
}