<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//全局日志记录方法,日志路径定死在runtime/log中
function WL($msg,$log_name)
{
    $path = dirname(dirname(__FILE__))."/runtime/log/WL/";
    is_dir($path) or mkdir($path,0777,true);
    $time = time();
    $day = date('Y-m-d',$time);
    $date_time = date('Y-m-d H:i:s',$time);
    $file = $path.$log_name.'-'.$day.'.log';
    $msg = '['.$date_time.'] '.$msg.PHP_EOL;
    file_put_contents($file,$msg,FILE_APPEND);
}