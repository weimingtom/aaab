<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

class common_control extends base_control {
	
	// 为了避免与 model 冲突，加下划线分开
	public $_sid = '';		// session id
	public $_user = array();	// 全局 user
	
	// header 相关
	public $_title = array();	// header.htm title
	public $_nav = array();		// header.htm 导航
	public $_seo_keywords = '';	// header.htm keywords
	public $_seo_description = '';	// header.htm description
	public $_checked = array();	// 选中状态
	
	// 计划任务
	protected $_cron_1_run = 0;	// 计划任务1 是否被激活
	
	// 初始化 _sid, _user, _title, _nav
	function __construct() {
		parent::__construct();
		$this->init_timezone();
		$this->conf['runtime'] = &$this->runtime->read_bbs();	// 析构函数会比 mysql 的析构函数早。所以不用担心mysql被释放。
		$this->init_view();
		$this->init_sid();
		$this->init_pm();
		$this->init_user();
		$this->check_ip();
		$this->check_domain();
		$this->init_cron();
		$this->init_online();
	}
	
	private function init_timezone() {
		// 不需要设置，使用gmdate()
		$offset = $this->conf['timeoffset'];
		if($offset) {
			date_default_timezone_set('Etc/GMT'.$offset);
		}
		
		// 今日凌晨0点的开始时间！
		$_SERVER['time_fmt'] = gmdate('Y-n-d H:i', $_SERVER['time'] + $offset * 3600);			// +8 hours
		$arr = explode(' ', $_SERVER['time_fmt']);
		list($y, $n, $d) = explode('-', $arr[0]);
		$_SERVER['time_today'] = gmmktime(0, 0, 0, $n, $d, $y) - $offset * 3600;	// -8 hours
	}
	
	private function init_view() {
		$this->view->assign('conf', $this->conf);
		$this->view->assign('_title', $this->_title);
		$this->view->assign('_nav', $this->_nav);
		$this->view->assign('seo_keywords', $this->_seo_keywords);
		$this->view->assign('seo_description', $this->_seo_description);
		$this->view->assign('_checked', $this->_checked);
		$this->view->assign_value('cron_1_run', $this->_cron_1_run);
	}
	
	// 初始化 sid
	private function init_sid() {
		$key = $this->conf['cookiepre'].'sid';
		if(!core::gpc($key, 'R')) {
			$sid = substr(md5($_SERVER['REMOTE_ADDR'].rand(1, 2147483647)), 0, 16); // 兼容32,64位
			misc::set_cookie($key, $sid, $_SERVER['time'] + 86400 * 30, '/');
		} else {
			$sid = core::gpc($key, 'R');
		}
		$this->_sid = $sid;
		$auth = core::gpc($this->conf['cookiepre'].'auth', 'R');
		$this->view->assign('_sid', $this->_sid);
		$this->view->assign('_auth', $auth);
		
		define('FORM_HASH', misc::form_hash($this->conf['public_key']));
	}
	
	private function init_pm() {
		// 设置短消息心跳频率
		if($this->conf['site_pv'] < 100000) { $pm_delay = 1500; }
		elseif($this->conf['site_pv'] < 1000000) { $pm_delay = 2500; }
		elseif($this->conf['site_pv'] < 6000000) { $pm_delay = 3500; }
		elseif($this->conf['site_pv'] >= 6000000) { $pm_delay = 5500; }
		$this->view->assign('pm_delay', $pm_delay);
	}
	
	// 初始化 _user, 解密 cookie
	private function init_user() {
		// 如果此处为 swfupload, 那么 user 将被初始化为游客。后面根据 md5_auth 进行提权。	
		$xn_auth = core::gpc($this->conf['cookiepre'].'auth', 'R');
		$this->_user = $this->user->decrypt_auth($xn_auth);
		if($this->_user['groupid'] == 1 && !$this->_user['ip_right']) {
			//misc::set_cookie($this->conf['cookiepre'].'auth', '', 0, '/');
			//$this->message('尊敬的管理员，系统检测到您的IP发生变化，为了您的安全，请重新登录。', 0);
		}
		
		$_SERVER['miscarr'] = $this->mcache->read('miscarr');
		$this->_user['groupname'] = $_SERVER['miscarr']['group'][$this->_user['groupid']];
		$this->view->assign('_user', $this->_user);
		
		
		
		// 站点访问权限判断 0:所有人均可访问; 1: 仅会员访问; 2:仅版主可访问; 3: 仅管理员
		if($this->conf['site_runlevel'] == 1 && $this->_user['groupid'] == 0) {
			if(!$this->conf['reg_on'] || !(core::gpc(0) == 'user' && (core::gpc(1) == 'create') || core::gpc(1) == 'login')) {
				$infoadd = $this->conf['reg_on'] ? '，您可以注册会员。' : '，当前注册已关闭。';
				$this->message('站点当前设置：只有会员能访问'.$infoadd, 0);
			}
		} elseif($this->conf['site_runlevel'] == 2 && $this->_user['groupid'] >= 11) {
			$this->message('站点当前设置：版主以上权限才能访问，（'.$this->_user['groupname'].'）不允许。', 0);
		} elseif($this->conf['site_runlevel'] == 3 && $this->_user['groupid'] != 1) {
			$this->message('站点当前设置：只有管理员才能访问。', 0);
		}
	}
	
	// 检查IP
	private function check_ip() {
		// IP 规则
		if($this->conf['iptable_on']) {
			$arr = include BBS_PATH.'conf/iptable.php';
			$blacklist = $arr['blacklist'];
			$whitelist = $arr['whitelist'];
			$ip = $_SERVER['REMOTE_ADDR'];
			if(!empty($blacklist)) {
				foreach($blacklist as $black) {
					if(substr($ip, 0, strlen($black)) == $black) {
						$this->message('对不起，您的IP ['.$ip.'] 已经被禁止，如果有疑问，请联系管理员。', 0);
					}
				}
			}
			if(!empty($whitelist)) {
				$ipaccess = FALSE;
				foreach($whitelist as $white) {
					if(substr($ip, 0, strlen($white)) == $white) {
						$ipaccess = TRUE;
						break;
					}
				}
				if(!$ipaccess) {
					$this->message('对不起，您的IP ['.$ip.'] 不允许访问，如果有疑问，请联系管理员。', 0);
				}
			}
		}
	}
	
	// 检查域名，如果不在安装域名下，跳转到安装域名。
	private function check_domain() {
		$appurl = $this->conf['app_url'];
		preg_match('#^http://([^/]+)/#', $appurl, $m);
		$installhost = $m[1];
		$host = core::gpc('HTTP_HOST', 'S');
		if($host != $m[1]) {
			$currurl = misc::get_script_uri();
			$newurl = preg_replace('#^http://([^/]+)/#', "http://$installhost/", $currurl);
			header("Location: $newurl");
			exit;
		}
	}
	
	private function init_cron() {
		$cron_1_next_time = $this->conf['runtime']['cron_1_next_time'];
		$cron_2_next_time = $this->conf['runtime']['cron_2_next_time'];
		if($_SERVER['time'] > min($cron_1_next_time, $cron_2_next_time)) {
			if($_SERVER['time'] > $cron_1_next_time) {
				$this->_cron_1_run = TRUE;
			}
			$this->cron->run();
		}
		$this->view->assign('cron_1_run', $this->_cron_1_run);
	}
	
	private function init_online() {
		// 每隔 5 分钟插入一次！ cookie 控制时间
		$lastonlineupdate = core::gpc($this->conf['cookiepre'].'lastonlineupdate', 'C');// cookie 中存放的为北京时间
		if(empty($lastonlineupdate) || $lastonlineupdate < $_SERVER['time'] - 300) {
			misc::set_cookie($this->conf['cookiepre'].'lastonlineupdate', $_SERVER['time'], $_SERVER['time'] + 86400, '/');
			$this->update_online();
		}
		// 每天更新一次用户组，发帖，回帖也会更新。
		$lastday = core::gpc($this->conf['cookiepre'].'lastday', 'C');
		if(empty($lastday) || $lastday < $_SERVER['time'] - 86400) {
			misc::set_cookie($this->conf['cookiepre'].'lastday', $_SERVER['time'], $_SERVER['time'] + 86400, '/');
			
			// 更新用户组
			$user = $this->user->read($this->_user['uid']);
			$this->user->update_group($user);
		}
	}
	
	// 初始化在线，
	public function update_online() {
		// 只执行一次。做标记位。
		static $updated = 0;
		if($updated) return;
		
		$online = array(
			'sid'=>$this->_sid,
			'uid'=>$this->_user['uid'],
			'username'=>$this->_user['username'],
			'groupid'=>$this->_user['groupid'],
			'ip'=>ip2long($_SERVER['REMOTE_ADDR']),
			'url'=>$_SERVER['REQUEST_URI'],
			'lastvisit'=>$_SERVER['time'],
		);
		$this->online->create($online);
		
		$updated = 1;
	}
	
	/*
	 * 功  能：
	 * 	提示单条信息
	 *  
	 * 用  法：
		 $this->message('站点维护中，请稍后访问！');
		$this->message('提交成功！', TRUE, '?forum-index-123.htm');
		$this->message('校验错误！', FALSE);
	 */
	public function message($message, $status = 1, $goto = '') {
		if(core::gpc('ajax', 'R')) {
			// 可能为窗口，也可能不为。
			$json = array('servererror'=>'', 'status'=>$status, 'message'=>$message);
			echo core::json_encode($json);
			exit;
		} else {
			$this->view->assign('message', $message);
			$this->view->assign('status', $status);
			$this->view->assign('goto', $goto);
			$this->view->display('message.htm');
			exit;
		}
	}
	
	// relocation
	public function location($url) {
		header("Location: ".$url);
		exit;
	}
	
	public function form_submit() {
		return misc::form_submit($this->conf['public_key']);
	}
	
	// --------------------------> bbs 权限相关和公共的方法
	
	// 检查是否登录
	public function check_login() {
		if(empty($this->_user['uid'])) {
			$this->message('您还没有登录，请先登录。', 0); // .print_r($_COOKIE, 1)
		}
	}
	
	protected function check_user_exists($user) {
		if(empty($user)) {
			$this->message('用户不存在！可能已经被删除。', 0);
		}
	}
	
	protected function check_post_exists($post) {
		if(empty($post)) {
			$this->message('帖子不存在！可能已经被删除。', 0);
		}
	}
	
	protected function check_thread_exists($thread) {
		if(empty($thread)) {
			$this->message('主题不存在！可能已经被删除。', 0);
		}
	}
	
	protected function check_forum_exists($forum) {
		if(empty($forum)) {
			$this->message('板块不存在！可能被设置了隐藏。', 0);
		}
	}
	
	// 检测权限
	protected function check_forum_access($forum, $pforum, $action = 'post') {
		$lang = array('read'=>'读贴', 'post'=>'发帖', 'thread'=>'发主题', 'attach'=>'上传附件');
		if($this->is_mod($forum, $pforum, $this->_user)) {
			return TRUE;
		}
		if(!$forum['accesson']) {
			return TRUE;
		}
		$access = $this->forum_access->read($forum['fid'], $this->_user['groupid']);
		if(empty($access)) {
			return TRUE;
		}
		if($access['allow'.$action]) {
			return TRUE;
		} else {
			$this->message('您没有对该论坛的<b>'.$lang[$action].'</b>权限！', 0);
		}
	}
	
	// 检测用户权限
	protected function check_user_access($user, $action = 'post') {
		$uid = $user['uid'];
		if($user['groupid'] == 1) {
			return TRUE;
		}
		$actiontext = array('post'=>'回复帖子', 'thread'=>'发表帖子', 'attach'=>'上传文件', 'read'=>'阅读帖子');
		if($user['accesson']) {
			$access = $this->user_access->read($uid);
			// 判断过期时间，如果已经过期，则恢复权限。
			if($access['expiry'] < $_SERVER['time']) {
				$user['accesson'] = 0;
				$this->user->update($uid, $user);
				$user = $this->user_access->_delete($uid);
				return TRUE;
			}
			if($access['allow'.$action]) {
				return TRUE;
			} else {
				$time = misc::date($access['expiry'], 'Y-n-j');
				$this->message('您没有['.$actiontext[$action].']的权限，该限制会在['.$time.']解除。如果您有疑问，请联系管理员！', 0);
			}
		} else {
			return TRUE;
		}
	}
	
	// upload 相关，可能会给人偶然扫描到。todo: 安全性
	protected function get_aid_from_tmp($uid) {
		$file = $this->conf['tmp_path'].$uid.'_aids.tmp';
		if(!is_file($file)) {
			return array();
		}
		$aids = trim(file_get_contents($file));
		return explode(' ', $aids);
	}
	
	// upload 相关
	protected function clear_aid_from_tmp($uid) {
		$file = $this->conf['tmp_path'].$uid.'_aids.tmp';
		is_file($file) && unlink($file);
	}
	
	protected function is_mod($forum, $pforum, $user) {
		// == 2 超级版主所有版块均有权限
		if($user['groupid'] == 1 || $user['groupid'] == 2) {
			return TRUE;
		} elseif($user['groupid'] == 3 || $user['groupid'] == 4) {
			return strpos(' '.$forum['modids'].' ', ' '.$user['uid'].' ') !== FALSE || strpos(' '.$pforum['modids'].' ', ' '.$user['uid'].' ') !== FALSE;
		}
		return FALSE;
	}
	
	// 检查是否为待验证用户组。
	protected function check_forbidden_group() {
		if($this->_user['groupid'] == 7) {
			$this->message('对不起，您的账户被禁止发帖。', 0);
		}
	}
	
	// 清楚某个版块的缓存
	protected function clear_forum_cache($fid, $force = FALSE) {
		// 10w pv 一下及时清除
		if($this->conf['site_pv'] <= 100000 || $force) {
			$cachefile = $this->conf['tmp_path']."forum_{$fid}_cache.php";
			is_file($cachefile) && unlink($cachefile);
			
			if($force) {
				$cachefile = $this->conf['tmp_path']."tidcache_$fid.txt";
				is_file($cachefile) && unlink($cachefile);
			}
			
			$cachefile = $this->conf['tmp_path']."forumlist_cache.php";
			is_file($cachefile) && unlink($cachefile);
			
			$cachefile = $this->conf['tmp_path']."miscmarr_cache.php";
			is_file($cachefile) && unlink($cachefile);
		}
	}
	
	protected function check_user_delete($user) {
		if(empty($user)) {
			misc::set_cookie($this->conf['cookiepre'].'auth', '', 0, '/');
			$this->message('您的账户已经被删除。', 0);
		}
	}
	
	// fix flash 权限限制
	protected function fix_swfupload_md5_auth() {
		// fix swfupload safe problem: do not send httponly cookie.
		$md5_auth = md5(core::gpc($this->conf['cookiepre'].'auth', 'C'));
		$this->view->assign('md5_auth', $md5_auth);
	}
	
	// 校验上传权限，用来替代 swfupload 上传时候的 check_login()
	protected function check_upload_priv() {
		$uid = intval(core::gpc('uid'));
		$md5_auth = core::gpc('md5_auth');
		
		$userdb = $this->user->read($uid);
		if(empty($userdb)) {
			$this->message("uid=$uid 的用户不存在。", 0);
		}
		$auth = $this->user->get_xn_auth($userdb);
		
		if(md5($auth) != $md5_auth) {
			misc::set_cookie($this->conf['cookiepre'].'auth', '', 0, '/');
			$this->message('对不起，校验 md5_auth 失败。', 0);
			//$this->message('对不起，校验 md5_auth 失败。'."uid: $uid, md5_auth: $md5_auth, ".print_r($_POST, 1).print_r($_COOKIE, 1), 0);
		}
		
		// 判断上传权限
		$this->check_user_access($uid, 'attach');
		
		$this->_user = $userdb;	
	}
}

?>