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

/**
 * [getArr description]
 * @Author   [NiuShao                   <camel_niu@163.com> <qq:370574131>]
 * @DateTime [2020-07-17T20:11:24+0800]
 * @param    [type]                     $arr                [description]
 * @param    [type]                     $index              [description]
 * @param    string                     $default            [description]
 * @return   [type]                                         [description]
 */
function getArr($arr,$index,$default="")
{
    return isset($arr[$index]) ? ( ($arr[$index] === "") ? $default : $arr[$index] ) : $default;
}
