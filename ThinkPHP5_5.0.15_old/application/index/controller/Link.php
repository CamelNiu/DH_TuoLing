<?php
namespace app\index\controller;
use think\Db;
use app\index\controller\Index;
use app\visit\controller\Write;
use app\index\model\LinkModel;
class Link extends Common
{
    private $M_Link;
    public function __construct()
    {
        parent::__construct();
        $this->M_Link = new LinkModel();
    }
    public function index()
    {
        $list = [];
        $list['recommend_link'] = $this->M_Common->get_link_clicknum(30);
        list(,,$list['class_link_info']) = $this->M_Link->get_class_link_info();
        $list['right_list'] = $this->get_right_list();
        $this->assign("list",$list);
        return $this->fetch();
    }
    public function click_num()
    {
        $url = input('get.url');
        $link_id = input('get.id');
        $this->redirect($url);
    }
}
