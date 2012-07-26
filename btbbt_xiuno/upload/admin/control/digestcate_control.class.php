<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class digestcate_control extends admin_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// 列表
	public function on_index() {
		$this->on_list();
	}	
	
	public function on_list() {
		$this->_title[] = '精华分类';
		$this->_nav[] = '<a href="./">精华分类</a>';
		
		$maxcateid = $this->digestcate->maxid();
		$this->view->assign('maxcateid', $maxcateid);
		
		// hook admin_digest_list.php
		
		$this->view->display('digestcate_list.htm');
	}

	public function on_add() {
		$this->_title[] = '添加分类';
		$this->_nav[] = '添加分类';
	}
	
	// ，添加，修改
	public function on_update() {
		$this->_title[] = '修改分类';
		$this->_nav[] = '修改分类';
		
		$cateid = intval(core::gpc('cateid'));
		$parentid = intval(core::gpc('parentid'));
		$name = core::gpc('name');
		
		// 判断是否存在
		$cate = $this->digestcate->read($cateid);
		if($cate) {
			// 只更新名字
			if($cate['name'] != $name) {
				$name = str_replace(' ', '.', $name);
				if($cate['threads'] > 0) {
					
					// 查找所有的精华帖，更新 thread.catename
					$digestlist = $this->digest->get_list_by_cateid($cateid, 1, 1000);
					foreach($digestlist as $v) {
						$fid = $v['fid'];
						$tid = $v['tid'];
						$thread = $this->thread->read($fid, $tid);
						$thread['catenames'] = str_replace($cate['name'], $name, $thread['catenames']);
						$this->thread->update($fid, $tid, $thread);
					}
					
					
				}
				$cate['name'] = $name;
			}
			
		} else {
			$cate = array (
				'cateid'=>$cateid,
				'parentid'=>$parentid,
				'name'=>$name,
				'threads'=>0,
				'uid'=>$this->_user['uid'],
				'rank'=>$cateid,
			);
			
			// maxid++
			$this->digestcate->maxid(intval($cateid));
		}
		
		// hook admin_digest_update_1.php
		
		$this->digestcate->update($cateid, $cate);
		
		$this->cache_cate_json();
		
		// hook admin_digest_update_2.php
		
		$this->message('更新成功！');
		
	}
	
	// 重新生成js
	public function on_rebuildjs() {
		
		// hook admin_digest_rebuildjs.php
		
		$this->cache_cate_json();
		$this->message('更新js成功！', 1, '?digestcate.htm');
	}
	
	public function on_delete() {
		$this->_title[] = '删除分类';
		$this->_nav[] = '删除分类';
		
		$cateid = intval(core::gpc('cateid'));
		
		// hook admin_digest_delete_before.php
		
		// 顶层至少要保留一个
		$arr = $this->digestcate->index_fetch(array('parentid'=>0), array(), 0, 2);
		if(count($arr) == 1) {
			$this->message('顶层分类至少应该保留一个，您可以修改其名字');
		}
		
		// todo: 删除3层子分类，理论上递归才会比较完美。
		$arrlist = $this->digestcate->index_fetch(array('parentid'=>$cateid), array(), 0, 500);
		foreach($arrlist as $arr) {
			$arrlist2 = $this->digestcate->index_fetch(array('parentid'=>$arr['cateid']), array(), 0, 500);
			foreach($arrlist2 as $arr2) {
				$arrlist3 = $this->digestcate->index_fetch(array('parentid'=>$arr2['cateid']), array(), 0, 500);
				foreach($arrlist3 as $arr3) {
					$this->digestcate->_delete($arr3['cateid']);
				}
				$this->digestcate->_delete($arr2['cateid']);
			}
			$this->digestcate->_delete($arr['cateid']);
		}
		
		// todo:查找子分类，删除子分类
		$this->digestcate->_delete($cateid);
		
		$this->digest->delete_by_cateid($cateid);
		
		$this->cache_cate_json();
		
		// hook admin_digest_delete_after.php
		
		$this->message('删除成功！');
	}
	
	// 提升，交换 cateid1, cateid2 的 rank 值
	public function on_top() {
		$cateid1 = intval(core::gpc('cateid1'));
		$cateid2 = intval(core::gpc('cateid2'));
		
		$cate1 = $this->digestcate->read($cateid1);
		$cate2 = $this->digestcate->read($cateid2);
		
		$rank = $cate1['rank'];
		$cate1['rank'] = $cate2['rank'];
		$cate2['rank'] = $rank;
		
		$this->digestcate->update($cateid1, $cate1);
		$this->digestcate->update($cateid2, $cate2);
		
		// hook admin_digest_top.php
		
		$this->message('删除成功！');
	}
	
	// <script src="?catelist-script.htm"...
	public function on_script() {
		echo $this->get_json();
	}
	
	private function get_json() {
		// 取出全部分类，按照 parentid 整理数组
		$parr = array();
		
		$catelist = $this->digestcate->index_fetch(array(), array('rank'=>1), 0, 9999);
		
		// 整理为 js 数组格式
		foreach($catelist as $cate) {
			!isset($parr[$cate['parentid']]) && $parr[$cate['parentid']] = array();
			$parr[$cate['parentid']][] = $cate;
		}
		
		return 'var tree_data = ['.$this->get_cate_json($parr, 0).'];';
	}
	
	private function get_cate_json($parr, $parentid) {
		$s = '';
		foreach($parr[$parentid] as $cate) {
			$next = isset($parr[$cate['cateid']]) ? '['.$this->get_cate_json($parr, $cate['cateid']).']' : 'null';
			$s .= ',["'.$cate['name'].'", '.$cate['cateid'].', '.$cate['parentid'].', '.$cate['rank'].', '.$next.']';
		}
		$s = substr($s, 1);
		return $s;
	}
	
	private function cache_cate_json() {
		$json = $this->get_json();
		$file = $this->conf['upload_path'].'digestcate.js';
		file_put_contents($file, $json);
	}
	
	//hook digestcate_control.php
}
?>