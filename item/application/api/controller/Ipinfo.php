<?php
namespace app\api\controller;

use think\Request;

class Ipinfo
{

    private $ipRegular = "/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";

    public function index()
    {
        \app\tools\controller\Visit::listen();

        $request = Request::instance();
        $ip = $request->param('ip');
        $checkRes = preg_match($this->ipRegular,$ip);
        if(!$checkRes){
            $dataInfo = [
                'code' => '-1',
                'msg'  => '[Error] Please enter the correct ip address',
                'data' => [$ip],
            ];
            return json_encode($dataInfo);
        }

        if("127.0.0.1" == $ip){
            $dataInfo = [
                'code' => '0',
                'msg'  => '[ok] This ip is localhost',
                'data' => [],
            ];
            return json_encode($dataInfo);
        }

        $ipInfo = \app\tools\controller\IpLocation::getIpLocation($ip);
        if(empty($ipInfo)){
            $dataInfo = [
                'code' => '-2',
                'msg'  => '[Sorry] This IP address is not recognized',
                'data' => [$ip],
            ];
            return json_encode($dataInfo);
        }

        $dataInfo = [
            'code' => 0,
            'msg'  => 'ok',
            'data' => $ipInfo,
        ];

        return json_encode($dataInfo);

    }
}
