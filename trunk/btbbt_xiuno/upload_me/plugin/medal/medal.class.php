<?php

/*
 * author:huyao
 * email:252288762@qq.com
 * qq:252288762
 * description:勋章插件和xiuno一起努力，做到最好
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
	
	// 用户页面出现领取按钮
	public function is_receive($user, $medal) {
		$isreceive = false;
		$medaluser = $this->medal_user->get_medaluser_by_uid_medalid($user['uid'], $medal['medalid']);
		if (!$medaluser) {
			switch ($medal['autogrant']){
				case 1: // 积分大于5000分
					if ($user['credits'] >= 500) {
						$isreceive = true;
					}
					break;
				case 2: // 发帖数量超过100
					if ($user['threads'] >= 100) {
						$isreceive = true;
					}
					break;
				case 3: // 回帖数量超过1000
					if ($user['posts'] >= 1000) {
						$isreceive = true;
					}
					break;
				case 4: // 精华帖子数量超过50
					if ($user['digests'] >= 50) {
						$isreceive = true;
					}
					break;
			}
		}
		return $isreceive;
	}
}
?>