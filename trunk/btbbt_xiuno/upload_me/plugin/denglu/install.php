<?php

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

// 改文件会被 include 执行。
if($this->conf['db']['type'] != 'mongodb') {
	$db = $this->user->db;	// 与 user model 同一台 db
	$tablepre = $db->tablepre;
	$db->query("CREATE TABLE IF NOT EXISTS {$tablepre}user_denglu (
		  uid int(11) unsigned NOT NULL DEFAULT '0',
		  muid int(11) unsigned NOT NULL DEFAULT '0',
		  PRIMARY KEY(muid),
		  UNIQUE KEY(uid)
	);");
}

?>