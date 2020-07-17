<?php
namespace app\service;

use app\tools\controller\IpLocation;
use think\Db;
/**
 *
 */
class visitLog
{

    private $visitLogCache;
    private $visitLog;

    private $logName = "visitLog";
    private $logNameError = "visitLogException";

    function __construct()
    {
        $this->visitLogCache = new \app\model\visitLogCache;
        $this->visitLog = new \app\model\visitLog;
    }

    public function run()
    {

        $ifDebug = 1;

        if($ifDebug){

            $this->execVisit();

        }else{


            while (true) {
                try {

                    $this->execVisit();

                } catch (\Exception $e) {

                    $msg = $e->getMessage();
                    WL($msg,$this->logNameError);

                }
            }

        }





    }

    private function execVisit()
    {
        $vstLogCacheInfo = $this->visitLogCache->getList('data,keys',['status'=>'n']);

        if(empty($vstLogCacheInfo)){
            return;
        }

        $msg = "[info][execVisit][start...]";
        WL($msg,$this->logName);

        $time1 = microtime(true);

        foreach($vstLogCacheInfo as $val){
            $this->formatVisitInfo($val);
        }

        $time2 = microtime(true);

        $time = $time2-$time1;

        $msg = "[info][execVisit][end...][time:".$time."]";
        WL($msg,$this->logName);

    }



    /**
     * [formatVisitInfo description]
     * @Author   [NiuShao                   <camel_niu@163.com> <qq:370574131>]
     * @DateTime [2020-07-17T20:11:30+0800]
     * @param    [type]                     $visitInfo          [description]
     * @return   [type]                                         [description]
     */
    private function formatVisitInfo($visitCacheInfo)
    {
        $keys = $visitCacheInfo['keys'];
        $visitInfo = json_decode($visitCacheInfo['data'],true);
        //$visitInfo['ip'] = 'abc';
        $ipInfo = IpLocation::getIpLocation($visitInfo['ip']);

        if(empty($ipInfo)){
            $msg = "[info][formatVisitInfo][Empty ipInfo][".json_encode($visitCacheInfo)."]";
            WL($msg,$this->logName);

            $this->ipInfoEmpty($keys);

            return ;
        }

        $visitLog = [
            'ip'       => getArr($ipInfo,'ip','-'),
            'url'      => getArr($visitInfo,'url','-'),
            'country'  => getArr($ipInfo,'country_name','-'),
            'province' => getArr($ipInfo,'province',"-"),
            'city'     => getArr($ipInfo,'city_name','-'),
            'time'     => date("Y-m-d H:i:s",getArr($visitInfo,'time',0)),
        ];

        $this->insertVisitLog($keys,$visitLog);

    }

    /**
     * [ipInfoEmpty description]
     * @Author   [NiuShao                   <camel_niu@163.com> <qq:370574131>]
     * @DateTime [2020-07-17T21:33:47+0800]
     * @param    [type]                     $keys               [description]
     * @return   [type]                                         [description]
     */
    private function ipInfoEmpty($keys)
    {
        try {

            $res = $this->visitLogCache->where(['keys'=>$keys])->update(['status'=>'x']);
            $sql = Db::getLastSql();

            $msg = "[info][ipInfoEmpty][res:".$res."][sql:".$sql."]";
            WL($msg,$this->logName);

        } catch (\Exception $e) {


            $msg = $e->getMessage();
            WL($msg,$this->logNameError);

        }
    }

    /**
     * [insertVisitLog description]
     * @Author   [NiuShao                   <camel_niu@163.com> <qq:370574131>]
     * @DateTime [2020-07-17T21:06:39+0800]
     * @param    [type]                     $keys               [description]
     * @param    [type]                     $visitLog           [description]
     * @return   [type]                                         [description]
     */
    private function insertVisitLog($keys,$visitLog)
    {
        try {
            Db::startTrans();
            //修改cache为已操作
            $res['update'] = $this->visitLogCache->where(['keys'=>$keys])->update(['status'=>'y']);
            $res['insert'] = $this->visitLog->insert($visitLog);

            foreach($res as $val){
                if(!$val){
                    throw new \Exception("[Error][insertVisitLog] insert db error",-1);
                }
            }


            $msg = "[info][insertVisitLog][res:".json_encode($res)."]";
            WL($msg,$this->logName);

            Db::commit();

        } catch (\Exception $e) {

            Db::rollback();

            $msg = $e->getMessage();
            WL($msg,$this->logNameError);
        }


    }

}