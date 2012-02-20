<?php
class AjaxAction extends HomeAction{
    public function index(){
		$this->show();
	}	
    public function show(){
		$id=!empty($_GET['id'])?$_GET['id']:'ajax';
		$this->assign('pplist',A("Home"));
		$this->display('pp_'.trim($id));
	}			
}
?>