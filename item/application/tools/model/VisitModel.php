<?php
namespace app\tools\model;

use think\Db;
use think\Model;

class VisitModel extends Model
{
    public function add_log_cache($data)
    {
        $res = Db::table('ns_visit_log_cache')->insert($data);
        return $res;
    }
}