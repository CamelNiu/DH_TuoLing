<?php
namespace app\command\model;
use think\Db;
use think\Model;

class VisitModel extends Model
{
    public function get_visit_log_cache()
    {
        $sql = "select * from ns_visit_log_cache where status = 'n' limit 50";
        $res = Db::query($sql);
        return $res;
    }
    public function insert_log($ip_info){
        $data = $this->format_insert_data($ip_info);
        $res = false;
        try {
            Db::startTrans();
            Db::table('ns_visit_log')->insert($data);
            Db::table('ns_visit_log_cache')->where('id',$ip_info['id'])->update(['status'=>'y']);
            Db::commit();
            $res = true;
        } catch (\Exception $e) {
            Db::rollback();
            $error['code'] = $e->getCode();
            $error['msg'] = $e->getMessage();
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
            $log_data = '[Error][insert_log]['.json_encode($error).']';
            WL($log_data,'Visit_Command');
        }
        return $res;

    }
    private function format_insert_data($ip_info)
    {
        $data['ip'] = $ip_info['ip'];
        $data['url'] = $ip_info['url'];
        $data['country'] = $ip_info['ip_info']['country'];
        $data['province'] = $ip_info['ip_info']['region'];
        $data['city'] = $ip_info['ip_info']['city'];
        $data['time'] = date("Y-m-d H:i:s",$ip_info['time']);
        return $data;
    }
}