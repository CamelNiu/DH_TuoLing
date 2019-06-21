<?php
namespace app\index\controller;
use think\Db;
class Weibo extends Common
{
    public function index()
    {
	return $this->redirect('https://weibo.com/5899556681/profile');
    }
}
