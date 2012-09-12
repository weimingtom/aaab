//<?php
$synctype = core::gpc('synctype');	// 1登录，2注册
if ($synctype && empty($this->_user['uid'])) 
{
	$file = BBS_PATH.'plugin/huaban_sync/conf.php';
	$pconf = include $file;
	
	session_start();
	
	switch ($synctype) {
		case 'login':	// 登录
			$_SESSION['is_huaban_sync_login'] = 1;
			$this->view->assign('huaban_sync_type', $synctype);
			break;
		case 'register': // 注册
			$_SESSION['is_huaban_sync_register'] = 1;
			$this->view->assign('huaban_sync_type', $synctype);
			break;
	}
} 