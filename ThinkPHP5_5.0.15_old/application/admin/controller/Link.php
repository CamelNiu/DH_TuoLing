<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
//use \app\admin\model\Common;
//分类管理控制器
class Link extends Common
{
    public function index()
    {
        $link_list = Db::name('link')
        ->alias('nl')
        ->field('class_title,link_id,link_title,link_url,from_unixtime(link_createtime,\'%Y-%m-%d %H:%i:%s\') as link_createtime,link_status')
        ->join('ns_class nc','nl.class_id=nc.class_id','LEFT')
        ->order('link_id desc')
        ->select();
        $this->assign('link_list',$link_list);
        return $this->fetch();
        return view();
    }
    public function add()
    {
        if(request()->isPost()){ 
            $data = input('post.');
            $data['link_createtime'] = time();
            $res = db('link')->insert($data);
            if($res){ 
                $this->success('添加标签成功',url('index'));
            }else{ 
                $this->error('添加标签失败');
            }
            return;
        }
        //展示之前查出链接的分类
        $class_link_id = 1;
        $where_condition = array( 
            'class_fid' => $class_link_id,
            'class_status' => 1
        );
        $link_list = Db::name('class')
        ->where($where_condition)
        ->field('class_id,class_title')
        ->order('class_oid,class_id')
        ->select();
        $this->assign('link_list',$link_list);
        return $this->fetch();
    }
    public function edit()
    { 
        if(request()->isPost()){
            $data = input('post.');
            $res = Db::name('link')
            ->update($data);
            if($res){
                $this->success('修改成功！',url('index'));
            }else{
                $this->error('修改失败');
            }
            return;
        }
        //查询分类信息
        $link_id = input('get.link_id');
        $sql = "select class_id,class_title from ns_class where class_fid = (select class_fid from ns_class where class_id = (select class_id from ns_link where link_id=".$link_id."))";
        $class_link_list = Db::query($sql);
        //查询链接信息
        $link_info = Db::name('link')
        ->field('link_id,class_id,link_title,link_url')
        ->where('link_id',$link_id)
        ->find();
        $this->assign('class_link_list',$class_link_list);
        $this->assign('link_info',$link_info);
        return $this->fetch();
    }
    public function statusMod()
    { 
        $link_id = input('get.link_id');
        $table_name = 'link';
        $res = $this->modify_status($table_name,$link_id);
        if($res){ 
            $this->redirect("index");
        }else{ 
            return "<h1>修改失败,请联系管理员</h1>";
        }
    }

}
