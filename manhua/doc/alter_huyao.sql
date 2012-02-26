#2010.11.13
alter table `god_novel_content` add column `chapterId` int (10)UNSIGNED  DEFAULT '0' NOT NULL  COMMENT '卷ID' after `novelId`

#2010.11.14
alter table `god_novel_content` change `contentId` `contentId` int (10)UNSIGNED NOT NULL AUTO_INCREMENT;