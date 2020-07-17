<?php
namespace app\tools\controller;

use GeoIp2\Database\Reader;

class IpLocation
{
    static private $path = [
        '20200714' => "public/static/GeoLite2/GeoLite2-City_20200714/GeoLite2-City.mmdb",
        '20180703' => "public/static/GeoLite2/GeoLite2-City_20180703/GeoLite2-City.mmdb",
    ];

    static private $logName = "ipLocation";

    static private $ipRegular = "/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";

    static private function getIpDataFile()
    {
        return ROOT_PATH.self::$path['20200714'];
    }

    static public function getIpLocation($ip = "")
    {

        try {

            $res = preg_match(self::$ipRegular,$ip);
            if(!$res){
                throw new \Exception("[Error] Ip Address (".$ip.")",-1);
            }

            $path = self::getIpDataFile();
            $reader = new Reader($path);
            $record = $reader->city($ip);

            $ipInfo = [
                'ip'               => $ip,
                'iso_code'         => $record->country->isoCode,
                'country_name'     => $record->country->name,
                'country_name_cn'  => $record->country->names['zh-CN'],
                'province'        => $record->mostSpecificSubdivision->name,
                'province_code'   => $record->mostSpecificSubdivision->isoCode,
                'city_name'        => $record->city->name,
                'postal_code'      => $record->postal->code,
                'latitude'         => $record->location->latitude,
                'longitude'        => $record->location->longitude,
                'network'          => $record->traits->network,
            ];
            return $ipInfo;

        } catch (\Exception $e) {
            $msg = $e->getMessage()." Database [".self::getIpDataFile()."]";
            WL($msg,self::$logName);
            return [];
        }


    }
}