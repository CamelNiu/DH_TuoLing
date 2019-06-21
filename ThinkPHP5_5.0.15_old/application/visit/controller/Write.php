<?php
namespace app\visit\controller;
use think\Controller;
use think\Db;
use app\helper\controller\GetIp;
use app\request\controller\Request;
use think\cache\driver\File;
use think\cache\driver\Redis;
use think\cache\driver\Memcached;
use app\visit\model\VisitModel;
use think\Log;
use app\visit\controller\Visit;
class Write extends controller
{
    public static function writeDB()
    {
        $visit_obj = new visit();
        $visit_obj->write_db();
    }
}