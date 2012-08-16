<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class admin_control extends common_control {
	
	// hook admin_control.php
	
	function __construct() {
		
		// hook admin_control_before_construct.php
		parent::__construct();
		// hook admin_control_after_construct.php
		
		if($this->_user['groupid'] != 1) {
			// 这里可能会有跨站脚本导致的提交，可以触发安全警报。管理员应该定期查看后台日志。
			log::write("非法尝试后台登录", 'login.php');
			$this->message('您不是管理员，没有权限进入后台！', 0);
		}
		
		// hook admin_control_after_check.php
	}
}

?>