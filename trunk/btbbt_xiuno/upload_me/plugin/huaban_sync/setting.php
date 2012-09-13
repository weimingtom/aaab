<?php

$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;

$error = $input = array();
if($this->form_submit()) {
	$pconf['api_key'] = core::gpc('key', 'P');
	$pconf['api_url'] = core::gpc('url', 'P');
        	
	$this->mconf->set_to('api_key', $pconf['api_key'], $file);
	$this->mconf->set_to('api_url', $pconf['api_url'], $file);

	$this->mconf->save($file);
}

$input['key'] = form::get_text('key', $pconf['api_key'], 200);
$input['url'] = form::get_text('url', $pconf['api_url'], 200);

$this->view->assign('dir', $dir);
$this->view->assign('input', $input);
$this->view->display('setting.htm');

?>