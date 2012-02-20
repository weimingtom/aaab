SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
CREATE TABLE IF NOT EXISTS pp_admin (
  admin_id smallint(6) unsigned NOT NULL auto_increment,
  admin_name varchar(50) NOT NULL,
  admin_pwd char(32) NOT NULL,
  admin_count smallint(6) NOT NULL,
  admin_ok varchar(50) NOT NULL,
  admin_del bigint(1) NOT NULL,
  admin_ip varchar(40) NOT NULL,
  admin_email varchar(40) NOT NULL,
  admin_logintime int(11) NOT NULL,
  PRIMARY KEY  (admin_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO pp_admin (admin_id, admin_name, admin_pwd, admin_count, admin_ok, admin_del, admin_ip, admin_email, admin_logintime) VALUES
(1, 'admin', '7fef6171469e80d32c0559f88b377245', 0, '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1', 0, '127.0.0.1', 'admin@qq.com', 1275677071);

CREATE TABLE IF NOT EXISTS pp_ads (
  ads_id smallint(4) unsigned NOT NULL auto_increment,
  ads_name varchar(50) NOT NULL,
  ads_content text NOT NULL,
  PRIMARY KEY  (ads_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `pp_ads` (`ads_id`, `ads_name`, `ads_content`) VALUES
(1, 'top72890', '<iframe width="728" scrolling="no" height="90" frameborder="0" src="http://union.ff84.com/ads/72890.html" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true"></iframe>'),
(2, 'top46860', '<iframe width="468" scrolling="no" height="60" frameborder="0" src="http://union.ff84.com/ads/46860.html" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true"></iframe>'),
(3, 'left250250', '<iframe width="250" scrolling="no" height="250" frameborder="0" src="http://union.ff84.com/ads/250250.html" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true"></iframe>'),
(4, 'right300250', '<iframe width="300" scrolling="no" height="250" frameborder="0" src="http://union.ff84.com/ads/300250.html" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true"></iframe>'),
(5, 'top960', '<iframe width="960" scrolling="no" height="90" frameborder="0" src="http://union.ff84.com/ads/96060.html" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true"></iframe>');

CREATE TABLE IF NOT EXISTS pp_cm (
  cm_id mediumint(8) unsigned NOT NULL auto_increment,
  cm_cid mediumint(8) NOT NULL,
  cm_user varchar(50) NOT NULL,
  cm_content text NOT NULL,
  cm_up mediumint(8) NOT NULL default '0',
  cm_down mediumint(8) NOT NULL default '0',
  cm_face tinyint(1) NOT NULL default '1',
  cm_ip varchar(20) NOT NULL,
  cm_addtime int(11) NOT NULL,
  cm_sid tinyint(1) NOT NULL default '1',
  cm_del tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (cm_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS pp_collect (
  collect_id smallint(6) unsigned NOT NULL auto_increment,
  collect_title varchar(50) NOT NULL,
  collect_encoding varchar(10) NOT NULL,
  collect_player varchar(50) NOT NULL,
  collect_savepic tinyint(4) NOT NULL,
  collect_order tinyint(4) NOT NULL,
  collect_pagetype tinyint(4) NOT NULL,
  collect_liststr text NOT NULL,
  collect_pagestr text NOT NULL,
  collect_pagesid smallint(6) unsigned NOT NULL,
  collect_pageeid smallint(6) unsigned NOT NULL,
  collect_listurlstr text NOT NULL,
  collect_listlink text NOT NULL,
  collect_listpicstr text NOT NULL,
  collect_cid text NOT NULL,
  collect_listname text NOT NULL,
  collect_name text NOT NULL,
  collect_actor text NOT NULL,
  collect_director text NOT NULL,
  collect_content text NOT NULL,
  collect_pic text NOT NULL,
  collect_area text NOT NULL,
  collect_language text NOT NULL,
  collect_year text NOT NULL,
  collect_continu text NOT NULL,
  collect_urlstr text NOT NULL,
  collect_urlname text NOT NULL,
  collect_urllink text NOT NULL,
  collect_url text NOT NULL,
  collect_replace text NOT NULL,
  PRIMARY KEY  (collect_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS pp_gb (
  gb_id mediumint(8) unsigned NOT NULL auto_increment,
  gb_cid mediumint(8) NOT NULL default '0',
  gb_user varchar(50) NOT NULL,
  gb_content text NOT NULL,
  gb_intro text NOT NULL,
  gb_addtime int(11) NOT NULL,
  gb_qq varchar(20) NOT NULL,
  gb_ip varchar(20) NOT NULL,
  gb_oid tinyint(1) NOT NULL default '0',
  gb_del tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (gb_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS pp_link (
  link_id tinyint(4) unsigned NOT NULL auto_increment,
  link_name varchar(255) NOT NULL,
  link_logo varchar(255) NOT NULL,
  link_url varchar(255) NOT NULL,
  link_order tinyint(4) NOT NULL,
  link_type tinyint(1) NOT NULL,
  PRIMARY KEY  (link_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `pp_link` (`link_id`, `link_name`, `link_logo`, `link_url`, `link_order`, `link_type`) VALUES
(1, '飞飞影视系统', 'http://', 'http://www.ff84.com', 1, 1),
(2, '电影网站导航', 'http://', 'http://www.8369.info', 2, 1);

CREATE TABLE IF NOT EXISTS pp_list (
  list_id smallint(6) unsigned NOT NULL auto_increment,
  list_pid smallint(6) NOT NULL,
  list_oid smallint(6) NOT NULL,
  list_sid tinyint(1) NOT NULL,
  list_name char(20) NOT NULL,
  list_skin char(20) NOT NULL,
  list_dir varchar(90) NOT NULL,
  list_keywords varchar(255) NOT NULL,
  list_title varchar(50) NOT NULL,
  list_description varchar(255) NOT NULL,
  PRIMARY KEY  (list_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO pp_list (list_id, list_pid, list_oid, list_sid, list_name, list_skin, list_dir, list_keywords, list_title, list_description) VALUES
(1, 0, 2, 1, '电影', 'pp_vodchannel', 'movie', '', '', ''),
(2, 0, 1, 1, '电视剧', 'pp_vodchannel', 'tv', '', '', ''),
(3, 0, 3, 1, '动漫', 'pp_vodlist', 'anime', '', '', ''),
(4, 0, 4, 1, '综艺', 'pp_vodlist', 'variety', '', '', ''),
(5, 0, 5, 1, '体育', 'pp_vodlist', 'sports', '', '', ''),
(6, 0, 6, 1, '游戏', 'pp_vodlist', 'games', '', '', ''),
(7, 0, 7, 1, '其它', 'pp_vodlist', 'other', '', '', ''),
(8, 1, 8, 1, '动作片', 'pp_vodlist', 'dz', '', '', ''),
(9, 1, 9, 1, '喜剧片', 'pp_vodlist', 'xj', '', '', ''),
(10, 1, 10, 1, '爱情片', 'pp_vodlist', 'aq', '', '', ''),
(11, 1, 11, 1, '科幻片', 'pp_vodlist', 'kh', '', '', ''),
(12, 1, 12, 1, '恐怖片', 'pp_vodlist', 'kb', '', '', ''),
(13, 1, 13, 1, '战争片', 'pp_vodlist', 'zz', '', '', ''),
(14, 1, 14, 1, '故事片', 'pp_vodlist', 'gs', '', '', ''),
(15, 2, 15, 1, '国产剧', 'pp_vodlist', 'gc', '', '', ''),
(16, 2, 16, 1, '港台剧', 'pp_vodlist', 'gt', '', '', ''),
(17, 2, 17, 1, '欧美剧', 'pp_vodlist', 'om', '', '', ''),
(18, 2, 18, 1, '日韩剧', 'pp_vodlist', 'rh', '', '', ''),
(19, 2, 19, 1, '海外剧', 'pp_vodlist', 'hw', '', '', ''),
(20, 0, 8, 2, '资讯', 'pp_newslist', 'news', '', '', ''),
(21, 0, 21, 9, '留言本', 'pp_newslist', '{$root}index.php?s=gb.html', '', '', ''),
(22, 0, 22, 9, 'TAG', 'pp_vodlist', '{$root}index.php?s=tag.html', '', '', '');

CREATE TABLE IF NOT EXISTS pp_news (
  news_id mediumint(8) unsigned NOT NULL auto_increment,
  news_cid smallint(6) NOT NULL,
  news_name varchar(255) NOT NULL,
  news_keywords varchar(255) NOT NULL,
  news_color char(8) NOT NULL,
  news_pic varchar(255) NOT NULL,
  news_inputer varchar(50) NOT NULL,
  news_reurl varchar(255) NOT NULL,
  news_remark text NOT NULL,
  news_content text NOT NULL,
  news_hits mediumint(8) NOT NULL,
  news_stars tinyint(1) NOT NULL,
  news_del tinyint(1) NOT NULL,
  news_up mediumint(8) NOT NULL,
  news_down mediumint(8) NOT NULL,
  news_gourl varchar(255) NOT NULL,
  news_letter char(2) NOT NULL,
  news_addtime int(8) NOT NULL,
  news_gold decimal(3,1) NOT NULL,
  news_golder smallint(6) NOT NULL,  
  PRIMARY KEY  (news_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS pp_tag (
  tag_id mediumint(8) NOT NULL,
  tag_sid tinyint(1) NOT NULL,
  tag_name varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS pp_vod (
  vod_id mediumint(8) unsigned NOT NULL auto_increment,
  vod_cid smallint(6) NOT NULL,
  vod_name varchar(255) NOT NULL,
  vod_title varchar(255) NOT NULL,
  vod_keywords varchar(255) NOT NULL,
  vod_color char(8) NOT NULL,
  vod_actor varchar(255) NOT NULL,
  vod_director varchar(255) NOT NULL,
  vod_content text NOT NULL,
  vod_pic varchar(255) NOT NULL,
  vod_area char(10) NOT NULL,
  vod_language char(10) NOT NULL,
  vod_year smallint(4) NOT NULL,
  vod_continu varchar(20) NOT NULL default '0',
  vod_addtime int(11) NOT NULL,
  vod_hits mediumint(8) NOT NULL default '0',
  vod_stars tinyint(1) NOT NULL default '0',
  vod_del tinyint(1) NOT NULL default '0',
  vod_up mediumint(8) NOT NULL default '0',
  vod_down mediumint(8) NOT NULL default '0',
  vod_play varchar(255) NOT NULL,
  vod_server varchar(255) NOT NULL,
  vod_url longtext NOT NULL,
  vod_inputer varchar(30) NOT NULL,
  vod_reurl varchar(255) NOT NULL,
  vod_letter char(2) NOT NULL,
  vod_skin varchar(30) NOT NULL,
  vod_gold decimal(3,1) NOT NULL,
  vod_golder smallint(6) NOT NULL,
  PRIMARY KEY  (vod_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;