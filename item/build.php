<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // // 生成应用公共文件
    // '__file__' => ['common.php', 'config.php', 'database.php'],

    // // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    // 'index'     => [
    //     '__file__'   => ['common.php','config.php',],
    //     '__dir__'    => ['controller', 'model', 'view'],
    //     'controller' => ['Index','Blog','Link','Category','Common','Label'],
    //     'model'      => ['IndexModel','BlogModel','LinkModel','CategoryModel'],
    // ]
        'tools' => [
            '__dir__'    => ['controller', 'model'],
            'controller' => ['Visit','Curl','Encrypt'],
            'model' => ['VisitModel'],
        ],

        'command'     => [
            '__file__'   => ['common.php','config.php',],
            '__dir__'    => ['command', 'model'],
            'command' => ['Visit','Test'],
            'model'      => ['VisitModel'],
        ],
        'index' => [
            '__file__'   => ['common.php','config.php',],
            '__dir__'    => ['controller', 'model', 'view','validate'],
            'validate'   => ['LinkValidate'],
        ]
];
