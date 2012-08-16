	
	
				// ------------------------> user_login_succeed.php
				
				$applist = $uc->index_fetch('applications', 'appid', array(), array(), 0, 20);
				$u = $uc->get("members-uid-$uid");
				
				// 从 ucenter 获取用户，如果用户在 ucenter 中，但是不在应用中，则插入到应用。
				$url = array();
				foreach($applist as $appid => $app) {
					if($app['synlogin'] && $app['appid'] == $ucconf['uc_appid']) continue;
					$code = urlencode(dz_authcode('action=synlogin&username='.$user['username'].'&uid='.$user['uid'].'&password='.$user['password']."&time=".$_SERVER['time'], 'ENCODE', $app['authkey']));
					$url[] = "$app[url]/api/uc.php?time=$_SERVER[time]&code=$code";
				}
				$error['sync_url'] = $url;
				
				// --------------------------> end
				
				