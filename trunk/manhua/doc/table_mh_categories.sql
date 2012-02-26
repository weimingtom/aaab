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

/*Data for the table `mh_categories` */

insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (1,0,0,1,'网络文学','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (2,0,0,1,'现代文学','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (3,0,0,1,'古典文学','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (4,0,0,1,'武侠小说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (5,0,0,1,'言情小说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (6,0,0,1,'玄幻小说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (7,0,0,1,'侦探小说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (8,0,0,1,'纪实文学','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (9,0,0,0,'少儿读物','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (10,0,0,0,'外国文学','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (11,1,0,0,'小说连载','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (12,1,0,0,'网络言情','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (13,1,0,0,'网络武侠','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (14,1,0,0,'网络科幻','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (15,1,0,0,'散文诗歌','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (16,1,0,0,'其他作品','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (17,2,0,0,'经典名篇','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (18,2,0,0,'社会生活','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (19,2,0,0,'文革岁月','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (20,2,0,0,'军事战争','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (21,2,0,0,'历史小说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (22,2,0,0,'校园文学','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (23,2,0,0,'散文杂文','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (24,2,0,0,'现代诗歌','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (25,3,0,0,'经典名著','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (26,3,0,0,'史学著作','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (27,3,0,0,'古典小说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (28,4,0,0,'经典作品','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (29,4,0,0,'其他武侠','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (30,5,0,0,'校园爱情','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (31,6,0,0,'外国科幻','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (32,6,0,0,'中国科幻','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (33,6,0,0,'神话传说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (34,6,0,0,'玄幻异幻','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (35,7,0,0,'欧美侦探','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (36,7,0,0,'日本推理','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (37,7,0,0,'国产侦破','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (38,7,0,0,'恐怖小说','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (39,8,0,0,'人物传记','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (40,8,0,0,'事件纪实','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (41,9,0,0,'童话故事','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (42,9,0,0,'寓言故事','','',0,2010);
insert  into `mh_categories`(`id`,`parent_id`,`store_id`,`ismenu`,`cate_name`,`menulink`,`content`,`sort_order`,`time`) values (43,5,0,0,'都市生活','','',1,2010);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;