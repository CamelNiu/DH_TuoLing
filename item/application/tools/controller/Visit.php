<?php
namespace app\tools\controller;
use app\tools\controller\GetIp;
use app\tools\model\VisitModel;
class Visit
{
    public static function listen($type = 0)
    {

        $ip_obj = new GetIp;
        $ip = $ip_obj->get_ip();
        $ip = isset($ip['ip'])?(empty($ip['ip'])?'ip is empty':$ip['ip']):'ip is not set';
        $info['time'] = time();
        $info['microtime'] = microtime(true);
        $info['url'] = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        $info['ip'] = $ip;
        $data['type'] = $type;
        $data['keys'] = md5($ip.microtime());
        $data['data'] = json_encode($info);
        $M_visit = new VisitModel;
        $res = $M_visit->add_log_cache($data);

    }
}