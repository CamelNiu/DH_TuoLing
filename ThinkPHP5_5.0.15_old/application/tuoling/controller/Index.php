<?php
namespace app\index\controller;
use think\Db;
class Index extends Common
{
    public function index()
    {
        //首页图片展示
        $homepage_list = $this->homepage();
        //首页博客展示,只显示技术博客
        $blog_list = $this->introBlogs();
        $this->assign('blog_list',$blog_list);
        $this->assign("homepage_list",$homepage_list);
        return view();
    }
    //首页三章图显示
    public function homepage()
    { 
        $sql = "select homepage_id,homepage_title,homepage_url,homepage_image,homepage_text from ns_homepage where homepage_status = 1 order by homepage_oid desc limit 3";
        $res = Db::query($sql);
        return $res;
    }
    //首页推荐博客显示
    public function introBlogs($id = 2)
    { 
        $sql = "SELECT bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,from_unixtime(bloginfo_createtime,'%Y') AS year,from_unixtime(bloginfo_createtime,'%m-%d') AS date,from_unixtime(bloginfo_createtime,'%Y-%m-%d') AS bloginfo_createtime 
                FROM ns_bloginfo 
                WHERE bloginfo_status = 1 and class_id in (SELECT 
                                                            class_id 
                                                            FROM ns_class 
                                                            WHERE class_fid={$id})
                ORDER BY bloginfo_click DESC LIMIT 10";
        $res = Db::query($sql);
        return $res;
    }
    public function click_num()
    { 
        $BlogObj = new \app\index\controller\Blog;
        $BlogObj->click_num();
    }
}
