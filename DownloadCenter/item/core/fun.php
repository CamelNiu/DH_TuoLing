<?php

//全局日志记录方法,日志路径定死在runtime/log中
function WL($msg,$log_name)
{
    $path = LOG_PATH;
    is_dir($path) or mkdir($path,0777,true);
    $time = time();
    $day = date('Y-m-d',$time);
    $date_time = date('Y-m-d H:i:s',$time);
    $file = $path.$log_name.'-'.$day.'.log';
    $msg = '['.$date_time.'] '.$msg.PHP_EOL;
    file_put_contents($file,$msg,FILE_APPEND);
}