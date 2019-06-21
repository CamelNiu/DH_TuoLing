<?php
namespace app\index\controller;
use app\index\controller\Common;
use app\index\model\LinkModel;
use app\index\model\CategoryModel;
use app\tools\controller\Encrypt;
use app\index\validate\LinkValidate;
class Link extends Common
{
    public $M_link;
    public $M_class;
    public $link_class = 1;
    public function __construct()
    {
        parent::__construct();
        $this->M_link = new LinkModel;
        $this->M_class = new CategoryModel;
    }
    public function index()
    {
        $list['class_link_info'] = $this->get_class_link_info();
        $list['recommend_link'] = $this->get_link_clicknum(35);
        $list['right_list'] = $this->get_right_list();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function get_link_clicknum($limit)
    {
        $recommend_link = $this->M_link->get_link_clicknum($limit);
        $recommend_link = array_map(function($val){
            $tmp['link_id'] = $val['link_id'];
            $tmp['url'] = $val['link_url'];
            $val['param'] = Encrypt::encryption($tmp);
            return $val;
        },$recommend_link);
        return $recommend_link;
    }
    public function get_class_link_info()
    {
        $link_info = $this->M_link->get_link_info('link_id,link_title,link_url,class_id');
        $class_info = $this->M_class->get_class_list_by_fid($this->link_class,'class_id,class_title');
        $class_link_info = array_map( function($v) use ($link_info){
                $v['link_info'] = [];
                foreach($link_info  as $val){
                    $tmp['link_id'] = $val['link_id'];
                    $tmp['url'] = $val['link_url'];
                    $val['param'] = Encrypt::encryption($tmp);
                    if($v['class_id'] == $val['class_id']) $v['link_info'][] = $val;
                }
                return $v;
        } ,$class_info);
        return $class_link_info;
    }
    public function click_num()
    {
        $data = input('get.param');
        try {
            $data = Encrypt::un_encryption($data);
            $validate_obj = new LinkValidate;
            $res = $validate_obj->check($data);
            if(!$res){
                throw new \Exception($validate_obj->getError(), 300001);
            }
        } catch (\Exception $e) {
            $error['code'] = $e->getCode();
            $msg = $e->getMessage();
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
            $log_data = "[Error-Param][".$msg."][".json_encode($error)."]";
            WL($log_data,'link_click_num');
             die("参数不正确，请重新访问<a href='http://www.niushao.net'>点击重新访问</a>");
        }
        $id = $data['link_id'];
        $url = $data['url'];
        //记录点击次数
        $res = $this->M_link->set_click_num($id);
        return $this->redirect($url);
    }
}