<?php

$file = BBS_PATH.'conf/conf.php';
$pconf = include $file;

empty($pconf['qq_appid']) && $pconf['qq_appid'] = '';
empty($pconf['qq_appkey']) && $pconf['qq_appkey'] = '';
empty($pconf['qq_meta']) && $pconf['qq_meta'] = '';

$error = $input = array();
if($this->form_submit()) {
	$qq_appid = core::gpc('qq_appid', 'P');
	$qq_appkey = core::gpc('qq_appkey', 'P');
	$qq_meta = core::gpc('qq_meta', 'P');
	
	$this->mconf->set_to('qq_appid', $qq_appid, $file);
	$this->mconf->set_to('qq_appkey', $qq_appkey, $file);
	$this->mconf->set_to('qq_meta', $qq_meta, $file);
	$this->mconf->save($file);
	
	$pconf['qq_appid'] = $qq_appid;
	$pconf['qq_appkey'] = $qq_appkey;
	$pconf['qq_meta'] = $qq_meta;
}

$input['qq_appid'] = form::get_text('qq_appid', $pconf['qq_appid'], 300);
$input['qq_appkey'] = form::get_text('qq_appkey', $pconf['qq_appkey'], 300);
$input['qq_meta'] = form::get_text('qq_meta', htmlspecialchars($pconf['qq_meta']), 450);

$this->view->assign('dir', $dir);
$this->view->assign('input', $input);
$this->view->display('qq_login_setting.htm');

?>