<?php
namespace app\admin\controller;
use app\admin\model;
use think\Controller;
use think\Db;
use page\Pagination;

//use \app\admin\model\Common;
//分类管理控制器
class Blog extends Common
{
    public function index()
    {
        if($_POST){
            $search_keywords = $_REQUEST['search_keywords'];
        }else{
            $search_keywords = "";
        }
        if($_GET){
            $page_id = $_GET['page_id'];
        }else{
            $page_id = 1;
        }
        //$search_keywords = ($_REQUEST['search_keywords'])?$_REQUEST['search_keywords']:"";
        //查询博客详情表,展示博客信息
        $pageObj = new pagination(10);
        $blog_total_sql = "SELECT COUNT(1) as total FROM ns_bloginfo WHERE bloginfo_title like '%".$search_keywords."%'";
        $total = current(current(Db::query($blog_total_sql)));
        $page_condition = $pageObj->page_condition($page_id,$total);
        $page_res = $pageObj->page($page_id,$total);
        $blog_sql = "SELECT bloginfo_id,bloginfo_title,bloginfo_status,
                            FROM_UNIXTIME(bloginfo_createtime,'%Y-%m-%d %H:%i:%s') AS bloginfo_createtime,class_title
                     FROM ns_bloginfo AS nb
                     LEFT JOIN ns_class nc
                     ON nb.class_id = nc.class_id
                     WHERE bloginfo_title like '%".$search_keywords."%'
                     ORDER BY bloginfo_id DESC ".$page_condition;
        $blog_list = Db::query($blog_sql);
        $this->assign('page_res',$page_res);
        $this->assign('blog_list',$blog_list);
        return view();
    }
    public function add()
    {
        if(request()->isPost()){
            //接受数据
            $data = input('post.');
            $data['bloginfo_createtime'] = time();
            $data['bloginfo_updatetime'] = time();
            //区别上传的图片和默认的图片
            if($_FILES['bloginfo_img']['error'] == 0){
                $data['bloginfo_img'] = $this->Uploads('bloginfo_img','BlogsImg');
            }else{
                $data['bloginfo_img'] = 'InitializeImg/initialize.jpg';
            }
            //分离需要上传其他表的数据
            if(!empty($data['label_id'])) $data_label_blog = $data['label_id'];
            if(!empty($data['bloginfo_link'][0])) $data_blog_link = array_filter($data['bloginfo_link']);
            $data_blogcontent_ctt['blogcontent_ctt'] = $data['blogcontent_ctt'];
            unset($data['bloginfo_link']);
            unset($data['label_id']);
            unset($data['blogcontent_ctt']);
            //如果没有填写描述,则截取正文作为描述
            trim(($data['bloginfo_describe'])) || str_replace(" ","",$data['bloginfo_describe'] = mb_substr(strip_tags($data_blogcontent_ctt['blogcontent_ctt']),0,130)."...");
            //上传数据整理完毕,上传数据
            //由于三表操作,开启事务
            Db::startTrans();
            //上传bloginfo,获得id
            $res_bloginfo = Db::name('bloginfo')->insertGetId($data);
            if($res_bloginfo){
                //处理需要上传的内容
                $data_blogcontent_ctt['bloginfo_id'] = $res_bloginfo;
                $res_ctt = Db::name('blogcontent')->insert($data_blogcontent_ctt);
                //处理需要上传的标签id数组
                if(isset($data_label_blog)){
                    $data_label_blog_ins = array();
                    foreach($data_label_blog as $key => $val){
                        $data_label_blog_ins[$key]['bloginfo_id'] = $res_bloginfo;
                        $data_label_blog_ins[$key]['label_id'] = $val;
                    }
                    $res_label_blog = Db::name('label_blog')->insertAll($data_label_blog_ins);
                }
                if(isset($data_blog_link)){
                    $data_link_blog_ins = array();
                    foreach($data_blog_link as $key => $val){
                        $data_link_blog_ins[$key]['bloginfo_id'] = $res_bloginfo;
                        $data_link_blog_ins[$key]['bloglink_url'] = $val;
                    }
                    $res_link_blog = Db::name('blog_link')->insertAll($data_link_blog_ins);
                }
            }
            $res_label_blog = isset($res_label_blog)?$res_label_blog:true;
            $res_link_blog = isset($res_link_blog)?$res_link_blog:true;
            if( $res_bloginfo>0 && $res_ctt && $res_label_blog && $res_link_blog ){
                Db::commit();
                $this->success('文章添加成功',url('index'));
            }else{
                Db::rollback();
                $this->errors('文章添加失败');
            }
            return;
        }
        //查询分类,供博客选择
        $class_sql = "select class_id,class_fid,class_title from ns_class where class_status = 1  order by class_oid desc";
        $class_list = Db::query($class_sql);
        $class_list = $this->MCommon->recursionNoTree($class_list,'class_id','class_fid',0,0);
        $class_list = $this->MCommon->addStrForArr($class_list);
        $this->assign("class_list",$class_list);
        //查询标签,供博客选择,多对多
        $label_sql = "select label_id,label_title from ns_label where label_status = 1 order by label_oid,label_id desc";
        $label_list = Db::query($label_sql);
        $this->assign("label_list",$label_list);
        return view();
    }

    public function edit()
    { 
        if(request()->isPost()){ 
            $data = input('post.');
            if($_FILES['bloginfo_img']['error'] == 0){ 
                $data['bloginfo_img'] = $this->Uploads('bloginfo_img','BlogsImg');
                $blog_img_path = str_replace('\\','/',dirname(dirname(dirname(__DIR__)))).'/public/uploads/BlogsImg/'.str_replace('\\','/',$data['old_img_path']);
                if(file_exists($blog_img_path) && !strpos($blog_img_path,'InitializeImg')){
                    unlink($blog_img_path);
                }
            }
            try {
                //进行修改
                //清楚标签和链接表
                Db::startTrans();
                $where['bloginfo_id'] = $data['bloginfo_id'];
                Db::name('label_blog')->where($where)->delete();
                Db::name('blog_link')->where($where)->delete();
                //添加标签表和链接表
                $data_label_blog = "";
                foreach($data['label_id'] as $k => $v){ 
                    $data_label_blog[$k]['label_id'] = $v;
                    $data_label_blog[$k]['bloginfo_id'] = $data['bloginfo_id'];
                }
                $data_blog_link = "";
                foreach($data['bloginfo_link'] as $k => $v){ 
                    if(empty($v)){ 
                        continue;
                    }
                    $data_blog_link[$k]['bloglink_url'] = $v;
                    $data_blog_link[$k]['bloginfo_id'] = $data['bloginfo_id'];
                }
                if(!empty($data_label_blog) && is_array($data_label_blog)) Db::name('label_blog')->insertAll($data_label_blog);
                if(!empty($data_blog_link) && is_array($data_blog_link)) Db::name('blog_link')->insertAll($data_blog_link);
                //修改主表
                $bloginfo_data['class_id'] = $data['class_id'];
                $bloginfo_data['bloginfo_title'] = $data['bloginfo_title'];
                $bloginfo_data['bloginfo_oid'] = $data['bloginfo_oid'];
                $bloginfo_data['bloginfo_describe'] = $data['bloginfo_describe'];
                if(isset($data['bloginfo_img'])){ 
                    $bloginfo_data['bloginfo_img'] = $data['bloginfo_img'];
                }
                Db::name('bloginfo')->where($where)->update($bloginfo_data);
                $blogctt_data['blogcontent_ctt'] = $data['blogcontent_ctt'];
                Db::name('blogcontent')->where($where)->update($blogctt_data);
                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            $this->success('修改成功',url('index'));
            return;
        }
        //分类信息
        $class_sql = "select class_id,class_fid,class_title from ns_class where class_status = 1  order by class_oid desc";
        $class_list = Db::query($class_sql);
        $class_list = $this->MCommon->recursionNoTree($class_list,'class_id','class_fid',0,0);
        $class_list = $this->MCommon->addStrForArr($class_list);
        $this->assign('class_list',$class_list);
        //标签信息
        $label_sql = "select label_id,label_title from ns_label where label_status = 1 order by label_oid,label_id desc";
        $label_list = Db::query($label_sql);
        $this->assign('label_list',$label_list);
        //BLOG详情
        $bloginfo_id = input('get.bloginfo_id');
        $bloginfo_sql = "select * from ns_bloginfo where bloginfo_id = {$bloginfo_id}";
        $bloginfo_list = Db::query($bloginfo_sql)[0];
        $blogctt_sql = "select blogcontent_ctt from ns_blogcontent where bloginfo_id = {$bloginfo_id}";
        $bloginfo_list['bloginfo_ctt'] = Db::query($blogctt_sql)[0]['blogcontent_ctt'];
        //blog链接地址
        $blog_link_sql = "select bloglink_url from ns_blog_link where bloginfo_id = {$bloginfo_id}";
        $bloginfo_list['blog_link_list'] = array_column(Db::query($blog_link_sql),'bloglink_url');
        //blog链接标签
        $blog_label_sql = "select label_id from ns_label_blog where bloginfo_id = {$bloginfo_id}";
        $bloginfo_list['blog_label_list'] = array_column(Db::query($blog_label_sql),'label_id');
        $this->assign('bloginfo_list',$bloginfo_list);
        return $this->fetch();
    }
    public function statusMod()
    { 
        $bloginfo_id = input('get.bloginfo_id');
        $tablename = 'bloginfo';
        $res = $this->modify_status($tablename,$bloginfo_id);
        if($res){ 
            $this->redirect('Blog/index');
        }else{ 
            return "<h1>修改失败,请联系管理员</h1>";
        }
    }

}
