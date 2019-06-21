<?php
namespace app\index\controller;
use think\Db;
class Content extends Common
{
    public function index()
    {
        $bloginfo_id = input("get.bloginfo_id");
        $res = preg_match("/\d/",$bloginfo_id);
        if(!$res) die('URL错误');
        $blogcontent_sql = "SELECT
                                nbi.bloginfo_id as blog_id,bloginfo_title,bloginfo_describe,FROM_UNIXTIME(bloginfo_createtime,'%Y-%m-%d') as bloginfo_createtime,bloginfo_like,bloginfo_hate,bloginfo_click,GROUP_CONCAT(nbl.bloglink_url) as bloglink_url,nbc.blogcontent_ctt
                            FROM 
                                ns_bloginfo as nbi
                            LEFT JOIN 
                                ns_blogcontent as nbc
                            ON nbi.bloginfo_id = nbc.bloginfo_id
                            LEFT JOIN 
                                ns_blog_link as nbl 
                            ON nbi.bloginfo_id = nbl.bloginfo_id
                            WHERE nbi.bloginfo_id = ".$bloginfo_id."
                            ";
        $blogcontent_info = Db::query($blogcontent_sql);
        $blogcontent_info = $blogcontent_info[0];
        $blogcontent_info['bloglink_url'] = explode(',',$blogcontent_info['bloglink_url']);
        $this->assign("blogcontent_info",$blogcontent_info);
        return view();
    }
    public function classList()
    { 
        
    }
}
