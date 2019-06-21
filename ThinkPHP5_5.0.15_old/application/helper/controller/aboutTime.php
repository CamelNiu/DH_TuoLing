<?php
namespace app\helper\controller;
use think\Controller;
class aboutTime extends controller
{
    public static $start = null;
    public static $end = null;
    public static function start()
    {
        $start = microtime();
        self::$start = $start;
        return $start;
    }
    public static function end()
    {
        $end = microtime();
        self::$end = $end;
        return $end;
    }
    public static micro_timestamp($data)
    {
        $data = explode(' ',$data);
        return $data[0];
    }
    public static timestamp($data)
    {
        $data = explode(" ",$data);
        return $data[1];
    }
    public static section()
    {
        $start = self::$start;
        $end = self::$end;
        $start_micro = self::micro_timestamp($start);
        $start_timestemp = self::timestamp($start);
        $end_micro = self::micro_timestamp($end);
        $end_timestamp = self::timestamp($end);
        $sec_micro = $end_micro-$start_micro;
        $sec = $end_timestamp-$start_timestemp;
        return $sec+$sec_micro;
    }
}