<?php
namespace app\index\controller;
use think\Db;
class Link extends Common
{
    private $link_class_list;
    public function index()
    {
        //首页需要显示分类,显示常用链接,显示分类链接

        $class_link_id = input('get.class_id');
        //查询链接分类下面的分类
        $link_class_sql = "select class_id,class_title from ns_class where class_fid = ".$class_link_id." and class_status = 1 order by class_oid desc";
        $link_class_list = Db::query($link_class_sql);
        $this->class_link_id = $link_class_list;
        //查询热门链接
        $hot_link_sql = "select link_id,link_title,link_url from ns_link where link_status=1 order by link_clicknum desc limit 30";
        $hot_link_list = Db::query($hot_link_sql);

        //查询分类链接
        $link_classed = $link_class_list;
        foreach($link_classed as $key => $val){ 
            $tmp_sql = "select link_id,link_title,link_url from ns_link where link_status=1 and class_id =".$val['class_id']." order by link_clicknum desc";
            $tmp_arr = Db::query($tmp_sql);
            $link_classed[$key]['link'] = $tmp_arr;
        }
        $this->assign('link_class_list',$link_class_list);
        $this->assign('hot_link_list',$hot_link_list);
        $this->assign('link_classed',$link_classed);
        return $this->fetch();
    }
    public function classify()
    { 
        $class_id = input("get.class_id");
        $res = preg_match("/\d/",$class_id);
        if(!$res) die('URL错误');
        //查询所有的链接分类
        $link_class_sql = "select class_id,class_title,class_Etitle from ns_class where class_fid = (select class_fid from ns_class where class_id=".$class_id.") and class_status=1 order by class_oid desc";
        $link_class_list = Db::query($link_class_sql);
        //查询该分类下的链接
        $where_arr['class_id'] = $class_id;
        $where_arr['link_status'] = 1;
        $link_list = Db::name('link')
        ->field('link_id,link_title,link_url')
        ->where($where_arr)
        ->order('link_clicknum desc')
        ->select();
        $class_title = Db::name('class')
        ->field('class_id,class_title')
        ->where('class_id',$class_id)
        ->find();
        $this->assign('link_class_list',$link_class_list);
        $this->assign('link_list',$link_list);
        $this->assign('class_title',$class_title);
        return $this->fetch();
    }
    public function click_num()
    { 
        if(!$_GET['link_id']){ 
            echo "错误，统计数据没有ID";
            die();
        }
        $link_id = input('get.link_id');

        /*==================原生语句模式=====================
        //获取到link_id,给当前id下的点击量+1；
        $link_clicknum_sql = "update ns_link set `link_clicknum` = ( select link_clicknum from (select link_clicknum from ns_link where link_id=".$link_id." ) as temptable )+1 where link_id   = ".$link_id;
        $res_linkclicknum = Db::query($link_clicknum_sql);
        var_dump($res_linkclicknum);
        =====================================================
        */
        $res = Db::table('ns_link')
        ->where('link_id',$link_id)
        ->setInc('link_clicknum');
        if($res){ 
            return $res;
        }else{ 
            return $res;
        }
    }
}
