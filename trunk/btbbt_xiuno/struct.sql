/*
SQLyog Enterprise - MySQL GUI v5.25
Host - 5.1.31-community : Database - xiuno
*********************************************************************
Server version : 5.1.31-community
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

USE `xiuno`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `bbs_attach` */

DROP TABLE IF EXISTS `bbs_attach`;

CREATE TABLE `bbs_attach` (
  `fid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `filesize` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `width` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `height` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `filename` char(120) NOT NULL DEFAULT '',
  `orgfilename` char(60) NOT NULL DEFAULT '',
  `filetype` char(7) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` char(100) NOT NULL DEFAULT '',
  `downloads` int(10) NOT NULL DEFAULT '0',
  `isimage` tinyint(1) NOT NULL DEFAULT '0',
  `golds` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `fidpid` (`fid`,`pid`),
  KEY `uid` (`uid`,`isimage`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_attach` */

LOCK TABLES `bbs_attach` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_attach_download` */

DROP TABLE IF EXISTS `bbs_attach_download`;

CREATE TABLE `bbs_attach_download` (
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `uploaduid` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `golds` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`aid`),
  KEY `uploaduid` (`uploaduid`,`dateline`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_attach_download` */

LOCK TABLES `bbs_attach_download` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_digest` */

DROP TABLE IF EXISTS `bbs_digest`;

CREATE TABLE `bbs_digest` (
  `digestid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` smallint(6) NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL,
  `cateid` tinyint(1) NOT NULL DEFAULT '0',
  `digest` tinyint(1) NOT NULL DEFAULT '0',
  `subject` char(80) NOT NULL DEFAULT '',
  `rank` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`digestid`),
  KEY `fid` (`fid`,`tid`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_digest` */

LOCK TABLES `bbs_digest` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_digestcate` */

DROP TABLE IF EXISTS `bbs_digestcate`;

CREATE TABLE `bbs_digestcate` (
  `cateid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(11) unsigned NOT NULL,
  `name` char(32) NOT NULL DEFAULT '0',
  `threads` int(1) NOT NULL DEFAULT '0',
  `rank` int(1) NOT NULL DEFAULT '0',
  `uid` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cateid`),
  KEY `rank` (`rank`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `bbs_digestcate` */

LOCK TABLES `bbs_digestcate` WRITE;

insert  into `bbs_digestcate`(`cateid`,`parentid`,`name`,`threads`,`rank`,`uid`) values (1,0,'精华分类一',0,1,1),(2,0,'精华分类二',0,2,1),(3,0,'精华分类三',0,3,1),(4,3,'精华分类3.1',0,4,1),(5,3,'精华分类3.3',0,5,1),(6,3,'精华分类3.3',0,6,1);

UNLOCK TABLES;

/*Table structure for table `bbs_follow` */

DROP TABLE IF EXISTS `bbs_follow`;

CREATE TABLE `bbs_follow` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fuid` int(11) unsigned NOT NULL DEFAULT '0',
  `direction` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`fuid`),
  KEY `uid` (`uid`),
  KEY `fuid` (`fuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_follow` */

LOCK TABLES `bbs_follow` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_forum` */

DROP TABLE IF EXISTS `bbs_forum`;

CREATE TABLE `bbs_forum` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fup` smallint(6) unsigned NOT NULL DEFAULT '0',
  `name` char(16) NOT NULL DEFAULT '',
  `rank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `threads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `digests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `todayposts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tops` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastpost` int(11) NOT NULL DEFAULT '0',
  `lasttid` int(11) NOT NULL DEFAULT '0',
  `lastsubject` char(32) NOT NULL DEFAULT '0',
  `lastuid` int(16) NOT NULL DEFAULT '0',
  `lastusername` char(16) NOT NULL DEFAULT '0',
  `brief` char(200) NOT NULL DEFAULT '',
  `rule` char(255) NOT NULL DEFAULT '',
  `icon` tinyint(4) NOT NULL DEFAULT '0',
  `accesson` tinyint(1) NOT NULL DEFAULT '0',
  `modids` char(47) NOT NULL DEFAULT '',
  `modnames` char(67) NOT NULL DEFAULT '',
  `toptids` char(240) NOT NULL DEFAULT '',
  `lastcachetime` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(11) NOT NULL DEFAULT '0',
  `listtype` tinyint(11) NOT NULL DEFAULT '0',
  `orderby` tinyint(11) NOT NULL DEFAULT '0',
  `indexforums` tinyint(11) NOT NULL DEFAULT '0',
  `seo_title` char(64) NOT NULL DEFAULT '',
  `seo_keywords` char(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`fid`),
  KEY `fup` (`fup`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `bbs_forum` */

LOCK TABLES `bbs_forum` WRITE;

insert  into `bbs_forum`(`fid`,`fup`,`name`,`rank`,`threads`,`posts`,`digests`,`todayposts`,`tops`,`lastpost`,`lasttid`,`lastsubject`,`lastuid`,`lastusername`,`brief`,`rule`,`icon`,`accesson`,`modids`,`modnames`,`toptids`,`lastcachetime`,`status`,`listtype`,`orderby`,`indexforums`,`seo_title`,`seo_keywords`) values (1,0,'默认分类',0,0,0,0,0,0,0,0,'',0,'','','',0,0,'','','',0,1,0,0,0,'',''),(2,1,'默认版块一',0,0,0,0,0,0,0,0,'',0,'','','',0,0,'','','',0,1,0,0,0,'',''),(3,1,'默认版块二',0,0,0,0,0,0,0,0,'',0,'','','',0,0,'','','',0,1,0,0,0,'',''),(4,0,'灌水区',0,0,0,0,0,0,0,0,'0',0,'0','','',0,0,'','','',0,1,0,0,0,'',''),(6,4,'浅水区',0,0,0,0,0,0,0,0,'0',0,'0','','',0,0,'','','',0,1,0,0,0,'',''),(7,4,'深水区',0,0,0,0,0,0,0,0,'0',0,'0','','',0,0,'','','',0,1,0,0,0,'','');

UNLOCK TABLES;

/*Table structure for table `bbs_forum_access` */

DROP TABLE IF EXISTS `bbs_forum_access`;

CREATE TABLE `bbs_forum_access` (
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `groupid` int(11) unsigned NOT NULL DEFAULT '0',
  `allowread` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowthread` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowpost` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowattach` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`,`groupid`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_forum_access` */

LOCK TABLES `bbs_forum_access` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_framework_count` */

DROP TABLE IF EXISTS `bbs_framework_count`;

CREATE TABLE `bbs_framework_count` (
  `name` char(32) NOT NULL DEFAULT '',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_framework_count` */

LOCK TABLES `bbs_framework_count` WRITE;

insert  into `bbs_framework_count`(`name`,`count`) values ('digestcate',6),('forum',6),('group',16),('post',0),('stat',2),('thread',0),('user',2);

UNLOCK TABLES;

/*Table structure for table `bbs_framework_maxid` */

DROP TABLE IF EXISTS `bbs_framework_maxid`;

CREATE TABLE `bbs_framework_maxid` (
  `name` char(32) NOT NULL DEFAULT '',
  `maxid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_framework_maxid` */

LOCK TABLES `bbs_framework_maxid` WRITE;

insert  into `bbs_framework_maxid`(`name`,`maxid`) values ('digestcate',6),('forum',7),('group',15),('user',2);

UNLOCK TABLES;

/*Table structure for table `bbs_friendlink` */

DROP TABLE IF EXISTS `bbs_friendlink`;

CREATE TABLE `bbs_friendlink` (
  `linkid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `rank` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sitename` char(16) NOT NULL DEFAULT '',
  `url` char(64) NOT NULL DEFAULT '',
  `logo` char(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`linkid`),
  KEY `type` (`type`,`rank`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `bbs_friendlink` */

LOCK TABLES `bbs_friendlink` WRITE;

insert  into `bbs_friendlink`(`linkid`,`type`,`rank`,`sitename`,`url`,`logo`) values (1,0,255,'Xiuno BBS','http://www.xiuno.com/','');

UNLOCK TABLES;

/*Table structure for table `bbs_group` */

DROP TABLE IF EXISTS `bbs_group`;

CREATE TABLE `bbs_group` (
  `groupid` smallint(6) unsigned NOT NULL,
  `name` char(20) NOT NULL DEFAULT '',
  `creditsfrom` int(10) NOT NULL DEFAULT '0',
  `creditsto` int(10) NOT NULL DEFAULT '0',
  `upfloors` int(10) NOT NULL DEFAULT '0',
  `color` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_group` */

LOCK TABLES `bbs_group` WRITE;

insert  into `bbs_group`(`groupid`,`name`,`creditsfrom`,`creditsto`,`upfloors`,`color`) values (0,'游客组',0,0,1,''),(1,'管理员组',0,0,0,''),(2,'超级版主组',0,0,0,''),(3,'大区版主组',0,0,0,''),(4,'版主组',0,0,0,''),(5,'VIP组',0,0,10,''),(6,'待验证用户组',0,0,0,''),(7,'禁止用户组',0,0,0,''),(11,'一级用户组',0,50,1,''),(12,'二级用户组',50,200,2,''),(13,'三级用户组',200,1000,3,''),(14,'四级用户组',1000,10000,4,''),(15,'五级级用户组',10000,10000000,5,'');

UNLOCK TABLES;

/*Table structure for table `bbs_kv` */

DROP TABLE IF EXISTS `bbs_kv`;

CREATE TABLE `bbs_kv` (
  `k` char(16) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  `expiry` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_kv` */

LOCK TABLES `bbs_kv` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_modlog` */

DROP TABLE IF EXISTS `bbs_modlog`;

CREATE TABLE `bbs_modlog` (
  `logid` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `username` char(16) NOT NULL DEFAULT '',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `pid` int(11) unsigned NOT NULL DEFAULT '0',
  `subject` char(32) NOT NULL DEFAULT '',
  `credits` int(11) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0',
  `action` char(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`logid`),
  KEY `uid` (`uid`,`logid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_modlog` */

LOCK TABLES `bbs_modlog` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_mypost` */

DROP TABLE IF EXISTS `bbs_mypost`;

CREATE TABLE `bbs_mypost` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `pid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`fid`,`pid`),
  KEY `uid` (`uid`,`fid`,`tid`),
  KEY `uid_2` (`uid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_mypost` */

LOCK TABLES `bbs_mypost` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_online` */

DROP TABLE IF EXISTS `bbs_online`;

CREATE TABLE `bbs_online` (
  `sid` char(16) NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `username` char(16) NOT NULL DEFAULT '',
  `ip` int(11) NOT NULL DEFAULT '0',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `url` char(100) NOT NULL DEFAULT '',
  `lastvisit` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `lastvisit` (`lastvisit`),
  KEY `uid` (`uid`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

/*Data for the table `bbs_online` */

LOCK TABLES `bbs_online` WRITE;

insert  into `bbs_online`(`sid`,`uid`,`username`,`ip`,`groupid`,`url`,`lastvisit`) values ('f528764d624db129',1,'admin',2130706433,1,'/admin/?log-login.htm',1343298264);

UNLOCK TABLES;

/*Table structure for table `bbs_pay` */

DROP TABLE IF EXISTS `bbs_pay`;

CREATE TABLE `bbs_pay` (
  `payid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `username` char(16) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `payamount` int(3) NOT NULL DEFAULT '0',
  `paytype` tinyint(3) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `alipay_email` char(60) NOT NULL DEFAULT '',
  `alipay_orderid` char(60) NOT NULL DEFAULT '',
  `alipay_fee` int(10) NOT NULL DEFAULT '0',
  `alipay_receive_name` char(10) NOT NULL DEFAULT '',
  `alipay_receive_phone` char(20) NOT NULL DEFAULT '',
  `alipay_receive_mobile` char(10) NOT NULL DEFAULT '',
  `ebank_orderid` char(64) NOT NULL DEFAULT '',
  `ebank_amount` mediumint(9) NOT NULL DEFAULT '0',
  `epay_amount` int(11) NOT NULL DEFAULT '0',
  `epay_orderid` char(64) NOT NULL DEFAULT '0',
  PRIMARY KEY (`payid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_pay` */

LOCK TABLES `bbs_pay` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_pm` */

DROP TABLE IF EXISTS `bbs_pm`;

CREATE TABLE `bbs_pm` (
  `pmid` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid1` int(11) unsigned NOT NULL DEFAULT '0',
  `uid2` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `page` int(11) unsigned NOT NULL DEFAULT '0',
  `username1` char(16) NOT NULL DEFAULT '',
  `username2` char(16) NOT NULL DEFAULT '',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0',
  `message` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pmid`),
  KEY `uid1` (`uid1`,`uid2`,`page`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_pm` */

LOCK TABLES `bbs_pm` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_pmcount` */

DROP TABLE IF EXISTS `bbs_pmcount`;

CREATE TABLE `bbs_pmcount` (
  `uid1` int(11) unsigned NOT NULL DEFAULT '0',
  `uid2` int(11) unsigned NOT NULL DEFAULT '0',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid1`,`uid2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_pmcount` */

LOCK TABLES `bbs_pmcount` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_pmnew` */

DROP TABLE IF EXISTS `bbs_pmnew`;

CREATE TABLE `bbs_pmnew` (
  `recvuid` int(11) unsigned NOT NULL DEFAULT '0',
  `senduid` int(11) unsigned NOT NULL DEFAULT '0',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`recvuid`,`senduid`),
  KEY `recvuid` (`recvuid`,`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_pmnew` */

LOCK TABLES `bbs_pmnew` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_post` */

DROP TABLE IF EXISTS `bbs_post`;

CREATE TABLE `bbs_post` (
  `fid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `userip` int(11) NOT NULL DEFAULT '0',
  `attachnum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `imagenum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `page` smallint(6) unsigned NOT NULL DEFAULT '0',
  `username` char(16) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` longtext NOT NULL,
  PRIMARY KEY (`fid`,`pid`),
  KEY `page` (`fid`,`tid`,`page`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_post` */

LOCK TABLES `bbs_post` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_runtime` */

DROP TABLE IF EXISTS `bbs_runtime`;

CREATE TABLE `bbs_runtime` (
  `k` char(16) NOT NULL DEFAULT '',
  `v` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`k`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

/*Data for the table `bbs_runtime` */

LOCK TABLES `bbs_runtime` WRITE;

insert  into `bbs_runtime`(`k`,`v`) values ('bbs','1|0|0|2|0|0|1343298564|1343318400|0||'),('cronlock','0');

UNLOCK TABLES;

/*Table structure for table `bbs_stat` */

DROP TABLE IF EXISTS `bbs_stat`;

CREATE TABLE `bbs_stat` (
  `year` int(11) unsigned NOT NULL DEFAULT '0',
  `month` int(11) unsigned NOT NULL DEFAULT '0',
  `day` int(11) unsigned NOT NULL DEFAULT '0',
  `threads` int(11) unsigned NOT NULL DEFAULT '0',
  `posts` int(11) unsigned NOT NULL DEFAULT '0',
  `users` int(11) unsigned NOT NULL DEFAULT '0',
  `newthreads` int(11) unsigned NOT NULL DEFAULT '0',
  `newposts` int(11) unsigned NOT NULL DEFAULT '0',
  `newusers` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`year`,`month`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_stat` */

LOCK TABLES `bbs_stat` WRITE;

insert  into `bbs_stat`(`year`,`month`,`day`,`threads`,`posts`,`users`,`newthreads`,`newposts`,`newusers`) values (2012,7,25,0,0,2,0,0,0),(2012,7,26,0,0,2,0,0,0);

UNLOCK TABLES;

/*Table structure for table `bbs_thread` */

DROP TABLE IF EXISTS `bbs_thread`;

CREATE TABLE `bbs_thread` (
  `fid` smallint(6) NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(16) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `subject` char(80) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `lastuid` int(10) unsigned NOT NULL DEFAULT '0',
  `lastusername` char(16) NOT NULL DEFAULT '',
  `floortime` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `digest` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `typename` char(16) NOT NULL DEFAULT '0',
  `cateids` char(47) NOT NULL DEFAULT '0',
  `catenames` char(67) NOT NULL DEFAULT '0',
  `attachnum` tinyint(3) NOT NULL DEFAULT '0',
  `imagenum` tinyint(3) NOT NULL DEFAULT '0',
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `firstpid` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `seo_keywords` char(64) NOT NULL DEFAULT '',
  `pids` char(240) NOT NULL DEFAULT '',
  `coverimg` char(64) NOT NULL DEFAULT '',
  `brief` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`fid`,`tid`),
  KEY `tid` (`tid`),
  KEY `typeid` (`typeid`),
  KEY `fid` (`fid`,`floortime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_thread` */

LOCK TABLES `bbs_thread` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_thread_type` */

DROP TABLE IF EXISTS `bbs_thread_type`;

CREATE TABLE `bbs_thread_type` (
  `typeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` smallint(6) NOT NULL DEFAULT '0',
  `typename` char(16) NOT NULL DEFAULT '',
  `rank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `bbs_thread_type` */

LOCK TABLES `bbs_thread_type` WRITE;

UNLOCK TABLES;

/*Table structure for table `bbs_user` */

DROP TABLE IF EXISTS `bbs_user`;

CREATE TABLE `bbs_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `regip` int(11) NOT NULL DEFAULT '0',
  `regdate` int(11) unsigned NOT NULL DEFAULT '0',
  `username` char(16) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `salt` char(8) NOT NULL DEFAULT '',
  `email` char(40) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `threads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `myposts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `digests` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `avatar` int(11) unsigned NOT NULL DEFAULT '0',
  `credits` int(11) unsigned NOT NULL DEFAULT '0',
  `golds` int(11) unsigned NOT NULL DEFAULT '0',
  `money` int(11) unsigned NOT NULL DEFAULT '0',
  `follows` smallint(3) unsigned NOT NULL DEFAULT '0',
  `followeds` int(11) unsigned NOT NULL DEFAULT '0',
  `newpms` int(11) unsigned NOT NULL DEFAULT '0',
  `newfeeds` int(11) NOT NULL DEFAULT '0',
  `homepage` char(40) NOT NULL DEFAULT '',
  `signature` char(32) NOT NULL DEFAULT '',
  `accesson` tinyint(1) NOT NULL DEFAULT '0',
  `lastactive` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `bbs_user` */

LOCK TABLES `bbs_user` WRITE;

insert  into `bbs_user`(`uid`,`regip`,`regdate`,`username`,`password`,`salt`,`email`,`groupid`,`threads`,`posts`,`myposts`,`digests`,`avatar`,`credits`,`golds`,`money`,`follows`,`followeds`,`newpms`,`newfeeds`,`homepage`,`signature`,`accesson`,`lastactive`) values (1,12345554,1343194648,'admin','d14be7f4d15d16de92b7e34e18d0d0f7','99adde','admin@admin.com',1,0,0,0,0,0,0,0,0,0,0,0,0,'','',0,1343298252);

UNLOCK TABLES;

/*Table structure for table `bbs_user_access` */

DROP TABLE IF EXISTS `bbs_user_access`;

CREATE TABLE `bbs_user_access` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `allowread` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowthread` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowpost` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowattach` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `expiry` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `bbs_user_access` */

LOCK TABLES `bbs_user_access` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
