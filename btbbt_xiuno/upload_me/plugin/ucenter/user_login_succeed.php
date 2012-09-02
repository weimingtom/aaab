	
	
				// ------------------------> user_login_succeed.php
				
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
				
				$applist = $uc->index_fetch('applications', 'appid', array(), array(), 0, 20);
				$u = $uc->get("members-uid-$uid");
				
				// 从 ucenter 获取用户，如果用户在 ucenter 中，但是不在应用中，则插入到应用。
				$url = array();
				foreach($applist as $appid => $app) {
					if($app['synlogin'] && $app['appid'] == $ucconf['uc_appid']) continue;
					$code = urlencode(dz_authcode('action=synlogin&username='.$u['username'].'&uid='.$u['uid'].'&password='.$u['password']."&time=".$_SERVER['time'], 'ENCODE', $app['authkey']));
					$url[] = "$app[url]/api/uc.php?time=$_SERVER[time]&code=$code";
				}
				$error['sync_url'] = $url;
				
				// --------------------------> end
				
				