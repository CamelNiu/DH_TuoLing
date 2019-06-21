<?php
namespace app\index\controller;
use app\index\model\CommonModel;
use app\index\model\BlogModel;
use think\Db;
use app\index\controller\Index;
use page\Pagination;
use app\visit\controller\Write;
class Blog extends Common
{
    public function index()
    {
        $list = [];
        $list['blog_list'] = $this->M_Blog->blog_list();
        $list['right_list'] = $this->get_right_list();
        list(,,$list['label_list']) = $this->M_Common->get_label_info();
        $list['class_list'] = $this->M_Class->class_list(2);
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function index_class()
    {
        $list = [];
        $class_id = input('get.class_id');
        $list['blog_list'] = $this->M_Blog->blog_list_index_class($class_id);
        $list['class_list'] = $this->M_Class->class_list(2);
        $list['label_list'] = $this->M_Label->get_label_list_by_class($class_id);
        $list['right_list'] = $this->get_right_list();
        $this->assign("list",$list);
        return $this->fetch('index');
    }
    public function index_label()
    {
        $list = [];
        $label_id = input('get.label_id');
        $list['blog_list'] = $this->M_Blog->blog_list_index_label($label_id);
        $list['class_list'] = $this->M_Class->get_class_list_by_label($label_id);
        list(,,$list['label_list']) = $this->M_Common->get_label_info();
        $list['right_list'] = $this->get_right_list();
        $this->assign("list",$list);
        return $this->fetch('index');
    }
}
