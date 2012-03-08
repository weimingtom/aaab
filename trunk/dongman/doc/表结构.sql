

--
-- 表的结构 `pp_vod`
--

CREATE TABLE IF NOT EXISTS `pp_vod` (
  `vod_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `vod_cid` smallint(6) NOT NULL COMMENT '分类ID',
  `vod_name` varchar(255) NOT NULL COMMENT '视频标题',
  `vod_title` varchar(255) NOT NULL COMMENT '副标题',
  `vod_keywords` varchar(255) NOT NULL COMMENT '关键字',
  `vod_color` char(8) NOT NULL COMMENT '颜色',
  `vod_actor` varchar(255) NOT NULL COMMENT '演员',
  `vod_director` varchar(255) NOT NULL COMMENT '导演',
  `vod_content` text NOT NULL COMMENT '视频简介',
  `vod_pic` varchar(255) NOT NULL COMMENT '缩略图',
  `vod_area` char(10) NOT NULL COMMENT '地区',
  `vod_language` char(10) NOT NULL COMMENT '语言',
  `vod_year` smallint(4) NOT NULL COMMENT '年代',
  `vod_continu` varchar(20) NOT NULL DEFAULT '0',
  `vod_addtime` int(11) NOT NULL COMMENT '时间',
  `vod_hits` mediumint(8) NOT NULL DEFAULT '0' COMMENT '人气',
  `vod_stars` tinyint(1) NOT NULL DEFAULT '0' COMMENT '五星评级',
  `vod_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '显示，隐藏',
  `vod_up` mediumint(8) NOT NULL DEFAULT '0' COMMENT '顶',
  `vod_down` mediumint(8) NOT NULL DEFAULT '0' COMMENT '踩',
  `vod_play` varchar(255) NOT NULL COMMENT '播放器选择',
  `vod_server` varchar(255) NOT NULL COMMENT '服务器组',
  `vod_url` longtext NOT NULL COMMENT '数据地址',
  `vod_inputer` varchar(30) NOT NULL COMMENT '录入',
  `vod_reurl` varchar(255) NOT NULL COMMENT '来源',
  `vod_letter` char(2) NOT NULL COMMENT '首字母',
  `vod_skin` varchar(30) NOT NULL COMMENT '模板',
  `vod_gold` decimal(3,1) NOT NULL COMMENT '评分',
  `vod_golder` smallint(6) NOT NULL COMMENT '人气',
  PRIMARY KEY (`vod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=393 ;




单播放器用法
  字段           值
vod_play      tudou
vod_url     64296798
            64292094
            64285227
            64280736
每一行为一集



多播放器用法
  字段           值
vod_play      yuku$$$qvod
vod_url       XMTE1MDUyNDky
              XMTE1MDUyNDAw
              XMTE1MDUyMzIw$$$11111
              22222
              3333
              4444
多播放器是用 三个$$$区分的
每一行为一集

