<?php
namespace app\visit\model;
use think\Model;
use think\Db;
use app\traits\logger\LoggerTrits;
class VisitModel extends Model
{
    use LoggerTrits;
    public static $limit_num = 50;
    public static function write_cache_db($data)
    {
        $res = Db::table('ns_visit_cache')->insert($data);
        if($res){
            return true;
        }else{
            return false;
        }
    }
    public static function sel_visit_cache()
    {
        $sql = "select k,data from ns_visit_cache where status='n' limit ".self::$limit_num;
        $visit_cache = Db::query($sql);
        return $visit_cache;
    }
    public static function trans_visit_db($data)
    {
        foreach($data as $key => $val){
            self::exec_trans_visit_db($val);
        }
    }
    public static function exec_trans_visit_db($data)
    {
        $ip_info = $data['ip_info'];
        Db::table('ns_visitor_log')->insert($ip_info);
        $where['k'] = $data['k'];
        $data_update['status'] = 'y';
        Db::table('ns_visit_cache')->where($where)->update($data_update);
    }
}
