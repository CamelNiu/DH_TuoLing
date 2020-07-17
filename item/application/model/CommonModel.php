<?php
namespace app\model;
use think\Db;
use think\Model;
class CommonModel extends Model
{
    /**
     * [getList 获取数据揭露]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2019-07-17T20:29:00+0800
     * @param    [type]                   $where           [description]
     * @param    [type]                   $other           [description]
     * @param    string                   $field           [description]
     * @return   [type]                                    [description]
     */
    public function getList($field='*',$where=[],$other=[])
    {
        $obj = $this->field($field)->where($where);
        isset($other['limit']) && $obj->limit($other['limit']);//分组
        isset($other['group']) && $obj->group($other['group']);//分组
        isset($other['order']) && $obj->order($other['order']);//排序\
        $list = $obj->select();
        if (empty($list)) return [];
        return $list->toArray();
    }

    /**
     * [getInfo 获取一条数据]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2019-07-17T20:30:01+0800
     * @param    [type]                   $where           [description]
     * @param    string                   $field           [description]
     * @return   [type]                                    [description]
     */
    public function getInfo($where,$field="*")
    {
        $infoObj = $this
        ->field($field)
        ->where($where)
        ->find();
        return empty($infoObj)?[]:$infoObj->toArray();
    }

    /**
     * [getCount description]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2019-07-24T15:49:05+0800
     * @param    [type]                   $where           [description]
     * @return   [type]                                    [description]
     */
    public function getCount($where)
    {
        if (empty($where)) return 0;
        $count = $this->where($where)->count();
        if (empty($count)) return 0;
        return $count;
    }

    /**
     * [getOneColumn 获取数据表某一个字段]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2019-08-16T12:04:28+0800
     * @param    [type]                   $where           [description]
     * @param    [type]                   $field           [description]
     * @return   [type]                                    [description]
     */
    public function getOneColumn($where,$field,$default="")
    {
        $res = $this->getInfo($where,$field);
        $column = getArr($res,$field,$default);
        return $column;
    }

    /**
     * [updateToData description]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2020-02-24T15:33:19+0800
     * @param    [type]                   $where           [description]
     * @param    [type]                   $updateArr       [description]
     * @return   [type]                                    [description]
     */
    public static function updateToData($where, $updateArr)
    {
        if (empty($where) || empty($updateArr)) return false;
        return self::where($where)->update($updateArr);
    }


    /**
     * [insertDataGetId 插入数据获取自增id]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2019-09-07T17:02:53+0800
     * @return   [type]                   [description]
     */
    public static function insertDataGetId($data)
    {
            $res = self::create($data);
            if(!$res) return false;
            $id = $res->id;
            return $id;
    }
}