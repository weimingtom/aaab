/*
SQLyog Enterprise - MySQL GUI v5.25
Host - 5.1.31-community : Database - muerwu
*********************************************************************
Server version : 5.1.31-community
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

USE `muerwu`;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `mh_member` */

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

/*Data for the table `mh_member` */

insert  into `mh_member`(`uid`,`username`,`password`,`groupid`) values (25,'huyao','c4ca4238a0b923820dcc509a6f75849b',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;