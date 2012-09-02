<?php
/*
 * author:huyao
 * email:252288762@qq.com
 * qq:252288762
 * description:勋章插件和xiuno一起努力，做到最好
 */
class medal_user extends base_model {
		
	function __construct() {
		parent::__construct();
		$this->table = 'medal_user';
		$this->primarykey = array('muid');
		$this->maxcol = 'muid';
	}
	
	public function create($arr) {
		$arr['muid'] = $this->maxid('+1');
		if($this->set($arr['muid'], $arr)) {
			$this->count('+1');
			return $arr['muid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($muid, $arr) {
		return $this->set($muid, $arr);
	}
	
	public function read($muid) {
		return $this->get($muid);
	}

	public function _delete($muid) {
		$return = $this->delete($muid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_medaluser_by_uid_medalid($uid, $medalid) {
		// 根据非主键取数据
		$medaluser = $this->index_fetch($cond = array('uid'=>$uid, 'medalid'=>$medalid), $orderby = array(), $start = 0, $limit = 1);
		return $medaluser ? array_pop($medaluser) : array();
	}
	
	public function get_user_medal($uid) {
		$medaluserlist = $this->index_fetch(array('uid'=>$uid, 'isapply'=>1), array('muid'=>-1));
		$s = '';
		foreach ($medaluserlist as $v) {
			$medal = $this->medal->get($v['medalid']);
			if ($medal) {
				$s .= '<img src="'.$this->conf['app_url'].'upload/'.$medal['picture'].'" title="'.$medal['medalname'].'" style="margin:3px"/>';
			}
		}
		return $s;
	}
	
	public function get_medaluserlist_by_uid_isapply($uid, $isapply=1) {
		$medaluserlist = $this->index_fetch(array('uid'=>$uid, 'isapply'=>$isapply), array('muid'=>-1));
		return $medaluserlist;
	}
	
	public function get_medaluserlist_by_isapply($isapply, $page=1, $pagesize=30) {
		$medaluserlist = $this->index_fetch(array('isapply'=>$isapply), array('muid'=>-1), ($page - 1) * $pagesize, $pagesize);
		return $medaluserlist;
	}
	
	// 获取本勋章的所有用户
	public function get_medaluserList_by_medalid($medalid) {
		$medaluserlist = $this->index_fetch(array('medalid'=>$medalid));
		return $medaluserlist;
	}
	
	public function medaluserlist_format(&$medaluserlist) {
		foreach ($medaluserlist as &$v) {
			$medal = $this->medal->get($v['medalid']);
			if ($medal) {
				$v['medalname'] = $medal['medalname'];
				$v['picture'] = $medal['picture'] ? $this->conf['upload_url'].$medal['picture'] : '';
				$v['createdtime'] = date('Y-m-d H:i:s', $v['createdtime']);
				$v['expiredtime'] = $v['expiredtime'] ? '有效期至 '.date('Y-m-d', $v['expiredtime']) : '永久有效';
				
				$v['receivetype'] = $this->medal->receivetypelist[$medal['receivetype']];
			}
		}
	}
	
	//--------------------------------------------------------------------以下用于计划任务
	
	// 用于计划任务，清除过期的用户勋章
	public function cron_clear_expiredtime() {
		$medaluserlist = $this->index_fetch(array('expiredtime'=>array('<'=>time())));
		foreach ($medaluserlist as $medaluser) {
			$this->_delete($medaluser['muid']);
		}
	}
}
?>