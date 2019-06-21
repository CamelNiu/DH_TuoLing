<?php
namespace app\index\model;
use think\Model;
use think\Db;
class LinkModel extends CommonModel
{
    public function get_link_clicknum($limit = 20)
    {
        $sql = "SELECT
                        link_id,link_title,link_url
                FROM
                        ns_link
                WHERE
                        link_status = 1
                ORDER BY
                        link_clicknum DESC
                LIMIT {$limit}";
        $res = Db::query($sql);
        return $res;
    }
    public function get_link_info($field = "*")
    {
        $sql = "SELECT
                        {$field}
                FROM
                        ns_link
                WHERE
                        link_status = 1
                ORDER BY
                        link_clicknum DESC";
        $res = Db::query($sql);
        return $res;
    }
    public function set_click_num($id)
    {
        $sql = "UPDATE
                    ns_link
                SET
                    link_clicknum = link_clicknum+1
                WHERE
                    link_id = {$id}";
        $res = Db::execute($sql);
        return $res;
    }
}