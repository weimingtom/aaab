<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class index_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// 给插件预留个位置
	public function on_index() {
		// hook index_index_before.php
		
		$this->on_bbs();
	}
	
	// 首页
	public function on_bbs() {
		// hook index_bbs_before.php
		$this->_title[] = $this->conf['seo_title'] ? $this->conf['seo_title'] : $this->conf['app_name'];
		$this->_seo_keywords = $this->conf['seo_keywords'];
		$this->_seo_description = $this->conf['seo_description'];
		
		// 统计信息
		$this->view->assign('runtime', $this->conf['runtime']);
		
		// 版块数据
		$catelist = $this->mcache->read('forumlist');
		// chunk 数据，横排
		foreach($catelist as &$cate) {
			if(empty($cate['indexforums'])) continue;
			$col = $cate['indexforums'];
			$cate['forumlist_chunk'] = array_chunk($cate['forumlist'], $col);
			$last = ceil(count($cate['forumlist']) / $col);
			$cate['forumlist_chunk'][$last - 1] = array_pad($cate['forumlist_chunk'][$last - 1], $col, array());
			$cate['width'] = ceil(100 / $col).'%';	// 百分比
		}
		$this->view->assign('catelist', $catelist);
		
		// 友情连接
		$friendlinklist = $this->mcache->read('friendlink');
		$this->view->assign('friendlinklist', $friendlinklist);
		
		// 在线会员
		$onlinelist = $this->online->get_onlinelist();
		$this->view->assign('onlinelist', $onlinelist);
		
		// hook index_bbs_after.php
		
		$this->view->display('index.htm');
	}
	
	// 显示某个大区下的板块
	public function on_forum() {
		
		// hook index_forum_before.php
		
		$this->_title[] = $this->conf['seo_title'] ? $this->conf['seo_title'] : $this->conf['app_name'];
		$this->_seo_keywords = $this->conf['seo_keywords'];
		$this->_seo_description = $this->conf['seo_description'];
		
		$fid = intval(core::gpc('fid'));
		// 版块数据
		$catelist = $this->mcache->read('forumlist');
		if(empty($catelist["forum-fid-$fid"])) {
			$this->message('板块不存在。');
		}
		$cate = $catelist["forum-fid-$fid"];
		$col = $cate['indexforums'];
		if($col > 0) {
			$cate['forumlist_chunk'] = array_chunk($cate['forumlist'], $col);
			$last = ceil(count($cate['forumlist']) / $col);
			$cate['forumlist_chunk'][$last - 1] = array_pad($cate['forumlist_chunk'][$last - 1], $col, array());
			$cate['width'] = ceil(100 / $col).'%';	// 百分比
		}
		$this->view->assign('cate', $cate);
		
		// hook index_forum_after.php
		
		$this->view->display('index_forum.htm');
	}
	
	//hook index_control_after.php
}

?>