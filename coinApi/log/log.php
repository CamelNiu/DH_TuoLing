<?php

class log
{
    static public function WL($msg,$log_name)
    {
        $path = __ROOT_PATH__."/runtime/log/WL/";
        is_dir($path) or mkdir($path,0777,true);
        $time = time();
        $day = date('Y-m-d',$time);
        $date_time = date('Y-m-d H:i:s',$time);
        $file = $path.$log_name.'-'.$day.'.log';
        $msg = '['.$date_time.'] '.$msg.PHP_EOL;
        file_put_contents($file,$msg,FILE_APPEND);
    }

}