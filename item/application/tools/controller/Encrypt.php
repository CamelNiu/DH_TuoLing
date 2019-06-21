<?php
namespace app\tools\controller;

class Encrypt
{
    public static $param_key = "dh_tuoling_param";
    //当前时间，当前key。传入的数据
    //加密方式 md5和base_64
    //当前key的md5.参数json.参数json的base64.参数json的base64的md5,然后总共的base64;
    public static function encryption($data)
    {
        if(!is_array($data)){
          throw new  \Exception("传参必须为数组", 100004);
        }
        $param['data'] = $data;
        $param['key'] = self::$param_key;
        $md51 = md5(self::$param_key);
        $param_str = base64_encode(json_encode($param));
        $md52 = md5($param_str);
        $param_url = base64_encode($md51.$param_str.$md52);
        return $param_url;

    }
    public static function un_encryption($data)
    {
        if(empty($data)) throw new  \Exception("待解析url参数为空", 100000);
        $md51_local = md5(self::$param_key);
        $param_url = base64_decode($data);
        $md51_data = substr($param_url,0,32);
        if($md51_local !== $md51_data){
           throw new  \Exception("参数验签和本地验签不一致", 100001);
        }
        $len = strlen($param_url);
        $data_left = substr($param_url,0,$len-32);
        $data_middle = substr($data_left,32);
        $data_param = base64_decode($data_middle);
        $data_param = json_decode($data_param,true);
        $data_encryption = self::encryption($data_param['data']);
        if( $data !== $data_encryption ){
            throw new   \Exception("接口密钥不一致", 100002);
        }
        return $data_param['data'];
    }
}