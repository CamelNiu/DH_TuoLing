<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Common as ModelCommon;
//use \app\admin\model\Common;
//分类管理控制器
class Common extends Controller
{
    protected $MCommon;
    public function _initialize()
    {
        //验证登陆
        $CLogin = new \app\admin\controller\Login;
        $CLogin->login_status();
        //构造方法实例化Model公共类
        $this->MCommon = new ModelCommon();
    }
    //文件上传类
    //$file_name文件上传上来的字段
    //$path 文件保存在uploads下的子目录,也就是最终目录
    public function Uploads($file_name,$path)
    {
        $file = request()->file($file_name);
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/'.$path."/");
        if($info){
            return $info->getSaveName();
        }else{
            echo $file->getError();
            die('文件上传失败');
        }
    }
    //修改状态
    public function modify_status($tablename,$id)
    {
        $status_res = Db::name($tablename)
        ->field($tablename.'_status')
        ->where($tablename.'_id',$id)
        ->find();
        switch($status_res[$tablename."_status"])
        {
            case "1":
                $data[$tablename."_id"] = $id;
                $data[$tablename."_status"] = 0;
                $res = Db::name($tablename)->update($data);
            break;
            case "0":
                $data[$tablename."_id"] = $id;
                $data[$tablename."_status"] = 1;
                $res = Db::name($tablename)->update($data);
            break;
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }

}
