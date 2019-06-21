/*访问记录统计表*/
create table if not exists `ns_visitor_log`(
`vst_id` int unsigned not null primary key auto_increment,
`vst_ip` varchar(16) not null,
`ip_country` varchar(32) not null,
`ip_province` varchar(32) not null,
`ip_city` varchar(32) not null,
`vst_at` int(11) not null
)engine=innodb default charset=utf8 comment='统计访问日志';

alter table `ns_visitor_log` add `vst_url` varchar(255) not null after `vst_ip`;
alter table `ns_visitor_log` add `vst_status` enum('1','0') not null default 1 comment '0:无效，1：有效';