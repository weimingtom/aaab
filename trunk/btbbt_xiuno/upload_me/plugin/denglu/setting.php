<?php

$file = BBS_PATH.'plugin/denglu/conf.php';
$pconf = include $file;

$sites = include BBS_PATH.'plugin/denglu/denglu_site.php';

$error = $input = array();
if($this->form_submit()) {
	
	$pconf['denglu_appid'] = core::gpc('denglu_appid', 'P');
	$pconf['denglu_appkey'] = core::gpc('denglu_appkey', 'P');
	$pconf['denglu_meta'] = core::gpc('denglu_meta', 'P');
	
	// 是否开启
	foreach($sites as $k=>$v) {
		$pconf['denglu_enable'][$k] = intval(core::gpc($k, 'P'));
		$this->mconf->set_to($k, $pconf['denglu_enable'][$k], $file);
	}
	
	$this->mconf->set_to('denglu_appid', $pconf['denglu_appid'], $file);
	$this->mconf->set_to('denglu_appkey', $pconf['denglu_appkey'], $file);
	$this->mconf->set_to('denglu_meta', $pconf['denglu_meta'], $file);
	
	$this->mconf->save($file);
}

$input['denglu_appid'] = form::get_text('denglu_appid', $pconf['denglu_appid'], 300);
$input['denglu_appkey'] = form::get_text('denglu_appkey', $pconf['denglu_appkey'], 300);
$input['denglu_meta'] = form::get_text('denglu_meta', htmlspecialchars($pconf['denglu_meta']), 300);

foreach($sites as $k=>$v) {
	$input[$k] = form::get_radio_yes_no($k, $pconf['denglu_enable'][$k]);
}

$this->view->assign('dir', $dir);
$this->view->assign('sites', $sites);
$this->view->assign('input', $input);
$this->view->display('denglu_setting.htm');

?>