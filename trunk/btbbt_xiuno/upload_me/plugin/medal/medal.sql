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