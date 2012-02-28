/*
SQLyog Enterprise - MySQL GUI v5.25
Host - 5.1.31-community : Database - mydecms
*********************************************************************
Server version : 5.1.31-community
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

USE `mydecms`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `mydecms_ad` */

DROP TABLE IF EXISTS `mydecms_ad`;

CREATE TABLE `mydecms_ad` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `beizhu` text,
  `type` int(10) DEFAULT '5',
  `sort` int(10) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_ad` */

LOCK TABLES `mydecms_ad` WRITE;

insert  into `mydecms_ad`(`id`,`name`,`beizhu`,`type`,`sort`,`content`) values (1,'全站头部广告一','468 x 60  全站头部广告一',5,1,'<a href=+=+http://www.mydecms.com+=+ target=+=+_blank+=+><img src=+=+http://btbbt.godhouse.com/biaozhun/template/default/images/top_ad_468x60.jpg+=+ /></a>'),(2,'全站头部广告二','250 x 60 全站头部广告二',5,2,'<a href=+=+http://www.mydecms.com+=+ target=+=+_blank+=+><img src=+=+http://btbbt.godhouse.com/biaozhun/template/default/images/top_ad_250x60.jpg+=+ /></a>'),(3,'全站右边广告一','300 x 250 全站广告，热门文章下面',5,3,'<a href=+=+http://www.mydecms.com+=+ target=+=+_blank+=+><img src=+=+http://btbbt.godhouse.com/biaozhun/template/default/images/right_ad_300x250.jpg+=+ /></a>'),(4,'全站右边广告二','300 x 250 全站广告，热门文章下面',5,4,'<a href=+=+http://www.mydecms.com+=+ target=+=+_blank+=+><img src=+=+http://btbbt.godhouse.com/biaozhun/template/default/images/right_ad_300x250.jpg+=+ /></a>'),(5,'文章内容顶部广告','300 x 250 文章内容顶部广告',5,5,'<a href=+=+http://www.mydecms.com+=+ target=+=+_blank+=+><img src=+=+http://btbbt.godhouse.com/biaozhun/template/default/images/right_ad_300x250.jpg+=+ /></a>'),(6,'首页左栏广告二','658 x 60 在第二个分类下面',5,6,'<a href=+=+http://www.mydecms.com+=+ target=+=+_blank+=+><img src=+=+http://btbbt.godhouse.com/biaozhun/template/default/images/ad_658x60.jpg+=+ /></a>'),(7,'页左栏广告二','658 x 60 在第四个分类下面',5,7,'<a href=+=+http://www.mydecms.com+=+ target=+=+_blank+=+><img src=+=+http://btbbt.godhouse.com/biaozhun/template/default/images/ad_658x60.jpg+=+ /></a>');

UNLOCK TABLES;

/*Table structure for table `mydecms_alink` */

DROP TABLE IF EXISTS `mydecms_alink`;

CREATE TABLE `mydecms_alink` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `type` int(10) DEFAULT '5',
  `sort` int(10) DEFAULT NULL,
  `target` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_alink` */

LOCK TABLES `mydecms_alink` WRITE;

UNLOCK TABLES;

/*Table structure for table `mydecms_article` */

DROP TABLE IF EXISTS `mydecms_article`;

CREATE TABLE `mydecms_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` text,
  `keywords` text,
  `description` text,
  `type` int(10) NOT NULL DEFAULT '1',
  `content` longtext,
  `date` datetime DEFAULT NULL,
  `dafenglei` int(10) NOT NULL DEFAULT '1',
  `count` int(10) NOT NULL DEFAULT '1',
  `tag` varchar(250) CHARACTER SET gb2312 DEFAULT NULL,
  `top` int(10) DEFAULT '1',
  `editdate` datetime NOT NULL,
  `img` text,
  `sort` int(10) DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_article` */

LOCK TABLES `mydecms_article` WRITE;

UNLOCK TABLES;

/*Table structure for table `mydecms_dafenglei` */

DROP TABLE IF EXISTS `mydecms_dafenglei`;

CREATE TABLE `mydecms_dafenglei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `classid` int(11) DEFAULT '0',
  `type` int(11) DEFAULT '1',
  `sort` int(11) DEFAULT '10',
  `title` text,
  `keywords` text,
  `description` text,
  `dir` text,
  `mobanname` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_dafenglei` */

LOCK TABLES `mydecms_dafenglei` WRITE;

insert  into `mydecms_dafenglei`(`id`,`name`,`classid`,`type`,`sort`,`title`,`keywords`,`description`,`dir`,`mobanname`) values (1,'sdaf',0,5,100,'sadf','sdfa','sdfasdf','',''),(2,'sadf',1,1,100,'asdf','sdfasd','fasdfasd','','');

UNLOCK TABLES;

/*Table structure for table `mydecms_link` */

DROP TABLE IF EXISTS `mydecms_link`;

CREATE TABLE `mydecms_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `type` int(10) DEFAULT '5',
  `sort` int(10) DEFAULT NULL,
  `target` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_link` */

LOCK TABLES `mydecms_link` WRITE;

insert  into `mydecms_link`(`id`,`name`,`url`,`type`,`sort`,`target`) values (1,'文章管理系统','http://www.mydecms.com',15,1,'_blank');

UNLOCK TABLES;

/*Table structure for table `mydecms_nav` */

DROP TABLE IF EXISTS `mydecms_nav`;

CREATE TABLE `mydecms_nav` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `type` int(10) DEFAULT '5',
  `sort` int(10) DEFAULT NULL,
  `target` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_nav` */

LOCK TABLES `mydecms_nav` WRITE;

insert  into `mydecms_nav`(`id`,`name`,`url`,`type`,`sort`,`target`) values (1,'网站首页','http://btbbt.godhouse.com/biaozhun',5,1,'NULL');

UNLOCK TABLES;

/*Table structure for table `mydecms_setconfig` */

DROP TABLE IF EXISTS `mydecms_setconfig`;

CREATE TABLE `mydecms_setconfig` (
  `webname` varchar(250) DEFAULT NULL,
  `weburl` varchar(200) DEFAULT NULL,
  `webtitle` varchar(250) DEFAULT NULL,
  `webkeywords` varchar(250) DEFAULT NULL,
  `webdescription` varchar(250) DEFAULT NULL,
  `webadmin` varchar(50) DEFAULT NULL,
  `webpass` varchar(50) DEFAULT NULL,
  `moban` varchar(250) DEFAULT NULL,
  `openspider` int(10) DEFAULT '5',
  `html` varchar(250) DEFAULT NULL,
  `icp` varchar(250) DEFAULT NULL,
  `tongji` text,
  `classid` text
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_setconfig` */

LOCK TABLES `mydecms_setconfig` WRITE;

insert  into `mydecms_setconfig`(`webname`,`weburl`,`webtitle`,`webkeywords`,`webdescription`,`webadmin`,`webpass`,`moban`,`openspider`,`html`,`icp`,`tongji`,`classid`) values ('我的网站','http://btbbt.godhouse.com/biaozhun','我的网站','','','21232f297a57a5a743894a0e4a801fc3','c4ca4238a0b923820dcc509a6f75849b','template/default/',5,'Html/','','','1,2,3,4,5,6');

UNLOCK TABLES;

/*Table structure for table `mydecms_spider` */

DROP TABLE IF EXISTS `mydecms_spider`;

CREATE TABLE `mydecms_spider` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `spidername` varchar(200) DEFAULT NULL,
  `url` text,
  `date` datetime DEFAULT NULL,
  `ip` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

/*Data for the table `mydecms_spider` */

LOCK TABLES `mydecms_spider` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
