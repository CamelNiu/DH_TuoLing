<?php
namespace app\index\controller;
use app\index\model\BlogModel;
use app\index\controller\Common;
use app\tools\controller\Encrypt;
class Index extends Common
{
    public function index()
    {
        $list = [];
        $list['index_blog_list'] = $this->get_index_blog_list();
        $list['right_list'] = $this->get_right_list();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function get_index_blog_list()
    {
        $M_blog = new BlogModel;
        $index_blog_list = $M_blog->get_index_blog_list();
        $index_blog_list = array_map(function($v){
            $v['created_at'] = date('Y-m-d H:i:s',$v['bloginfo_createtime']);unset($v['bloginfo_createtime']);
            $v['param'] = Encrypt::encryption(['blog_id' => $v['bloginfo_id']]);
            return $v;
        },$index_blog_list);
        return $index_blog_list;
    }
}
