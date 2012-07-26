<?php

/*
 * Copyright (C) xiuno.com
 */

// 本程序用来升级测试版本的 RC2 到 RC2
/*
	流程：
		1. 备份 conf.php 到 conf.php.bak
		2. 清空老的 plugin, tmp 目录
		3. 上传 rc2 程序到网站根目录
		4. 还原 conf.php.bak 到 conf.php
		4. 访问 http://www.domain.com/upgrade/rc2_to_rc2.php
		5. 删除升级目录 upgrade!!!
*/

@set_time_limit(0);

define('DEBUG', 2);

define('BBS_PATH', '../');

// 加载应用的配置文件，唯一的全局变量 $conf
if(!($conf = include BBS_PATH.'conf/conf.php')) {
	message('配置文件不存在。');
}

define('FRAMEWORK_PATH', BBS_PATH.'framework2.3/');
define('FRAMEWORK_TMP_PATH', $conf['tmp_path']);
define('FRAMEWORK_LOG_PATH', $conf['log_path']);
include FRAMEWORK_PATH.'core.php';
core::init();

// 升级配置文件
alter_table();

function alter_table() {
	global $conf;
	// 2. 修改表结构
	$sql = "
	alter table bbs_group 
		add column color char(6) NOT NULL default '';
	
	alter table bbs_forum 
		add column indexforums tinyint(11) NOT NULL default '0' after orderby;

	DROP TABLE IF EXISTS bbs_modlog;
	CREATE TABLE bbs_modlog(
	  logid bigint(11) unsigned NOT NULL auto_increment,	# logid
	  uid int(11) unsigned NOT NULL default '0',		# 版主 uid
	  username char(16) NOT NULL default '',		# 版主 用户名
	  fid int(11) unsigned NOT NULL default '0',		# 版块id
	  tid int(11) unsigned NOT NULL default '0',		# 主题id
	  pid int(11) unsigned NOT NULL default '0',		# 帖子id
	  subject char(32) NOT NULL default '',			# 主题
	  credits  int(11) unsigned NOT NULL default '0',	# 加减积分
	  dateline int(11) unsigned NOT NULL default '0',	# 时间
	  action char(16) NOT NULL default '',			# digest|top|delete|undigest|untop
	  PRIMARY KEY (logid),
	  KEY (uid, logid)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
	
	DROP TABLE IF EXISTS bbs_attach_download;
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
	
	alter table bbs_attach drop index uid;
	
	alter table bbs_attach add index (uid, dateline);
	
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
