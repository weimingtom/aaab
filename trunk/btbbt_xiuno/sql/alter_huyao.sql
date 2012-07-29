#2012.7.28
drop table if exists `hy_medal`;
CREATE TABLE `hy_medal` (                                                                                     
	`medalId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '勋章ID',                                        
	`medalName` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '勋章名称',                                     
	`description` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '勋章描述',                                  
	`picture` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '图片地址',                                      
	`receiveType` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',                                           
	`userId` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',                                                     
	`createdTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',  
	PRIMARY KEY (`medalId`)                                                                                     
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='勋章信息表';

drop table if exists `hy_user_medal`;
CREATE TABLE `hy_user_medal` (                                                                                     
	`userMedalId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '发放ID',                                        
	`medalId` int(10) unsigned NOT NULL default '0' COMMENT '勋章ID',                                    
	`userId` int(10) NOT NULL DEFAULT '0' COMMENT '获取勋章用户ID',
	`receiveType` tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取方式',
	`fuserId` int(10) NOT NULL DEFAULT '0' COMMENT '发出者用户ID',
	`createdTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',  
	PRIMARY KEY (`userMedalId`)                                                                                     
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户所得勋章表';