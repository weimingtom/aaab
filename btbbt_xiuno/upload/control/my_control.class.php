<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class my_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->check_login();
		
		// 初始化用户信息
		$uid = $this->_user['uid'];
		$user = $this->user->get($uid);
		$this->check_user_exists($user);
		$this->user->format($user);
		$user['groupname'] = $_SERVER['miscarr']['group'][$user['groupid']];
		
		$this->_user = $user;
	}
	
	public function on_index() {
		
		// hook my_index.php
		$this->on_profile();
	}
	
	// ------------------> 个人资料
	
	public function on_profile() {
		$this->_checked['my_profile'] = 'class="checked"';
		$this->_checked['profile'] = 'class="checked"';
		
		$this->_title[] = '基本资料';
		$this->_nav[] = '基本资料';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
			
		// hook my_profile.php
		$this->view->display('my_profile.htm');
	}
	
	public function on_homepage() {
		$this->_checked['my_profile'] = 'class="checked"';
		$this->_checked['homepage'] = 'class="checked"';
		
		$this->_title[] = '修改个人信息';
		$this->_nav[] = '修改个人信息';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$error = array();
		if($this->form_submit()) {
			$homepage = core::gpc('homepage', 'P');
			$signature = core::gpc('signature', 'P');
			
			// 检查原密码是否正确
			$error['homepage'] = $this->user->check_homepage($homepage);
			$error['signature'] = $this->user->check_signature($signature);
			
			// hook my_homepage_before.php
			if(misc::values_empty($error)) {
				$user = $this->user->read($uid);
				$user['homepage'] = $homepage;
				$user['signature'] = $signature;
				$this->user->update($uid, $user);
				$error = array();
				// hook my_homepage_after.php
			}
		}
		$this->user->format($user);
		
		$this->view->assign('error', $error);
		
		// hook my_homepage.php
		$this->view->display('my_homepage.htm');
	}
	
	public function on_password() {
		$this->_checked['my_profile'] = 'class="checked"';
		$this->_checked['password'] = 'class="checked"';
		
		$this->_title[] = '修改密码';
		$this->_nav[] = '修改密码';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$error = array();
		if($this->form_submit()) {
			$password = core::gpc('password', 'P');
			$newpassword = core::gpc('newpassword', 'P');
			$newpassword2 = core::gpc('newpassword2', 'P');
			
			// 检查原密码是否正确
			$error['password'] = $this->user->verify_password($password, $_user['password'], $_user['salt']) ? '' : '旧密码错误';
			$error['newpassword'] = $this->user->check_password($newpassword);
			$error['newpassword2'] = $this->user->check_password2($newpassword, $newpassword2);
			// hook my_password_before.php
			
			if(misc::values_empty($error)) {
				$user = $this->user->read($uid);
				$user['password'] = $this->user->md5_md5($newpassword, $user['salt']);
				$this->user->update($uid, $user);
				$error = array();
				
				// 重新设置 cookie
				$this->user->set_login_cookie($user);
				
				// hook my_password_after.php
			}
		}
		
		$this->view->assign('error', $error);
		// hook my_password.php
		$this->view->display('my_password.htm');
	}
	
	public function on_avatar() {
		$this->_checked['my_profile'] = 'class="checked"';
		$this->_checked['avatar'] = 'class="checked"';
		
		$this->_title[] = '修改头像';
		$this->_nav[] = '修改头像';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$this->fix_swfupload_md5_auth();
		// hook my_avatar.php
		$this->view->display('my_avatar.htm');
	}
	
	// -----------------> 我的发帖
	public function on_post() {
		$this->_checked['my_post'] = 'class="checked"';
		
		$this->_title[] = '我的帖子';
		$this->_nav[] = '我的帖子';
		
		// hook my_post_before.php
		
		// 翻页：上一页，下一页
		$page = misc::page();
		$uid = $this->_user['uid'];
		$user = $this->_user;
		
		$page = misc::page();
		$pagesize = 40;
		$mypostlist = $this->mypost->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-post.htm", count($mypostlist), $page, $pagesize);
		
		foreach($mypostlist as &$post) {
			$post['forumname'] = isset($_SERVER['miscarr']['forum'][$post['fid']]) ? $_SERVER['miscarr']['forum'][$post['fid']] : '';
			$this->mypost->format($post);
		}
		
		$this->view->assign('pages', $pages);
		$this->view->assign('mypostlist', $mypostlist);
		
		// hook my_post_after.php
		$this->view->display('my_post.htm');
	}
	
	// -----------------------> 我的联系人
	
	// 我的关注，一次全部取出。最多100个。
	public function on_follow() {
		$this->_checked['my_follow'] = 'class="checked"';
		$this->_checked['follow'] = 'class="checked"';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$this->_title[] = '我的关注';
		$this->_nav[] = '我的关注';
		
		$page = misc::page();
		$pagesize = 64;
		$followlist = $this->follow->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-follow.htm", count($followlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('userlist', $followlist);
		
		// hook my_follow.php
		$this->view->display('my_follow.htm');
		
	}
	
	// 我的粉丝，100个
	public function on_followed() {
		$this->_checked['my_follow'] = 'class="checked"';
		$this->_checked['followed'] = 'class="checked"';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$this->_title[] = '我的粉丝';
		$this->_nav[] = '我的粉丝';
		
		$page = misc::page();
		$pagesize = 64;
		$followedlist = $this->follow->get_followedlist_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-follow.htm", count($followedlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('userlist', $followedlist);
		
		// hook my_followed.php
		$this->view->display('my_followed.htm');
	}
	
	
	// 最近联系人 40 个
	public function on_pm() {
		$this->_checked['my_follow'] = 'class="checked"';
		$this->_checked['pm'] = 'class="checked"';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$this->_title[] = '最近联系人';
		$this->_nav[] = '最近联系人';
		
		// hook my_pm_before.php
		$userlist = $this->pmnew->get_recent_userlist($uid);
		
		$this->view->assign('userlist', $userlist);
		// hook my_pm_after.php
		$this->view->display('my_pm.htm');
	}
	
	
	// ----------------------------> 我的财富
	
	// 我的金币，支付记录
	public function on_pay() {
		$this->_checked['my_wealth'] = 'class="checked"';
		$this->_checked['pay'] = 'class="checked"';
		
		$this->_title[] = '支付记录';
		$this->_nav[] = '支付记录';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		// hook my_pay_before.php
		$page = misc::page();
		$pagesize = 20;
		$paylist = $this->pay->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-pay.htm", count($paylist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('paylist', $paylist);
		
		// hook my_pay_after.php
		$this->view->display('my_pay.htm');
	}
	
	// 收入记录
	public function on_income() {
		$this->_checked['my_wealth'] = 'class="checked"';
		$this->_checked['income'] = 'class="checked"';
		
		$this->_title[] = '收入记录';
		$this->_nav[] = '收入记录';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		// hook my_income_before.php
		
		// 简单分页
		$page = misc::page();
		$pagesize = 20;
		$incomelist = $this->attach_download->get_list_by_uploaduid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-income.htm", count($incomelist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('incomelist', $incomelist);
		
		// hook my_income_after.php
		$this->view->display('my_income.htm');
	}
	
	// -------------------------> 我的文件
	
	// 下载记录
	public function on_download() {
		$this->_checked['my_file'] = 'class="checked"';
		$this->_checked['download'] = 'class="checked"';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$this->_title[] = '下载文件';
		$this->_nav[] = '下载文件';
		
		// hook my_download_before.php
		$page = misc::page();
		$pagesize = 20;
		$downlist = $this->attach_download->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-download.htm", count($downlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('downlist', $downlist);
		
		// hook my_download_after.php
		$this->view->display('my_download.htm');
	}
	

	// 我的附件
	public function on_upload() {
		$this->_checked['my_file'] = 'class="checked"';
		$this->_checked['upload'] = 'class="checked"';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$this->_title[] = '上传文件';
		$this->_nav[] = '上传文件';
		
		// hook my_upload_before.php
		
		$page = misc::page();
		$pagesize = 20;
		$attachlist = $this->attach->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-upload.htm", count($attachlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('attachlist', $attachlist);
		
		// hook my_upload_after.php
		$this->view->display('my_upload.htm');
	}
	
	// 我的图片
	public function on_image() {
		$this->_checked['my_file'] = 'class="checked"';
		$this->_checked['image'] = 'class="checked"';
		
		$_user = $this->_user;
		$uid = $_user['uid'];
		
		$this->_title[] = '上传图片';
		$this->_nav[] = '上传图片';
		
		// hook my_image_before.php
		
		$page = misc::page();
		$pagesize = 20;
		$attachlist = $this->attach->get_imagelist_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-image.htm", count($attachlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('attachlist', $attachlist);
		
		// hook my_upload_after.php
		$this->view->display('my_image.htm');
	}
	
	// 附件所在的主题，按照 aid 倒序。
	public function on_attachthread() {
		$fid = intval(core::gpc('fid'));
		$pid = intval(core::gpc('pid'));
		$post = $this->post->read($fid, $pid);
		$this->location("?thread-index-fid-$fid-tid-$post[tid].htm");
	}
	
	// 附件下载历史，翻页显示。?my-upload.htm
	public function on_downlog() {
		$fid = intval(core::gpc('fid'));
		$aid = intval(core::gpc('aid'));
		
		$attach = $this->attach->read($aid);
		$this->attach->format($attach);
		
		// hook my_downlog_before.php
		
		$page = misc::page();
		$pagesize = 20;
		$downlist = $this->attach_download->get_list_by_aid($aid, $page, $pagesize);
		$pages = misc::simple_page("?my-downlog-aid-$aid.htm", count($downlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('downlist', $downlist);
		$this->view->assign('aid', $aid);
		$this->view->assign('attach', $attach);
		
		// hook my_downlog_after.php
		
		$this->view->display('my_downlog.htm');
	}
	
	// --------------------> 版主管理日志
	
	//hook my_control.php
}

?>