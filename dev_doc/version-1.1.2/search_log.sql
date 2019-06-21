create table if not exists `ns_search_log`(
`id` int unsigned not null primary key auto_increment,
`search_type` enum("db","cache") not null,
`is_cache` enum("0","1") not null,
`search_time` varchar(255) not null,
`search_key` varchar(255) not null,
`created_at` datetime not null,
`res_count` int not null,
`res_content` varchar(255) not null
)engine=innodb default charset=utf8 comment='搜索日志表';