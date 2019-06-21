<?php
namespace app\index\controller;
use think\Db;
use app\visit\controller\Write;
class Aboutme extends Common
{
    public function index()
    {
        Write::writeDB();
        return view();
    }
}
