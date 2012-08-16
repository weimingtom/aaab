<?php

// 独立应用

// 加载框架
define('DEBUG', 2);
define('BBS_PATH', '../../');
$conf = include BBS_PATH.'conf/conf.php';
define('FRAMEWORK_PATH', BBS_PATH.'framework2.3/');
define('FRAMEWORK_TMP_PATH', $conf['tmp_path']);
define('FRAMEWORK_LOG_PATH', $conf['log_path']);
include FRAMEWORK_PATH.'core.php';
core::init();
core::ob_start();

// 加载插件配置
$pconf = include BBS_PATH.'plugin/denglu/conf.php';
include BBS_PATH.'plugin/denglu/user_denglu.class.php';
include BBS_PATH.'model/user.class.php';
$time = $_SERVER['time'];

// 获取回调
$token = core::gpc('token');
if(empty($token)) {
	exit('token 为空。');
}
//$muid = $_GET['mediaUserID'];
//$redirect = $_GET['redirect_url'];

$appid = $pconf['denglu_appid'];
$appkey = $pconf['denglu_appkey'];

$post = create_post_body($token, $appid, $appkey);

$s = misc::get_url("http://open.denglu.cc/api/v4/user_info", 5, $post);
//$s = '{"mediaID":13,"createTime":"2012-08-09 17:04:14","friendsCount":0,"location":null,"favouritesCount":0,"screenName":"黄","profileImageUrl":"http:\/\/qzapp.qlogo.cn\/qzapp\/100232596\/EFDDF6C09915842D19F6D267297BC81C\/100","mediaUserID":19599303,"url":null,"homepage":null,"city":null,"email":null,"createdAt":"","verified":0,"description":null,"name":"黄","province":null,"domain":null,"followersCount":0,"gender":0,"statusesCount":0,"personID":18462738}';
$arr = json_decode($s, true);

if(!empty($arr['errorCode'])) {
	exit($arr['errorDescription']);
} else {
	$mediaid = $arr['mediaID'];		// qzone = 13
	$muid = $arr['mediaUserID'];		// 用户唯一id，可以用来生成一个id
	$username = $arr['name'];
	$avatarurl = $arr['profileImageUrl'];
	
	$mdenglu = new user_denglu();
	$muser = new user();
	$user = $mdenglu->read($muid);
	if(empty($user)) {
		
		// 注册用户，生成用户名，头像。
		$random = $_SERVER['time'].rand(1000,9999);
		
		// 如果用户名包含非法字符，则过滤
		$muser->safe_username($username);
		if($muser->check_username_exists($username) != '') {
			// 如果用户名被占用，则尝试在末尾添加数字，排除了不吉利的数字。
			$usernames = array($username.'1', $username.'5', $username.'6', $username.'7', $username.'8', $username.'9', $username.'10', $username.'11', $username.'12', $username.'16', $username.'18', $username.'19', 
				'denglu'.$muid, 'user'.$muid.rand(10,99), $random);
			foreach($usernames as $v) {
				if($muser->check_username_exists($v) == '') {
					$username = $v;
					break;
				}
			}
		}
		
		// 全部随机，只能通过接口登录。
		$salt = rand(100000, 999999);
		$user = array (
			'email'=>$random.'@x.xxx',
			'salt'=>$salt,
			'username'=>$username,
			'password'=>$muser->md5_md5(rand(100000000, 999999999), $salt),
			'groupid'=>11
		);
		
		// 注册用户
		$uid = $muser->create($user);
		$user = $muser->read($uid);
		
		// 更新头像
		if($avatarurl) {
			$dir = image::get_dir($uid);
			$smallfile = $conf['upload_path']."avatar/$dir/{$uid}_small.gif";
			$middlefile = $conf['upload_path']."avatar/$dir/{$uid}_middle.gif";
			$bigfile = $conf['upload_path']."avatar/$dir/{$uid}_big.gif";
			
			try {
				$s = misc::get_url($avatarurl, 5);
				file_put_contents($bigfile, $s);
				
				image::thumb($bigfile, $smallfile, $conf['avatar_width_small'], $conf['avatar_width_small']);
				image::thumb($bigfile, $middlefile, $conf['avatar_width_middle'], $conf['avatar_width_middle']);
				image::thumb($bigfile, $bigfile, $conf['avatar_width_big'], $conf['avatar_width_big']);
				$user['avatar'] = $_SERVER['time'];
				
			} catch (Exception $e) {
				$user['avatar'] = 0;
			}
			
			$muser->update($uid, $user);
			
		}
		
		$mdenglu->create(array('muid'=>$muid, 'uid'=>$uid));
		
		// 设置 cookie
		$muser->set_login_cookie($user);
		
	} else {
		$user = $muser->read($user['uid']);
		$muser->set_login_cookie($user);
	}
	
	// 关闭当前窗口，回调父窗口函数
	echo '<html><body>'.$user['username'].'，您好，登录成功，<a href="../../"><b>点击进入首页</b></a>。
		<script>
			/*
			opener.window.denglu_recall({"uid": '.$user['uid'].', "username": "'.$user['username'].'", "groupid": '.$user['groupid'].'});
			window.opener = null;
			window.open("", "_self");
			window.close();
			*/
			window.location = "../../";
		</script>
	</body></html>';
	exit;
}

function create_post_body($token, $appid, $appkey) {
	$arr = array();
	$arr['appid'] = $appid;
	$arr['sign_type'] = 'MD5';
	$arr['timestamp'] = time().'000';
	$arr['token'] = $token;
	
	ksort($arr);
	$sig = '';
	foreach($arr as $k=>$v) {
		$sig .= "$k=$v";
	}
	$sig .= $appkey;
	$arr['sign'] = md5($sig);
	
	$r = '';
	foreach($arr as $k=>$v) {
		$r .= "&$k=".urlencode($v);
	}
	$r = substr($r, 1);
	return $r;
}

function post_url($url, $timeout = 5, $post = '', $cookie = '') {
	if(ini_get('allow_url_fopen') && empty($post)) {
		// 尝试连接
		$opts = array ('http'=>array('method'=>'GET', 'timeout'=>$timeout)); 
		$context = stream_context_create($opts);  
		$html = file_get_contents($url, false, $context);  
		return $html;
	} else {
		$ip = '';
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].(!empty($matches['query']) ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
	
		if(empty($post)) {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie:$cookie\r\n\r\n";
		} else {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie:$cookie\r\n\r\n";
			$out .= $post;
		}
		$host == 'localhost' && $ip = '127.0.0.1';
		$fp = fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, TRUE);
			stream_set_timeout($fp, $timeout);
			fwrite($fp, $out);
			$s = '';
			while(!feof($fp)) {
				$s .= fgets($fp, 128);
			}
			fclose($fp);
			$s && $s = substr(strrchr($s, "\r\n\r\n"), 2);
			return $s;
		}
	}
}

?>