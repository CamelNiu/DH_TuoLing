<?php
namespace app\traits\logger;
trait LoggerTrits
{
    private $path = LOG_PATH;
    private $now_file;
    protected function logger_init($filename,$path='')
    {
        if(empty($filename)){
            echo '日志名称不能缺';
            return;
        }
        if(empty($path)){
            $path = $this->path.'self_Log/';
        }
        is_dir($path) or mkdir($path,0777,true);
        $day = date('Y-m-d');
        $file = $path.$filename.'_'.$day.'.log';
        if(!file_exists($file)){
            $first_line = '['.date('Y-m-d H:i:s').']['.$filename.']';
            file_put_contents($file,$first_line.PHP_EOL,FILE_APPEND);
        }
        $this->now_file = $file;
    }
    protected function info($msg)
    {
        file_put_contents($this->now_file,$this->log_format_msg($msg,'info'),FILE_APPEND);
    }
    protected function err($msg)
    {
        file_put_contents($this->now_file,$this->log_format_msg($msg,'err'),FILE_APPEND);
    }
    protected function log($msg)
    {
        file_put_contents($this->now_file,$this->log_format_msg($msg,'log'),FILE_APPEND);
    }
    protected function test($msg)
    {
        file_put_contents($this->now_file,$this->log_format_msg($msg,'test'),FILE_APPEND);
    }
    protected function log_format_date()
    {
        return "[".date('Y-m-d H:i:s')."]";
    }
    protected function log_format_msg($msg,$type)
    {
        return $this->log_format_date()."[".$type."] ".$msg.PHP_EOL;
    }
}