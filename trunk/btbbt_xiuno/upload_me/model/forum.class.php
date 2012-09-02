<?php

/*
 * Copyright (C) xiuno.com
 */

class forum extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'forum';
		$this->primarykey = array('fid');
		$this->maxcol = 'fid';
	}
	
	public function create($arr) {
		$fid = $arr['fid'] = $this->maxid('+1');
		if($this->set($fid, $arr)) {
			$this->count('+1');
			return array('fid'=>$fid);
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($fid, $arr) {
		return $this->set($fid, $arr);
	}
	
	// 更新版块的最后发帖
	public function update_last($fid) {
		$forum = $this->read($fid);
		$threadlist = $this->thread->index_fetch(array('fid'=>$fid), array('tid'=>-1), 0, 1);
		if(empty($threadlist)) {
			$forum['lasttid'] = 0;
			$forum['lastsubject'] = '';
			$forum['lastuid'] = 0;
			$forum['lastusername'] = '';
			$forum['lastpost'] = 0;
		} else {
			$thread = array_pop($threadlist);
			$forum['lasttid'] = $thread['tid'];
			$forum['lastsubject'] = $thread['subject'];
			$forum['lastuid'] = $thread['uid'];
			$forum['lastusername'] = $thread['username'];
			$forum['lastpost'] = $thread['lastpost'] ? $thread['lastpost'] : $thread['dateline'];
		}
		$this->update($fid, $forum);
	}
	
	public function read($fid) {
		return $this->get($fid);
	}

	public function _delete($fid) {
		$return = $this->delete($fid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// 取板块列表，二级
	public function get_forum_list() {
		$forumlist = array();
		$catelist = $this->index_fetch(array('fup'=>0), array('rank'=>1), 0, 1000);
		foreach($catelist as &$cate) {
			$cate['forumlist'] = $this->index_fetch(array('fup'=>$cate['fid']), array('rank'=>-1), 0, 1000);
		}
		return $catelist;
	}
	
	// 最简单的格式 fid=>name
	public function get_all_forum() {
		return $this->index_fetch(array(), array(), 0, 1000);
	}
	
	// 取子版块
	public function get_forumlist_by_fup($fup) {
		return $this->index_fetch(array('fup'=>intval($fup)), array('rank'=>1), 0, 1000);
	}
	
	// 生成 <option>
	public function get_forum_options($checkedfid = 0) {
		$s = '';
		$catelist = $this->get_forum_list();
		foreach($catelist as &$cate) {
			$checked = $checkedfid == $cate['fid'] ? ' selected="selected"' : '';
			$s .= '<option value="'.$cate['fid'].'"'.$checked.' style="font-weight: 800;">'.$cate['name'].'</option>';
			foreach($cate['forumlist'] as &$forum) {
				$checked = $checkedfid == $forum['fid'] ? ' selected="selected"' : '';
				$s .= '<option value="'.$forum['fid'].'"'.$checked.'>    &nbsp;   &nbsp; |-'.$forum['name'].'</option>';
			}
		}
		return $s;
	}
	
	public function check_fup($fid, $fup) {
		$forum = $this->read($fup);
		if($forum['fup'] != 0) {
			return "$forum[name] 是板块，不是分类。";
		}
		return '';
	}
	
	public function check_name(&$name) {
		if(empty($name)) {
			return '板块名称不能为空。';
		}
		return '';
	}
	
	public function check_rank(&$rank) {
		if(empty($rank)) {
			return '显示倒序不能为空。';
		}
		return '';
	}
	
	public function check_brief(&$brief) {
		if(empty($brief)) {
			return '版块简介不能为空。';
		}
		return '';
	}
	
	public function check_rule(&$rule) {
		if(empty($rule)) {
			return '版规不能为空。';
		}
		return '';
	}
	
	public function check_icon(&$icon) {
		if(empty($icon)) {
			return '版块图标不能为空。';
		}
		return '';
	}
	
	public function format(&$forum) {
		$forum['forumicon_small'] = $forum['icon'] ? $this->conf['upload_url']."forum/$forum[fid]_small.gif" : '';
		$forum['forumicon_middle'] = $forum['icon'] ? $this->conf['upload_url']."forum/$forum[fid]_middle.gif" : '';
		$forum['forumicon_big'] = $forum['icon'] ? $this->conf['upload_url']."forum/$forum[fid]_big.gif" : '';
		$forum['lastpost_fmt'] = misc::humandate($forum['lastpost']);
		
		// 版主
		$forum['modlist'] = array();
		if(!empty($forum['modids'])) {
			$modidarr = explode(' ', $forum['modids']);
			$modnamearr = explode(' ', $forum['modnames']);
			foreach($modidarr as $k=>$modid) {
				$forum['modlist'][$modid] = $modnamearr[$k];
			}
		}
		
		// 主题分类，查询
		$forum['typelist'] = array();
		$forum['typelist'] = $this->thread_type->get_list_by_fid($forum['fid']);
	}
	
	public function format_mod(&$forum, $pforum = array()) {
		
	}
	
	/*
	protected function is_mod($forum, $pforum, $user) {
		// == 2 超级版主所有版块均有权限
		if($user['groupid'] == 1 || $user['groupid'] == 2) {
			return TRUE;
		} elseif($user['groupid'] == 3 || $user['groupid'] == 4) {
			return strpos(' '.$forum['modids'].' ', ' '.$user['uid'].' ') !== FALSE || strpos(' '.$pforum['modids'].' ', ' '.$user['uid'].' ') !== FALSE;
		}
		return FALSE;
	}*/
		
	// 获取有权限的版块列表，默认第一个
	public function get_priv_forumoptions($user, &$checkedfid) {
		$uid = $user['uid'];
		$groupid = $user['groupid'];
		
		$catelist = $this->mcache->read('forumlist');
		
		// 超版，管理员
		if($groupid == 1 || $groupid == 2) {
			$forumarr = $this->mcache->read('forumarr');
			$s = $this->get_forum_options($checkedfid);
			if(empty($checkedfid)) {
				list($k, $v) = each($catelist);
				list($k, $v) = each($v['forumlist']);
				$checkedfid = $v['fid'];
			}
			return $s;
		// 大区版主
		} elseif($groupid == 3) {
			$s = '';
			foreach($catelist as &$cate) {
				if(strpos(' '.$cate['modids'].' ', ' '.$user['uid'].' ') !== FALSE) {
					$s .= '<option value="'.$cate['fid'].'">'.$cate['name'].'</option>';
					foreach($cate['forumlist'] as &$forum) {
						empty($checkedfid) && $checkedfid = $forum['fid'];
						$checked = $checkedfid == $forum['fid'] ? ' selected="selected"' : '';
						$s .= '<option value="'.$forum['fid'].'"'.$checked.' style="font-weight: 800;">    &nbsp;   &nbsp; |-'.$forum['name'].'</option>';
					}
				}
			}
			return $s;
		// 普通版主
		} elseif($groupid == 4) {
			$s = '';
			foreach($catelist as &$cate) {
				$s2 = '<option value="'.$cate['fid'].'">'.$cate['name'].'</option>';
				$s3 = '';
				foreach($cate['forumlist'] as &$forum) {
					if(strpos(' '.$forum['modids'].' ', ' '.$user['uid'].' ') !== FALSE) {
						empty($checkedfid) && $checkedfid = $forum['fid'];
						$checked = $checkedfid == $forum['fid'] ? ' selected="selected"' : '';
						$s3 .= '<option value="'.$forum['fid'].'"'.$checked.' style="font-weight: 800;">    &nbsp;   &nbsp; |-'.$forum['name'].'</option>';
					}
				}
				!empty($s3) && $s .= $s2.$s3;
			}
			return $s;
		}
	}
}
?>