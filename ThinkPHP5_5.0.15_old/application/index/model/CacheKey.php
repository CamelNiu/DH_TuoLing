<?php
namespace app\index\model;
class CacheKey
{
    private static $cache_key = [
        'class_info'=>[
            'key'=>"class_info_key",
            'time'=>86400,
            "hash_key" =>"class_info_hash_key",
        ],
        'label_info'=>[
            'key'=>"label_info_key",
            'time'=>86400,
            "hash_key"=>"label_info_hash_key",
        ],
        'link_info'=>[
            'key'=>"link_info_key",
            'time'=>86400,
            "hash_key"=>"link_info_hash_key",
        ],
        'blog_info'=>[
            'key'=>"blog_info_key",
            'time'=>86400,
            "hash_key"=>"blog_info_hash_key",
        ],
        'class_link_info'=>[
            'key'=>'class_link_info_key',
            'hash_key'=>'class_link_info_hash_key',
            'time'=>86400,
        ],
        'class_label_info'=>[
            'key'=>'class_label_info',
            'hash_key'=>'class_label_info_hash_key',
            'time'=>86400
        ],
        'blog_label_info'=>[
            'key'=>'blog_label_info',
            'hash_key'=>'blog_label_info_hash_key',
            'time'=>86400
        ],
        'content_info'=>[
            'key'=>'content_info_key',
            'hash_key'=>'content_info_hash_key',
            'time'=>86400
        ]
    ];

    public static function get_cache_key($type_info = null)
    {
        if($type_info == null) return self::$cache_key;
        return self::$cache_key[$type_info];
    }

}


