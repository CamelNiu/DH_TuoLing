<?php
namespace app\index\controller;
use think\Db;
use think\Cache;
class Blog extends Common
{
    //=================================首页展示=====================================
    //大分类下的首页显示
    public function index()
    {
        $class_id = input('get.class_id');
        $blog_class_soninfo = $this->selNextClass($class_id);
        $blog_list = $this->IndexBlogList($class_id);
        //查询博客内容
        $this->assign('blog_class_soninfo',$blog_class_soninfo);
        $this->assign('blog_list',$blog_list);
        return $this->fetch();
    }
    //根据大分类查询二级分类的信息
    public function selNextClass($id)
    { 
        $sql = "SELECT 
                    class_id,class_title
                FROM ns_class 
                WHERE class_fid=".$id."
                ORDER BY class_oid,class_id desc";
        $res = Db::query($sql);
        return $res;
    }
    //查询当前分类下二级分类的id,便于遍历本大分类下的内容
    public function selNextClassId($id)
    { 
        $sql = "SELECT 
                    class_id  
                FROM ns_class 
                WHERE class_fid = ".$id;
        $res = Db::query($sql);
        return $res;
    }
    //查询首页需要的博客数据列表,利用了union all 优化
    public function IndexBlogList($id)
    { 
        /* 
            bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,from_unixtime(bloginfo_createtime,'%Y') AS year,from_unixtime(bloginfo_createtime,'%m-%d') AS date,from_unixtime(bloginfo_createtime,'%Y-%m-%d') AS bloginfo_createtime,bloginfo_like,bloginfo_hate,bloginfo_click
        */
        $next_classid = $this->selNextClassId($id);
        $prefix = "SELECT 
                        bloginfo_id,bloginfo_click
                    FROM ns_bloginfo 
                    WHERE bloginfo_status = 1 and class_id = ";
        $nextfix = " ORDER BY bloginfo_click DESC";
        $sql = "";
        foreach($next_classid as $val){ 
            $sql .= $prefix.$val['class_id']." UNION ALL ";
        }
        $sql = trim($sql,'UNION ALL').$nextfix." limit 10";
        $res = Db::query($sql);
        $list = array();
        $list_sql ="";
        $list_prefix = "SELECT 
                        bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,from_unixtime(bloginfo_createtime,'%Y') AS year,from_unixtime(bloginfo_createtime,'%m-%d') AS `date`,from_unixtime(bloginfo_createtime,'%Y-%m-%d') AS bloginfo_createtime,bloginfo_like,bloginfo_hate,bloginfo_click
                    FROM ns_bloginfo WHERE bloginfo_id = ";
        foreach($res as $val){ 
            $list_sql .= $list_prefix.$val['bloginfo_id']." union all ";
        }
        $list_sql = trim($list_sql,'union all');
        $list = Db::query($list_sql);
        return $list;
    }
    //===================================首页展示结束==============================================
    //点击二级分类的时候展示的页面
    public function classList()
    { 

        $class_id = input('get.class_id');
        //查询当前博客下的自分类,并加载
        $blog_class_soninfo = $this->selCurClass($class_id);
        //查询当前博客下的title
        $class_title = $this->selClassTitle($class_id);
        //查询当前分类下的所有博客
        $blog_list_class = $this->selCurClaBlog($class_id);
        //加载
        $this->assign('blog_class_soninfo',$blog_class_soninfo);
        $this->assign('class_title',$class_title);
        $this->assign('blog_list_class',$blog_list_class);
        return $this->fetch('class_list');
        
    }
    //根据当前子分类查询同级别自分类的信息
    public function selCurClass($id)
    { 
        $sql = "select class_id,class_title from ns_class where class_fid = (select class_fid from ns_class where class_id = {$id}) ORDER BY class_oid,class_id desc";
        $res = Db::query($sql);
        return $res;
    }
    //查询当前分类下的class_title
    public function selClassTitle($id)
    { 
        $sql = "select class_title from ns_class where class_id = {$id}";
        $res = current(current(Db::query($sql)));
        return $res;
    }
    //查询当前分类下的博客列表
    public function selCurClaBlog($id)
    { 
        $sql = "SELECT 
                    bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,from_unixtime(bloginfo_createtime,'%Y') AS year,from_unixtime(bloginfo_createtime,'%m-%d') AS date,from_unixtime(bloginfo_createtime,'%Y-%m-%d') AS bloginfo_createtime,bloginfo_like,bloginfo_hate,bloginfo_click 
                FROM ns_bloginfo 
                WHERE bloginfo_status = 1 AND class_id=".$id."
                    ORDER BY bloginfo_click DESC limit 10";
        $res = Db::query($sql);
        return $res;
    }
    public function click_num()
    { 
        if(!$_GET['bloginfo_id']){ 
            die('统计数据错误,没有ID');
        }
        $bloginfo_id = input('get.bloginfo_id');
        $res = Db::table('ns_bloginfo')
        ->where('bloginfo_id',$bloginfo_id)
        ->setInc('bloginfo_click');
        if($res){ 
            return true;
        }else{ 
            return false;
        }
    }
}
