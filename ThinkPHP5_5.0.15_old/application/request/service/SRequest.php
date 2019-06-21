<?php
namespace app\admin\model;
use think\Model;
class Common extends Model
{
    /**
    * 分支树显示无限分类
    * @param $arr 需要处理的数组
    * @param $key 相当于数组单元的id
    * @param $fkey 相当于数组单元的父类id
    * @param $num 从第几层查起，也就是要查层的父类id
    * @return $list 返回的数组
    */
    public function recursionTree($arr,$key,$fkey,$num)
    {
        $list = array();
        foreach($arr as $k => $v){
            if($num == $v[$fkey]){
                $tmp = $this->recursionTree($arr,$key,$fkey,$v[$key]);
                $tmp && $v['son'] = $tmp;
                $list[] = $v;
            }
        }
        return $list;
    }
    /**
    * 非树状显示无限分类,并输出分类路径
    * @param $arr 需要处理的数组
    * @param $key 相当于数组单元的id
    * @param $fkey 相当于数组单元的父类id
    * @param $num 从第几层查起，也就是要查层的父类id
    * @param $path 分类路径
    * @return $list 返回的数组
    */
    public function recursionNoTree($arr,$key,$fkey,$num,$path)
    {
        static $list = array();
        foreach($arr as $k => $v){
            if($num == $v[$fkey]){
                $v['class_path'] = $path;
                $list[] = $v;
                $this->recursionNoTree($arr,$key,$fkey,$v[$key],$path.",".$v[$key]);
            }
        }
        return $list;
    }
    /**
    * 给无线分类数组加前缀
    * @param $arr 需要处理的数组
    * @param $str 前缀符号
    * @param $path  path的隔断符号
    * @return $list 返回的数组
    */
    public function addStrForArr($arr)
    {
        foreach($arr as $key => $val){
            $num = substr_count($val['class_path'],',');
            $str = str_repeat('|--',$num);
            $arr[$key]['class_title'] = $str.$val['class_title'];
            unset($arr[$key]['class_path']);
        }
        return $arr;
    }
    /**
    * windows下兼容文件路径
    * @param $str 需要替换分隔符的路径
    * @return $list 返回标准化之后的路径
    */
    public function pathSign($str)
    { 
        if(strpos($str,'\\')){ 
            $str = str_replace('\\','/',$str);
            return $str;
        }else{ 
            return $str;
        }
    }
}