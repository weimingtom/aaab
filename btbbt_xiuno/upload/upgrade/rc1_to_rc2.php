<?php

/*
 * Copyright (C) xiuno.com
 */

// 本程序用来升级 RC1 到 RC2
/*
	流程：
		1. 备份原站点：新建目录: rc1, 将所有文件移动到 rc1 中
		2. 上传 RC2 源代码
		3. 将 rc1/upload 目录往上移动一层，也就是覆盖当前的 ./upload 目录
		4. 不要安装RC2，直接进入第4步！
		5. 升级 RC1：http://www.domain.com/upgrade/rc1_to_rc2.php
		5. 删除升级目录 upgrade!!!
*/

@set_time_limit(0);

define('DEBUG', 2);

define('BBS_PATH', '../');

$rc1_path = BBS_PATH.'rc1/';
if(!is_dir($rc1_path)) {
	message("路径: $rc_path 不存在。");
}

if(!is_file($rc1_path.'conf/conf.php')) {
	message("RC1 版本的 conf.php 不存在。");
} else {
	include $rc1_path.'conf/conf.php';
	$old = $conf['bbs'];
	unset($conf);
}

// 加载应用的配置文件，唯一的全局变量 $conf
if(!($conf = include BBS_PATH.'conf/conf.php')) {
	message('配置文件不存在。');
}

define('FRAMEWORK_PATH', BBS_PATH.'framework2.3/');
define('FRAMEWORK_TMP_PATH', $conf['tmp_path']);
define('FRAMEWORK_LOG_PATH', $conf['log_path']);
include FRAMEWORK_PATH.'core.php';
core::init();

$step = core::gpc('step');
	
// 升级配置文件
if(empty($step)) {
	upgrade_conf();
} elseif($step == 'alter_table') {
	alter_table();
} elseif($step == 'upgrade_avatar') {
	upgrade_avatar();
} elseif($step == 'upgrade_forumicon') {
	upgrade_forumicon();
} elseif($step == 'laststep') {
	laststep();
}

function upgrade_conf() {
	global $old, $conf;
	
	// 写入配置文件，仅支持mysql
	$configfile = BBS_PATH.'conf/conf.php';
	$type = $old['db']['type'];
	$user = $old['db']['mysql']['master']['user'];
	$host = $old['db']['mysql']['master']['host'];
	$pass = $old['db']['mysql']['master']['password'];
	$name = $old['db']['mysql']['master']['name'];
	$tablepre = $old['db']['mysql']['master']['tablepre'];
	$replacearr = array('type'=>$type, 'user'=>$user, 'host'=>$host, 'password'=>$pass, 'name'=>$name, 'tablepre'=>$tablepre);
	if($type == 'mysql') {
		file_line_replace($configfile, 18, 30, $replacearr);
	} elseif($type == 'pdo') {
		file_line_replace($configfile, 23, 35, $replacearr);
	} elseif($type == 'mongodb') {
		file_line_replace($configfile, 36, 46, $replacearr);
	}
	
	$conf = include $configfile;
	
	include BBS_PATH.'model/mconf.class.php';
	$mconf = new mconf();
	$mconf->set_to('app_name', $old['app_name']);
	$mconf->set_to('app_brief', $old['app_brief']);
	$mconf->set_to('app_copyright', $old['app_copyright']);
	$mconf->set_to('app_starttime', $old['app_starttime']);
	$mconf->set_to('app_url', $old['app_url']);
	$mconf->set_to('static_url', $old['app_url']);
	$mconf->set_to('upload_url', $old['app_url'].'upload/');
	$mconf->set_to('public_key', $old['public_key']);
	$mconf->set_to('seo_title', $old['seo_title']);
	$mconf->set_to('seo_keywords', $old['seo_keywords']);
	$mconf->set_to('seo_description', $old['seo_description']);
	$mconf->set_to('china_icp', $old['china_icp']);
	$mconf->set_to('footer_js', addslashes($old['footer_js']));
	$mconf->set_to('installed', 1);
	
	$mconf->save();
	
	// copy mail.php
	is_file(BBS_PATH.'conf/mail.php') && unlink(BBS_PATH.'conf/mail.php');
	copy(BBS_PATH.'rc1/conf/mail.php', BBS_PATH.'conf/mail.php');

	message('修改配置文件成功，接下来修改表结构...', '?step=alter_table');
}

function alter_table() {
	global $conf;
	// 2. 修改表结构
	$sql = "
	alter table bbs_group 
		add column color char(6) NOT NULL default '';

	alter table bbs_attach 
		add column isimage tinyint(1) NOT NULL  DEFAULT '0' after downloads, 
		add column golds int(10) NOT NULL  DEFAULT '0' after isimage, COMMENT='';
	
	create table bbs_attach_download ( 
		aid int(10) unsigned NOT NULL  auto_increment  , 
		uid int(10) NOT NULL  DEFAULT '0'  , 
		uploaduid int(10) NOT NULL  DEFAULT '0'  , 
		dateline int(10) unsigned NOT NULL  DEFAULT '0'  , 
		golds int(10) NOT NULL  DEFAULT '0'  , 
		PRIMARY KEY (uid, aid),
		KEY (uploaduid, dateline),
		KEY (aid)
	)Engine=MyISAM;
	
	alter table bbs_forum 
		add column lasttid int(11) NOT NULL  DEFAULT '0' after lastpost, 
		add column lastsubject char(32) NOT NULL  DEFAULT '0' after lasttid, 
		add column lastuid int(16) NOT NULL  DEFAULT '0' after lastsubject, 
		add column lastusername char(16) NOT NULL  DEFAULT '0' after lastuid, 
		add column indexforums tinyint(11) NOT NULL default '0' after orderby, 
		change brief brief char(200) NOT NULL  after lastusername, 
		add column rule char(255) NOT NULL  after brief, 
		change icon icon tinyint(4) NOT NULL  DEFAULT '0' after rule, COMMENT='';
	
	DROP TABLE IF EXISTS bbs_modlog;
	CREATE TABLE bbs_modlog(
		logid bigint(11) unsigned NOT NULL auto_increment,	# logid
		uid int(11) unsigned NOT NULL default '0',		# 版主 uid
		username char(16) NOT NULL default '',			# 版主 用户名
		fid int(11) unsigned NOT NULL default '0',		# 版块id
		tid int(11) unsigned NOT NULL default '0',		# 主题id
		pid int(11) unsigned NOT NULL default '0',		# 帖子id
		subject char(32) NOT NULL default '',			# 主题
		credits  int(11) unsigned NOT NULL default '0',		# 加减积分
		dateline int(11) unsigned NOT NULL default '0',		# 时间
		action char(16) NOT NULL default '',			# digest|top|delete|undigest|untop
		PRIMARY KEY (logid),
		KEY (uid, logid)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

	create table bbs_kv ( 
		k char(16) NOT NULL default ''  , 
		v text NOT NULL default ''  , 
		expiry int(10) unsigned NOT NULL  DEFAULT '0'  , 
		PRIMARY KEY (k) 
	)Engine=MyISAM;
	
	alter table bbs_post 
		add column imagenum tinyint(3) unsigned NOT NULL  DEFAULT '0' after attachnum, 
		change page page smallint(6) unsigned NOT NULL  DEFAULT '0' after imagenum, COMMENT='';
	
	create table bbs_runtime ( 
		k char(16) NOT NULL  default '' , 
		v char(255) NOT NULL  default '' , 
		PRIMARY KEY (k)
	)Engine=MEMORY;
	
	alter table bbs_thread 
		add column lastuid int(10) unsigned NOT NULL  DEFAULT '0' after lastpost, 
		add column lastusername char(16) NOT NULL  after lastuid, 
		change floortime floortime int(10) unsigned NOT NULL  DEFAULT '0' after lastusername, 
		add column typeid int(10) unsigned NOT NULL  DEFAULT '0' after digest, 
		add column typename char(16) NOT NULL  DEFAULT '0' after typeid, 
		change cateids cateids char(47) NOT NULL  DEFAULT '0' after typename, 
		add column imagenum tinyint(3) NOT NULL  DEFAULT '0' after attachnum, 
		change closed closed tinyint(1) unsigned NOT NULL  DEFAULT '0' after imagenum, 
		drop column lastposter, 
		add KEY typeid (typeid), COMMENT='';
	
	create table bbs_thread_type ( 
		typeid int(11) unsigned NOT NULL  auto_increment  , 
		fid smallint(6) NOT NULL  DEFAULT '0'  , 
		typename char(16) NOT NULL   , 
		rank tinyint(3) unsigned NOT NULL  DEFAULT '0'  , 
		PRIMARY KEY (typeid) , 
		KEY fid (fid) 
	)Engine=MyISAM;
	
	alter table bbs_user 
		add column golds int(11) unsigned NOT NULL  DEFAULT '0' after credits, 
		change money money int(11) unsigned NOT NULL  DEFAULT '0' after golds, COMMENT='';
	";
	
	$db = new db_mysql($conf['db']['mysql']);
	$s = $sql;
	$s = str_replace("\r\n", "\n", $s);
	$s = preg_replace('#\n\#[^\n]*?\n#is', "\n", $s);	// 去掉注释行
	$sqlarr = explode(";\n", $s);
	$tablepre = $conf['db'][$conf['db']['type']]['master']['tablepre'];
	
	foreach($sqlarr as $sql) {
		if(trim($sql)) {
			$sql = str_replace('bbs_', $tablepre, $sql);
			try {
				$db->query($sql);
			} catch (Exception $e) {
				$error = $e->getMessage();
				// Duplicate column, Table exists, column/key does not exists
				if(mysql_errno() != 1060 && mysql_errno() != 1050 && mysql_errno() != 1091) {
					echo mysql_errno();
					message($error);
					break;
				}
			}
		}
	}
	
	$db->truncate('framework_count');
	$db->truncate('framework_maxid');
	
	message('升级表结构完成，接下来升级用户头像...', '?step=upgrade_avatar');
}

// 升级头像，一次1000，跳转升级。一百万用户需要跳转1000次。一次大概5秒。5000秒。大概2小时。
function upgrade_avatar() {
	global $old, $conf;
	$count = intval(core::gpc('count'));
	$start = intval(core::gpc('start'));
	$limit = 1000;// 每次处理的行数
	
	$tablepre = $conf['db'][$conf['db']['type']]['master']['tablepre'];
	$user = new user();
	$db = $user->get_db_instance();
	
	if(!isset($_GET['count'])) {
		$arr = $db->fetch_first("SELECT COUNT(*) as num FROM {$tablepre}user WHERE avatar>0");
		$count = $arr['num'];
	}
	
	if(empty($count) || $start >= $count) {
		message('升级头像完成，接下来升级论坛图标', '?step=upgrade_forumicon', 2);
	}
	
	// 查找有头像的用户 无索引查询，慢
	$keys = $user->index_fetch_id(array('avatar'=>array('>'=>0)), array(), $start, $limit);
	foreach($keys as $key) {
		$arr = explode('-', $key);
		$uid = $arr[array_search('uid', $arr) + 1];
		$oldpath = BBS_PATH.'rc1/upload/avatar/'.image::get_dir($uid).'/';
		$oldbig = $oldpath.$uid."_big.gif";
		$oldmiddle = $oldpath."{$uid}.gif";
		//$newpath = $conf['upload_path'].'avatar/'.image::set_dir($uid, $conf['upload_path'].'avatar/').'/';
		$newpath = $oldpath;
		$newbig = $newpath.$uid."_big.gif";
		$newmiddle = $newpath.$uid."_middle.gif";
		$newsmall = $newpath.$uid."_small.gif";
		is_file($oldbig) && image::thumb($oldbig, $newbig, $conf['avatar_width_big'], $conf['avatar_width_big']);
		is_file($oldmiddle) && image::thumb($oldmiddle, $newmiddle, $conf['avatar_width_middle'], $conf['avatar_width_middle']);
		is_file($oldmiddle) && image::thumb($oldmiddle, $newsmall, $conf['avatar_width_small'], $conf['avatar_width_small']);
	}
	$start += $limit;
	message("正在升级头像...一共 $count, 已经完成 start, 每次 $limit 条，完成进度 ".number_format($start/$count, 2, '.', '')."%", "?count=$count&start=$start&step=upgrade_avatar", 0);
}

function upgrade_forumicon() {
	global $old, $conf;
	$forum = new forum();
	$db = $forum->get_db_instance();
	
	$keys = $forum->index_fetch_id(array(), array(), 0, 10000);
	foreach($keys as $key) {
		$arr = explode('-', $key);
		$fid = $arr[array_search('fid', $arr) + 1];
		
		// 查找目录，进行缩略
		$oldpath = BBS_PATH.'rc1/upload/forum/';
		$oldbig = $oldpath.$fid.".gif";
		//$newpath = $conf['upload_path'].'forum/';
		$newpath = BBS_PATH.'rc1/upload/forum/';
		$newbig = $newpath.$fid."_big.gif";
		$newmiddle = $newpath.$fid."_middle.gif";
		$newsmall = $newpath.$fid."_small.gif";
		is_file($oldbig) && copy($oldbig, $newbig);
		is_file($oldbig) && copy($oldbig, $newmiddle);
		is_file($oldbig) && image::thumb($oldbig, $newsmall, $conf['forumicon_width_small'], $conf['forumicon_width_small']);
	}
	message('升级论坛图标完毕，因为原图尺寸比较小，建议重新上传论坛图标，请移动 <b>rc1/upload </b>到网站根目录，接下来清空缓存...', '?step=laststep');
}

function laststep() {
	global $conf;
	truncate_dir($conf['tmp_path']);
	message('升级完毕，请<b>删除升级目录</b>，防止重复升级！！！');
}

function message($s, $url = '', $timeout = 2) {
	$s = $url ? "<h2>$s</h2><p><a href=\"$url\">页面将在<b>$timeout</b>秒后自动跳转，点击这里手工跳转。</a></p><script>setTimeout(function() {window.location=\"$url\"}, ".($timeout * 1000).");</script></script>" : "<h2>$s</h2>";
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Xiuno BBS 2.0.0 RC1 TO RC2 升级程序 </title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="../view/common.css" />
	</head>
	<body>
	<div id="header" style="overflow: hidden;">
		<h3 style="color: #FFFFFF; line-height: 26px;margin-left: 16px;">Xiuno BBS 2.0.0 RC1 TO RC2 升级程序</h3>
	</div>
	<div id="body" style="padding: 16px;">
		'.$s.'
	</div>
	<div id="footer"> Powered by Xiuno (c) 2010 </div>
	</body>
	</html>';
	exit;
}

function truncate_dir($dir) {
	$dh = opendir($dir);
	while(($file = readdir($dh)) !== false ) {
		if($file != "." && $file != ".." ) {
			if(is_dir( $dir . $file ) ) {
				//opendir_recursive( $dir . $file . "/", $recall);
			} else {
				unlink($dir."$file");
			}
		}
	}
	closedir($dh);
}

function clear_cache($dir, $pre) {
	$dh = opendir($dir);
	while(($file = readdir($dh)) !== false ) {
		if($file != "." && $file != ".." ) {
			if(is_dir( $dir . $file ) ) {
				//opendir_recursive( $dir . $file . "/", $recall);
			} else {
				if(substr($file, 0, strlen($pre)) == $pre) {
					unlink($dir."$file");
				}
			}
		}
	}
	closedir($dh);
}

function file_line_replace($configfile, $startline, $endline, $replacearr) {
	// 从16行-33行，正则替换
	$arr = file($configfile);
	$arr1 = array_slice($arr, 0, $startline - 1); // 此处: startline - 1 为长度
	$arr2 = array_slice($arr, $startline - 1, $endline - $startline + 1); // 此处: startline - 1 为偏移量
	$arr3 = array_slice($arr, $endline);
	
	$s = implode("", $arr2);
	foreach($replacearr as $k=>$v) { 
		$s = preg_replace('#\''.preg_quote($k).'\'\s*=\>\s*\'?.*?\'?,#ism', "'$k' => '$v',", $s);
	}
	$s = implode("", $arr1).$s.implode("", $arr3);
	file_put_contents($configfile, $s);
}
