<?php

/*
 * Copyright (C) xiuno.com
 */

class user extends base_model{
	
	function __construct() {
		parent::__construct();
		$this->table = 'user';
		$this->primarykey = array('uid');
		$this->maxcol = 'uid';
		
		/*
		// 可以单独指定 db, 非常强大啊！方便分库，适应大规模数据环境。
		
		// 拷贝
		$conf = $this->conf;
		$conf['db'] = array (
			'type'=>'mysql',
			'mysql' => array (
				// 主 MySQL Server
				'master' => array (
						'host' => '127.0.0.1',
						'user' => 'root',
						'password' => 'root',
						'name' => 'test',
						'charset' => 'utf8',
						'tablepre' => 'xn_',
						'engine'=>'MyISAM',
				),
				// 从 MySQL Server
				'slaves' => array (
				)
			)
		);
		
		// 可以指定单独的 cache
		$conf['cache'] = array (
			'enable'=>FALSE,
			'type'=>'memcache',
			'memcache'=>array (
				'multi'=>FALSE,
				'host'=>'10.0.0.200',
				'port'=>'11211',
			)
		);
		
		// 这个model的$this->conf指向此变量，区别于其他model的$this->conf
		$this->conf = &$conf;
		*/
	}
	
	public function create($arr) {
		empty($arr['uid']) && $arr['uid'] = $this->maxid('+1');
		$arr['regdate'] = $_SERVER['time'];
		$arr['regip'] = ip2long($_SERVER['REMOTE_ADDR']);
		$arr['threads'] = 0;
		$arr['posts'] = 0;
		$arr['myposts'] = 0;
		$arr['digests'] = 0;
		$arr['avatar'] = 0;
		$arr['credits'] = 0;
		$arr['golds'] = $this->conf['reg_init_golds'];
		$arr['money'] = 0;
		$arr['follows'] = 0;
		$arr['followeds'] = 0;
		$arr['newpms'] = 0;
		$arr['newfeeds'] = 0;
		$arr['homepage'] = '';
		$arr['signature'] = '';
		$arr['accesson'] = 0;
		$arr['lastactive'] = 0;
		if($this->set($arr['uid'], $arr)) {
			$this->count('+1');
			return $arr['uid'];
		} else {
			$this->maxid('-1');
			return 0;
		}
	}
	
	public function update($uid, $arr) {
		return $this->set($uid, $arr);
	}
	
	// 更新用户用户组
	public function update_group($user, $cookie_groupid = 0) {
		$groupid = $this->group->get_groupid_by_credits($user['groupid'], $user['credits']);
		if($groupid != $user['groupid'] || ($cookie_groupid && $cookie_groupid != $user['groupid'])) {
			$this->set_login_cookie($user);
		}
	}
	
	public function read($uid) {
		return $this->get($uid);
	}

	public function _delete($uid) {
		$return = $this->delete($uid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// 根据 username 获取 uid
	public function get_uid_by_email($email) {
		// 根据非主键取数据
		$user = $this->index_fetch(array('email'=>$email), array(), 0, 1);
		if(!empty($user)) {
			list($uid, $_) = each($user);
			return $uid;
		} else {
			return FALSE;
		}
	}
	
	public function get_user_by_email($email) {
		$uid = $this->get_uid_by_email($email);
		if($uid) {
			return $this->get($uid);
		} else {
			return array();
		}
	}
	
	public function get_user_by_username($username) {
		// 根据非主键取数据
		$userlist = $this->index_fetch($cond = array('username'=>$username), $orderby = array(), $start = 0, $limit = 1);
		return $userlist ? array_pop($userlist) : array();
	}
	
	public function get_xn_auth($user) {
		if(empty($user)) {
			return '';
		}
		$xn_auth = $this->encrypt_auth($user['uid'], $user['username'], $user['password'], $user['groupid']);
		return $xn_auth;
	}
	
	public function set_login_cookie($user) {
		// 包含登录信息，重要。HTTP_ONLY
		$xn_auth = $this->get_xn_auth($user);
		misc::set_cookie($this->conf['cookiepre'].'auth', $xn_auth, $_SERVER['time'] + 86400 * 30, '/', TRUE);
	}
	
	// ----------------------> 其他杂项
	public function check_email(&$email) {
		$emaildefault = array('admin', 'system');
		if(empty($email)) {
			return 'EMAIL 不能为空';
		} elseif(utf8::strlen($email) < 6) {
			return 'Email 长度不能小于 6 个字符';
		//} elseif(utf8::strlen($email) > 32) {
		//	return 'Email 长度不能大于 32 个字符。';
		} elseif(!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)) {
			return 'Email 格式不对';
		//} elseif(str_replace($emaildefault, '', $email) != $email) {
		//	return 'Email 含有非法关键词';
		}
		return '';
	}
	
	public function check_email_exists($email) {
		if($this->get_uid_by_email($email)) {
			return 'Email 已经被注册';
		}
		return '';
	}
	
	public function check_username_exists($username) {
		if($this->get_user_by_username($username)) {
			return '用户名已经存在';
		}
		return '';
	}
	
	public function check_username(&$username) {
		$username = trim($username);
		if(empty($username)) {
			return '用户名不能为空。';
		//} elseif(utf8::strlen($username) > 16) {
		//	return '用户名太长';
		} elseif(str_replace(array("\t", "\r", "\n", ' ', '　', ',', '，', '-'), '', $username) != $username) {
			return '用户名中不能含有空格和 , - 等字符';
		//} elseif(!preg_match('#^[\w\'\-\x7f-\xff]+$#', $username)) {
		//	return '用户名必须为中文';
		} elseif(htmlspecialchars($username) != $username) {
			return '用户名中不能含有HTML字符（尖括号）';
		}
		// 最多为6个中文字符
		//misc::safe_str($username, '\'-.');
		
		return '';
	}
	
	public function safe_username(&$username) {
		$username = str_replace(array("\t", "\r", "\n", ' ', '　', ',', '，', '-'), '', $username);
		$username = htmlspecialchars($username);
	}
	
	public function check_password(&$password) {
		if(empty($password)) {
			return '密码不能为空';
		} elseif(utf8::strlen($password) > 32) {
			return '密码不能超过 32 个字符';
		}
		return '';
	}
	
	public function check_password2(&$password, &$password2) {
		if(empty($password2)) {
			return '重复输入密码不能为空';
		} elseif($password != $password2) {
			return '两次输入的密码不符合';
		}
		return '';
	}
	
	public function check_signature(&$signature) {
		$signature = htmlspecialchars($signature);
		if(utf8::strlen($signature) > 32) {
			return '签名不能超过 32 个字符';
		}
		return '';
	}
	
	public function check_homepage(&$homepage) {
		if(!empty($homepage)) {
			$homepage = htmlspecialchars($homepage);
			if(utf8::strlen($homepage) > 40) {
				return '网址不能超过 40 个字符';
			} elseif(substr($homepage, 0, 7) != 'http://') {
				return '网址必须以 http:// 开头';
			}
		}
		return '';
	}
	
	// 用来显示给用户
	public function format(&$user) {
		if(!$user) return;
		$user['regdate_fmt'] = misc::date($user['regdate'], 'Y-n-j');
		$user['regip'] = long2ip($user['regip']);
		$dir = image::get_dir($user['uid']);
		if($user['avatar']) {
			$user['avatar_small'] = $this->conf['upload_url'].'avatar/'.$dir.'/'.$user['uid'].'_small.gif?'.$user['avatar'];
			$user['avatar_middle'] = $this->conf['upload_url'].'avatar/'.$dir.'/'.$user['uid'].'_middle.gif?'.$user['avatar'];
			$user['avatar_big'] = $this->conf['upload_url'].'avatar/'.$dir.'/'.$user['uid'].'_big.gif?'.$user['avatar'];
		} else {
			$user['avatar_small'] = '';
			$user['avatar_middle'] = '';
			$user['avatar_big'] = '';
		}
		
		$user['lastactive_fmt'] = misc::date($user['lastactive'], 'Y-n-j H:i');
		$user['isonline'] = $_SERVER['time'] - $user['lastactive'] < $this->conf['online_hold_time'] ? 1 : 0;
		
		$user['groupname'] = $_SERVER['miscarr']['group'][$user['groupid']];
	}
	
	// followstatus: 0: 加关注, 1: 取消关注, 2: 互相关注, 3:取消相互关注
	public function format_follow(&$user, $myuid, $uid) {
		if($uid != $myuid) {
			$user['followstatus'] = $this->follow->check_follow($myuid, $uid);
		}
	}
	
	// ----------------------> 加密解密
	public function verify_password($password1, $password2, $salt) {
		return $this->md5_md5($password1, $salt) == $password2;
	}
	
	public function md5_md5($s, $salt = '') {
		return md5(md5($s).$salt);
	}
	
	/**
		依赖于全局变量:
			$_SERVER['REMOTE_ADDR']
			$this->conf['public_key']
		全局函数:
			encrypt()
			decrypt()
	*/
	public function encrypt_auth($uid, $username, $password, $groupid) {
		$browser = md5($_SERVER['HTTP_USER_AGENT']);
		$key = $this->conf['public_key'].$browser;
		$password = substr($password, 0, 8);
		
		$time = $_SERVER['time'];
		$ip = $_SERVER['REMOTE_ADDR'];
		
		
		// 所有项目中，不允许有\t，否则可能会被伪造
		$xn_auth = encrypt("$uid	$username	$groupid	$password	$ip	$time", $key);
		return $xn_auth;
	}
	
	public function decrypt_auth($xn_auth) {
		$browser = md5($_SERVER['HTTP_USER_AGENT']);
		$key = $this->conf['public_key'].$browser;
		
		$s = decrypt($xn_auth, $key);
		$return =  array('uid'=>0, 'username'=>'', 'groupid'=>0, 'password'=>'', 'ip_right'=>FALSE, 'cookietime'=>0);
		if(!$s) {
			return $return;
		}
		$arr = explode("\t", $s);
		if(count($arr) < 5) {
			return $return;
		}
		$return = array (
			'uid'=>intval($arr[0]),
			'username'=>$arr[1],
			'groupid'=>intval($arr[2]),
			'password'=>$arr[3],
			'ip_right'=>$_SERVER['REMOTE_ADDR'] == $arr[4],
			'cookietime'=>$arr[5],
		);
		return $return;
	}
	
	// 关联删除某个用户，清除所有相关数据，缓存，一次只能删除一个用户，否则复杂度太高，容易超时。
	public function xdelete($uid) {
		$runtime = $this->conf['runtime'];
		$user = $this->user->read($uid);
		
		// 帖子数过多，不能删除，建议禁用。
		if($user['threads'] > 10000) {
			$user['groupid'] = 7;
			$this->user->update($uid, $user);
			return FALSE;
		}
		
		// 最多 1w 篇，超出后可能会导致超时，遍历所有参与过的主题。
		$mypostlist = $this->mypost->get_list_by_uid($uid, 1, 10000);
		
		// 超出10000篇，不能删除，建议禁用此用户
		if(count($mypostlist) >  10000) {
			return FALSE;
		}
		
		$thread_return = array();
		$post_return = array();
		foreach($mypostlist as &$post) {
			$fid = $post['fid'];
			$tid = $post['tid'];
			$pid = $post['pid'];
			$thread = $this->thread->read($fid, $tid);
			
			isset($return['forum'][$fid]) &&  $return['forum'][$fid] = array('threads'=>0, 'posts'=>0, 'digests'=>0);
			isset($return['user'][$uid]) &&  $return['user'][$uid] = array('threads'=>0, 'posts'=>0, 'digests'=>0, 'credits'=>0);
			$rforum = &$return['forum'][$fid];
			$ruser = &$return['user'][$uid];
		
			// 主题
			if($thread['firstpid'] == $pid) {
				// 删除所有回复。。。更新受影响用户的发帖数，积分。这个比较麻烦。
				$return = $this->thread->xdelete($fid, $tid, FALSE, $runtime);
				$this->thread->xdelete_merge_return($thread_return, $return);
			// 回复
			} else {
				// 遍历所有页码，删除，不整理楼层！ todo: 对于高楼，这里会有性能问题！
				//if($thread['posts'] > 1000000) continue; // 为了保险，跳过高层，万恶的高楼~ 
				$totalpage = ceil($thread['posts'] / $this->conf['pagesize']);
				for($i = 1; $i <= $totalpage; $i++) {
					$postlist = $this->post->index_fetch(array('fid'=>$fid, 'tid'=>$tid, 'page'=>$i), array(), 0, $this->conf['pagesize']);
					foreach($postlist as $post) {
						if($post['uid'] == $uid) {
							$return = $this->post->xdelete($fid, $post['pid'], FALSE);
							$this->post->xdelete_merge_return($post_return, $return);
						}
					}
				}
			}
		}
		
		$this->thread->xdelete_update($thread_return);
		$this->post->xdelete_update($post_return);
		
		$this->_delete($uid);
		
		// 删除其主题
		// 删除回帖
		// 删除精华
		// 删除附件
		// 不调整楼层
		// 清除受影响的板块的缓存，包括 tidcache
		// 清除首页缓存
		// 更新板块主题数，回复数，精华数
		// xdelete 里面已经搞定上述操作
		
		return TRUE;
	}
}
?>