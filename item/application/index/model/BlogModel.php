<?php
namespace app\index\model;
use think\Model;
use think\Db;
class BlogModel extends CommonModel
{
    public function get_index_blog_list()
    {
        $sql = "SELECT
                        bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,bloginfo_createtime,class_title
                FROM
                        ns_bloginfo AS nb
                LEFT JOIN
                        ns_class AS nc on nb.class_id = nc.class_id
                WHERE
                        nb.bloginfo_status=1
                ORDER BY
                        nb.bloginfo_click DESC
                LIMIT 15";
        $res = Db::query($sql);
        return $res;
    }
    public function get_blog_clicknum()
    {
        $sql = "SELECT
                        bloginfo_id,bloginfo_title,bloginfo_click
                FROM
                        ns_bloginfo
                WHERE
                        bloginfo_status=1
                ORDER BY
                        bloginfo_click DESC
                LIMIT 10";
        $res = Db::query($sql);
        return $res;
    }
    public function get_blog_latest_publish()
    {
        $sql = "SELECT
                        bloginfo_id,bloginfo_title
                FROM
                        ns_bloginfo
                WHERE
                        bloginfo_status = 1
                ORDER BY
                        bloginfo_createtime DESC
                LIMIT 5";
        $res = Db::query($sql);
        return $res;
    }
    public function get_blog_list($search_key = "",$limit = "")
    {
        $sql = "SELECT
                        bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,bloginfo_createtime,class_title
                FROM
                        ns_bloginfo AS nb
                LEFT JOIN
                        ns_class AS nc on nb.class_id = nc.class_id
                WHERE
                        nb.bloginfo_status=1 and nb.bloginfo_title like '%$search_key%'
                ORDER BY
                        nb.bloginfo_createtime DESC
                {$limit}
                ";
        $res = Db::query($sql);
        return $res;
    }
    public function get_blog_count()
    {
        $sql = "SELECT
                        count(*) as total
                FROM
                        ns_bloginfo
                WHERE
                        bloginfo_status = 1
                ";
        $res = Db::query($sql);
        return $res[0]['total'];
    }
    public function get_blog_by_class($class_id)
    {
        $sql = "SELECT
                        bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,bloginfo_createtime,class_title
                FROM
                        ns_bloginfo AS nb
                LEFT JOIN
                        ns_class AS nc on nb.class_id = nc.class_id
                WHERE
                        nb.bloginfo_status=1 AND nb.class_id = {$class_id}
                ORDER BY
                        nb.bloginfo_createtime DESC
                ";
        $res = Db::query($sql);
        return $res;
    }
    public function get_blog_by_label($label_id)
    {
        $sql = "SELECT
                        bloginfo_id,bloginfo_title,bloginfo_describe,bloginfo_img,bloginfo_createtime,class_title
                FROM
                        ns_bloginfo AS nb
                LEFT JOIN
                        ns_class AS nc on nb.class_id = nc.class_id
                WHERE
                        nb.bloginfo_id IN  (
                                        SELECT
                                            bloginfo_id
                                        FROM
                                            ns_label_blog
                                        WHERE
                                            label_id = {$label_id}

                                        )
                        AND nb.bloginfo_status=1
                ORDER BY
                        nb.bloginfo_createtime DESC
                ";
        $res = Db::query($sql);
        return $res;
    }
    public function get_content_info($blog_id)
    {
        $sql = "SELECT
                        nb.bloginfo_id AS blog_id,bloginfo_title,bloginfo_createtime,class_title,blogcontent_ctt AS content,bloginfo_like,bloginfo_click,bloginfo_hate
                FROM
                        ns_bloginfo AS nb
                LEFT JOIN
                        ns_class AS nc on nb.class_id = nc.class_id
                LEFT JOIN
                        ns_blogcontent AS nbc ON nb.bloginfo_id = nbc.bloginfo_id
                WHERE
                        nb.bloginfo_id = {$blog_id}";
        $res = Db::query($sql);
        return $res[0];
    }
    public function add_scan_num($blog_id)
    {
        $sql = "UPDATE
                    ns_bloginfo
                SET
                    bloginfo_click = bloginfo_click+1
                WHERE
                    bloginfo_id = {$blog_id}";
        $res = Db::execute($sql);
        return $res;
    }
}



















































