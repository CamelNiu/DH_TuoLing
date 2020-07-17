<?php
namespace app\service;

use think\Db;
/**
 *
 */
class Server
{
    static public function getHost()
    {
        $host = isset($_SERVER['HTTP_DOMAIN']) ? $_SERVER['HTTP_DOMAIN']:$_SERVER['HTTP_HOST'];
        return $host;
    }

}