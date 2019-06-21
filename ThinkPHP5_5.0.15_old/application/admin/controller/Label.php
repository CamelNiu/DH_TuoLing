<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
//use \app\admin\model\Common;
//分类管理控制器
class Label extends Common
{
    public $class_id_techBlog = 2;
    public function index()
    {
        $label_sql = "select label_id,label_title,label_status from ns_label order by label_oid desc,label_id desc ";
        $label_list = Db::query($label_sql);
        $this->assign("label_list",$label_list);
        return view();
    }
    public function add()
    {
        if(request()->isPost()){
            $data = input('post.');
            $data['label_createtime'] = time();
            //数据收集完成，操作两表，启用事务
            Db::startTrans();
            //插入标签表
            $label_class = $data['class_id'];unset($data['class_id']);
            $label_id = Db::name('label')->insertGetId($data);
            //插入多对多链接表
            $data_label_class = "";
            foreach($label_class as $key => $val){
                $data_label_class[$key]['label_id'] = $label_id;
                $data_label_class[$key]['class_id'] = $val;
            }
            $label_class_res = Db::name('class_label')->insertAll($data_label_class);
            if($label_id && $label_class_res){
                Db::commit();
                $this->success('标签添加成功',url('index'));
            }else{
                Db::rollback();
                $this->error('标签添加失败');
            }
            return;
        }
        //只有技术博客有标签
        $class_tech_sql = "select class_title,class_id from ns_class where class_status = 1 and class_fid = {$this->class_id_techBlog} order by class_oid desc";
        $class_tech_arr = Db::query($class_tech_sql);
        $this->assign('class_tech_arr',$class_tech_arr);
        return view();
    }
    public function edit()
    { 
        if(request()->isPost()){ 
            $data = input('post.');
            $where_del['label_id'] = $data['label_id'];
            $data_class_label = [];
            foreach ($data['class_id'] as $k => $v) {
                $data_class_label[$k]['class_id'] = $v;
                $data_class_label[$k]['label_id'] = $data['label_id'];
            }
            $data_label_update['label_title'] = $data['label_title'];
            $data_label_update['label_oid'] = $data['label_oid'];
            Db::startTrans();
            $res_delete = Db::name('class_label')->where($where_del)->delete();
            $res_insert = Db::name('class_label')->insertAll($data_class_label);
            $res_update = Db::name('label')->where($where_del)->update($data_label_update);
            Db::commit();
            $this->success('标签修改成功',url('index'));
            return;
        }
        $label_id = $_GET['label_id'];
        $class_tech_sql = "select class_title,class_id from ns_class where class_status = 1 and class_fid = {$this->class_id_techBlog} order by class_oid desc";
        $class_tech_arr = Db::query($class_tech_sql);
        $label_sql = "select label_id,label_title,label_oid from ns_label where label_id={$label_id}";
        $label_arr = Db::query($label_sql)[0];
        $label_class_sql = "select class_id from ns_class_label where label_id={$label_id}";
        $label_arr['label_class'] = array_column(Db::query($label_class_sql),'class_id');
        $this->assign('label_arr',$label_arr);
        $this->assign('class_tech_arr',$class_tech_arr);
        return $this->fetch();
    }
    public function statusMod()
    { 
        $label_id = input('get.label_id');
        $tablename = 'label';
        $res = $this->modify_status($tablename,$label_id);
        if($res){ 
            $this->redirect('Label/index');
        }else{ 
            return '<h1>修改失败，请联系管理员</h1>';
        }
    }
}
