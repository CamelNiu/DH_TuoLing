<?php
namespace app\index\model;

use think\Model;
use think\Db;

class CategoryModel extends CommonModel
{
    public function get_class_list_by_fid($fid = 0,$field = "*")
    {
        $sql = "SELECT
                        {$field}
                FROM
                        ns_class
                WHERE
                        class_fid = {$fid} and class_status=1
                ORDER BY
                        class_oid DESC";
        $res = Db::query($sql);
        return $res;
    }
}