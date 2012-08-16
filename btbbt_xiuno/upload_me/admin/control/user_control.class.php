<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class user_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	public function on_index() {
		$this->view->display('user.htm');
	}
	
	public function on_list() {
		$this->_title[] = '用户列表';
		$this->_nav[] = '<a href="./">用户列表</a>';
		
		$keyword = urldecode(core::gpc('keyword', 'R'));
		$keyword_url = urlencode($keyword);
		$cond = array();
		if($keyword) {
			if(is_numeric($keyword)) {
				$cond = array('uid'=>intval($keyword));
			} elseif(strpos($keyword, '@') !== FALSE) {
				$cond = array('email'=>$keyword);
			} else {
				$cond = array('username'=>$keyword);
			}
		}
		
		if($this->form_submit()) {
			$uids = core::gpc('uids', 'P');
			if(!empty($uids)) {
				foreach($uids as $uid) {
					$uid = intval($uid);
					if($uid != 1) {
						$this->user->xdelete($uid);
					}
				}
			}
		}
		
		$page = misc::page();
		$users = $cond ? 1 : $this->user->count();
		$userlist = $this->user->index_fetch($cond, array(), ($page - 1) * $this->conf['pagesize'], $this->conf['pagesize']);
		foreach($userlist as &$user) {
			$this->user->format($user);
			$user['groupname'] = $this->group->groupid_to_name($user['groupid']);
		}
		
		$pages = misc::pages("?user-list.htm", $users, $page, $this->conf['pagesize']);
		
		$this->view->assign('page', $page);
		$this->view->assign('$keyword', $$keyword);
		$this->view->assign('pages', $pages);
		$this->view->assign('userlist', $userlist);
		
		// hook admin_user_list.php
		
		$this->view->display('user_list.htm');
	}
	
	// 批量添加
	public function on_create() {
		$this->_title[] = '用户注册';
		$this->_nav[] = '用户注册';
		
		$user = $error = array();
		if($this->form_submit()) {
			
			// 接受数据
			$user['email'] = core::gpc('email', 'P');
			$user['username'] = core::gpc('username', 'P');
			$user['password'] = core::gpc('password', 'P');
			$password2 = core::gpc('password2', 'P');
			$user['regdate'] = $_SERVER['time'];
			
			// check 数据格式
			$error['email'] = $this->user->check_email($user['email']);
			$error['email_exists'] = $this->user->check_email_exists($user['email']);
			$error['username'] = $this->user->check_username($user['username']);
			$error['password'] = $this->user->check_password($user['password']);
			$error['password2'] = $this->user->check_password2($user['password'], $password2);
			
			// 判断结果
			if(misc::values_empty($error)) {
				$error = array();
				$salt = rand(100000, 999999);
				$user['salt'] = $salt;
				$user['password'] = $this->user->md5_md5($user['password'], $salt);
				$this->user->create($user);
				$this->runtime->update_bbs('todayusers', '+1');
			}
		}
		
		$this->view->assign('user', $user);
		$this->view->assign('error', $error);
		
		// hook admin_user_create.php
		
		$this->view->display('user_create.htm');
	}
	
	// 修改
	public function on_update() {
		$this->_title[] = '修改用户资料';
		$this->_nav[] = '修改用户资料';
		
		$uid = intval(core::gpc('uid'));
		$user = $this->user->get($uid);
		$this->check_user_exists($user);
		
		$input = $error = array();
		if($this->form_submit()) {
			$post = array('uid'=>$uid);
			
			$post['email'] = core::gpc('email', 'P');
			$post['groupid'] = intval(core::gpc('groupid', 'P'));
			$post['threads'] = intval(core::gpc('threads', 'P'));
			$post['posts'] = intval(core::gpc('posts', 'P'));
			$post['digests'] = intval(core::gpc('digests', 'P'));
			$post['credits'] = intval(core::gpc('credits', 'P'));
			$post['golds'] = intval(core::gpc('golds', 'P'));
			$post['money'] = intval(core::gpc('money', 'P'));
			$post['password'] = core::gpc('password', 'P');
			
			// check 数据格式
			$error['email'] = $this->user->check_email($post['email']);
			if(!empty($post['password'])) {
				$error['password'] = $this->user->check_password($post['password']);
				$post['password'] = $this->user->md5_md5($post['password'], $user['salt']);
			} else {
				$post['password'] = $user['password'];
			}
			if(misc::values_empty($error)) {
				$error = array();
				$user = array_merge($user, $post);
				$this->user->update($uid, $user);
			}
		}
		
		//$input['username'] = form::get_text('username', $user['username'], 300);
		//$input['email'] = form::get_text('email', $user['email'], 300);
		$grouplist = $this->group->get_grouplist();
		$grouparr = misc::arrlist_key_values($grouplist, 'groupid', 'name');
		$input['groupid'] = form::get_select('groupid', $grouparr, $user['groupid']);
		$input['threads'] = form::get_text('threads', $user['threads'], 100);
		$input['posts'] = form::get_text('posts', $user['posts'], 100);
		$input['digests'] = form::get_text('digests', $user['digests'], 100);
		$input['credits'] = form::get_text('credits', $user['credits'], 100);
		$input['golds'] = form::get_text('golds', $user['golds'], 100);
		$input['money'] = form::get_text('money', $user['money'], 100);
		
		$this->view->assign('input', $input);
		$this->view->assign('user', $user);
		$this->view->assign('error', $error);
		
		// hook admin_user_update.php
		
		$this->view->display('user_update.htm');
	}
	
	// 读取
	public function on_read() {
		$this->_title[] = '查看用户资料';
		$this->_nav[] = '查看用户资料';
		
		$uid = intval(core::gpc('uid'));
		empty($uid) && $uid = $this->_user['uid'];
		$user = $this->user->get($uid);
		$this->check_user_exists($user);
		$this->user->format($user);
		$this->view->assign('user', $user);
		
		// hook admin_user_read.php
		
		$this->view->display('user_read.htm');
	}
	
	public function on_delete() {
		$this->_title[] = '删除用户';
		$this->_nav[] = '删除用户';
		
		$uid = intval(core::gpc('uid'));
		$user = $this->user->get($uid);
		$this->check_user_exists($user);
		
		if($uid == 1) {
			$this->message('NO.1 管理员不能删除！');
		} else {
			$this->user->xdelete($uid);
			
			// hook admin_user_delete.php
			
			$this->message('删除用户成功！', TRUE, $_SERVER['HTTP_REFERER']);
		}
	}
	
	// 禁言用户，加时间戳
	public function on_access() {
		$this->_title[] = '禁止用户';
		$this->_nav[] = '禁止用户';
		
		$uid = intval(core::gpc('uid'));
		$user = $this->user->read($uid);
		$this->check_user_exists($user);
		$access = $this->user_access->read($uid);
		if(empty($access)) {
			$access = array(
				'uid'=>$uid,
				'allowread'=>1,
				'allowthread'=>1,
				'allowpost'=>1,
				'allowattach'=>1,
				'expiry'=>$_SERVER['time'] + 86400 * 365
			);
		}
		
		$input = $error = array();
		if($this->form_submit()) {
			$post = array('uid'=>$uid);
			$post['allowpost'] = intval(!core::gpc('allowpost', 'P'));
			$post['allowthread'] = intval(!core::gpc('allowthread', 'P'));
			$post['allowattach'] = intval(!core::gpc('allowattach', 'P'));
			$post['allowread'] = intval(!core::gpc('allowread', 'P'));
			$post['expiry'] = strtotime(core::gpc('expiry', 'P'));
			
			if(misc::values_empty($error)) {
				$error = array();
				$access = array_merge($access, $post);
				$this->user_access->update($uid, $access);
			}
		}
		
		$input['allowpost'] = form::get_checkbox_yes_no('allowpost', !$access['allowpost']);
		$input['allowthread'] = form::get_checkbox_yes_no('allowthread', !$access['allowthread']);
		$input['allowattach'] = form::get_checkbox_yes_no('allowattach', !$access['allowattach']);
		$input['allowread'] = form::get_checkbox_yes_no('allowread', !$access['allowread']);
		$input['expiry'] = form::get_text('expiry', misc::date($access['expiry'], 'Y-n-j'), 150);
		
		$this->view->assign('input', $input);
		$this->view->assign('user', $user);
		$this->view->assign('error', $error);
		
		// hook admin_user_access.php
		
		$this->view->display('user_access.htm');
	}
	
	//hook user_control.php
	
}

?>