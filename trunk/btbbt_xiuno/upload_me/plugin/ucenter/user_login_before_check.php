
			// -----------------------> user_login_before_check
			include BBS_PATH.'plugin/ucenter/dz_authcode.php';
			$ucconf = include BBS_PATH.'plugin/ucenter/conf.php';
			$dbconf = array (
				'master'=>array(
					'host'=>$ucconf['uc_host'],
					'user'=>$ucconf['uc_user'],
					'password'=>$ucconf['uc_password'],
					'name'=>$ucconf['uc_name'],
					'charset'=>$ucconf['uc_charset'],
					'tablepre'=>$ucconf['uc_tablepre'],
				), 
				'slave'=>array()
			);
			$uc = new db_mysql($dbconf);
			
			// 从UC获取用户
			$ucuser = array();
			$userlist = $uc->index_fetch('members', 'uid', array('email'=>$email), array(), 0, 1);
			empty($userlist) && $userlist = $uc->index_fetch('members', 'uid', array('username'=>$email), array(), 0, 1);
			!empty($userlist) && $ucuser = array_pop($userlist);
			if(empty($ucuser)) {
				$error['email'] = '用户名/Email 不存在';
				$this->message($error);
			}
			
			// 看是否存在于BBS应用的 db.user 中，如果不存在，则插入
			$user = array();
			$uid = $this->user->get_uid_by_email($email);
			$user = $uid ? $this->user->read($uid) : $this->user->get_user_by_username($email);
			if(empty($user)) {
				// 插入到 user.db，实现注册同步
				$arr = array(
					'uid'=>$ucuser['uid'],
					'email'=>$ucuser['email'],
					'username'=>$ucuser['username'],
					'password'=>$ucuser['password'],
					'salt'=>$ucuser['salt'],
					'regdate'=>$ucuser['regdate'],
					'groupid'=>$this->conf['reg_email_on'] ? 6 : 11
				);
				$this->user->create($arr);
				$user = $this->user->read($ucuser['uid']);
			}
			
			// -----------------------> end
			
			
			