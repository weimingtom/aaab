<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class user_control extends common_control {
	
	function __construct() {
		parent::__construct();
	}
	
	// ajax 登录
	public function on_login() {
		
		// hook user_login_1.php
		
		if($this->form_submit()) {
			$userdb = $error = array();
			$email = core::gpc('email', 'P');
			$password = core::gpc('password', 'P');
			
			if(empty($email)) {
				$error['email'] = '请填写用户名或Email';
				$this->message($error);
			}
			
			// hook user_login_before_check.php
			
			$uid = $this->user->get_uid_by_email($email);
			$userdb = $this->user->get($uid);
			if(empty($userdb)) {
				$userdb = $this->user->get_user_by_username($email);
				if(empty($userdb)) {
					$error['email'] = '用户名/Email 不存在';
					log::write('EMAIL不存在:'.$email, 'login.php');
					$this->message($error);
				}
			}
			
			if(!$this->user->verify_password($password, $userdb['password'], $userdb['salt'])) {
				$error['password'] = '密码错误!';
				$log_password = '******'.substr($password, 6);
				log::write("密码错误：$email - $log_password", 'login.php');
				$this->message($error);
			}
			
			// hook user_login_after.php
			
			if(misc::values_empty($error)) {
				$error = array();
				$error['user']['username'] =  $userdb['username'];
				$error['user']['auth'] =  $this->user->get_xn_auth($userdb);
				$error['user']['groupid'] =  $userdb['groupid'];
				
				// hook user_login_succeed.php
				$this->user->set_login_cookie($userdb);
				
				// 更新在线列表
				$this->update_online();
			}
			$this->message($error);
		} else {
			
			// hook user_login_before.php
			$this->view->display('user_login_ajax.htm');
		}
	}
	
	public function on_logout() {
		// hook user_logout_1.php
		$error = array();
		if($this->form_submit()) {
			
			// hook user_logout_after.php
			misc::set_cookie($this->conf['cookiepre'].'auth', '', 0, '/');
			$this->message($error);
		} else {
			
			// hook user_logout_before.php
			$this->view->display('user_logout_ajax.htm');
		}
	}
	
	// ajax 注册
	public function on_create() {
		if(!$this->conf['reg_on']) {
			$this->message('当前注册功能已经关闭。');
		}
		// hook user_create_1.php
		if($this->form_submit()) {
			// 接受数据
			$userdb = $error = array();
			$email = core::gpc('email', 'P');
			$username = core::gpc('username', 'P');
			$password= core::gpc('password', 'P');
			$password2 = core::gpc('password2', 'P');
			
			// check 数据格式
			$error['email'] = $this->user->check_email($email);
			$error['email_exists'] = $this->user->check_email_exists($email);
			
			// 如果email存在
			if($error['email_exists']) {
				// 如果该Email一天内没激活，则删除掉，防止被坏蛋“占坑”。
				$uid = $this->user->get_uid_by_email($email);
				$_user = $this->user->read($uid);
				if($_user['groupid'] == 6 && $_SERVER['time'] - $_user['regdate'] > 86400) {
					$this->user->_delete($uid);
					$error['email_exists'] = '';
				}
			}
			$error['username'] = $this->user->check_username($username);
			$error['username_exists'] = $this->user->check_username_exists($username);
			$error['password'] = $this->user->check_password($password);
			$error['password2'] = $this->user->check_password2($password, $password2);
			
			$groupid = $this->conf['reg_email_on'] ? 6 : 11;
			$salt = rand(100000, 999999);
			$user = array(
				'username'=>$username,
				'email'=>$email,
				'password'=>$this->user->md5_md5($password, $salt),
				'groupid'=>$groupid,
				'salt'=>$salt,
			);
			
			// hook user_create_after.php
			
			// 判断结果
			if(misc::values_empty($error)) {
				$error = array();
				$uid = $this->user->create($user);
				if($uid) {
					// 发送激活邮件
					if($this->conf['reg_email_on']) {
						try {
							$this->send_active_mail($uid, $username, $email, $error);	// $error['email_smtp_url']
						} catch(Exception $e) {
							$error['emailsend'] = '激活邮件发送失败！';
						}
					}
					
					// 此处由 $error 携带部分用户信息返回。
					$userdb = $this->user->get($uid);
					$error[$this->conf['cookiepre'].'username'] = $userdb['username'];
					$error[$this->conf['cookiepre'].'auth'] = $this->user->get_xn_auth($userdb);
					
					$error['user'] = array();
					$error['user']['username'] = $userdb['username'];
					$error['user']['auth'] = $this->user->get_xn_auth($userdb);
					$error['user']['groupid'] = $userdb['groupid'];
					
					$this->user->set_login_cookie($userdb);
					$this->runtime->update_bbs('users', '+1');
					$this->runtime->update_bbs('todayusers', '+1');
					$this->runtime->update_bbs('newuid', $uid);
					$this->runtime->update_bbs('newusername', $userdb['username']);
					
					// hook user_create_succeed.php
					
				}
			}
			$this->message($error);
		} else {
			
			// hook user_create_before.php
			$this->view->display('user_create_ajax.htm');
		}
	}
	
	// 重新发送激活连接
	public function on_reactive() {
		// 检查是否已经激活
		$user = $this->user->read($this->_user['uid']);
		if(empty($user)) {
			$this->message('用户不存在！');
		}
		if($user['groupid'] != 6) {
			$this->message('您的账户已经激活，无需再次获取激活码！');
		}
		
		// 判断上次激活的时间（这里重用注册时间）
		if($_SERVER['time'] - $user['regdate'] > 86400) {
			$error = array();
			try {
				
				// hook user_reactive.php
				$this->send_active_mail($user['uid'], $user['username'], $user['email'], $error);
				
				// 更新最后发送的时间，防止重复发送
				$user['regdate'] = $_SERVER['time'];
				$this->user->update($user['uid'], $user);
			} catch(Exception $e) {
				
				log::write('发送激活码失败:'.$user['email'], 'login.php');
				
				$this->message('可能服务器繁忙，发送邮件失败，请您明天再来尝试获取激活码！');
			}
			
			if(empty($error)) {
				$this->message($error);
			} else {
				$emailarr = explode('@', $user['email']);
				$emailinfo = $this->mmisc->get_email_site($emailarr[1]);
				$url = "<a href=\"$emailinfo[url]\" target=\"_blank\"><b>【$emailinfo[name]】</b></a>";
				$this->message('已经重新给您的信箱 ('.$user['email'].') 发送了激活码邮件：登录：'.$url);
			}
			
		} else {
			$this->message('一天只能获取激活一次激活码！');
		}
	}
	
	// 重设密码，邮箱验证
	public function on_resetpw() {
	
	}
	
	// email 激活
	public function on_active() {
		$code = core::gpc('code');
		if(empty($code)) {
			$this->message('缺少参数 code。');
		}
		$md5_key = md5($this->conf['public_key']);
		$s = decrypt($code, $md5_key);
		$arr = explode("\t",  $s);
		if(empty($arr) || empty($s)) {
			$this->message('参数解密错误。');
		}
		
		$uid = $arr[0];
		$time = $arr[1];
		if($_SERVER['time'] - $time > 86400) {
			$this->message('激活链接已经过期（超过一天），请重新注册。');
		}
		
		$user = $this->user->read($uid);
		$this->check_user_exists($user);
		if($user['groupid'] != 6) {
			$this->message('您的账户已经激活，不需要重新激活。');
		}
		$user['groupid'] = 11;
		$this->user->update($uid, $user);
		
		// hook user_active.php
		
		// 手工设置 cookie.
		$this->user->set_login_cookie($user);
		
		$this->message($user['username'].'，您好！您的账号激活成功！', 1, $this->conf['app_url']);
	}
	
	public function on_uploadavatar() {
		$uid = $this->_user['uid'];
		$this->check_forbidden_group();
		$user = $this->user->read($uid);
		$this->check_user_access($user, 'attach');
		
		$dir = image::set_dir($uid, $this->conf['upload_path'].'avatar/');
		$destfile = $this->conf['upload_path']."avatar/$dir/{$uid}_tmp.jpg";
		$desturl = $this->conf['upload_url']."avatar/$dir/{$uid}_tmp.jpg?".$_SERVER['time'];
		
		$arr = image::thumb($_FILES['Filedata']['tmp_name'], $destfile, 800, 800);
		$json = array('width'=>$arr['width'], 'height'=>$arr['height'], 'body'=>$desturl);
		
		// hook user_uploadavatar.php
		$this->message($json, 1);
	}
	
	public function on_clipavatar() {
		$uid = $this->_user['uid'];
		$this->check_login();
		$user = $this->user->read($uid);
		$this->check_user_exists($user);
		
		$x = intval(core::gpc('x', 'P'));
		$y = intval(core::gpc('y', 'P'));
		$w = intval(core::gpc('w', 'P'));
		$h = intval(core::gpc('h', 'P'));
		$dir = image::get_dir($uid);
		
		$srcfile = $this->conf['upload_path']."avatar/$dir/$user[uid]_tmp.jpg";
		$tmpfile = $this->conf['upload_path']."avatar/$dir/$user[uid]_tmp_tmp.jpg";
		$smallfile = $this->conf['upload_path']."avatar/$dir/$user[uid]_small.gif";
		$middlefile = $this->conf['upload_path']."avatar/$dir/$user[uid]_middle.gif";
		$bigfile = $this->conf['upload_path']."avatar/$dir/$user[uid]_big.gif";
		$bigurl = $this->conf['upload_url']."avatar/$dir/$user[uid]_big.gif?".$_SERVER['time'];
		
		image::clip($srcfile, $tmpfile, $x, $y, $w, $h);
		
		image::thumb($tmpfile, $smallfile, $this->conf['avatar_width_small'], $this->conf['avatar_width_small']);
		image::thumb($tmpfile, $middlefile, $this->conf['avatar_width_middle'], $this->conf['avatar_width_middle']);
		image::thumb($tmpfile, $bigfile, $this->conf['avatar_width_big'], $this->conf['avatar_width_big']);
		
		unlink($srcfile);
		unlink($tmpfile);
		
		if(is_file($middlefile)) {
			$user['avatar'] = $_SERVER['time'];
			$this->user->update($uid, $user);
			
			// hook user_clipavatar.php
			$this->message($bigurl, 1);
		} else {
			$this->message('保存失败', 0);
		}
	}
	
	// 发送激活连接 $user[username]
	private function send_active_mail($uid, $username, $email, &$error) {
		$emailarr = explode('@',$email);
		$emailinfo = $this->mmisc->get_email_site($emailarr[1]);	
		$error['email_smtp_url'] = $emailinfo['url'];
		$error['email_smtp_name'] = $emailinfo['name'];
		$md5_key = md5($this->conf['public_key']);
		$code = encrypt("$uid	$_SERVER[time]", $md5_key);
		$url = "?user-active-code-$code.htm";
		$subject = '请激活您在'.$this->conf['app_name'].'注册的账号！';
		$message = "尊敬的用户 {$username}，您好！<br />
			您在本站注册的账号还需一步完成注册，请点击以下链接激活您的账号：<br />
			<a href=\"$url\">$url</a>";
		
		// hook user_send_active_mail.php
		$error['emailsend'] = $this->mmisc->sendmail($username, $email, $subject, $message);
	}
	
	//hook user_control.php
	
}

?>