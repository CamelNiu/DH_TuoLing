<?php
namespace app\command\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\command\model\VisitModel;
use app\tools\controller\Curl;
use think\cache\driver\Redis;
use think\cache\driver\File;
class Visit extends Command
{
    private $taobao_ip = 'http://ip.taobao.com/service/getIpInfo.php?ip=';
    private $M_visit;
    private $T_curl;
    private $Redis_obj;
    private $File_obj;
    private $lock_key = "command_Visit";
    public function __construct()
    {
        parent::__construct();
        $this->M_visit = new VisitModel;
        $this->Redis_obj = new Redis;
        $this->File_obj = new File;
    }
    protected function configure()
    {
        $this->setName('Visit')->setDescription('访问日志，缓存翻译脚本');
    }

    protected function execute(Input $input, Output $output)
    {

        $this->lock();
        try {
            WL('start...','Visit_Command');
            //查询缓存数据
            $time1 = microtime(true);
            $cache_list = $this->format_visit_log_cache();
            $visit_info = $this->trans_ip($cache_list);
            $insert_data = $this->format_visit_info($visit_info);
            //入库
            $res = $this->insert_log($insert_data);
            $time5 = microtime(true);
            $total_time = $time5-$time1;
            WL('total_time ['.$total_time.']','Visit_Command');
            WL('end...','Visit_Command');
        } catch (\Exception $e) {
            $error['code'] = $e->getCode();
            $error['msg'] = $e->getMessage();
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
            $log_data = '[Error][Visit_execute]['.json_encode($error).']';
            WL($log_data,'Visit_Command');

        }
        $this->un_lock();
        return;
    }

    private function lock(){
        if($this->File_obj->has($this->lock_key)){
            var_dump("Visit脚本正在执行，不可重新开启");
            WL('[locked] Visit execute is locked,end','Visit_Command');
            die();
        }else{
            $this->File_obj->set($this->lock_key,1);
        }
    }
    private function un_lock()
    {
        $this->File_obj->rm($this->lock_key);
    }

    private function format_visit_log_cache()
    {
        $cache_list = $this->M_visit->get_visit_log_cache();
        if(count($cache_list) == 0){
            WL('Visit_log_cache_list id [0]','Visit_Command');
            $this->un_lock();
            die();
        }
        $cache_list = array_map(function($v){
            $tmp = json_decode($v['data'],true);
            unset($v['data']);
            $v = array_merge($v,$tmp);
            return $v;
        },$cache_list);
        //log
        $log_data = 'cache_list count ['.count($cache_list).']';
        WL($log_data,'Visit_Command');
        return $cache_list;
    }

    private function trans_ip($cache_list)
    {
        $time1 = microtime(true);
        $cache_list = array_map(function($v){
            sleep(1);
            $v['ip_info'] = $this->format_ip($v['ip']);
            return $v;
        },$cache_list);
        $time2 = microtime(true);
        $time = $time2-$time1;
        $log_data = 'curl ip time ['.$time.']';
        WL($log_data,'Visit_Command');
        return $cache_list;
    }

    private function format_ip($ip)
    {

        if( !isset($ip) || empty($ip) ) return [];
        $url = $this->taobao_ip.$ip;
        $res = json_decode(Curl::curl_get($url),true);
        if( isset($res['code']) && $res['code'] == 0 )return $res['data'];
        return [];
    }

    private function format_visit_info($visit_info)
    {
        $insert_data = [];
        foreach($visit_info as $val){
            if(!empty($val['ip_info'])){
                $insert_data[] = $val;
            }
        }
        $log_data1 = 'format_ip count ['.count($insert_data).']';
        WL($log_data1,'Visit_Command');
        return $insert_data;
    }

    private function insert_log($insert_data)
    {
        foreach($insert_data as $val){
            usleep(100);
            $res = $this->M_visit->insert_log($val);
        }
    }
}