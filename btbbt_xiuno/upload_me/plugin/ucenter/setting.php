<?php

$file = BBS_PATH.'plugin/ucenter/conf.php';
$pconf = include $file;

$error = $input = array();
if($this->form_submit()) {
	$pconf['uc_host'] = core::gpc('host', 'P');
	$pconf['uc_user'] = core::gpc('username', 'P');
	$pconf['uc_password'] = core::gpc('password', 'P');
	$pconf['uc_name'] = core::gpc('name', 'P');
	$pconf['uc_charset'] = core::gpc('charset', 'P');
	$pconf['uc_tablepre'] = core::gpc('tablepre', 'P');
	$pconf['uc_appid'] = intval(core::gpc('appid', 'P'));
	$pconf['uc_appkey'] = core::gpc('appkey', 'P');
	
	$this->mconf->set_to('uc_host', $pconf['uc_host'], $file);
	$this->mconf->set_to('uc_user', $pconf['uc_user'], $file);
	$this->mconf->set_to('uc_password', $pconf['uc_password'], $file);
	$this->mconf->set_to('uc_name', $pconf['uc_name'], $file);
	$this->mconf->set_to('uc_charset', $pconf['uc_charset'], $file);
	$this->mconf->set_to('uc_tablepre', $pconf['uc_tablepre'], $file);
	$this->mconf->set_to('uc_appid', $pconf['uc_appid'], $file);
	$this->mconf->set_to('uc_appkey', $pconf['uc_appkey'], $file);
	$this->mconf->save($file);
}

$input['host'] = form::get_text('host', $pconf['uc_host'], 200);
$input['username'] = form::get_text('username', $pconf['uc_user'], 200);
$input['password'] = form::get_text('password', $pconf['uc_password'], 200);
$input['name'] = form::get_text('name', $pconf['uc_name'], 200);
$input['charset'] = form::get_text('charset', $pconf['uc_charset'], 200);
$input['tablepre'] = form::get_text('tablepre', $pconf['uc_tablepre'], 200);
$input['appid'] = form::get_text('appid', $pconf['uc_appid'], 200);
$input['appkey'] = form::get_text('appkey', $pconf['uc_appkey'], 200);

$this->view->assign('dir', $dir);
$this->view->assign('input', $input);
$this->view->display('ucenter_setting.htm');

?>