<?php

/*
 * Copyright (C) xiuno.com
 */

class medal extends base_model {
	
	// 自动发放勋章规则
	public $autograntlist = array(
		1=>'积分大于5000分',
		2=>'发帖数量超过100',
		3=>'回帖数量超过1000',
		4=>'精华帖子数量超过50',
	);
	
	public $receivetypelist = array(
		1=>'自动颁发',
		2=>'手动颁发',
	);
		
	function __construct() {
		parent::__construct();
		$this->table = 'medal';
		$this->primarykey = array('medalid');
		$this->maxcol = 'medalid';
	}
	
	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['medalid'] = $this->maxid('+1');
		if($this->set($arr['medalid'], $arr)) {
			$this->count('+1');
			return $arr['medalid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($medalid, $arr) {
		return $this->set($medalid, $arr);
	}
	
	public function read($medalid) {
		return $this->get($medalid);
	}

	public function _delete($medalid) {
		$return = $this->delete($medalid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// 获取勋章所有列表
	public function get_medallist() {
		$medallist = $this->medal->index_fetch(array(), array('seq'=>1));
		$this->medallist_form($medallist);
		return $medallist;
	}
	
	// 格式化数据
	public function medallist_form(&$medallist) {
		foreach ($medallist as &$v) {
			$v['picture'] = $this->conf['upload_url'].$v['picture'];
			$v['receivetype'] = $this->receivetypelist[$v['receivetype']];
			
		}
		return $medallist;
	}
	
	public function get_medal($cond) {
		$medallist = $this->index_fetch($cond, array('medalId'=>-1));
		return $medallist ? array_pop($medallist) : array();
	}
}
?>