<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Cookie;
//分类管理控制器
class Login extends controller
{
    public function index()
    {
        return view();
    }
    public function login_check()
    {
        Cookie::init(['prefix'=>'login_','expire'=>3600*24,'path'=>'/']);
        $post_admin_name = trim(input('post.admin_name'),'');
        $post_admin_password = trim(input('post.admin_password'),'');
        $data_password = Db::query("select admin_password,admin_id from ns_admin where admin_name='{$post_admin_name}'");
        if(empty($data_password[0])){
            $msg = sprintf('[ pwd empty ]admin is %s password is %s',$post_admin_name,$post_admin_password);
            WL($msg,'loginCheck');
            $this->error();
        }
        if($data_password[0]['admin_password'] != md5($post_admin_password)){
            $msg = sprintf('[ pwd error ]admin is %s password is %s',$post_admin_name,$post_admin_password);
            WL($msg,'loginCheck');
            $this->error();
        }
        $admin_info['admin_id'] = $data_password[0]['admin_id'];
        $admin_info['admin_password'] = $post_admin_password;
        $admin_info['admin_name'] = $post_admin_name;
        $admin_info_str = serialize($admin_info);
        Cookie::set('admin_info',$admin_info);
        $msg = sprintf('[ success ]admin is %s password is %s',$post_admin_name,$post_admin_password);
        WL($msg,'loginCheck');
        $this->redirect('Index/index');
    }
    public function login_status()
    {
        $res = Cookie::has('admin_info','login_');
        if(!$res){
            $this->redirect('Login/index');
        }
    }
}