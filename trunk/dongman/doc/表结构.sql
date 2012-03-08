

--
-- ��Ľṹ `pp_vod`
--

CREATE TABLE IF NOT EXISTS `pp_vod` (
  `vod_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `vod_cid` smallint(6) NOT NULL COMMENT '����ID',
  `vod_name` varchar(255) NOT NULL COMMENT '��Ƶ����',
  `vod_title` varchar(255) NOT NULL COMMENT '������',
  `vod_keywords` varchar(255) NOT NULL COMMENT '�ؼ���',
  `vod_color` char(8) NOT NULL COMMENT '��ɫ',
  `vod_actor` varchar(255) NOT NULL COMMENT '��Ա',
  `vod_director` varchar(255) NOT NULL COMMENT '����',
  `vod_content` text NOT NULL COMMENT '��Ƶ���',
  `vod_pic` varchar(255) NOT NULL COMMENT '����ͼ',
  `vod_area` char(10) NOT NULL COMMENT '����',
  `vod_language` char(10) NOT NULL COMMENT '����',
  `vod_year` smallint(4) NOT NULL COMMENT '���',
  `vod_continu` varchar(20) NOT NULL DEFAULT '0',
  `vod_addtime` int(11) NOT NULL COMMENT 'ʱ��',
  `vod_hits` mediumint(8) NOT NULL DEFAULT '0' COMMENT '����',
  `vod_stars` tinyint(1) NOT NULL DEFAULT '0' COMMENT '��������',
  `vod_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '��ʾ������',
  `vod_up` mediumint(8) NOT NULL DEFAULT '0' COMMENT '��',
  `vod_down` mediumint(8) NOT NULL DEFAULT '0' COMMENT '��',
  `vod_play` varchar(255) NOT NULL COMMENT '������ѡ��',
  `vod_server` varchar(255) NOT NULL COMMENT '��������',
  `vod_url` longtext NOT NULL COMMENT '���ݵ�ַ',
  `vod_inputer` varchar(30) NOT NULL COMMENT '¼��',
  `vod_reurl` varchar(255) NOT NULL COMMENT '��Դ',
  `vod_letter` char(2) NOT NULL COMMENT '����ĸ',
  `vod_skin` varchar(30) NOT NULL COMMENT 'ģ��',
  `vod_gold` decimal(3,1) NOT NULL COMMENT '����',
  `vod_golder` smallint(6) NOT NULL COMMENT '����',
  PRIMARY KEY (`vod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=393 ;

