<?php
namespace core\coreClass;
class Common
{
    /**
     * [checkSourceDir 检查下载中心目录是否存在,不存在则创建,且是否存在资源,如果不存在资源,马上中断]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2019-04-04T19:38:25+0800
     * @return   [type]                   [description]
     */
    public static function checkSourceDir()
    {
        if(!is_dir(RESOURCE_CENTER)){
            mkdir (RESOURCE_CENTER,0777,true);
        }
        $resource = scandir(RESOURCE_CENTER);
        if(count($resource) <= 2){
          die('当前没有资源,无需入库');
        }

    }
    /**
     * [cursionDir 递归目录，树状图显示]
     * @Author   NiuShao                  370574131@qq.com
     * @DateTime 2019-04-04T20:11:22+0800
     * @param    [type]                   $dir             [description]
     * @param    integer                  $type            [1不要前缀,2要前缀]
     * @return   [type]                                    [description]
     */
    public static function cursionDir($dir = RESOURCE_CENTER,$type=1)
    {
        $fileArray = [];
        $files = scandir($dir);
        foreach($files as $key => $val){
            if($val != '.' && $val != '..'){
                if(is_dir($dir.'/'.$val)){
                  $fileArray[$val] = self::cursionDir($dir.'/'.$val,$type);
                }else{
                  if($type == 1){
                    $fileArray[] = $val;
                  }elseif($type == 2){
                    $fileArray[] = $dir.'/'.$val;
                  }

                }
            }
        }
        return $fileArray;
    }
    /**
     * 便利目录中所有文件
     */
    public static function cursionDirAllFile(&$fileArray,$dir=RESOURCE_CENTER,$type=1)
    {
        $files = scandir($dir);
        foreach($files as $key => $val){
            if($val != '.' && $val != ".."){
                if(is_dir($dir.'/'.$val)){
                   self::cursionDirAllFile($fileArray,$dir.'/'.$val,$type);
                }else{
                    if($type == 1){
                        $fileArray[] = $val;
                    }elseif($type == 2){
                        $fileArray[] = $dir.'/'.$val;
                    }
                }
            }
        }
        return $fileArray;
    }
}