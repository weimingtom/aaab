<?php

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

// 改文件会被 include 执行。
if($this->conf['db']['type'] != 'mongodb') {
	$db = $this->user->db;
	$tablepre = $db->tablepre;
	//$db->query("ALTER TABLE {$tablepre}user DROP COLUMN qq_openid;");
}

//$db->index_drop('user', array('qq_openid'=>1));

?>