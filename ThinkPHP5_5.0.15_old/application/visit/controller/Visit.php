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
use app\traits\logger\LoggerTrits;
//use \app\admin\model\Common;
//分类管理控制器
//use app\visit\controller\Visit
//Visit::write_db();
class Visit extends controller
{
    use LoggerTrits;
    public static $get_ip_url_taobao = 'http://ip.taobao.com/service/getIpInfo.php';
    public static $get_ipInfo_sleep_time = 3;
    public function main()
    {
        echo GetIp::get_ip();
    }
    public function data_cache_info()
    {
        $data['ip'] = json_decode(GetIp::get_ip(),true)['data'];
        //$data['ip'] = '116.24.66.133';
        $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        $data['time'] = date('Y-m-d H:i:s');
        $data['microtime'] = microtime(true);
        return $data;
    }
    public function write_db()
    {
$this->logger_init('write_db');
$this->info('start...');
        $data_cache = $this->data_cache_info();
        $data['data'] = json_encode($data_cache);
        $data['k'] =  md5($data_cache['microtime']);
        $res = VisitModel::write_cache_db($data);
$this->info('[write is ok][k='.$data['k'].']');
$this->info('end...');
    }
    public function trans_visit()
    {
$this->logger_init('trans_visit');
$this->info('start...');
        $visit_cache = VisitModel::sel_visit_cache();
$this->info('[first_sel_visit_cache][count]['.count($visit_cache).']');
        if(empty($visit_cache)){
$this->info('[visit_cache is empty]');
$this->info('end...');
            return;
        }
        foreach($visit_cache as $k=>&$v){
            $tmp = json_decode($v['data'],true);
            $v['data'] = $tmp;
            $v['ip_info'] = $this->get_ipInfo($tmp['ip']);
            sleep(self::$get_ipInfo_sleep_time);
        };unset($v);
        $visit_ip_info = $this->format_visit_cache($visit_cache);
$this->info('[visit_cache_ip_info][count]['.count($visit_ip_info).']');
        VisitModel::trans_visit_db($visit_ip_info);
$this->info('end...');
    }
    public function format_visit_cache($visit_cache)
    {
        $visit_ip_info = [];
        foreach($visit_cache as $key => $val){
            if($val['ip_info'] == null){
                continue;
            }
            $visit_ip_info[$key]['k'] = $val['k'];
            $visit_ip_info[$key]['ip_info']['vst_ip'] = $val['ip_info']['ip'];
            $visit_ip_info[$key]['ip_info']['vst_url'] = $val['data']['url'];
            $visit_ip_info[$key]['ip_info']['ip_country'] = $val['ip_info']['country'];
            $visit_ip_info[$key]['ip_info']['ip_province'] = $val['ip_info']['region'];
            $visit_ip_info[$key]['ip_info']['ip_city'] = $val['ip_info']['city'];
            $visit_ip_info[$key]['ip_info']['vst_at'] = '0';
            $visit_ip_info[$key]['ip_info']['vst_date'] = $val['data']['time'];
        }
        return $visit_ip_info;
    }
    public function get_ipInfo($ip)
    {
        $ip_info_url = self::$get_ip_url_taobao.'?ip='.$ip;
        $ip_info = json_decode(Request::curl_get($ip_info_url),true);
        $ip_info = json_decode($ip_info['data'],true);
        $ip_info = $ip_info['data'];
        return $ip_info;
    }
    public function write_file()
    {
        $data_cache = $this->data_cache_info();
        $file_obj = new File();
        $time1 = microtime(true);
        for($i=0;$i<8000;$i++){
            $file_obj->set('data'.$i,$data_cache);
        }
        $time2 = microtime(true);
        echo $time2-$time1;

    }
    public function write_redis()
    {
        $data_cache = $this->data_cache_info();
        $redis_obj = new Redis();
        $time1 = microtime(true);
        for($i=0;$i<8000;$i++){
            $redis_obj->set('data'.$i,$data_cache);
        }
        $time2 = microtime(true);
        echo $time2-$time1;
    }
    public function write_Memcached()
    {
        $data_cache = $this->data_cache_info();
        $Memcached_obj = new Redis();
        $time1 = microtime(true);
        for($i=0;$i<8000;$i++){
            $Memcached_obj->set('data'.$i,$data_cache);
        }
        $time2 = microtime(true);
        echo $time2-$time1;
    }
}