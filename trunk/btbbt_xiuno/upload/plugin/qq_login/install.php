<?php

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

// 改文件会被 include 执行。
if($this->conf['db']['type'] != 'mongodb') {
	$db = $this->user->db;
	$tablepre = $db->tablepre;
	$db->query("ALTER TABLE {$tablepre}user ADD COLUMN qq_openid char(40) NOT NULL default '';");
}

//$db->index_create('user', array('qq_openid'=>1));
	


?>