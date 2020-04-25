<?php
namespace app\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Config;


class Test extends Command
{
    protected function configure()
    {
        $this->setName('Test')->setDescription('测试脚本');
    }
    protected function execute(Input $input, Output $output)
    {
        print_r(Config::get('database'));
    }
}