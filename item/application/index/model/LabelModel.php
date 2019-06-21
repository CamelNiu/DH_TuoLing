<?php
namespace app\index\model;
use think\Db;
use think\Model;

class LabelModel extends CommonModel
{
    public function get_label_list($field = "*")
    {
        $sql = "SELECT
                        {$field}
                FROM
                        ns_label
                WHERE
                        label_status = 1
                ORDER BY
                        label_oid DESC";
        $res = Db::query($sql);
        return $res;
    }
}