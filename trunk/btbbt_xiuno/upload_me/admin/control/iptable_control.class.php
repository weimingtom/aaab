<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class iptable_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	public function on_index() {
		$this->on_black();
	}
	
	public function on_black() {
		$this->_checked['black'] = ' class="checked"';
		
		$bbsconfile = BBS_PATH.'conf/conf.php';
		$input = $error = array();
		$iptablefile = BBS_PATH.'conf/iptable.php';
		$arr = include $iptablefile;
		$blacklist = $arr['blacklist'];
		$whitelist = $arr['whitelist'];
		
		$banip = core::gpc('banip');// GET 接受参数
		if(!empty($banip)) {
			array_push($blacklist, $banip);
		}
		
		if($this->form_submit()) {
			$iptable_on = intval(core::gpc('iptable_on', 'P'));
			$this->mconf->set_to('iptable_on', $iptable_on, $bbsconfile);
			$this->mconf->save($bbsconfile);
			$this->conf['iptable_on'] = $iptable_on;
			
			$blacklist = core::gpc('ip', 'R');
			$blacklist = array_diff($blacklist, array(''));
			$blacklist = array_unique($blacklist);
			
			foreach($blacklist as $k=>$v) {
				$blacklist[$k] = trim($blacklist[$k]);
				if(!$this->is_ip($v)) unset($blacklist[$k]);
			}
				
			$var_whitelist = var_export($whitelist, TRUE);
			$var_blacklist = var_export($blacklist, TRUE);
			$s = "<?php\r\nreturn array('whitelist' => $var_whitelist, 'blacklist' => $var_blacklist);\r\n?>";
			file_put_contents($iptablefile, $s);
		}
		
		$input['iptable_on'] = form::get_radio_yes_no('iptable_on', $this->conf['iptable_on']);
		
		$this->view->assign('whitelist', $whitelist);
		$this->view->assign('blacklist', $blacklist);
		$this->view->assign('input', $input);
		$this->view->assign('error', $error);
		
		// hook admin_iptable_black_view_before.php
		
		$this->view->display('iptable.htm');
	}
	
	public function on_white() {
		$this->_checked['white'] = ' class="checked"';
		
		$bbsconfile = BBS_PATH.'conf/conf.php';
		$input = $error = array();
		$iptablefile = BBS_PATH.'conf/iptable.php';
		$arr = include $iptablefile;
		$whitelist = $arr['whitelist'];
		$blacklist = $arr['blacklist'];
		
		if($this->form_submit()) {
			$iptable_on = intval(core::gpc('iptable_on', 'P'));
			$this->mconf->set_to('iptable_on', $iptable_on, $bbsconfile);
			$this->mconf->save($bbsconfile);
			$this->conf['iptable_on'] = $iptable_on;
			
			$whitelist = core::gpc('ip', 'P');
			$whitelist = array_diff($whitelist, array(''));
			$whitelist = array_unique($whitelist);
			
			foreach($whitelist as $k=>$v) {
				$whitelist[$k] = trim($whitelist[$k]);
				if(!$this->is_ip($v)) unset($whitelist[$k]);
			}
			
			$var_whitelist = var_export($whitelist, TRUE);
			$var_blacklist = var_export($blacklist, TRUE);
			$s = "<?php\r\nreturn array('whitelist' => $var_whitelist, 'blacklist' => $var_blacklist);\r\n?>";
			file_put_contents($iptablefile, $s);
		}
		
		$input['iptable_on'] = form::get_radio_yes_no('iptable_on', $this->conf['iptable_on']);
		
		$this->view->assign('whitelist', $whitelist);
		$this->view->assign('blacklist', $blacklist);
		$this->view->assign('input', $input);
		$this->view->assign('error', $error);
		
		// hook admin_iptable_white_view_before.php
		
		$this->view->display('iptable.htm');
	}
	
	private function is_ip($ip) {
		return preg_match('#^\d+\.\d+\.\d+\.\d+$#', $ip) || preg_match('#^\d+\.\d+\.\d+$#', $ip) || preg_match('#^\d+\.\d+$#', $ip);
	}
	
	//hook iptable_control_after.php
}

?>