<?php
!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

// 改文件会被 include 执行。
if($this->conf['db']['type'] == 'mysql') {
	// 执行SQL语句
	$db = $this->user->db;
	$tablepre = $db->tablepre;
	$s = "
drop table if exists `{$tablepre}medal`;
CREATE TABLE `{$tablepre}medal` (                                                   
	`medalid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '勋章ID',      
	`medalname` varchar(30) NOT NULL COMMENT '勋章名称',                      
	`description` varchar(255) NOT NULL COMMENT '勋章描述',                   
	`picture` varchar(255) NOT NULL COMMENT '图片地址',                       
	`receivetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',         
	`autogrant` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动发放规则',       
	`seq` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',                     
	`isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',              
	`uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',                      
	`createdtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',            
	PRIMARY KEY (`medalid`),                                                  
KEY `seq` (`seq`)                  
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='勋章信息表';

drop table if exists `{$tablepre}medal_user`;
CREATE TABLE `{$tablepre}medal_user` (                                                 
	 `muid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '发放ID',             
	  `medalid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '勋章ID',             
	  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '获取勋章用户ID',                  
	  `username` char(30) NOT NULL COMMENT '用户名',                                
	  `receivetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',             
	  `autogrant` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动发放规则',           
	  `expiredtime` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',                
	  `isapply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否用户申请',             
	  `fuid` int(10) NOT NULL DEFAULT '0' COMMENT '发出者用户ID',                   
	  `createdtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',                
	  PRIMARY KEY (`muid`),                                                         
	  KEY `uid_medalid` (`uid`,`medalid`),                                          
	  KEY `uid_isapply_muid` (`uid`,`isapply`,`muid`),                                   
	  KEY `isapply_muid` (`isapply`,`muid`),                     
	  KEY `expiredtime` (`expiredtime`)                             
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='用户所得勋章表';

drop table if exists `{$tablepre}user_active`;
CREATE TABLE `{$tablepre}user_active` (                                                 
	`uid` int(10) unsigned NOT NULL COMMENT '用户ID',             
	`username` char(16) NOT NULL COMMENT '用户名',                                
	`threads` mediumint(8) NOT NULL DEFAULT '0' COMMENT '发帖数',                
	`posts` mediumint(8) NOT NULL DEFAULT '0' COMMENT '回帖数',                
	`myposts` mediumint(8) NOT NULL DEFAULT '0' COMMENT '',                
	`digests` mediumint(8) NOT NULL DEFAULT '0' COMMENT '精华帖子数',                
	`credits` mediumint(8) NOT NULL DEFAULT '0' COMMENT '积分',                
	`activetime` int(10) NOT NULL DEFAULT '0' COMMENT '活跃时间',                
	`createdtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
	PRIMARY KEY (`uid`)
	KEY `activetime` (`activetime`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='用户活跃表';
	";
	$sqlarr = explode(';', $s);
	foreach ($sqlarr as $sql) {
		$db->query($sql);
	}
	
	// 创建勋章上传目录
	$medal_upload_path = $this->conf['upload_path'].'/medal';
	if(!file_exists($medal_upload_path)) {
		mkdir($medal_upload_path, 0777);
	}
}
?>