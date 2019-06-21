<?php
namespace app\index\controller;
use think\Db;
use app\index\controller\Index;
use app\visit\controller\Write;
class Content extends Common
{
    public function index()
    {
        $list = [];
        $blog_id = input('get.id');
        list(,,$list['content_info']) = $this->M_Content->get_content_info($blog_id);
        $list['right_list'] = $this->get_right_list();
        $this->assign("list",$list);
        return $this->fetch();
    }

}
