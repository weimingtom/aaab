<?php
require('config.php');
class uc_client {
	/**
	 * 用户注册
	 *
	 * @param string $username 	用户名
	 * @param string $password 	密码
	 * @param string $email		Email
	 * @param int $questionid	安全提问
	 * @param string $answer 	安全提问答案
	 * @return int
		-1 : 用户名不合法
		-2 : 包含不允许注册的词语
		-3 : 用户名已经存在
		-4 : email 格式有误
		-5 : email 不允许注册
		-6 : 该 email 已经被注册
		>1 : 表示成功，数值为 UID
	*/
	function uc_user_register($username, $password, $email, $questionid = '', $answer = '') {
		return $this->uc_api_post('user', 'register', array('username'=>$username, 'password'=>$password, 'email'=>$email, 'questionid'=>$questionid, 'answer'=>$answer));
	}
	
	/**
	 * 用户登陆检查
	 *
	 * @param string $username	用户名/uid
	 * @param string $password	密码
	 * @param int $isuid		是否为uid
	 * @param int $checkques	是否使用检查安全问答
	 * @param int $questionid	安全提问
	 * @param string $answer 	安全提问答案
	 * @return array (uid/status, username, password, email)
	 	数组第一项
	 	1  : 成功
		-1 : 用户不存在,或者被删除
		-2 : 密码错
	*/
	function uc_user_login($username, $password, $isuid = 0, $checkques = 0, $questionid = '', $answer = '') {
		$isuid = intval($isuid);
		$return = $this->uc_api_post('user', 'login', array('username'=>$username, 'password'=>$password, 'isuid'=>$isuid, 'checkques'=>$checkques, 'questionid'=>$questionid, 'answer'=>$answer));
		return $this->uc_unserialize($return);
	}
	
	/**
	 * 进入同步登录代码
	 *
	 * @param int $uid		用户ID
	 * @return string 		HTML代码
	 */
	function uc_user_synlogin($uid) {
		$uid = intval($uid);
		$return = $this->uc_api_post('user', 'synlogin', array('uid'=>$uid));
		return $return;
	}
	
	/**
	 * 进入同步登出代码
	 *
	 * @return string 		HTML代码
	 */
	function uc_user_synlogout() {
		$return = $this->uc_api_post('user', 'synlogout', array());
		return $return;
	}
	
	/**
	 * 编辑用户
	 *
	 * @param string $username	用户名
	 * @param string $oldpw		旧密码
	 * @param string $newpw		新密码
	 * @param string $email		Email
	 * @param int $ignoreoldpw 	是否忽略旧密码, 忽略旧密码, 则不进行旧密码校验.
	 * @param int $questionid	安全提问
	 * @param string $answer 	安全提问答案
	 * @return int
	 	1  : 修改成功
	 	0  : 没有任何修改
	  	-1 : 旧密码不正确
		-4 : email 格式有误
		-5 : email 不允许注册
		-6 : 该 email 已经被注册
		-7 : 没有做任何修改
		-8 : 受保护的用户，没有权限修改
	*/
	function uc_user_edit($username, $oldpw, $newpw, $email, $ignoreoldpw = 0, $questionid = '', $answer = '') {
		return $this->uc_api_post('user', 'edit', array('username'=>$username, 'oldpw'=>$oldpw, 'newpw'=>$newpw, 'email'=>$email, 'ignoreoldpw'=>$ignoreoldpw, 'questionid'=>$questionid, 'answer'=>$answer));
	}
	
	/**
	 *  dfopen 方式取指定的模块和动作的数据
	 *
	 * @param string $module	请求的模块
	 * @param string $action 	请求的动作
	 * @param array $arg		参数（会加密的方式传送）
	 * @return string
	 */
	function uc_api_post($module, $action, $arg = array()) {
		$s = $sep = '';
		foreach($arg as $k => $v) {
			$k = urlencode($k);
			if(is_array($v)) {
				$s2 = $sep2 = '';
				foreach($v as $k2 => $v2) {
					$k2 = urlencode($k2);
					$s2 .= "$sep2{$k}[$k2]=".urlencode($this->uc_stripslashes($v2));
					$sep2 = '&';
				}
				$s .= $sep.$s2;
			} else {
				$s .= "$sep$k=".urlencode($this->uc_stripslashes($v));
			}
			$sep = '&';
		}
		$postdata = $this->uc_api_requestdata($module, $action, $s);
		return $this->uc_fopen2(UC_API.'/index.php', 500000, $postdata, '', TRUE, UC_IP, 20);
	}
	
	/**
	 * 构造发送给用户中心的请求数据
	 *
	 * @param string $module	请求的模块
	 * @param string $action	请求的动作
	 * @param string $arg		参数（会加密的方式传送）
	 * @param string $extra		附加参数（传送时不加密）
	 * @return string
	 */
	function uc_api_requestdata($module, $action, $arg='', $extra='') {
		$input = $this->uc_api_input($arg);
		$post = "m=$module&a=$action&inajax=2&release=".UC_CLIENT_RELEASE."&input=$input&appid=".UC_APPID.$extra;
		return $post;
	}
	
	function uc_api_url($module, $action, $arg='', $extra='') {
		$url = UC_API.'/index.php?'.$this->uc_api_requestdata($module, $action, $arg, $extra);
		return $url;
	}
	
	function uc_api_input($data) {
		$s = urlencode($this->uc_authcode($data.'&agent='.md5($_SERVER['HTTP_USER_AGENT'])."&time=".time(), 'ENCODE', UC_KEY));
		return $s;
	}
	
	/**
	 * 字符串加密以及解密函数
	 *
	 * @param string $string	原文或者密文
	 * @param string $operation	操作(ENCODE | DECODE), 默认为 DECODE
	 * @param string $key		密钥
	 * @param int $expiry		密文有效期, 加密时候有效， 单位 秒，0 为永久有效
	 * @return string		处理后的 原文或者 经过 base64_encode 处理后的密文
	 *
	 * @example
	 *
	 * 	$a = authcode('abc', 'ENCODE', 'key');
	 * 	$b = authcode($a, 'DECODE', 'key');  // $b(abc)
	 *
	 * 	$a = authcode('abc', 'ENCODE', 'key', 3600);
	 * 	$b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
	 */
	function uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	
		$ckey_length = 4;	//note 随机密钥长度 取值 0-32;
					//note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
					//note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
					//note 当此值为 0 时，则不产生随机密钥
	
		$key = md5($key ? $key : UC_KEY);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
	
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
	
		$result = '';
		$box = range(0, 255);
	
		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
	
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
	
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
	
	/**
	 *  远程打开URL
	 *  @param string $url		打开的url，　如 http://www.baidu.com/123.htm
	 *  @param int $limit		取返回的数据的长度
	 *  @param string $post		要发送的 POST 数据，如uid=1&password=1234
	 *  @param string $cookie	要模拟的 COOKIE 数据，如uid=123&auth=a2323sd2323
	 *  @param bool $bysocket	TRUE/FALSE 是否通过SOCKET打开
	 *  @param string $ip		IP地址
	 *  @param int $timeout		连接超时时间
	 *  @param bool $block		是否为阻塞模式
	 *  @return			取到的字符串
	 */
	function uc_fopen2($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
			$__times__ = isset($_GET['__times__']) ? intval($_GET['__times__']) + 1 : 1;
			if($__times__ > 2) {
				return '';
			}
			$url .= (strpos($url, '?') === FALSE ? '?' : '&')."__times__=$__times__";
		return $this->uc_fopen($url, $limit, $post, $cookie, $bysocket, $ip, $timeout, $block);
	}
	
	function uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
		$return = '';
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) {
			return '';//note $errstr : $errno \r\n
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}
	
				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
	}
	
	function uc_app_ls() {
		$return = $this->uc_api_post('app', 'ls', array());
		return UC_CONNECT == 'mysql' ? $return : $this->uc_unserialize($return);
	}
	
	function uc_serialize($arr, $htmlon = 0) {
		include_once GODHOUSE_ROOT.'library/xml.class.php';
		return xml_serialize($arr, $htmlon);
	}
	
	function uc_unserialize($s) {
		include_once GODHOUSE_ROOT.'library/xml.class.php';
		return xml_unserialize($s);
	}
	
	function uc_stripslashes($string) {
		!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
		if(MAGIC_QUOTES_GPC) {
			return stripslashes($string);
		} else {
			return $string;
		}
	}
}