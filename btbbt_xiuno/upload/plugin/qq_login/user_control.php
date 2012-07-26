
	// qq 登录成功后跳转到的页面，显示欢迎信息
	public function on_qqloginok() {
	
		// 设置自己的 cookie
		echo '<html><head><meta property="qc:admins" content="75557137776015676375" />
</head><body><script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" charset="utf-8" data-callback="true"></script> <script>alert(1111111111);/*opener.qq_login_recall();window.close();*/</script></body></html>';
		exit;
	}
	
	public function on_qqlogin() {
						
		//$cookie_id = core::gpc('__qc__k', 'C');
		$qq_openid = core::gpc('qq_openid');
		$md5_qq_openid = md5($qq_openid);
		
		$error = $post = array();
		
		$post['username'] = htmlspecialchars(strip_tags(core::gpc('qq_name')));
		$post['password'] = $md5_qq_openid;
		$post['email'] = $md5_qq_openid.'@.qq.com';
		$post['groupid'] = 11;
		$post['qq_openid'] = $qq_openid;
		
		// 自动生成用户名
		$error = $this->user->check_username($post['username']);
		if($error) {
			misc::set_cookie('__qc__k', '');
			$this->message($error, 0);
		}
		$username = $this->get_username($post['username']);
		if(empty($username)) {
			misc::set_cookie('__qc__k', '');
			$this->message('用户名被占用，请重新登录，生成新的随机用户名。', 0);
		}
		$post['username'] = $username;
		
		$user = $this->user->get_uid_by_email($md5_qq_openid);
		if(!empty($user)) {
			$this->user->set_login_cookie($user);
			$this->message('登录成功。', 1);
		} else {
			$getuid = $this->user->create($post);
			$user = $this->user->read($getuid);
			$this->user->set_login_cookie($user);
			$this->message('注册成功。', 1);
		}
	}
	
	private function get_username($username) {
		$exists = $this->user->check_username_exists($username);
		if($exists) {
			list($y, $m, $d, $h, $i, $s) = explode('-', date('y-m-d-h-i-s', time()));
			$uid = $this->user->maxid();
			$usernames = array($username.'_'.$d, $username.'_'.$m.$d, $username.'_'.$y.$m.$d, $username.'_'.$y.$m.$d.$h, $username.'_'.$y.$m.$d.$h.$i, $username.'_'.$y.$m.$d.$h.$i.$s, $uid.'-'.$_SERVER['time'].rand(100, 999));
			foreach($usernames as $v) {
				if(!$this->user->check_username_exists($v)) {
					return $v;
				}
			}
			return '';
		} else {
			return $username;
		}
	}
	