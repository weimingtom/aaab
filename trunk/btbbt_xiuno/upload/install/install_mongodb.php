<?php

$db_data = array(
	'group'=> array (
		array('groupid'=>0, 'name'=>'游客组', 'creditsfrom'=>0, 'creditsto'=>0, 'upfloors'=>1, 'color'=>''),
		array('groupid'=>1, 'name'=>'管理员组', 'creditsfrom'=>0, 'creditsto'=>0, 'upfloors'=>0, 'color'=>''),
		array('groupid'=>2, 'name'=>'超级版主组', 'creditsfrom'=>0, 'creditsto'=>0, 'upfloors'=>0, 'color'=>''),
		array('groupid'=>3, 'name'=>'大区版主组', 'creditsfrom'=>0, 'creditsto'=>0, 'upfloors'=>0, 'color'=>''),
		array('groupid'=>4, 'name'=>'版主组', 'creditsfrom'=>0, 'creditsto'=>0, 'upfloors'=>0, 'color'=>''),
		array('groupid'=>5, 'name'=>'VIP组', 'creditsfrom'=>0, 'creditsto'=>0, 'upfloors'=>10, 'color'=>''),
		array('groupid'=>11, 'name'=>'一级用户组', 'creditsfrom'=>0, 'creditsto'=>50, 'upfloors'=>1, 'color'=>''),
		array('groupid'=>12, 'name'=>'二级用户组', 'creditsfrom'=>50, 'creditsto'=>200, 'upfloors'=>2, 'color'=>''),
		array('groupid'=>13, 'name'=>'三级用户组', 'creditsfrom'=>200, 'creditsto'=>1000, 'upfloors'=>3, 'color'=>''),
		array('groupid'=>14, 'name'=>'四级用户组', 'creditsfrom'=>1000, 'creditsto'=>10000, 'upfloors'=>4, 'color'=>''),
		array('groupid'=>15, 'name'=>'五级级用户组', 'creditsfrom'=>10000, 'creditsto'=>10000000, 'upfloors'=>5, 'color'=>''),
	), 
	'user'=> array(
		array('uid'=>1, 'regip'=>'12345554', 'regdate'=>0, 'username'=>'admin', 'password'=>'d14be7f4d15d16de92b7e34e18d0d0f7', 
			'salt'=>'99adde', 'email'=>'admin@admin.com', 'groupid'=>1, 'money'=>0, 'avatar'=>0, 'threads'=>0, 
			'posts'=>0, 'myposts'=>0, 'digests'=>0, 'credits'=>0, 'money'=>0, 'follows'=>0, 'followeds'=>0, 
			'newpms'=>0, 'newfeeds'=>0, 'homepage'=>'', 'signature'=>'', 'accesson'=>0, 
			'lastactive'=>0,'rmb'=>'0','dollars'=>'0'),
			/*
		array('uid'=>2, 'regip'=>'12345554', 'regdate'=>0, 'username'=>'test', 'password'=>'d14be7f4d15d16de92b7e34e18d0d0f7', 
			'salt'=>'99adde', 'email'=>'test@admin.com', 'groupid'=>11, 'money'=>0, 'avatar'=>0, 'threads'=>0, 
			'posts'=>0, 'myposts'=>0, 'digests'=>0, 'credits'=>0, 'money'=>0, 'follows'=>0, 'followeds'=>0, 
			'newpms'=>0, 'newfeeds'=>0, 'homepage'=>'', 'signature'=>'', 'accesson'=>0, 
			'lastactive'=>0),
			*/
	),
	'forum'=> array(
		array('fid'=>1, 'fup'=>0, 'name'=>'默认分类', 'rank'=>0, 'threads'=>0, 'posts'=>0, 'digests'=>0, 'todayposts'=>0, 'tops'=>0, 'lastpost'=>0, 'lasttid'=>0, 'lastsubject'=>'', 'lastuid'=>0, 'lastusername'=>'', 'brief'=>'', 'rule'=>'', 'icon'=>'', 'accesson'=>0, 'modids'=>'', 'modnames'=>'', 'toptids'=>'', 'lastcachetime'=>0, 'status'=>1, 'listtype'=>0, 'orderby'=>0, 'indexforums'=>0, 'seo_title'=>'', 'seo_keywords'=>''),
		array('fid'=>2, 'fup'=>1, 'name'=>'默认版块一', 'rank'=>0, 'threads'=>0, 'posts'=>0, 'digests'=>0, 'todayposts'=>0, 'tops'=>0, 'lastpost'=>0, 'lasttid'=>0, 'lastsubject'=>'', 'lastuid'=>0, 'lastusername'=>'', 'brief'=>'', 'rule'=>'', 'icon'=>'', 'accesson'=>0, 'modids'=>'', 'modnames'=>'', 'toptids'=>'', 'lastcachetime'=>0, 'status'=>1, 'listtype'=>0, 'orderby'=>0, 'indexforums'=>0, 'seo_title'=>'', 'seo_keywords'=>''),
		array('fid'=>3, 'fup'=>1, 'name'=>'默认版块二', 'rank'=>0, 'threads'=>0, 'posts'=>0, 'digests'=>0, 'todayposts'=>0, 'tops'=>0, 'lastpost'=>0, 'lasttid'=>0, 'lastsubject'=>'', 'lastuid'=>0, 'lastusername'=>'', 'brief'=>'', 'rule'=>'', 'icon'=>'', 'accesson'=>0, 'modids'=>'', 'modnames'=>'', 'toptids'=>'', 'lastcachetime'=>0, 'status'=>1, 'listtype'=>0, 'orderby'=>0, 'indexforums'=>0, 'seo_title'=>'', 'seo_keywords'=>''),
	),
	'digestcate' => array(
		array('cateid'=>1, 'parentid'=>0, 'name'=>'精华分类一', 'threads'=>0, 'rank'=>0, 'uid'=>1),
		array('cateid'=>2, 'parentid'=>0, 'name'=>'精华分类二', 'threads'=>0, 'rank'=>0, 'uid'=>1),
		array('cateid'=>3, 'parentid'=>1, 'name'=>'精华分类1.1', 'threads'=>0, 'rank'=>0, 'uid'=>1),
		array('cateid'=>4, 'parentid'=>1, 'name'=>'精华分类1.2', 'threads'=>0, 'rank'=>0, 'uid'=>1),
		array('cateid'=>5, 'parentid'=>2, 'name'=>'精华分类2.1', 'threads'=>0, 'rank'=>0, 'uid'=>1),
		array('cateid'=>6, 'parentid'=>2, 'name'=>'精华分类2.2', 'threads'=>0, 'rank'=>0, 'uid'=>1),
	),
);

$db_index = array(
	'group'=>array(array('groupid'=>1)),
	'user'=>array(array('uid'=>1), array('username'=>1), array('email'=>1)),
	'user_access'=>array(array('uid'=>1)),
	'forum'=>array(array('fid'=>1), array('fup'=>1)),
	'forum_access'=>array(array('fid'=>1, 'groupid'=>1), array('fid'=>1)),
	'thread_type'=>array(array('fid'=>1), array('typeid'=>1)),
	'thread'=>array(array('fid'=>1, 'tid'=>0), array('fid'=>1, 'floortime'=>0), array('fid', 'typeid')),
	'post'=>array(array('fid'=>1, 'pid'=>1), array('fid'=>1, 'tid'=>1, 'page'=>1)),
	'digestcate'=>array(array('cateid'=>1), array('rank'=>1)),
	'digest'=>array(array('digestid'=>1), array('fid'=>1, 'tid'=>0), array('cateid'=>1)),
	'attach'=>array(array('aid'=>1), array('fid'=>1, 'pid'=>1), array('uid'=>1, 'isimage'=>1)),
	'attach_download'=>array(array('uid'=>1, 'aid'=>1), array('aid'=>1), array('uploaduid'=>1, 'dateline'=>0)),
	'mypost'=>array(array('uid'=>1, 'fid'=>1, 'pid'=>1), array('uid'=>1, 'fid'=>1, 'tid'=>1), array('uid'=>1, 'pid'=>0)),
	'online'=>array(array('sid'=>1), array('lastvisit'=>1), array('uid'=>1)),
	'friendlink'=>array(array('linkid'=>1), array('type'=>1, 'rank'=>0)),
	'pmnew'=>array(array('recvuid'=>1, 'senduid'=>1), array('recvuid'=>1, 'count'=>1)),
	'pmcount'=>array(array('uid1'=>1, 'uid2'=>1)),
	'pm'=>array(array('pmid'=>1), array('uid1'=>1, 'uid2'=>1, 'pmid'=>1)),
	'follow'=>array(array('uid'=>1, 'fuid'=>1), array('uid'=>1), array('fuid'=>1)),
	'pay'=>array(array('payid'=>1), array('uid'=>1)),
	'modlog'=>array(array('logid'=>1), array('uid'=>1, 'logid'=>1)),
	'kv'=>array(array('k'=>1)),
	'stat'=>array(array('year'=>1, 'month'=>1, 'day'=>1)),
	'runtime'=>array(array('k'=>1)),
	
);