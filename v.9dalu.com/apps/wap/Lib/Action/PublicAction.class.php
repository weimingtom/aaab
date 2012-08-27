<?php
class PublicAction extends Action {
	
	public function login() {
		// 登录验证
		$passport = service('Passport');
		if ($passport->isLogged()) {
			redirect(U('wap/Index/index'));
		}
		global $ts;
		$this->assign('site_name', $ts['site']['site_name'].' WAP版');
		$this->display();
	}
	
	public function doLogin() {
		if (empty($_POST['email']) || empty($_POST['password'])) {
			redirect(U('wap/Public/login'), 3, '用户名和密码不能为空');
		}
		if (!isValidEmail($_POST['email'])) {
			redirect(U('wap/Public/login'), 3, 'Email格式错误，请重新输入');
		}
		if ($user = service('Passport')->getLocalUser($_POST['email'], $_POST['password'])) {
			if ($user['is_active'] == 0) {
				redirect(U('wap/Public/login'), 3, '帐号尚未激活，请激活后重新登录');
			}
            service('Passport')->registerLogin($user, intval($_POST['remember']) === 1);
            
            redirect(U('wap/Index/index'));
		} else {
			redirect(U('wap/Public/login'), 3, '帐号或密码错误，请重新输入');
		}
	}
	
	public function logout() {
		service('Passport')->logoutLocal();
		redirect(U('wap/Public/login'));
	}
	
	// 访问正常版
	public function wapToNormal() {
		$_SESSION['wap_to_normal'] = '1';
		cookie('wap_to_normal', '1', 3600*24*365);
		redirect(U('home'));
	}
}