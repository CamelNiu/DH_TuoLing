select nl.class_id,class_title,class_oid,link_id,link_title,link_url,link_createtime
from ns_link nl
left join ns_class ns
on nl.class_id = ns.class_id
order by class_oid;



select class_id
from ns_class
where class_fid = 5 and class_status = 1
order by class_oid desc;


select
*
from ns_link
where class_id in ( select class_id
                    from ns_class
                    where class_fid = 5 and class_status = 1
                    order by class_oid desc) order by class_id desc;


select nc.class_title,count(nl.link_id) from ns_link as nl left join ns_class as nc on nc.class_id = nl.class_id group by nc.class_id;

select nc.class_title,count(nl.link_id) from ns_class as nc left join ns_link as nl on nl.class_id = nc.class_id group by nc.class_id;








select class_id from ns_link where link_id=26;
select class_fid from ns_class where class_id = (select class_id from ns_link where link_id=26);
select class_id,class_title from ns_class where class_fid = (select class_fid from ns_class where class_id = (select class_id from ns_link where link_id=26));

city(城市表)
    cidy_id
    city_name

user(用户表)
    city_id
    user_id
    user_name
    user_status

查询含有会员的城市
    select city_id from user where user_status = 1 group by city_id;
                或者
    select distinct(city_id) from user where user_status = 1;


最后的结果(排除有会员的城市,就是完全没有会员的城市)
    select city_name from city where city_id not in ( select city_id from user where user_status = 1 group by city_id )
                或者
    select city_name from city where city_id not in ( select distinct(city_id) from user where user_status = 1 )



create table if not exists `ns_bloglink`(
`bloginfo_id` int unsigned not null,
`bloglink_url` varchar(128) not null
)engine=innodb default charset=utf8 comment="博客链接其他文章表";


| ns_bloginfo | CREATE TABLE `ns_bloginfo` (
  `bloginfo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bloginfo_oid` tinyint(3) unsigned NOT NULL,
  `bloginfo_title` varchar(64) NOT NULL,
  `bloginfo_describe` varchar(255) NOT NULL,
  `bloginfo_img` varchar(128) NOT NULL,
  `bloginfo_status` tinyint(4) NOT NULL DEFAULT '1',
  `bloginfo_createtime` int(11) NOT NULL,
  `bloginfo_updatetime` int(11) NOT NULL,
  `class_id` tinyint(3) unsigned NOT NULL,
  `bloginfo_like` int(10) unsigned NOT NULL DEFAULT '0',
  `bloginfo_hate` int(10) unsigned NOT NULL DEFAULT '0',
  `bloginfo_click` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bloginfo_id`),
  UNIQUE KEY `bloginfo_title` (`bloginfo_title`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='博客信息表'      |


| ns_blogcontent | CREATE TABLE `ns_blogcontent` (
  `blogcontent_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bloginfo_id` int(10) unsigned NOT NULL,
  `blogcontent_ctt` mediumtext NOT NULL,
  PRIMARY KEY (`blogcontent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='博客内容表'      |
#输出任意字数中文
delimiter $$
    create function rand_chinese(strlen bigint) returns longtext
    no sql
    begin
        declare rand_CN text DEFAULT '欺感交甫之弃言兮怅犹豫而狐疑收和颜而静志兮申礼防以自持于是洛灵感焉徙倚彷徨神光离合乍阴乍阳竦轻躯以鹤立若将飞而未翔践椒涂之郁烈步蘅薄而流芳超长吟以永慕兮声哀厉而弥长尔乃众灵杂遝tà命俦chóu啸侣或戏清流或翔神渚或采明珠或拾翠羽从南湘之二妃携汉滨之游女叹匏瓜之无匹兮咏牵牛之独处扬轻袿guī之猗靡yīmí兮翳yì修袖以延伫体迅飞凫飘忽若神凌波微步罗袜生尘动无常则若危若安进止难期若往若还转眄流精光润玉颜含辞未吐气若幽兰华容婀娜令我忘餐于是屏翳收风川后静波冯夷鸣鼓女娲清歌腾文鱼以警乘鸣玉鸾以偕逝六龙俨其齐首载云车之容裔鲸鲵ní踊而夹毂gǔ水禽翔而为卫于是越北沚过南冈纡素领回清阳动朱唇以徐言陈交接之大纲恨人神之道殊兮怨盛年之莫当抗罗袂以掩涕兮泪流襟之浪浪悼良会之永绝兮哀一逝而异乡无微情以效爱兮献江南之明珰虽潜处于太阴长寄心于君王忽不悟其所舍怅神宵而蔽光于是背下陵高足往神留遗情想像顾望怀愁冀灵体之复形御轻舟而上溯浮长川而忘返思绵绵而增慕夜耿耿而不寐沾繁霜而至曙命仆夫而就驾吾将归乎东路揽騑fēi辔pèi以抗策怅盘桓而不能去　记曰植初求甄逸女不遂后太祖因与五官中郎将植昼思夜想废寝与食黄初中入朝帝示植甄后玉镂金带枕植见之不觉泣下时已为郭后谗死帝仍以枕赍植植还度轘辕息洛水上因思甄氏忽若有见遂述其事作感甄赋后明帝见之改为洛神赋燮按植在黄初猜嫌方剧安敢于帝前思甄泣下帝又何至以甄枕赐植此国章家典所无也若事因感甄而名托洛神间有之耳岂待明帝始改皆傅会者之过矣';
        declare i bigint default 0;
        declare resultstr longtext default "";
        while i<strlen do
        set resultstr = concat(substr(rand_CN,floor(rand()*length(rand_CN))+1,1),resultstr);
        set i = i+1;
        end while;
        return resultstr;
    end;
$$
delimiter ;
#获取随机数字
#获取随机数字：
    delimiter $$
    create function rand_num(strlen bigint) RETURNS longtext
    no SQL
    begin
        declare randStr varchar(11) DEFAULT '0123456789';
        declare i bigINT DEFAULT 0;
        declare resultStr longtext default "";
        repeat
        begin
            set resultStr = CONCAT(SUBSTR(randStr,FLOOR(RAND()*LENGTH(randStr))+1,1),resultStr);
            set i = i+1;
        end;
        until i>strlen
        end repeat;
        RETURN resultStr;
    end;
    $$
    delimiter ;
#查询最大的id
    delimiter $$
        create function sel_maxid() returns int
        reads sql data
        begin
            declare max_id int default 0;
            select bloginfo_id into max_id from ns_bloginfo order by bloginfo_id desc limit 1;
            return max_id;
        end;
    $$
    delimiter ;
#存储过程
delimiter $$
    create procedure insert_blog(j bigint,class_id int)
    begin
        declare i int default 0;
        declare orde int default 0;
        declare title varchar(255) default "";
        declare descr varchar(255) default "";
        declare img varchar(128) default 'InitializeImg/initialize.jpg';
        declare stat int default 1;
        declare createt int default 0;
        declare updatet int default 0;
        declare click bigint default 0;
        declare content longtext default "";
        declare bloginfo_id int default 0;
        while(i<j) do
            begin
                set i = i+1;
                set title = rand_chinese(28);
                set descr = rand_chinese(270);
                set createt = unix_timestamp();
                set updatet = unix_timestamp();
                set click = rand_num(rand_num(0));
                insert into ns_bloginfo(`bloginfo_oid`,`bloginfo_title`,`bloginfo_describe`,`bloginfo_img`,`bloginfo_status`,`bloginfo_createtime`,`bloginfo_updatetime`,`class_id`,`bloginfo_like`,`bloginfo_hate`,`bloginfo_click`) values(orde,title,descr,img,stat,createt,updatet,class_id,5,2,click);
                set bloginfo_id = sel_maxid();
                set content = rand_chinese(10000);
                insert into ns_blogcontent(`bloginfo_id`,`blogcontent_ctt`) values (bloginfo_id,content);
            end;
        end while;
    end;
$$
delimiter ;







update t_product_price set currency_id = 0 where product_id in (select product_id from t_product where product_model='AD977BNZ');




CREATE TABLE IF NOT EXISTS `ns_class_label`(
`class_id` int unsigned NOT NULL,
`label_id` int unsigned NOT NULL
)ENGINE=MYISAM DEFAULT CHARSET=UTF8 COMMENT="标签和分类链接表";



select bloginfo_title,label_title from ns_bloginfo as nb
left join ns_label_blog as nlb on nb.bloginfo_id = nlb.bloginfo_id
left join ns_label as nl on nlb.label_id = nl.label_id;


create table `ns_admin`(
    `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT primary key,
    `admin_name` varchar(32) NOT NULL,
    `admin_password` varchar(32) NOT NULL
)engine=innodb default charset =utf8 comment = '管理员表';

insert into ns_admin (admin_name,admin_password) values('niushao',md5('niushao'));