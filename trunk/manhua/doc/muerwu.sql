USE `muerwu`;

DROP TABLE IF EXISTS `god_novel`;

CREATE TABLE `god_novel` (
  `novelId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `novelName` varchar(50) NOT NULL COMMENT '小说明',
  `parentId` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父分类ID',
  `categoryId` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `categoryName` varchar(30) NOT NULL COMMENT '分类名',
  `author` varchar(30) NOT NULL COMMENT '作者',
  `picture` varchar(255) NOT NULL COMMENT '图片地址',
  `comment` varchar(255) NOT NULL COMMENT '简介',
  `views` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '查看数',
  `totals` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '总字数',
  `createdTime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `modifiedTime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`novelId`),
  KEY `categoryId` (`categoryId`),
  KEY `createdTime` (`createdTime`)
) ENGINE=MyISAM AUTO_INCREMENT=3153 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `god_novel_content`;

CREATE TABLE `god_novel_content` (
  `contentId` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `novelId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '小说ID',
  `chapterId` int (10)UNSIGNED  DEFAULT '0' NOT NULL  COMMENT '卷ID',
  `title` varchar(100) NOT NULL,
  `contentText` text NOT NULL COMMENT '内容',
  `views` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '查看数',
  `createdTime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`contentId`),
  KEY `novelId` (`novelId`)
) ENGINE=MyISAM AUTO_INCREMENT=19734 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `god_spider`;

CREATE TABLE `god_spider` (
  `spiderId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spiderName` varchar(100) NOT NULL COMMENT '项目名',
  `cutCode` char(8) NOT NULL COMMENT '?',
  `listRule` varchar(255) NOT NULL COMMENT '列表正则',
  `contentRule` varchar(255) NOT NULL,
  `normalUrl` varchar(255) NOT NULL COMMENT '?',
  `pageUrl` varchar(255) NOT NULL COMMENT '分页URL',
  `domain` varchar(100) NOT NULL COMMENT '域名',
  `startPage` tinyint(2) unsigned NOT NULL,
  `endPage` tinyint(2) unsigned NOT NULL,
  `stepPage` tinyint(2) unsigned NOT NULL,
  `comment` varchar(255) NOT NULL,
  `author` varchar(10) NOT NULL,
  `createdTime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `recordTotal` smallint(6) unsigned NOT NULL COMMENT '记录抓取数据总数',
  PRIMARY KEY (`spiderId`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `mh_categories`;

CREATE TABLE `mh_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '上级分类',
  `store_id` int(11) NOT NULL COMMENT '属于哪个版块的分类',
  `ismenu` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是不是导航',
  `cate_name` varchar(20) NOT NULL COMMENT '用户名',
  `menulink` varchar(50) NOT NULL,
  `content` text NOT NULL COMMENT '描述',
  `sort_order` smallint(6) NOT NULL COMMENT '分类排序',
  `time` int(8) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mh_member`;

CREATE TABLE `mh_member` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL,
  `password` char(32) NOT NULL,
  `groupid` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `god_novel_spider`;
CREATE TABLE `god_novel_spider` (       
	`novelId` int(10) unsigned NOT NULL,  
	`novelUrl` varchar(255) NOT NULL,     
	PRIMARY KEY (`novelId`)          
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 

DROP TABLE IF EXISTS `god_novel_chapter`;
CREATE TABLE `god_novel_chapter` (                       
`chapterId` int(10) unsigned NOT NULL AUTO_INCREMENT,  
`novelId` int(10) unsigned NOT NULL DEFAULT '0',       
`title` varchar(100) NOT NULL,                         
`comment` varchar(255) NOT NULL,                       
`createdTime` int(10) unsigned NOT NULL DEFAULT '0',   
PRIMARY KEY (`chapterId`)                              
) ENGINE=MyISAM DEFAULT CHARSET=utf8;