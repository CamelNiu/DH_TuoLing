/*个人博客数据库架构*/
/*分类表-无限分类，包涵一级分类和二级分类*/
CREATE TABLE IF NOT EXISTS `ns_class`(
    `class_id` tinyint unsigned not null auto_increment primary key,
    `class_fid` tinyint unsigned not null comment "父类id",
    `class_oid` tinyint unsigned not null comment '排序id',
    `class_title` varchar(32) not null comment '标题',
    `class_Etitle`  varchar(64) not null comment '英文标题',
    `class_describe` varchar(255) not null comment "描述,备注",
    `class_status` tinyint not null default 1 comment '状态0:禁用，1:启用',
    `class_createtime` int(11) not null comment "创建时间",
    `class_updatetime` int(11) not null comment "修改时间",
    `class_path`   varchar(16) not null comment "分类路径"
)engine = myisam default charset=utf8 comment "分类表";
/*博客内容表*/
CREATE TABLE IF NOT EXISTS `ns_bloginfo`(
    `bloginfo_id` int unsigned not null auto_increment primary key,
    `bloginfo_oid` tinyint unsigned not null,
    `bloginfo_title` varchar(64) not null,
    `bloginfo_describe` varchar(255) not null,
    `bloginfo_img` varchar(128) not null,
    `bloginfo_status` tinyint not null default 1,
    `bloginfo_createtime` int(11) not null,
    `bloginfo_updatetime` int(11) not null,
    `class_id` tinyint unsigned not null,
    `label_id` tinyint unsigned not null
)engine = myisam default charset=utf8 comment "博客信息表";
/*博客内容表*/
CREATE TABLE IF NOT EXISTS `ns_blogcontent`(
    `blogcontent_id` int unsigned not null auto_increment primary key,
    `bloginfo_id` int unsigned not null,
    `blogcontent_ctt` MEDIUMTEXT not null
)engine = innodb default charset=utf8 comment "博客内容表";
/*标签表*/
CREATE TABLE `ns_label` (
  `label_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label_oid` int(11) NOT NULL,
  `label_title` varchar(64) NOT NULL,
  `label_status` tinyint(4) NOT NULL DEFAULT '1',
  `label_createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`label_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='标签表';
/*标签和博客的链接表*/
CREATE TABLE IF NOT EXISTS `ns_label_blog`(
    `label_id` int unsigned not null,
    `bloginfo_id` int unsigned not null
)ENGINE=myisam AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='标签和博客的链接表';
/*链接表*/
CREATE TABLE IF NOT EXISTS `ns_link`(
    `link_id` int unsigned not null auto_increment primary key,
    `link_title` varchar(64) not null,
    `link_url` varchar(128) not null,
    `link_status` tinyint not null default 1,
    `link_createtime` int(11) not null comment "创建时间"
)engine = innodb default charset=utf8 comment "链接表";
/*管理员表*/
CREATE TABLE `ns_admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(32) NOT NULL,
  `admin_password` varchar(32) NOT NULL,
  `admin_sex` tinyint(4) NOT NULL COMMENT '0:women 1,men',
  `admin_image` varchar(128) NOT NULL,
  `admin_describe` varchar(255) NOT NULL,
  `admin_email` varchar(64) NOT NULL,
  `admin_tel` bigint(11) NOT NULL,
  `admin_section` tinyint(4) NOT NULL COMMENT '用户所属组',
  `admin_createtime` int(10) NOT NULL,
  `admin_updatetime` int(10) NOT NULL,
  `admin_createadminid` tinyint(4) NOT NULL,
  `admin_status` tinyint(4) NOT NULL DEFAULT '1',
  `user_lastlogin` bigint(11) NOT NULL COMMENT '上次登录时间',
  `user_lastlayout` bigint(11) NOT NULL COMMENT '上次下线时间',
  `user_logincnt` int(10) unsigned NOT NULL COMMENT '总共登录次数',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';
/*首页轮播图表格*/
CREATE TABLE IF NOT EXISTS `ns_homepage`( 
    `homepage_id` int unsigned NOT null auto_increment primary key,
    `homepage_oid` tinyint unsigned not null,
    `homepage_title` varchar(64) not null,
    `homepage_url` varchar(128) not null,
    `homepage_text` text not null,
    `homepage_image` varchar(128) not null,
    `homepage_createtime` int(10) not null,
    `homepage_status` tinyint not null default 1
)engine=InnoDB default charset=utf8 comment="首页三个展示栏目管理表";