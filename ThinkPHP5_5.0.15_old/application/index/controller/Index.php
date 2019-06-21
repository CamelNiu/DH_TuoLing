<?php
namespace app\index\controller;
use think\Db;
use app\visit\controller\Write;
use app\traits\logger\LoggerTrits;
class Index extends Common
{
    public function index()
    {
        $list = [];
        $index_blog_list = $this->M_Blog->index_blog_list(15);
        list(,,$class_info) = $this->M_Common->get_class_info();
        $class_info = array_column($class_info,'class_title','class_id');
        $index_blog_list = array_map(function($v)use($class_info){$v['class_title'] = $class_info[$v['class_id']];return $v;},$index_blog_list);
        $right_list = $this->get_right_list();
        $list['index_blog_list'] = $index_blog_list;
        $list['right_list'] = $right_list;
        $this->assign("list",$list);
        return $this->fetch();
    }
}
