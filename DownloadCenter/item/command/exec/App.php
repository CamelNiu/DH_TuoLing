<?php
namespace command\exec;
use \core\coreClass\Common;
class App
{
    public $db;
    public function __construct()
    {
        $this->db = new \core\coreClass\DatabaseOperate(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    }
    public function run()
    {
        WL('[start...]','command_refresh_dir');

        try {
            //检测目录以及检测资源
            Common::checkSourceDir();
            //格式化入库数据
            $insertData = $this->getInsertData();
            //获取入库sql
            $insertSql = $this->getInsertSql($insertData);
            //入库
            $res = $this->insertData($insertSql);
            WL('[end...]','command_refresh_dir');
        } catch (\Exception $e) {
            $error['code'] = $e->getCode();
            $error['msg'] = $e->getMessage();
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
            $log_data = '[Error][Visit_execute]['.json_encode($error).']';
            WL($log_data,'command_refresh_dir');
            WL('[end...]','command_refresh_dir');
        }

    }
    public function insertData($sql)
    {
        $res = [];
        foreach($sql as $key => $val){
            $res[] = $this->db->getRows($val);
        }
        WL('[InsertData]['.json_encode($res).']','command_refresh_dir');

    }
    private function getInsertData()
    {
        $nameList = Common::cursionDirAllFile($nameList);
        $pathList = Common::cursionDirAllFile($pathList,RESOURCE_CENTER,2);
        $insertData = [];
        foreach($pathList as $key => $val){
            $insertData[$key]['name'] = $nameList[$key];
            $insertData[$key]['path'] = $val;
            $tmp = explode(RESOURCE_CENTER,$val);
            $insertData[$key]['curPath'] = $tmp[1];
        }
        return $insertData;
    }
    private function getInsertSql($insertData)
    {
        $insertData = array_chunk($insertData,200);
        $sqlPre = "insert into ns_download_center (`name`,`path`,`cur_path`) values ";
        $sqlArr = [];
        $sqlSux = " on duplicate key update `name`=values(`name`),`path`=values(`path`),`cur_path`=values(`cur_path`)";
        foreach($insertData as $key => $val){
            $sqlValue = "";
            foreach($val as $k => $v){
                $sqlValue .= "('".$v['name']."','".$v['path']."','".$v['curPath']."'),";
            }
            $sqlValue = trim($sqlValue,',');
            $sqlArr[$key] = $sqlPre.$sqlValue.$sqlSux;
        }
        return $sqlArr;
    }
}