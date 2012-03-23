#2012.03.23
alter table `pp_vod` add column `vod_updatetime` int (11) DEFAULT '0' NOT NULL  after `vod_addtime`;