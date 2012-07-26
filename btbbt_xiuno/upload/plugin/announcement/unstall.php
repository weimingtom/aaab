<?php

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

// 改文件会被 include 执行。
if($this->conf['db']['type'] == 'mysql') {
	$db = $this->user->get_db_instance();
	//$db->query('CREATE TABLE ...');
}


?>