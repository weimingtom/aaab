#2012.7.28
drop table if exists `bbs_medal`;
CREATE TABLE `bbs_medal` (                                                   
`medalId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '勋章ID',       
`medalName` varchar(30) NOT NULL COMMENT '勋章名称',                       
`description` varchar(255) NOT NULL COMMENT '勋章描述',                    
`picture` varchar(255) NOT NULL COMMENT '图片地址',                        
`receiveType` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',          
`seq` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',                      
`userId` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',                    
`createdTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',             
PRIMARY KEY (`medalId`)                                                    
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='勋章信息表';

drop table if exists `bbs_medal_user`;
CREATE TABLE `bbs_medal_user` (                                                 
	`medalUserId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '发放ID',      
	`medalId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '勋章ID',             
	`userName` char(30) NOT NULL COMMENT '用户名',                                
	`userId` int(10) NOT NULL DEFAULT '0' COMMENT '获取勋章用户ID',               
	`receiveType` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',             
	`expiredTime` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',                
	`isApply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否用户申请',             
	`fuserId` int(10) NOT NULL DEFAULT '0' COMMENT '发出者用户ID',                
	`createdTime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',                
	PRIMARY KEY (`medalUserId`)                                                   
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户所得勋章表';