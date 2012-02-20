<?php
class TagAction extends HomeAction{
    public function index(){
		$this->assign('pplist',A("Home"));
		$this->assign('title','Tag列表'.'-'.C('site_name').C('site_by'));
		$this->display('pp_tag');
    }
    public function show(){
		$sid=trim($_REQUEST['sid']);
		$keyword=trim($_REQUEST['id']);
		$this->assign('tag_name',$keyword);
		$this->assign('pplist',A("Home"));
		$this->assign('title',C('site_name').'-标签:'.$keyword.C('site_by'));
		if(2==$sid){
			$this->display('pp_tagnews');
		}else{
			$this->display('pp_tagvod');
		}
    }
}
?>