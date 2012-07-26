<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class friendlink_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	public function on_index() {
		$this->on_list();
	}
	
	public function on_list() {
		$this->_title[] = '友情链接列表';
		$this->_nav[] = '<a href="">友情链接列表</a>';
		
		$type = intval(core::gpc('type'));
		!in_array($type, array(0, 1)) && $type = 0;
		
		$pagesize = 10;
		
		$page = misc::page();
		$friendlinks = $this->friendlink->count();
		$friendlinklist = $this->friendlink->index_fetch(array('type'=>$type), array('rank'=>-1), ($page - 1) * $pagesize, $pagesize);
		foreach($friendlinklist as &$friendlink) {
			$this->friendlink->format($friendlink);
		}
		$pages = misc::pages("?friendlink-list-type-$type.htm", $friendlinks, $page, $pagesize);
		
		$typearr = $this->friendlink->typearr;
		$typeoptions = form::get_options($typearr, $type);
		$this->view->assign('typeoptions', $typeoptions);
		$this->view->assign('type', $type);
		
		// setting
		$bbsconf = $this->conf;
		$input = array();
		$input['friendlink_on'] = form::get_radio_yes_no('friendlink_on', $bbsconf['friendlink_on']);
		$this->view->assign('input', $input);
		$this->view->assign('pages', $pages);
		$this->view->assign('type', $type);
		$this->view->assign('page', $page);
		$this->view->assign('friendlinklist', $friendlinklist);
		
		// hook admin_friendlink_list.php
		
		$this->view->display('friendlink_list.htm');
	}

	// 批量设置 rank
	public function on_rank() {
		$page = intval(core::gpc('page'));
		if($this->form_submit()) {
			$ranks = core::gpc('ranks', 'P');
			foreach((array)$ranks as $linkid=>$v) {
				$linkid = intval($linkid);
				$v = intval($v);
				$friendlink = $this->friendlink->read($linkid);
				if(!empty($friendlink)) {
					$friendlink['rank'] = $v;
					$this->friendlink->update($linkid, $friendlink);
				}
			}
			$this->mcache->clear('friendlink');
		}
		
		// hook admin_friendlink_rank.php
		
		$this->location('?friendlink-list-page-'.$page.'.htm');
	}
	
	// 批量添加
	public function on_create() {
		$this->_title[] = '友情链接注册';
		$this->_nav[] = '友情链接注册';
		
		$page = intval(core::gpc('page'));
		$type = intval(core::gpc('type'));
		$friendlink = $error = array();
		// 添加，编辑，删除友情连接。
		if($this->form_submit()) {
			// 添加
			$sitenames = core::gpc('sitenames', 'P');
			$urls = core::gpc('urls', 'P');
			$ranks = core::gpc('ranks', 'P');
			$types = core::gpc('types', 'P');
			$logopath = $this->conf['upload_path'].'friendlink/';
			foreach($sitenames as $k=>$sitename) {
				$type = intval($types[$k]);
				$url = $urls[$k];
				$rank = intval($ranks[$k]);
				if(empty($url)) continue;
				$arr = array(
					'rank'=>$rank,
					'type'=>$type,
					'sitename'=>$sitename,
					'url'=>$url,
					'logo'=>'',
				);
				$linkid = $this->friendlink->create($arr);
				if(!$linkid) continue;
				
				$tmpfile = empty($_FILES['logos']['tmp_name'][$k]) ? '' : $_FILES['logos']['tmp_name'][$k];
				if($tmpfile) {
					$filename = $_FILES['logos']['name'][$k];
					$ext = strrchr($filename, '.');
					// 防止传马
					if(!in_array($ext, array('.jpg', '.gif', '.png', '.bmp'))) continue;
					$logofile = $logopath.$linkid.$ext;
					$arr['logo'] = 'friendlink/'.$linkid.$ext;
					if(is_file($tmpfile) && move_uploaded_file($tmpfile, $logofile)) {
						image::thumb($logofile, $logofile, 88, 31);
						$this->friendlink->update($linkid, $arr);
					}
				}
			}
			$this->mcache->clear('friendlink');
		}
		
		// hook admin_friendlink_create.php
		
		$this->location("?friendlink-list-type-$type-page-$page.htm");
		
	}
	
	// 修改
	public function on_update() {
		$this->_title[] = '修改友情链接资料';
		$this->_nav[] = '修改友情链接资料';
		
		$linkid = intval(core::gpc('linkid'));

		$friendlink = $this->friendlink->get($linkid);
		$this->check_friendlink_exists($friendlink);
		
		$input = $error = array();
		if($this->form_submit()) {
			
			// 准备更新数据
			$post = array();
			
			$post['rank'] = intval(core::gpc('rank', 'P'));
			$post['type'] = intval(core::gpc('type', 'P'));
			$post['sitename'] = core::gpc('sitename', 'P');
			$post['url'] = core::gpc('url', 'P');

			$logopath = $this->conf['upload_path'].'friendlink/';
			$tmpfile = empty($_FILES['logo']['tmp_name']) ? '' : $_FILES['logo']['tmp_name'];
			if($tmpfile) {
				$filename = $_FILES['logo']['name'];
				$ext = strrchr($filename, '.');
				// 防止传马
				if(in_array($ext, array('.jpg', '.gif', '.png', '.bmp'))) {;
					$logofile = $logopath.$linkid.$ext;
					if(is_file($tmpfile) && move_uploaded_file($tmpfile, $logofile)) {
						image::thumb($logofile, $logofile, 88, 31);
						$friendlink['logo'] = 'friendlink/'.$linkid.$ext;
					} else {
						$friendlink['logo'] = '';
					}
				}
			}
			
			if(misc::values_empty($error)) {
				$error = array();
				$friendlink = array_merge($friendlink, $post);
				$this->friendlink->update($linkid, $friendlink);
			}
			$this->mcache->clear('friendlink');
		}
		
		
		$typearr = $this->friendlink->typearr;
		$typeoptions = form::get_options($typearr, $friendlink['type']);
		$this->view->assign('typeoptions', $typeoptions);
		
		$this->friendlink->format($friendlink);
		
		$this->view->assign('input', $input);
		$this->view->assign('friendlink', $friendlink);
		$this->view->assign('error', $error);
		
		// hook admin_friendlink_create.php
		
		$this->view->display('friendlink_update.htm');
	}
	
	public function on_delete() {
		$this->_title[] = '删除友情链接';
		$this->_nav[] = '删除友情链接';
		
		$page = intval(core::gpc('page'));
		$linkid = intval(core::gpc('linkid'));
		$linkids = empty($linkid) ? core::gpc('linkids', 'P') : array($linkid);
		foreach((array)$linkids as $linkid) {
			$linkid = intval($linkid);
			$this->friendlink->_delete($linkid);
		}
		$this->mcache->clear('friendlink');
		
		// hook admin_friendlink_delete.php
		
		$this->location("?friendlink-list-page-$page.htm");
	}
	
	public function on_setting() {
		$page = intval(core::gpc('page'));
		$input = array();
		$bbsconf = $this->conf;
		$error = array();
		if($this->form_submit()) {
			$bbsconf['friendlink_on'] = intval(core::gpc('friendlink_on', 'P'));
			if(misc::values_empty($error)) {
				$error = array();
				$this->mconf->set_to('friendlink_on', $bbsconf['friendlink_on']);
				$this->mconf->save();
			}
		}
		$random = rand(1, 10000000);
		
		// hook admin_friendlink_setting.php
		
		$this->location("?friendlink-list-page-$page-random-$random.htm");
	}
	
	public function check_friendlink_exists($arr) {
		if(empty($arr)) {
			$this->message('该友情链接不存在。');
		}
	}
	
	//hook friendlink_control.php
}

?>