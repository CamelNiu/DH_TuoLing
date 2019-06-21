/*访问日志临时表,存放访问者信息，定期脚本会通过淘宝链接分析插入访问日志详细表*/

CREATE TABLE if not exists `ns_visit_log_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keys` char(32) NOT NULL,
  `data` text NOT NULL,
  `status` enum('y','n') NOT NULL DEFAULT 'n',
  `type` enum('0','1') NOT NULL DEFAULT '0' COMMENT '访问类型，0正常，1不正常',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_keys` (`keys`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='访问统计临时表';

/*访问日志详情表,包含所有的访问*/

CREATE TABLE if not exists `ns_visit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `url` text NOT NULL,
  `country` varchar(32) NOT NULL,
  `province` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `time` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11393 DEFAULT CHARSET=utf8 COMMENT='统计访问日志';

/*非正常访问表*/
CREATE TABLE if not exists `ns_exception_visit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `url` text NOT NULL,
  `country` varchar(32) NOT NULL,
  `province` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `time` timestamp NOT NULL,
  `remark` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11393 DEFAULT CHARSET=utf8 COMMENT='非正常访问日志';
/*修改表*/

/*之前的日志表废弃  abandon  */
ALTER TABLE ns_visitor_log RENAME TO abandon_ns_visitor_log;
ALTER TABLE ns_visit_cache RENAME TO abandon_ns_visit_cache;