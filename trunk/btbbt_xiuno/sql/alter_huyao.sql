#2012.7.28
drop table if exists `bbs_medal`;
CREATE TABLE `bbs_medal` (                                                   
`medalid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '勋章ID',       
`medalname` varchar(30) NOT NULL COMMENT '勋章名称',                       
`description` varchar(255) NOT NULL COMMENT '勋章描述',                    
`picture` varchar(255) NOT NULL COMMENT '图片地址',                        
`receivetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',          
`seq` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',                      
`uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',                    
`createdtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',             
PRIMARY KEY (`medalid`)                                                    
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='勋章信息表';

drop table if exists `bbs_medal_user`;
CREATE TABLE `bbs_medal_user` (                                                 
	`muid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '发放ID',      
	`medalid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '勋章ID',
	`uid` int(10) NOT NULL DEFAULT '0' COMMENT '获取勋章用户ID',            
	`username` char(30) NOT NULL COMMENT '用户名',                                
	`receivetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',             
	`expiredtime` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',                
	`isapply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否用户申请',             
	`fuid` int(10) NOT NULL DEFAULT '0' COMMENT '发出者用户ID',                
	`createdtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',                
	PRIMARY KEY (`muid`)                                                   
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户所得勋章表';