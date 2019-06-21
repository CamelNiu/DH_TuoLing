<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
//use app\admin\model;
//use \app\admin\model\Common;
//分类管理控制器
class Homepage extends Common
{
    //展示信息
    public function index()
    {
        //首页栏目展示
        $homepage_sql = "select homepage_id,homepage_title,homepage_url,homepage_image,FROM_UNIXTIME(homepage_createtime,'%Y-%m-%d %H:%i:%s') as homepage_createtime,homepage_status from ns_homepage order by homepage_oid desc";
        $homepage_list = Db::query($homepage_sql);
        $this->assign("homepage_list",$homepage_list);
        /*
        echo "<pre>";
        print_r($homepage_list);
        echo "</pre>";
        */
        return view();
    }
    //添加信息
    public function add()
    {
        if(request()->isPost()){ 
            $data = input('post.');
            $data['homepage_createtime'] = time();
            //上传文件
            if($_FILES['homepage_image']['error'] == 0){ 
                //如果文件上传无误，调用上传类，获取文件路径
                $data['homepage_image'] = $this->MCommon->pathSign($this->Uploads('homepage_image','HomepageImg'));
            }else{ 
                echo "文件上传失败";
                dump($_FILES);die();
            }
            //文件上传完毕,上传数据
            $res = Db::name('homepage')->insert($data);
            if($res){ 
                $this->success('添加成功,稍后返回首页',url("index"));
            }else{ 
                $this->errors('添加失败,请重试');
            }
            return;
        }
        return view();
    }
    //修改信息
    public function edit()
    {  
        if(request()->isPost()){
            $data = input('post.');
            //查看有没有上传文件,如果有上传,删除旧的上传新的,如果没有上穿,图片路径不变
            if($_FILES['homepage_image']['name']){ 
                //上传文件了
                $data['homepage_image'] = $this->MCommon->pathSign($this->Uploads('homepage_image','HomepageImg'));
                if(file_exists(ROOT_PATH.'public/uploads/HomepageImg/'.$data['homepage_image_old'])){
                    unlink(ROOT_PATH.'public/uploads/HomepageImg/'.$data['homepage_image_old']);
                }
            }else{ 
                $data['homepage_image'] = $data['homepage_image_old'];
            }
            unset($data['homepage_image_old']);
            $res = Db::name('homepage')->update($data);
            if($res){ 
                $this->success('修改成功,稍后返回首页',url("index"));
            }else{ 
                $this->errors('修改失败');
            }
            return;
        }
        $homepage_id = input('get.homepage_id');
        $homepage_sql = "select * from ns_homepage where homepage_id = ".$homepage_id;
        $homepage_list = Db::query($homepage_sql);
        $homepage_list = $homepage_list[0];
        $this->assign('homepage_list',$homepage_list);
        return view();
    }
    //
    public function statusMod()
    { 
        $homepage_id = input('get.homepage_id');
        $tablename = 'homepage';
        $res = $this->modify_status($tablename,$homepage_id);
        if($res){ 
            $this->redirect("index");
        }else{ 
            return "<h1>修改失败,请联系管理员</h1>";
        }
    }
    public function delete()
    { 
        $homepage_id = input('get.homepage_id');
        $homepage_image_path = ROOT_PATH.'public/uploads/HomepageImg/'.input('get.homepage_image');
        //删除的过程先删除图片文件，再删除数据库
        if(file_exists($homepage_image_path)){
            $res = unlink($homepage_image_path);
            if(!$res){ 
                //文件删除失败
                $this->errors('文件删除失败,联系管理员');
            }
        }
        
        $res_del = Db::name('homepage')->delete($homepage_id);
        if($res_del){ 
            $this->success('删除成功');
        }else{ 
            $this->errors('数据删除失败,请联系管理员');
        }
    }

}
