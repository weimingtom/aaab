<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class conf_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	public function on_index() {
		
		$this->on_base();
	}
	
	public function on_base() {
		global $conf;
		$timezones = array(
			'+0' => '+0 ',
			'+1' => '+1 ',
			'+2' => '+2 ',
			'+3' => '+3 ',
			'+4' => '+4 ',
			'+5' => '+5 ',
			'+6' => '+6 ',
			'+7' => '+7 ',
			'+8' => '+8 北京时间',
			'+9' => '+9 ',
			'+10' => '+10 ',
			'+11' => '+11 ',
			'+12' => '+12 ',
			'+13' => '+13 ',
			'+14' => '+14 ',
			'+15' => '+15 ',
			'+16' => '+16 ',
			'+17' => '+17 ',
			'+18' => '+18 ',
			'+19' => '+19 ',
			'+20' => '+20 ',
			'+21' => '+21 ',
			'+22' => '+22 ',
			'+23' => '+23 ',
		);
		
		$input = array();
		$bbs = include BBS_PATH.'conf/conf.php';
		$error = $post = array();
		if($this->form_submit()) {
			$post['app_name'] = core::gpc('app_name', 'P');
			$post['urlrewrite'] = intval(core::gpc('urlrewrite', 'P'));
			$post['timeoffset'] = core::gpc('timeoffset', 'P');
			$post['upload_url'] = core::gpc('upload_url', 'P');
			$post['static_url'] = core::gpc('static_url', 'P');
			$post['credits_policy_post'] = intval(core::gpc('credits_policy_post', 'P'));
			$post['credits_policy_thread'] = intval(core::gpc('credits_policy_thread', 'P'));
			$post['credits_policy_digest_1'] = intval(core::gpc('credits_policy_digest_1', 'P'));
			$post['credits_policy_digest_2'] = intval(core::gpc('credits_policy_digest_2', 'P'));
			$post['credits_policy_digest_3'] = intval(core::gpc('credits_policy_digest_3', 'P'));
			$post['gold_policy_post'] = intval(core::gpc('gold_policy_post', 'P'));
			$post['gold_policy_thread'] = intval(core::gpc('gold_policy_thread', 'P'));
			$post['gold_policy_digest_1'] = intval(core::gpc('gold_policy_digest_1', 'P'));
			$post['gold_policy_digest_2'] = intval(core::gpc('gold_policy_digest_2', 'P'));
			$post['gold_policy_digest_3'] = intval(core::gpc('gold_policy_digest_3', 'P'));
			$post['cache_pid'] = intval(core::gpc('cache_pid', 'P'));
			$post['cache_tid'] = intval(core::gpc('cache_tid', 'P'));
			$post['app_brief'] = core::gpc('app_brief', 'P');
			$post['app_starttime'] = core::gpc('app_starttime', 'P');
			$post['tmp_path'] = core::gpc('tmp_path', 'P');
			$post['click_server'] = core::gpc('click_server', 'P');
			$post['reg_on'] = intval(core::gpc('reg_on', 'P'));
			$post['reg_email_on'] = intval(core::gpc('reg_email_on', 'P'));
			$post['reg_init_golds'] = intval(core::gpc('reg_init_golds', 'P'));
			$post['app_copyright'] = core::gpc('app_copyright', 'P');
			$post['seo_title'] = core::gpc('seo_title', 'P');
			$post['seo_keywords'] = core::gpc('seo_keywords', 'P');
			$post['seo_description'] = core::gpc('seo_description', 'P');
			$post['threadlist_hotviews'] = intval(core::gpc('threadlist_hotviews', 'P'));
			$post['search_type'] = core::gpc('search_type', 'P');
			$post['sphinx_host'] = core::gpc('sphinx_host', 'P');
			$post['sphinx_port'] = core::gpc('sphinx_port', 'P');
			$post['sphinx_datasrc'] = core::gpc('sphinx_datasrc', 'P');
			$post['china_icp'] = core::gpc('china_icp', 'P');
			$post['footer_js'] = core::gpc('footer_js', 'P');
			$post['site_pv'] = intval(core::gpc('site_pv', 'P'));
			$post['site_runlevel'] = intval(core::gpc('site_runlevel', 'P'));
			
			// hook admin_conf_base_1.php
			
			// check 数据格式
			$error['app_name'] = $this->check_app_name($post['app_name']);
			
			if(misc::values_empty($error)) {
				$error = array();
				if($post['urlrewrite'] != $this->conf['urlrewrite']) {
					$this->clear_cache($this->conf['tmp_path'], 'bbs_');
					$this->clear_cache($this->conf['tmp_path'], 'bbsadmin_');
					if(!empty($post['urlrewrite']) && $post['urlrewrite'] != $bbs['urlrewrite']) {
						$testurl = $bbs['app_url'].'index.htm';
						try {
							$html = misc::get_url($testurl);
						} catch(Exception $e) {
							$html = '';
						}
						if(strpos($html, '<html') === FALSE) {
							$post['urlrewrite'] = 0;
						}
					}
				}
				
				$this->mconf->set_to('app_name', $post['app_name']);
				$this->mconf->set_to('urlrewrite', $post['urlrewrite']);
				$this->mconf->set_to('timeoffset', $post['timeoffset']);
				$this->mconf->set_to('upload_url', $post['upload_url']);
				$this->mconf->set_to('static_url', $post['static_url']);
				$this->mconf->set_to('click_server', $post['click_server']);
				$this->mconf->set_to('credits_policy_post', $post['credits_policy_post']);
				$this->mconf->set_to('credits_policy_thread', $post['credits_policy_thread']);
				$this->mconf->set_to('credits_policy_digest_1', $post['credits_policy_digest_1']);
				$this->mconf->set_to('credits_policy_digest_2', $post['credits_policy_digest_2']);
				$this->mconf->set_to('credits_policy_digest_3', $post['credits_policy_digest_3']);
				$this->mconf->set_to('gold_policy_post', $post['gold_policy_post']);
				$this->mconf->set_to('gold_policy_thread', $post['gold_policy_thread']);
				$this->mconf->set_to('gold_policy_digest_1', $post['gold_policy_digest_1']);
				$this->mconf->set_to('gold_policy_digest_2', $post['gold_policy_digest_2']);
				$this->mconf->set_to('gold_policy_digest_3', $post['gold_policy_digest_3']);
				$this->mconf->set_to('cache_pid', $post['cache_pid']);
				$this->mconf->set_to('cache_tid', $post['cache_tid']);
				$this->mconf->set_to('app_brief', $post['app_brief']);
				$this->mconf->set_to('app_starttime', $post['app_starttime']);
				$this->mconf->set_to('reg_on', $post['reg_on']);
				$this->mconf->set_to('reg_email_on', $post['reg_email_on']);
				$this->mconf->set_to('reg_init_golds', $post['reg_init_golds']);
				$this->mconf->set_to('app_copyright', $post['app_copyright']);
				$this->mconf->set_to('seo_title', $post['seo_title']);
				$this->mconf->set_to('seo_keywords', $post['seo_keywords']);
				$this->mconf->set_to('seo_description', $post['seo_description']);
				$this->mconf->set_to('threadlist_hotviews', $post['threadlist_hotviews']);
				$this->mconf->set_to('search_type', $post['search_type']);
				$this->mconf->set_to('sphinx_host', $post['sphinx_host']);
				$this->mconf->set_to('sphinx_port', $post['sphinx_port']);
				$this->mconf->set_to('sphinx_datasrc', $post['sphinx_datasrc']);
				$this->mconf->set_to('china_icp', $post['china_icp']);
				$this->mconf->set_to('footer_js', $post['footer_js']);
				$this->mconf->set_to('site_pv', $post['site_pv']);
				$this->mconf->set_to('site_runlevel', $post['site_runlevel']);
				
				// hook admin_conf_base_2.php
				
				//$this->mconf->set_to('tmp_path', $post['tmp_path']);
				$this->mconf->save();
			}
			
			// 删除模板缓存
			$this->truncate_tpl_cache();
		}
		
		// 用 $post 覆盖 $bbs
		$bbs = array_merge($bbs, $post);
		
		$input['app_name'] = form::get_text('app_name', $bbs['app_name'], 300);
		$input['app_copyright'] = form::get_text('app_copyright', $bbs['app_copyright'], 300);
		$input['app_starttime'] = form::get_text('app_starttime', $bbs['app_starttime'], 150);
		$input['static_url'] = form::get_text('static_url', $bbs['static_url'], 300);
		$input['upload_url'] = form::get_text('upload_url', $bbs['upload_url'], 300);
		$input['click_server'] = form::get_text('click_server', $bbs['click_server'], 300);
		$input['tmp_path'] = form::get_text('tmp_path', str_replace('\\', '/', realpath($bbs['tmp_path'])).'/', 300);
		$input['urlrewrite'] = form::get_radio_yes_no('urlrewrite', $bbs['urlrewrite']);
		$input['timeoffset'] = form::get_select('timeoffset', $timezones, $bbs['timeoffset']);
		$input['credits_policy_post'] = form::get_text('credits_policy_post', $bbs['credits_policy_post'], 50);
		$input['credits_policy_thread'] = form::get_text('credits_policy_thread', $bbs['credits_policy_thread'], 50);
		$input['credits_policy_digest_1'] = form::get_text('credits_policy_digest_1', $bbs['credits_policy_digest_1'], 50);
		$input['credits_policy_digest_2'] = form::get_text('credits_policy_digest_2', $bbs['credits_policy_digest_2'], 50);
		$input['credits_policy_digest_3'] = form::get_text('credits_policy_digest_3', $bbs['credits_policy_digest_3'], 50);
		$input['gold_policy_post'] = form::get_text('gold_policy_post', $bbs['gold_policy_post'], 50);
		$input['gold_policy_thread'] = form::get_text('gold_policy_thread', $bbs['gold_policy_thread'], 50);
		$input['gold_policy_digest_1'] = form::get_text('gold_policy_digest_1', $bbs['gold_policy_digest_1'], 50);
		$input['gold_policy_digest_2'] = form::get_text('gold_policy_digest_2', $bbs['gold_policy_digest_2'], 50);
		$input['gold_policy_digest_3'] = form::get_text('gold_policy_digest_3', $bbs['gold_policy_digest_3'], 50);
		$input['cache_pid'] = form::get_radio_yes_no('cache_pid', $bbs['cache_pid']);
		$input['cache_tid'] = form::get_radio_yes_no('cache_tid', $bbs['cache_tid']);
		$input['reg_on'] = form::get_radio_yes_no('reg_on', $bbs['reg_on']);
		$input['reg_email_on'] = form::get_radio_yes_no('reg_email_on', $bbs['reg_email_on']);
		$input['reg_init_golds'] = form::get_text('reg_init_golds', $bbs['reg_init_golds'], 50);
		$input['seo_title'] = form::get_text('seo_title', $bbs['seo_title'], 300);
		$input['seo_keywords'] = form::get_text('seo_keywords', $bbs['seo_keywords'], 300);
		$input['seo_description'] = form::get_text('seo_description', $bbs['seo_description'], 300);
		$input['threadlist_hotviews'] = form::get_text('threadlist_hotviews', $bbs['threadlist_hotviews'], 50);
		$input['search_type'] = form::get_radio('search_type', array(''=>'无', 'title'=>'标题', 'baidu'=>'百度', 'google'=>'谷歌', 'bing'=>'Bing', 'sphinx'=>'Sphinx'), $bbs['search_type']);
		$input['sphinx_host'] = form::get_text('sphinx_host', $bbs['sphinx_host'], 150);
		$input['sphinx_port'] = form::get_text('sphinx_port', $bbs['sphinx_port'], 50);
		$input['sphinx_datasrc'] = form::get_text('sphinx_datasrc', $bbs['sphinx_datasrc'], 50);
		$input['china_icp'] = form::get_text('china_icp', $bbs['china_icp'], 150);
		$input['footer_js'] = form::get_text('footer_js', htmlspecialchars($bbs['footer_js']), 300);
		$input['site_pv'] = form::get_text('site_pv', $bbs['site_pv'], 70);
		$input['site_runlevel'] = form::get_radio('site_runlevel', array(0=>'所有人可访问', 1=>'会员可访问', 2=>'版主可访问', 3=>'管理员可访问'), $bbs['site_runlevel']);
		
		// hook admin_conf_base_3.php
		
		$maxtid = $this->thread->maxid();
		
		$limittid = $maxtid; // $maxtid * 2
		
		$this->view->assign('limittid', $limittid);
		$this->view->assign('maxtid', $maxtid);
		$this->view->assign('input', $input);
		$this->view->assign('bbs', $bbs);
		$this->view->assign('error', $error);
		$this->view->display('conf_base.htm');
	}
	
	// 支付相关 ?
	// paytype
	public function on_pay() {
		$tab = core::gpc('tab');
		empty($tab) && $tab = 'setting';
		$this->_checked[$tab] = ' class="checked"';
		
		$bbsconfile = BBS_PATH.'conf/conf.php';
		
		$input = $error = array();
		if($tab == 'setting') {
			if($this->form_submit()) {
				$pay_on = intval(core::gpc('pay_on', 'P'));
				$pay_rate = intval(core::gpc('pay_rate', 'P'));
				
				/*
				// 正则替换，写入
				$s = file_get_contents($bbsconfile);
				$s = $this->replace_key_value('pay_on', $pay_on, $s);
				$s = $this->replace_key_value('pay_rate', $pay_rate, $s);
				file_put_contents($bbsconfile, $s);
				*/
				
				$this->mconf->set_to('pay_on', $pay_on, $bbsconfile);
				$this->mconf->set_to('pay_rate', $pay_rate, $bbsconfile);
				$this->mconf->save($bbsconfile);
				$this->conf['pay_on'] = $pay_on;
				$this->conf['pay_rate'] = $pay_rate;
			}
			
			// hook admin_conf_pay_1.php
			
			include $bbsconfile;
			
			$input['pay_on'] = form::get_radio_yes_no('pay_on', $this->conf['pay_on']);
			$input['pay_rate'] = form::get_text('pay_rate', $this->conf['pay_rate'], 100);
			
		} elseif($tab == 'ebank') {
			$conffile = BBS_PATH.'conf/ebank.php';
			if(!is_file($conffile)) {
				$this->message($conffile.' 文件不存在！');
			}
			if($this->form_submit()) {
				$v_mid = core::gpc('v_mid', 'P');
				$key = core::gpc('key', 'P');
				$ebank_on = intval(core::gpc('ebank_on', 'P'));
				
				if(!is_writable($conffile)) {
					$this->message($conffile.' 文件不可写！');
				}
				
				//empty($v_mid) && $error['v_mid'] = 'v_mid 不能为空';
				//empty($key) && $error['key'] = 'key 不能为空';
				
				if(empty($error)) {
					// 正则替换，写入
					$s = file_get_contents($conffile);
					$s = $this->replace_key_value('v_mid', $v_mid, $s);
					$s = $this->replace_key_value('key', $key, $s);
					file_put_contents($conffile, $s);
					
					// hook admin_conf_3.php
					
					$this->mconf->set_to('ebank_on', $ebank_on, $bbsconfile);
					$this->mconf->save($bbsconfile);
					$this->conf['ebank_on'] = $ebank_on;
					
				}
			}
			
			// hook admin_conf_pay_2.php
			
			include $conffile;
			$input['v_mid'] = form::get_text('v_mid', $v_mid, 300);
			$input['key'] = form::get_text('key', $key, 300);
			$input['ebank_on'] = form::get_radio_yes_no('ebank_on', $this->conf['ebank_on']);
			
		} elseif($tab == 'alipay') {
			$conffile = BBS_PATH.'conf/alipay.php';
			if(!is_file($conffile)) {
				$this->message($conffile.' 文件不存在！');
			}
			if($this->form_submit()) {
				$partner = core::gpc('partner', 'P');
				$security_code = core::gpc('security_code', 'P');
				$seller_email = core::gpc('seller_email', 'P');
				$alipay_on = intval(core::gpc('alipay_on', 'P'));
				
				if(!is_writable($conffile)) {
					$this->message($conffile.' 文件不可写！');
				}
				
				//empty($partner) && $error['partner'] = 'partner 不能为空';
				//empty($security_code) && $error['security_code'] = 'security_code 不能为空';
				//empty($seller_email) && $error['seller_email'] = 'seller_email 不能为空';
				
				if(empty($error)) {
					// 正则替换，写入
					$s = file_get_contents($conffile);
					$s = $this->replace_key_value('partner', $partner, $s);
					$s = $this->replace_key_value('security_code', $security_code, $s);
					$s = $this->replace_key_value('seller_email', $seller_email, $s);
					file_put_contents($conffile, $s);
					
					$this->mconf->set_to('alipay_on', $alipay_on, $bbsconfile);
					$this->mconf->save($bbsconfile);
					$this->conf['alipay_on'] = $alipay_on;
				}
			}
			
			// hook admin_conf_pay_3.php

			include $conffile;
			$input['partner'] = form::get_text('partner', $partner, 300);
			$input['security_code'] = form::get_text('security_code', $security_code, 300);
			$input['seller_email'] = form::get_text('seller_email', $seller_email, 300);
			$input['alipay_on'] = form::get_radio_yes_no('alipay_on', $this->conf['alipay_on']);
			
		} elseif($tab == 'banklist') {
			// 各大银行
			$conffile = BBS_PATH.'conf/bank.php';
			if(!is_file($conffile)) {
				$this->message($conffile.' 文件不存在！');
			}
			
			if($this->form_submit()) {
				$banklist = core::gpc('banklist', 'P');
				$banklist_on = intval(core::gpc('banklist_on', 'P'));
				
				if(!is_writable($conffile)) {
					$this->message($conffile.' 文件不可写！');
				}
				
				// 正则替换，写入
				file_put_contents($conffile, "<?php exit;?>\r\n".$banklist);
				
				$this->mconf->set_to('banklist_on', $banklist_on, $bbsconfile);
				$this->mconf->save($bbsconfile);
				$this->conf['banklist_on'] = $banklist_on;
			}
			$banklist = file_get_contents($conffile);
			$banklist = substr($banklist, 15);
			$this->view->assign('banklist', $banklist);
			
			$input['banklist_on'] = form::get_radio_yes_no('banklist_on', $this->conf['banklist_on']);
			
			// hook admin_conf_pay_4.php
			
		} elseif($tab == 'sms') {
			
			// hook admin_conf_pay_5.php
		}
		
		// hook admin_conf_pay_6.php
		
		$this->view->assign('error', $error);
		$this->view->assign('input', $input);
		$this->view->assign('tab', $tab);
		$this->view->display('conf_pay.htm');
	}
	
	private function replace_key_value($k, $v, $s) {
		$s = preg_replace('#\$'.$k.'\s*=\s*(\d+?);#ism', "\$$k = $v;", $s);
		$s = preg_replace('#\$'.$k.'\s*=\s*\'(.*?)\';#ism', "\$$k = '$v';", $s);
		return $s;
	}
	
	// 设置 SMTP 账号
	public function on_mail() {
		$error = array();
		$conffile = BBS_PATH.'conf/mail.php';
		$mailconf = include $conffile;
		$sendtype = $mailconf['sendtype'];
		$smtplist = $mailconf['smtplist'];
		if($this->form_submit()) {
			$email = (array)core::gpc('email', 'P');
			$host = (array)core::gpc('host', 'P');
			$port = (array)core::gpc('port', 'P');
			$user = (array)core::gpc('user', 'P');
			$pass = (array)core::gpc('pass', 'P');
			$delete = (array)core::gpc('delete', 'P');
			$sendtype = intval(core::gpc('sendtype', 'P'));
			$smtplist = array();
			foreach($email as $k=>$v) {
				empty($port[$k]) && $port[$k] = 25;
				if(in_array($k, $delete)) continue;
				if(empty($email[$k]) || empty($host[$k]) || empty($user[$k])) continue;
				$smtplist[$k] = array('email'=>$email[$k], 'host'=>$host[$k], 'port'=>$port[$k], 'user'=>$user[$k], 'pass'=>$pass[$k]);
			}
			$var_smtplist = var_export($smtplist, TRUE);
			$var_sendtype = var_export($sendtype, TRUE);
			$s = '<?php
// mail发送方式, 0:PHP内置函数 mail(), 1: SMTP 方式
return array(
	\'sendtype\' => '.$var_sendtype.',

	\'smtplist\' => '.$var_smtplist.'
);

?>';
			file_put_contents($conffile, $s);
			$mail_smtplist = $smtplist;
			
			// hook admin_conf_mail_1.php
			
		}
		
		$input = array();
		$input['sendtype'] = form::get_radio('sendtype', array(0=>'PHP内置mail函数 ', 1=>'SMTP 方式'), $sendtype);
		
		$this->view->assign('error', $error);
		$this->view->assign('smtplist', $smtplist);
		$this->view->assign('input', $input);
		
		// hook admin_conf_mail_2.php
		
		$this->view->display('conf_mail.htm');
	}
	
	public function on_cache() {
		
		$tid = intval(core::gpc('tid', 'P'));
		$tpl = core::gpc('tpl', 'P');
		$forum = core::gpc('forum', 'P');
		$group = core::gpc('group', 'P');
		$index = core::gpc('index', 'P');
		$threadlist = core::gpc('threadlist', 'P');
		$count_maxid = core::gpc('count_maxid', 'P');
		if($tid) {
			$this->clear_cache($this->conf['tmp_path'], 'tidcache_');
		}
		if($tpl) {
			$this->clear_cache($this->conf['tmp_path'], 'bbs_');
			$this->clear_cache($this->conf['tmp_path'], 'bbsadmin_');
		}
		if($forum) {
			$this->clear_cache($this->conf['tmp_path'], 'forum_');
			$this->clear_cache($this->conf['tmp_path'], 'forumlist');
			$this->clear_cache($this->conf['tmp_path'], 'miscarr_');
		}
		if($group) {
			$this->clear_cache($this->conf['tmp_path'], 'group_');
			$this->clear_cache($this->conf['tmp_path'], 'grouplist_');
		}
		
		// 校对 framework 的 count, maxid
		if($count_maxid) {
			
			// copy  from install_mongodb		
			$maxs = array(
				'group'=>'groupid',
				'user'=>'uid',
				'user_access'=>'uid',
				'forum'=>'fid',
				'forum_access'=>'fid',
				'thread_type'=>'typeid',
				'thread'=>'tid',
				'post'=>'pid',
				'digestcate'=>'cateid',
				'digest'=>'digestid',
				'attach'=>'aid',
				'attach_download'=>'aid',
				'friendlink'=>'linkid',
				'pm'=>'pmid',
				'pay'=>'payid'
			);
			
			foreach($maxs as $table=>$maxcol) {
				$m = $this->$table->native_maxid();
				$this->$table->maxid($m);
				
				$n = $this->$table->native_count();
				$this->$table->count($n);
			}
			
			// online 比较特殊
			$n = $this->online->count();
			$this->runtime->update_bbs('onlines', $n);
		}
		// hook admin_conf_cache.php
		
		$this->view->display('conf_cache.htm');
	}

	private function check_app_name(&$app_name) {
		if(utf8::strlen($app_name) > 32) {
			return '站点名称不能超过32个字符: '.$app_name.'<br />';
		}
		return '';
	}

	public function truncate_tpl_cache() {
		
	}
	
	private function clear_cache($dir, $pre) {
		$dh = opendir($dir);
		while(($file = readdir($dh)) !== false ) {
			if($file != "." && $file != ".." ) {
				if(is_dir( $dir . $file ) ) {
					//opendir_recursive( $dir . $file . "/", $recall);
				} else {
					if(substr($file, 0, strlen($pre)) == $pre) {
						unlink($dir."$file");
					}
				}
			}
		}
		closedir($dh);
	}
		
	private function truncate_dir($dir) {
		$dh = opendir($dir);
		while(($file = readdir($dh)) !== false ) {
			if($file != "." && $file != ".." ) {
				if(is_dir( $dir . $file ) ) {
					//opendir_recursive( $dir . $file . "/", $recall);
				} else {
					unlink($dir."$file");
				}
			}
		}
		closedir($dh);
	}

}

?>