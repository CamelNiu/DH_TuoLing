<?php
namespace app\tools\controller;

class GetIp
{
    public static function get_ip()
    {
        try {
            $realip = '';
            if(isset($_SERVER)){
                //如果客户端用了代理ip
                if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                    /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                    foreach ($arr AS $ip){
                        $ip = trim($ip);
                        if ($ip != 'unknown'){
                            $realip = $ip;
                            break;
                        }
                    }
                //代理ip
                }elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                //握手ip，有代理则是代理ip，没有代理则是真实ip
                }else{
                    if (isset($_SERVER['REMOTE_ADDR'])){
                        $realip = $_SERVER['REMOTE_ADDR'];
                    }else{
                        $realip = '0.0.0.0';
                    }
                }
            }else{
                if (getenv('HTTP_X_FORWARDED_FOR')){
                    $realip = getenv('HTTP_X_FORWARDED_FOR');
                }elseif (getenv('HTTP_CLIENT_IP')){
                    $realip = getenv('HTTP_CLIENT_IP');
                }else{
                    $realip = getenv('REMOTE_ADDR');
                }
            }
            $code = 0;
            $msg = 'ok';
        } catch (\Exception $e) {
            $code = $e->getMessage();
            $msg = $e->getCode();
        }
        return ['code' => $code,'msg' => $msg,'ip' => $realip];
    }
}