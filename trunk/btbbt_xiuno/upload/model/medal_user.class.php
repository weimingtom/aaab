<?php

/*
 * Copyright (C) xiuno.com
 */

class medal_user extends base_model {
		
	function __construct() {
		parent::__construct();
		$this->table = 'medal_user';
		$this->primarykey = array('medalUserId');
		$this->maxcol = 'medalUserId';
	}
	
	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['medalUserId'] = $this->maxid('+1');
		if($this->set($arr['medalUserId'], $arr)) {
			$this->count('+1');
			return $arr['medalUserId'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($medalUserId, $arr) {
		return $this->set($medalUserId, $arr);
	}
	
	public function read($medalUserId) {
		return $this->get($medalUserId);
	}

	public function _delete($medalUserId) {
		$return = $this->delete($medalUserId);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_user_medal($userId) {
		$medal_user_list = $this->index_fetch(array('userId'=>$userId, 'isApply'=>1), array('medalUserId'=>-1));
		$s = '';
		foreach ($medal_user_list as $v) {
			$medal = $this->medal->get($v['medalId']);
			if ($medal) {
				$s .= '<img src="'.$this->conf['app_url'].'upload/'.$medal['picture'].'" title="'.$medal['medalName'].'" style="margin:3px"/>';
			}
		}
		return $s;
	}
}
?>