<?php
namespace app\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Config;


class Visit extends Command
{

    protected function configure()
    {
        $this->setName('Visit')->setDescription('整理访问日志');
    }
    protected function execute(Input $input, Output $output)
    {
        $visitCache = Db::query(' select data from ns_visit_log_cache where status="n" ');
        foreach($visitCache as $val){
            $this->execVisitCache($visitInfo);
        }
    }

    protected function execVisitCache($visitInfo)
    {

    }

}