<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class pay_control extends admin_control {
	
	// paytype
	public function on_pay() {
		$tab = core::gpc('tab');
		empty($tab) && $tab = 'setting';
		$this->_checked[$tab] = ' class="checked"';
		
		$shopconffile = BBS_PATH.'conf/conf.php';
		
		$input = $error = array();
		if($tab == 'setting') {
			// 
			
			
		} elseif($tab == 'ebank') {
			$conffile = BBS_PATH.'conf/ebank.php';
			if(!is_file($conffile)) {
				$this->message($conffile.' 文件不存在！');
			}
			if($this->form_submit()) {
				$v_mid = core::gpc('v_mid', 'P');
				$key = core::gpc('key', 'P');
				$ebank_on = core::gpc('ebank_on', 'P');
				
				if(!is_writable($conffile)) {
					$this->message($conffile.' 文件不可写！');
				}
				
				empty($v_mid) && $error['v_mid'] = 'v_mid 不能为空';
				empty($key) && $error['key'] = 'key 不能为空';
				
				if(empty($error)) {
					// 正则替换，写入
					$s = file_get_contents($conffile);
					$s = $this->replace_key_value('v_mid', $v_mid, $s);
					$s = $this->replace_key_value('key', $key, $s);
					file_put_contents($conffile, $s);
					
					$this->mconf->set_to('ebank_on', $ebank_on, $shopconffile);
					$this->mconf->save($shopconffile);
					$this->conf['ebank_on'] = $ebank_on;
					
				}
			}
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
				$alipay_on = core::gpc('alipay_on', 'P');
				
				if(!is_writable($conffile)) {
					$this->message($conffile.' 文件不可写！');
				}
				
				empty($partner) && $error['partner'] = 'partner 不能为空';
				empty($security_code) && $error['security_code'] = 'security_code 不能为空';
				empty($seller_email) && $error['seller_email'] = 'seller_email 不能为空';
				
				if(empty($error)) {
					// 正则替换，写入
					$s = file_get_contents($conffile);
					$s = $this->replace_key_value('partner', $partner, $s);
					$s = $this->replace_key_value('security_code', $security_code, $s);
					$s = $this->replace_key_value('seller_email', $seller_email, $s);
					file_put_contents($conffile, $s);
					
					$this->mconf->set_to('alipay_on', $alipay_on, $shopconffile);
					$this->mconf->save($shopconffile);
					$this->conf['alipay_on'] = $alipay_on;
				}
			}
			
			include $conffile;
			$input['partner'] = form::get_text('partner', $partner, 300);
			$input['security_code'] = form::get_text('security_code', $security_code, 300);
			$input['seller_email'] = form::get_text('seller_email', $seller_email, 300);
			$input['alipay_on'] = form::get_radio_yes_no('alipay_on', $this->conf['alipay_on']);
			
		} elseif($tab == 'banklist') {
			// 各大银行
			var_dump($banklist);
			exit;
			$conffile = BBS_PATH.'conf/bank.php';
			if(!is_file($conffile)) {
				$this->message($conffile.' 文件不存在！');
			}
			if($this->form_submit()) {
				$banklist = core::gpc('banklist', 'P');
				$banklist_on = core::gpc('banklist_on', 'P');
				
				if(!is_writable($conffile)) {
					$this->message($conffile.' 文件不可写！');
				}
				
				// 正则替换，写入
				file_put_contents($conffile, "<?php exit;?>\r\n".$banklist);
				
				$this->mconf->set_to('banklist_on', $banklist_on, $shopconffile);
				$this->mconf->save($shopconffile);
				$this->conf['banklist_on'] = $banklist_on;
			}
			$banklist = file_get_contents($conffile);
			
			$banklist = substr($banklist, 15);
			$this->view->assign('banklist', $banklist);
			
			$input['banklist_on'] = form::get_radio_yes_no('banklist_on', $this->conf['banklist_on']);
			
		} elseif($tab == 'sms') {
		
		}
		
		$this->view->assign('error', $error);
		$this->view->assign('input', $input);
		$this->view->assign('tab', $tab);
		$this->view->display('conf_pay.htm');
	}
	
	private function replace_key_value($k, $v, $s) {
		$s = preg_replace('#\$'.$k.'\s*=\s*(.*?);#ism', "\$$k = '$v';", $s);
		return $s;
	}

}

?>