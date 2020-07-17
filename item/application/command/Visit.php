<?php
namespace app\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Config;
use app\service\visitLog;
class Visit extends Command
{

    protected function configure()
    {
        $this->setName('Visit')->setDescription('整理访问日志');
    }

    protected function execute(Input $input, Output $output)
    {
        ( new visitLog )->run();
    }



}