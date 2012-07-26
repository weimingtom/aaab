<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class admin_control extends common_control {
	
	function __construct() {
		parent::__construct();
		
		// 这里需要为 万恶的 swfupload 放行，降低安全，而且多了这么一段补丁代码。让我再诅咒一次 flash 的安全问题。
		// 好吧，我仅仅为你留几个口，我发誓，我不会为你全开的。
		if(core::gpc(0) == 'forum' && core::gpc(1) == 'uploadicon') {
			$this->check_upload_priv();
			$uid = $this->_user['uid'];
			$user = $this->user->read($uid);
			$this->_user = $user;
		}
		
		if($this->_user['groupid'] != 1) {
			// 这里可能会有跨站脚本导致的提交，可以触发安全警报。管理员应该定期查看后台日志。
			log::write("非法尝试后台登录", 'login.php');
			$this->message('您不是管理员，没有权限进入后台！', 0);
		}
	}
}

?>