<?php
namespace app\index\validate;
use \think\Validate;
class LinkValidate extends Validate
{
    protected $rule = [
        'url'     => 'require',
        'link_id' => 'require|number',
    ];
}