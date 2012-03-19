<?php
class HomeAction extends AllAction{
	//前台公用文件
	public function _initialize(){
		$style=array();
		header("Content-Type:text/html; charset=".C(DEFAULT_CHARSET));
		$style['tpl']=C('site_path').TEMPLATE_PATH.'/';
		$style['css']='<link rel="stylesheet" type="text/css" href="'.C('site_path').TEMPLATE_PATH.'/style.css">'."\n";
		$style['root']=C('site_path');//APP_PATH.'/'
		$style['sitename']=C('site_name');
		$style['siteurl']=C('site_url');
		$style['keywords']=C('site_keywords');
		$style['description']=C('site_description');
		$style['email']=C('site_email');
		$style['copyright']=C('site_copyright');
		$style['tongji']=C('site_tongji');
		$style['icp']=C('site_icp');
		$style['model']=strtolower(MODULE_NAME);
		$style['action']=strtolower(ACTION_NAME);
		C('TOKEN_NAME','__ppvod__');
		$this->assign($style);
		$this->assign('pplink',F('_ppvod/link'));
		$this->assign('ppmenu',F('_ppvod/listtree'));
	}
	public function showmenu(){
		return F('_ppvod/listtree');
	}
}
?>