
	$pconf = include BBS_PATH.'plugin/denglu/conf.php';
	$denglu_sites = include BBS_PATH.'plugin/denglu/denglu_site.php';
	
	$denglu_links = array();
	foreach($denglu_sites as $k=>$v) {
		if(!$pconf['denglu_enable'][$k]) continue;
		$denglu_links[$k] = "http://open.denglu.cc/transfer/$k?appid=".$pconf['denglu_appid'];
	}
	$this->view->assign('$denglu_sites', $denglu_sites);
	$this->view->assign('denglu_links', $denglu_links);