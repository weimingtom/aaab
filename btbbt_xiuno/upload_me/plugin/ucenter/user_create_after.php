			
	
			// --------------------------> user_create_after start
	
			// 直接从底层操作 UCenter DB，避免各种 ucenter api 各种版本差异和未知错误，java, .net 等程序也可以参考此方法。
			
			if(misc::values_empty($error)) {
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
				
				$salt = substr(uniqid(rand()), -6);
				$password = md5(md5($user['password']).$salt);
				
				$user['uid'] = $uc->native_maxid('members', 'uid') + 1;	// 确定 uid，插入到 bbs db 中。
				$arr = array (
					'uid'=>$user['uid'],
					'username'=>$user['username'],
					'password'=>$password,
					'email'=>$user['email'],
					'regip'=>$_SERVER['ip'],
					'regdate'=>$_SERVER['time'],
					'salt'=>$salt,
				);
				$uc->set("members-uid-$user[uid]", $arr);	// 插入到 uc db 中
			}
	
			//--------------------------> end
			
			