<?php
namespace app\index\model;
use think\Model;
use think\Db;
use app\index\model\CommonModel;
class LabelModel extends CommonModel
{
    public function get_label_list_by_class($class_id)
    {
        list(,,$class_label_info) = $this->get_class_label_info();
        $cur_class_label_key = $class_label_info['class_label_key'][$class_id];
        list(,,$label_info) = $this->get_label_info();
        $cur_label_info = array_map(function($v)use($label_info){
            $v = $label_info[$v];
            return $v;
        },$cur_class_label_key['label_id']);
        return $cur_label_info;
    }

}