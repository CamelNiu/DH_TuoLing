<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------



if( ENV == 'dev' ){
    return [

        'view_replace_str' => [
            '__ADMIN_STYLE__'     => 'http://127.0.0.1:18080/DH_TuoLing/ThinkPHP5_5.0.15_old/public/static/AdminStyle',
            '__KINDEDITOR_PATH__' => 'http://127.0.0.1:18080/DH_TuoLing/ThinkPHP5_5.0.15_old/public/static/kindeditor',
            '__UEDITOR_PATH__'    => 'http://127.0.0.1:18080/DH_TuoLing/ThinkPHP5_5.0.15_old/public/static/ueditor1_4_3_3-utf8-php/utf8-php',
            '__HOMEPAGE_IMG__'    => 'http://127.0.0.1:18080/DH_TuoLing/ThinkPHP5_5.0.15_old/public/uploads/HomepageImg'
        ],
    ];
}