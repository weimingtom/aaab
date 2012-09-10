<?php
!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

// 改文件会被 include 执行。
if($this->conf['db']['type'] == 'mysql') {
	// 执行SQL语句
	$db = $this->user->db;
	$tablepre = $db->tablepre;
}
?>