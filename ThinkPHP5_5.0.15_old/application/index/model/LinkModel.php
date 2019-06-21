<?php
namespace app\index\model;
use think\Model;
use think\Db;
use app\index\model\CommonModel;
class LinkModel extends CommonModel
{
    private $link_class_id=1;
    public function get_class_link_info()
    {
        try {
            $cache_info = CacheKey::get_cache_key('class_link_info');
            if( $this->cache_config['if_cache'] && $this->cache_config['if_redis'] && $this->Redis_obj->exists($cache_info['key']) ){
                $res = $this->redis_get($cache_info['key']);
            }else if( $this->cache_config['if_cache'] && $this->cache_config['if_file'] && $this->File_obj->has($cache_info['key']) ){
                $res = $this->File_obj->get($cache_info['key']);
            }else{
                $res = $this->get_class_link_data();
                //文件缓存
                $this->File_obj->set($cache_info['key'],$res,$cache_info['time']);
                //redis缓存
                $this->redis_set($cache_info['key'],$res,$cache_info['time']);
            }
            $code = 0;
            $msg = "ok";
        } catch (Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            $res = [];
        }
        return [$code,$msg,$res];
    }
    public function get_class_link_data()
    {
        $link_info = $this->get_link_clicknum(-1);
        list(,,$class_info) = $this->get_class_info();
        $link_class_info = array_filter($class_info,function($v){ return ($v['class_fid'] == $this->link_class_id)?true:false;});
        array_multisort(array_column($link_class_info,'class_oid'),SORT_DESC,$link_class_info);
        $link_info_class_group = [];
        foreach($link_info as $key => $val){
            $link_info_class_group[$val['class_id']][] = $val;
        }
        $class_link_info = array_map(function($v)use($link_info_class_group){
            $v['link_info'] = $link_info_class_group[$v['class_id']];
            return $v;
        },$link_class_info);
        return $class_link_info;

    }
}