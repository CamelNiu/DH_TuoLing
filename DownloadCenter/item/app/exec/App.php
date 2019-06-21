<?php
namespace app\exec;
use core\coreClass\test;
class App
{
    private $db;
    public function __construct()
    {
         $this->db = new \core\coreClass\DatabaseOperate(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    }
    public function run()
    {
        WL('[start...]','download_page_log');
        if( is_file(VIEW_PATH.'/cache/cache.html') && (time()-filemtime(VIEW_PATH.'/cache/cache.html'))<18000 ){
            WL('[cachePage]','download_page_log');
            require_once VIEW_PATH.'/cache/cache.html';
        }else{
            WL('[refreshPage]','download_page_log');
            $sourceList = $this->getSource();
            ob_start();
            require_once VIEW_PATH.'download.html';
            file_put_contents(VIEW_PATH.'/cache/cache.html',ob_get_contents());
        }
        WL('[end...]','download_page_log');
    }
    /**
     * [getSource 获取数据库中的资源]
     * @Author   NiuShao
     * @DataTime 2019-04-05T12:09:57+0800
     * @return   [type]                   [description]
     */
    public function getSource()
    {
        $sql = "select name,cur_path,name_cn from ns_download_center where status = 1";
        $sourceList = $this->db->getAllArray($sql);
        $sourceList = array_map(function($v){
            $v['download_path'] = RESOURCE_DOMAIN.$v['cur_path'];
            return $v;
        },$sourceList);
        $sourceList = $this->formatSource($sourceList);
        return $sourceList;
    }
    /**
     * [formatSource 按照首字母排序]
     * @Author   NiuShao
     * @DataTime 2019-04-05T12:10:48+0800
     * @param    [type]                   $sourceList [description]
     * @return   [type]                               [description]
     */
    public function formatSource($sourceList)
    {
        $handlerList = array_column($sourceList,'name');
        $handlerList = array_map(function($v){
            return strtolower($v);
        },$handlerList);
        array_multisort($handlerList,SORT_ASC,$sourceList);
        return $sourceList;
    }

}