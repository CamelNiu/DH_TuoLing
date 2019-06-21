<?php
namespace app\request\controller;
class Request
{
    const TIMEOUT = 10;
    public static function curl_get($url,$timeout="")
    { 
        $errno = -1;$error = '系统错误';$data = [];
        try {
                $timeout = empty($timeout)?self::TIMEOUT:$timeout;
                //$start_at = microtime(true);
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
                //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
                $res = curl_exec($curl);
                $status_code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
                $msg =   curl_error($curl) . curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                if($status_code != 200){ 
                    throw new \Exception("{$url}报错".$msg,25000);
                }
                $errno = 0;
                $error = 'ok';
            } catch (\Exception $e) {
                $error = $e->getMessage();
                $errno = $e->getCode();
            }     
            $data['code'] = $errno;
            $data['msg'] = $error;
            $data['data'] = $res;
            return json_encode($data);
    }
    public static function curl_post()
    { 

    }
}
