<?php

$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;

$error = $input = array();
if($this->form_submit()) {
	$pconf['db_host'] = core::gpc('host', 'P');
	$pconf['db_user'] = core::gpc('username', 'P');
	$pconf['db_password'] = core::gpc('password', 'P');
	$pconf['db_name'] = core::gpc('name', 'P');
	$pconf['db_charset'] = core::gpc('charset', 'P');
	$pconf['db_tablepre'] = core::gpc('tablepre', 'P');
        
        $pconf['cookie_domain'] = core::gpc('domain', 'P');
	
	$this->mconf->set_to('db_host', $pconf['db_host'], $file);
	$this->mconf->set_to('db_user', $pconf['db_user'], $file);
	$this->mconf->set_to('db_password', $pconf['db_password'], $file);
	$this->mconf->set_to('db_name', $pconf['db_name'], $file);
	$this->mconf->set_to('db_charset', $pconf['db_charset'], $file);
	$this->mconf->set_to('db_tablepre', $pconf['db_tablepre'], $file);
        $this->mconf->set_to('cookie_domain', $pconf['cookie_domain'], $file);
	$this->mconf->save($file);
}

$input['host'] = form::get_text('host', $pconf['db_host'], 200);
$input['username'] = form::get_text('username', $pconf['db_user'], 200);
$input['password'] = form::get_text('password', $pconf['db_password'], 200);
$input['name'] = form::get_text('name', $pconf['db_name'], 200);
$input['charset'] = form::get_text('charset', $pconf['db_charset'], 200);
$input['tablepre'] = form::get_text('tablepre', $pconf['db_tablepre'], 200);
$input['domain'] = form::get_text('domain', $pconf['cookie_domain'], 200);

$this->view->assign('dir', $dir);
$this->view->assign('input', $input);
$this->view->display('setting.htm');

?>