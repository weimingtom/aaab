<?php

/*
 * author:huyao
 * email:252288762@qq.com
 * qq:252288762
 * description:勋章插件和xiuno一起努力，做到最好
 */

class medal extends base_model {
	
	public $receivetypelist = array(
		1=>'手动颁发',
	);
		
	function __construct() {
		parent::__construct();
		$this->table = 'medal';
		$this->primarykey = array('medalid');
		$this->maxcol = 'medalid';
	}
	
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

	public function _delete($medalid) 
	{
		$medal = $this->get($medalid);
		if ($medal && $medal['picture']) {
			$filepath = $this->conf['upload_path'].$medal['picture'];
			if (file_exists($filepath)) {
				unlink($filepath);
			}
		}
			
		$return = $this->delete($medalid);
		if($return) {
			$this->count('-1');
		}
		
		$medaluserlist = $this->medal_user->get_medaluserList_by_medalid($medalid);
		foreach ($medaluserlist as $v) {
			$this->medal_user->_delete($v['muid']);
		}
		
		return $return;
	}
		
	public function get_medallist_orderby_seq($value=1, $page=1, $pagesize=1000) {
		$medallist = $this->index_fetch(array(), array('seq'=>$value), ($page - 1) * $pagesize, $pagesize);
		return $medallist;
	}
	
	// 格式化数据
	public function medallist_format(&$medallist) {
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