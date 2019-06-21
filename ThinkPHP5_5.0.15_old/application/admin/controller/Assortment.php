<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
//use \app\admin\model\Common;
//分类管理控制器
class Assortment extends Common
{
    public function index()
    {
        $assortment_list = db('class')->field('class_id,class_fid,class_oid,class_title,class_Etitle,class_describe,class_status,class_path')->order('class_oid desc')->select();
        //无限分类排序
        $assortment_list = $this->MCommon->recursionNoTree($assortment_list,'class_id','class_fid',0,0);
        //无限分类标题拼接延伸符
        $assortment_list = $this->MCommon->addStrForArr($assortment_list);
        $this->assign('assortment_list',$assortment_list);
        return $this->fetch();
    }
    public function add()
    {
        //判断是否传递数据,传递数据处理数据，没传递数据展示页面
        if($_POST){
        //获取数据以及input函数清洗数据
            $data = input('post.');
            $data['class_createtime'] = time();
            $data['class_updatetime'] = time();
            //插入数据
            //方法1，助手函数
            $res = Db::name('class')->insert($data);
            //方法2，导入Db的命名空间,实例化Db类进行插入
            //$res = Db::table('ns_class')->insert($data);
            //方法3,利用Model类上传
            if($res){
                $this->success('添加分类成功！',url('index'));
            }else{
                $this->error('添加分类失败');
            }
            return;
        }else{
            //查询分类
            $assortment_list = db('class')
            ->field('class_id,class_fid,class_title')
            ->order('class_oid desc')
            ->select();
            //无限分类排序
            $assortment_list = $this->MCommon->recursionNoTree($assortment_list,'class_id','class_fid',0,0);
            //无限分类标题拼接延伸符
            $assortment_list = $this->MCommon->addStrForArr($assortment_list);
            $this->assign('assortment_list',$assortment_list);
            return $this->fetch();
        }
    }
    public function edit()
    { 
        //查看是否有post数据
        if($_POST){ 
            $data = input('post.');
            $res_updateclass = Db::name('class')
            ->update($data);
            if($res_updateclass){ 
                $this->success('SUCCESS',url('index'));
            }else{ 
                $this->error('ERROR');
            }
            return;
        }
        $class_id = input('get.class_id');
        //查询分类信息
        $class_info_sql = "select * from ns_class where class_id=".$class_id;
        $class_info = Db::query($class_info_sql);
        $class_info = $class_info[0];
        $assortment_list = db('class')
            ->field('class_id,class_fid,class_title')
            ->order('class_oid desc')
            ->select();
        //无限分类排序
        $assortment_list = $this->MCommon->recursionNoTree($assortment_list,'class_id','class_fid',0,0);
        //无限分类标题拼接延伸符
        $assortment_list = $this->MCommon->addStrForArr($assortment_list);
        $this->assign('assortment_list',$assortment_list);
        $this->assign('class_info',$class_info);
        return $this->fetch();
    }
    public function delete()
    { 
        $class_id = input('get.class_id');
        //查询此id下还有没有其他子分类,如果有自分类,则不可删除
        $res_ifson = Db::name('class')->where('class_fid',$class_id)->field('class_id')->find();
        if($res_ifson){$this->error('当前有子类，不能删除');die();}
        $res_delete = Db::name('class')->delete($class_id);
        if($res_delete){ 
            $this->success('SUCCESS',url('index'));
        }else{ 
            $this->error('ERROR');
        }
    }
    public function statusMod()
    { 
        $class_id = input('get.class_id');
        $tablename = 'class';
        $res = $this->modify_status($tablename,$class_id);
        if($res){ 
            $this->redirect("Assortment/index");
        }else{ 
            return "<h1>修改失败,请联系管理员</h1>";
        }
    }

}
