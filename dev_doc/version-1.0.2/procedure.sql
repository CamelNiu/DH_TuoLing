#dh_tuoling procedure

#1,方便查询访问时间以及日期
delimiter $$
create procedure vst_log()
    begin
        select vst_id,vst_ip,vst_url,ip_province,ip_city,from_unixtime(vst_at,'%Y-%m-%d %H:%i:%s') as vst_at from ns_visitor_log where vst_status=1;
    end;
$$
delimiter ;

#2
#delimiter $$
#create procedure vst_log_count()
#begin
#    declare total_vst int default "";
#    select count(*) into total_vst from ns_visitor_log where vst_status=1;
#    select total_vst;
#end;
#$$
#delimiter ;
#3
#随机输出顶级父类下子类的一个id
delimiter $$
    create function rand_sel_1_children_class_id(fid int) returns int
    reads sql data
        begin
            declare children_class_id int ;
            select class_id into children_class_id from ns_class where class_status = 1 and class_fid=fid order by rand() limit 1;
            return children_class_id;
        end;

$$
delimiter ;

#随机查询标签id
delimiter $$
    create function rand_label_id() returns int
    reads sql data
    begin
        declare rand_label_id int default 0;
        select label_id into rand_label_id from ns_label where label_status=1 order by rand() limit 1;
        return rand_label_id;
    end;
$$
delimiter ;
#随机输出篇幅类中文字符串
delimiter $$
    create function rand_chinese(strlen bigint) returns longtext character set utf8
    no sql
        begin
            declare rand_cn text character set utf8 default '欺感交甫之弃言兮怅犹豫而狐疑收和颜而静志兮申礼防以自持于是洛灵感焉徙倚彷徨神光离合乍阴乍阳竦轻躯以鹤立若将飞而未翔践椒涂之郁烈步蘅薄而流芳超长吟以永慕兮声哀厉而弥长尔乃众灵杂遝命俦啸侣或戏清流或翔神渚或采明珠或拾翠羽从南湘之二妃携汉滨之游女叹匏瓜之无匹兮咏牵牛之独处扬轻袿之猗靡兮翳修袖以延伫体迅飞凫飘忽若神凌波微步罗袜生尘动无常则若危若安进止难期若往若还转眄流精光润玉颜含辞未吐气若幽兰华容婀娜令我忘餐于是屏翳收风川后静波冯夷鸣鼓女娲清歌腾文鱼以警乘鸣玉鸾以偕逝六龙俨其齐首载云车之容裔鲸鲵踊而夹毂水禽翔而为卫于是越北沚过南冈纡素领回清阳动朱唇以徐言陈交接之大纲恨人神之道殊兮怨盛年之莫当抗罗袂以掩涕兮泪流襟之浪浪悼良会之永绝兮哀一逝而异乡无微情以效爱兮献江南之明珰虽潜处于太阴长寄心于君王忽不悟其所舍怅神宵而蔽光于是背下陵高足往神留遗情想像顾望怀愁冀灵体之复形御轻舟而上溯浮长川而忘返思绵绵而增慕夜耿耿而不寐沾繁霜而至曙命仆夫而就驾吾将归乎东路揽騑辔以抗策怅盘桓而不能去记曰植初求甄逸女不遂后太祖因与五官中郎将植昼思夜想废寝与食黄初中入朝帝示植甄后玉镂金带枕植见之不觉泣下时已为郭后谗死帝仍以枕赍植植还度轘辕息洛水上因思甄氏忽若有见遂述其事作感甄赋后明帝见之改为洛神赋燮按植在黄初猜嫌方剧安敢于帝前思甄泣下帝又何至以甄枕赐植此国章家典所无也若事因感甄而名托洛神间有之耳岂待明帝始改皆傅会者之过矣';
            declare i bigint default 0;
            declare resultstr longtext character set utf8 default '';
            while i<strlen do
                set resultstr = concat(substr(rand_cn,floor(rand()*length(rand_cn))+1,1),resultstr);
                set i = i+1;
            end while;
            return resultstr;
        end;
$$
delimiter ;

#查询ns_bloginfo最后一次插入的主键id
delimiter $$
    create function sel_maxid() returns int
    reads sql data
    begin
        declare max_id int;
        select bloginfo_id into max_id from ns_bloginfo order by bloginfo_id desc limit 1;
        return max_id;
    end;
$$
delimiter ;
#输出随机数字
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
#给blog,content,label,class,插入大数据
delimiter $$
    create procedure insert_bigdata_blog(total bigint)
    begin
        declare i int default 0;
        declare title varchar(255)  character set utf8 default "";
        declare descr varchar(255)  character set utf8 default "";
        declare img varchar(64) default 'InitializeImg/initialize.jpg';
        declare created int default 0;
        declare updated int default 0;
        declare click bigint default 0;
        declare content longtext  character set utf8 default "";
        declare new_bloginfo_id int default 0;
        declare class_id int default 0;
        declare label_id int default 0;
        declare a int default 0;
        declare b int default 0;
        while(i<total) do
            begin
                set i = i+1;
                set title = rand_chinese(18);
                set descr = rand_chinese(180);
                set created = unix_timestamp();
                set updated = unix_timestamp();
                set click = rand_num(rand_num(0));
                set class_id = rand_sel_1_children_class_id(2);
                insert into ns_bloginfo(`bloginfo_title`,`bloginfo_describe`,`bloginfo_img`,`bloginfo_createtime`,`bloginfo_updatetime`,`class_id`,`bloginfo_click`,`bloginfo_oid`) values(title,descr,img,created,updated,class_id,click,0);
                set new_bloginfo_id = sel_maxid();
                set content = rand_chinese(50000);
                insert into ns_blogcontent(`bloginfo_id`,`blogcontent_ctt`) values (new_bloginfo_id,content);
                set b = rand_num(0);
                while(b>a)do
                    begin
                        set label_id = rand_label_id();
                        set a = a+1;
                        insert into ns_label_blog(`label_id`,`bloginfo_id`) values(label_id,new_bloginfo_id);
                    end;
                end while;
            end;
        end while;
    end;
$$
delimiter ;



































