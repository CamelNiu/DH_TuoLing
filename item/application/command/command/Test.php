<?php
namespace app\command\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\tools\controller\Encrypt;
use think\Db;
class Test extends Command
{
    protected function configure()
    {
        $this->setName('Test')->setDescription('测试脚本');
    }
    protected function execute(Input $input, Output $output)
    {
        while (true) {
            $sql = "select count(*) from abandon_ns_visitor_log where ip_city='深圳'";
            $res = Db::query($sql);
        }
    }
}