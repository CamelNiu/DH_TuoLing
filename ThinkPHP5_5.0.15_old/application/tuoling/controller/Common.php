<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Url;
class Common extends controller
{
    //构造方法,初始化前端页面,展示分类
    public function _initialize()
    {
        //$this->cookieLog();
        $top_class_sql = "select class_id,class_title,class_Etitle from ns_class where class_fid = 0 and class_status = 1 order by class_oid desc";
        
        $top_class_list = Db::query($top_class_sql);
        foreach($top_class_list as $key => $val){
            $top_class_list[$key]['path_url'] = "http://".$_SERVER['HTTP_HOST'].Url::build('index/'.$val["class_Etitle"].'/index')."?class_id=".$val['class_id'];
        }
        $this->assign('top_class_list',$top_class_list);
        return $this->fetch('public/header');
    }

    //输出来访者的ip
    public function getIp()
    { 
        //静态变量只存在于函数作用域内，也就是说，静态变量只存活在栈中。一般的函数内变量在函数结束后会释放，比如局部变量，但是静态变量却不会
        static $realip = NULL;
        if ($realip !== NULL)
        {
            return $realip;
        }
        //可获取到$_SERVER的情况下
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
        //获取不到$_SERVER的情况下
        }else{
            if (getenv('HTTP_X_FORWARDED_FOR')){
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }elseif (getenv('HTTP_CLIENT_IP')){
                $realip = getenv('HTTP_CLIENT_IP');
            }else{
                $realip = getenv('REMOTE_ADDR');
            }
        }
        //最后正则过滤ip地址
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }
    public function cookieLog()
    { 
        session_start();
        $ip = $this->getIp();
        $cookie_name = md5($ip);
        $time = date('Y-m-d H:i:s');
        $_SESSION[$cookie_name]['ip'] = $ip;
        $_SESSION[$cookie_name]['time'] = $time;
        $visitor = serialize($_SESSION);
        $log_path = LOG_PATH."visitor.txt";
        file_put_contents($log_path,$visitor);
        session_unset($cookie_name);
        dump($_SESSION);
        dump($_SERVER);die();
    }
}
