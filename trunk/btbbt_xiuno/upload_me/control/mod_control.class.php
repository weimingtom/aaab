<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class mod_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->check_login();
		if($this->_user['groupid'] > 4) {
			$this->message('对不起，您没有权限访问此模块。');
		}
	}
	
	public function on_index() {
		$this->on_setforum();
	}
	
	// 设置版规，简介
	public function on_setforum() {
		$this->_checked['mod_setforum'] = 'class="checked"';
		
		$this->_title[] = '基本资料';
		$this->_nav[] = '基本资料';
		
		$fid = intval(core::gpc('fid'));
		
		$error = array();
		if($this->form_submit()) {
			$rule = core::gpc('rule', 'P');
			$brief = core::gpc('brief', 'P');
			
			// 检查权限
			$forum = $this->forum->read($fid);
			$pforum = $this->forum->read($forum['fup']);
			if(!$this->is_mod($forum, $pforum, $this->_user)) {
				$this->message('您没有权限管理此版块。');
			}
			
			$brief = htmlspecialchars(strip_tags($brief));
			$rule = nl2br($rule);
			$rule = $this->post->html_safe($rule);
			$rule = misc::html_space($rule, TRUE);
			
			$error['rule'] = $this->forum->check_rule($rule);
			$error['brief'] = $this->forum->check_brief($brief);
			
			if(misc::values_empty($error)) {
				$forum = $this->forum->read($fid);
				$forum['rule'] = $rule;
				$forum['brief'] = $brief;
				$this->forum->update($fid, $forum);
				$this->mcache->clear('forum', $fid);
				$error = array();
			}
		}
		$this->view->assign('error', $error);
		
		$forumoptions = $this->forum->get_priv_forumoptions($this->_user, $fid);
		$this->view->assign('forumoptions', $forumoptions);
		$this->view->assign('fid', $fid);
		
		$forum = $this->forum->read($fid);
		$forum['rule'] = str_replace('<br>', '<br />', "\r\n", $forum['rule']);
		$this->view->assign('forum', $forum);
		
		$this->view->display('mod_setforum.htm');
	}
	
	// 列出版主管理日志
	public function on_listlog() {
		$this->_checked['mod_listlog'] = 'class="checked"';
		
		$this->_title[] = '操作日志';
		$this->_nav[] = '操作日志';
		
		$uid = $this->_user['uid'];
		$fid = intval(core::gpc('fid'));
		
		// hook my_pay_before.php
		$page = misc::page();
		$pagesize = 20;
		$loglist = $this->modlog->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?mod-listlog-fid-$fid.htm", count($loglist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('loglist', $loglist);
		
		$this->view->display('mod_listlog.htm');
	}
	
	// 设置置顶 各种置顶最多十个！
	public function on_top() {
		$this->_title[] = '设置置顶';
		$this->_nav[] = '设置置顶';
		
		$this->check_login();
		
		$fid = intval(core::gpc('fid'));
		$tidarr = (array)core::gpc('tidarr');		//fid_tid
		//foreach($tidarr as &$v) $v = intval($v);
		
		$forum = $this->forum->read($fid);
		$pforum = $this->forum->read($forum['fup']);

		if(!$this->is_mod($forum, $pforum, $this->_user)) {
			$this->message('您没有权限在该板块对主题进行置顶操作！');
		}
		if(!$this->form_submit()) {
			
			// tidsurl
			$tidsurl = '';
			foreach($tidarr as $fid_tid) {$tidsurl .= '&tidarr[]='.$fid_tid;}
			$threads = count($tidarr);
			
			$this->view->assign('tidsurl', $tidsurl);
			$this->view->assign('fid', $fid);
			$this->view->assign('threads', $threads);
			
			// hook mod_top_before.php
			
			$this->view->display('mod_top_ajax.htm');
		} else {
			$rank = intval(core::gpc('rank', 'P'));
			if($this->_user['groupid'] == 3 && $rank != 1) {
				$this->message('您只能对帖子进行板块置顶！', 0);
			}
			if($this->_user['groupid'] == 2 && $rank == 3) {
				$this->message('您不能对帖子进行全站置顶！', 0);
			}
			
			// -------> 统计 top_1 2 3 的总数，是否超过5个。
			$n = count($tidarr);
			if($rank == 1) {
				// 1 级置顶
				$keys = array();
				$this->tidkeys_to_keys($keys, $forum['toptids']);
				if(count($keys) + $n > 8) {
					$this->message('一级置顶的个数不能超过8个。', 0);
				}
			} elseif($rank == 2) {
				$keys = array();
				$this->tidkeys_to_keys($keys, $pforum['toptids']);
				if(count($keys) + $n > 8) {
					$this->message('二级置顶的个数不能超过8个。', 0);
				}
			} elseif($rank == 3) {
				$keys = array();
				$this->tidkeys_to_keys($keys, $this->conf['runtime']['toptids']);
				if(count($keys) + $n > 8) {
					$this->message('三级置顶的个数不能超过8个。', 0);
				}
			}
			// end
			
			// hook mod_top_after.php
			
			// 先去除已有，然后加入
			$pforum = $this->forum->read($forum['fup']);
			$this->thread_top->delete_top_1($forum, $tidarr);
			$this->thread_top->delete_top_2($forum, $tidarr, $pforum);
			$this->thread_top->delete_top_3($forum, $tidarr);
			
			if($rank == 0) {
				
			} elseif($rank == 1) {
				$this->thread_top->add_top_1($forum, $tidarr);
			} elseif($rank == 2) {
				$this->thread_top->add_top_2($forum, $tidarr, $pforum);
			} elseif($rank == 3) {
				$this->thread_top->add_top_3($forum, $tidarr);
			}
			
			// 记录到版主操作日志
			foreach($tidarr as &$v) {			// 此处也得用 &
				// 初始化数据
				list($fid, $tid) = explode('-', $v);
				$fid = intval($fid);
				$tid = intval($tid);
				
				$thread = $this->thread->read($fid, $tid);
				if(empty($thread)) continue;
				$this->modlog->create(array(
					'uid'=>$this->_user['uid'],
					'username'=>$this->_user['username'],
					'fid'=>$fid,
					'tid'=>$tid,
					'pid'=>0,
					'subject'=>$thread['subject'],
					'credits'=>0,
					'dateline'=>$_SERVER['time'],
					'action'=>$rank == 0 ? 'untop' : 'top',
				));
			}
			
			// hook mod_top_succeed.php
			
			$this->message('操作成功！', 1);
		}
		
	}
	
	public function on_digest() {
		$this->_title[] = '设置精华';
		$this->_nav[] = '设置精华';
		
		$this->check_login();
		
		$fid = intval(core::gpc('fid'));
		$tidarr = (array)core::gpc('tidarr');// 此处是 fid-tid
		$tidnum = 0;
		
		$forum = $this->forum->read($fid);
		$pforum = $this->forum->read($forum['fup']);
		if(!$this->is_mod($forum, $pforum, $this->_user)) {
			$this->message('您没有权限在该板块对主题设置精华！');
		}
		
		if(!$this->form_submit()) {
			// tidsurl
			$tidsurl = '';
			foreach($tidarr as $fid_tid) {$tidsurl .= '&tidarr[]='.$fid_tid;}
			$threads = count($tidarr);
			
			$this->view->assign('tidsurl', $tidsurl);
			$this->view->assign('fid', $fid);
			$this->view->assign('threads', $threads);
			
			// hook mod_digest_before.php
			$this->view->display('mod_digest_ajax.htm');
		} else {
			// 修改精华等级，分类。
			$rank = intval(core::gpc('rank', 'P'));
			$cateids = core::gpc('cateids', 'P');
			$cateidarr = explode(' ', $cateids);
			foreach($cateidarr as &$v) $v = intval($v);	// 此处用&以后，后面的也得用&，否则被引用改变，达不到期望值
			$catenames = core::gpc('catenames', 'P');
			//$this->safe_str($catenames, '\\s');
			$fidarr = $uidarr = array();
			$catestat = array();	// 分类下的 digest 计数
			
			// hook mod_digest_after.php
			foreach($tidarr as &$v) {			// 此处也得用 &
				// 初始化数据
				list($fid, $tid) = explode('-', $v);
				$fid = intval($fid);
				$tid = intval($tid);
				$thread = $this->thread->read($fid, $tid);
				if(empty($thread)) continue;
				
				// 更新 digestcate.threads
				$tidnum++;	// 帖子数，用来统计精华数
				// 查找原先属于哪些精华分类
				if($thread['digest'] > 0) {
					$_catelist = $this->digest->get_list_by_fid_tid($fid, $tid);
					foreach($_catelist as $_cate) {
						!isset($catestat[$_cate['cateid']]) && $catestat[$_cate['cateid']] = 0;
						$catestat[$_cate['cateid']]++;
					}
				}
				
				// 更新论坛精华数 todo: 准确？
				$forum = $this->forum->read($fid);
				$rank == 0 ? ($thread['digest'] && $forum['digests']--) : (!$thread['digest'] && $forum['digests']++);
				$this->forum->update($fid, $forum);
				$fidarr[$fid] = $fid;
				
				// 更新用户精华数，积分
				!isset($uidarr[$thread['uid']]) && $uidarr[$thread['uid']] = 0;
				$rank == 0 ? $uidarr[$thread['uid']] -= $this->conf['credits_policy_digest_'.$thread['digest']] : $uidarr[$thread['uid']] += $this->conf['credits_policy_digest_'.$rank];
				$rank == 0 ? $uidarr[$thread['uid']] -= $this->conf['gold_policy_digest_'.$thread['digest']] : $uidarr[$thread['uid']] += $this->conf['gold_policy_digest_'.$rank];
				
				// 记录到版主操作日志，可能影响到多条
				$this->modlog->create(array(
					'uid'=>$this->_user['uid'],
					'username'=>$this->_user['username'],
					'fid'=>$fid,
					'tid'=>$tid,
					'pid'=>0,
					'subject'=>$thread['subject'],
					'credits'=> $rank == 0 ? 0 - $this->conf['credits_policy_digest_'.$thread['digest']] : $this->conf['credits_policy_digest_'.$rank],
					'dateline'=>$_SERVER['time'],
					'action'=>$rank == 0 ? 'undigest' : 'digest',
				));
				
				// 帖子精华信息 
				$thread['digest'] = $rank;
				$thread['cateids'] = $this->substr_by_sep($cateids, ' ', 4);
				$thread['catenames'] = $this->substr_by_sep($catenames, ' ', 4);
				$this->thread->update($fid, $tid, $thread);
				
				if($rank == 0) {
					$this->digest->delete_by_fid_tid($fid, $tid);
				} else {
					// 先删除所有的分类，然后添加进去。
					$this->digest->delete_by_fid_tid($fid, $tid);
					foreach($cateidarr as $cateid) {
						$cateid = intval($cateid);
						$arr = array(
							'fid'=>$fid,
							'tid'=>$tid,
							'cateid'=>$cateid,
							'digest'=>$rank,
							'subject'=>$thread['subject'],
							'rank'=>$rank,
						);
						$this->digest->create($arr);
					}
				}
				
				// hook mod_digest_loop.php
			}
			
			if(empty($cateidarr)) {
				$this->message('请选择分类！', 0);
			}
			
			// 更新分类下的精华数，有点小麻烦
			// 原先的先减去
			foreach($catestat as $cateid=>$n) {
				$cate = $this->digestcate->read($cateid);
				$cate['threads'] -= $n;
				$this->digestcate->update($cateid, $cate);
			}
			// 如果 rank > 0 ，加上
			if($rank > 0) {
				foreach($cateidarr as $cateid) {
					$cateid = intval($cateid);
					$cate = $this->digestcate->read($cateid);
					$cate['threads'] += $tidnum;
					$this->digestcate->update($cateid, $cate);
				}
			}
			
			foreach($fidarr as $fid) {
				$this->mcache->clear('forum', $fid);
			}
			
			foreach($uidarr as $uid=>$credits) {
				$uid = intval($uid);
				$user = $this->user->read($uid);
				$user['credits'] += $credits;
				$credits > 0 ? $user['digests']++ : $user['digests']--;
				$this->user->update($uid, $user);
			}
			
			// hook mod_digest_succeed.php
			$this->message('操作成功！');
		}
	}
	
	// 主题分类
	public function on_type() {
		$this->_title[] = '设置主题分类';
		$this->_nav[] = '设置主题分类';
		
		$this->check_login();
		
		$fid = intval(core::gpc('fid'));
		$tidarr = (array)core::gpc('tidarr');// 此处是 fid-tid
		$tidnum = 0;
		
		$forum = $this->mcache->read('forum', $fid);
		$pforum = $this->mcache->read('forum', $forum['fup']);
		if(!$this->is_mod($forum, $pforum, $this->_user)) {
			$this->message('您没有权限在该板块设置主题分类！');
		}
		
		if(!$this->form_submit()) {
			// tidsurl
			$tidsurl = '';
			foreach($tidarr as $fid_tid) {$tidsurl .= '&tidarr[]='.$fid_tid;}
			
			$this->view->assign('tidsurl', $tidsurl);
			$this->view->assign('fid', $fid);
			
			$typeselect = form::get_select('typeid', $forum['typelist'], 0);
			$this->view->assign('typeselect', $typeselect);
			
			$threads = count($tidarr);
			$this->view->assign('threads', $threads);
			
			// hook mod_type_before.php
			$this->view->display('mod_type_ajax.htm');
		} else {
			// 修改精华等级，分类。
			$typeid = intval(core::gpc('typeid', 'P'));
			$typename = $this->thread_type->get_typename($typeid, $fid);
			
			// hook mod_type_after.php
			foreach($tidarr as &$v) {			// 此处也得用 &
				// 初始化数据
				list($fid, $tid) = explode('-', $v);
				$fid = intval($fid);
				$tid = intval($tid);
				
				$thread = $this->thread->read($fid, $tid);
				if(empty($thread)) continue;
				
				if($thread['typeid'] != $typeid) {
					$this->thread_type->count_threads($thread['typeid'], -1);
					$this->thread_type->count_threads($typeid, 1);
				}
				
				$thread['typeid'] = $typeid;
				$thread['typename'] = $typename;
				$this->thread->update($fid, $tid, $thread);
				
				// 记录到版主操作日志
				$this->modlog->create(array(
					'uid'=>$this->_user['uid'],
					'username'=>$this->_user['username'],
					'fid'=>$fid,
					'tid'=>$tid,
					'pid'=>0,
					'subject'=>$thread['subject'],
					'credits'=>0,
					'dateline'=>$_SERVER['time'],
					'action'=>'type',
				));
			}
			
			// hook mod_digest_succeed.php
			$this->message('操作成功！');
		}
	}
	
	// 所有 fid 相关表都需要更新，板块的统计数也需要更新。
	public function on_move() {
		$this->_title[] = '移动主题';
		$this->_nav[] = '移动主题';
		
		$this->check_login();
		
		$fid = intval(core::gpc('fid'));
		$tidarr = (array)core::gpc('tidarr');	// 此处是 fid_tid
		
		$forum = $this->forum->read($fid);
		$pforum = $this->forum->read($forum['fup']);

		if(!$this->is_mod($forum, $pforum, $this->_user)) {
			$this->message('您没有权限在该板块对主题进行移动操作！');
		}
		
		if(!$this->form_submit()) {
			
			// tidsurl
			$tidsurl = '';
			foreach($tidarr as $fid_tid) {$tidsurl .= '&tidarr[]='.$fid_tid;}
			$threads = count($tidarr);
			
			$forumoptions = $this->forum->get_forum_options($fid);
			$this->view->assign('forumoptions', $forumoptions);
			
			$this->view->assign('forum', $forum);
			$this->view->assign('tidsurl', $tidsurl);
			$this->view->assign('fid', $fid);
			$this->view->assign('threads', $threads);
			
			// hook mod_move_before.php
			$this->view->display('mod_move_ajax.htm');
		} else {
			
			// 目标论坛的发帖权限
			$fid2 = intval(core::gpc('fid2', 'P'));
			$forum2 = $this->forum->read($fid2);
			$this->check_forum_exists($forum2);
			if(empty($forum2['fup'])) {
				$this->message('请选择板块，不能选择分类。', 0);
			}
			$pforum2 = $this->forum->read($forum2['fup']);
			$this->check_forum_access($forum2, $pforum2, 'post');
			if($fid == $fid2) {
				$this->message('请选择其他板块。', 0);
			}
			
			// hook mod_move_after.php
			
			// 查找主题。更新 fid
			$tidnum = $pidnum = $digestnum = 0;
			foreach($tidarr as $v) {
				list($fid, $tid) = explode('-', $v);
				$fid = intval($fid);
				$tid = intval($tid);
				$thread = $this->thread->read($fid, $tid);
				if(empty($thread)) continue;
				if($thread['posts'] > 20000) continue;	// 高楼跳过
				if($thread['top'] > 0) {
					$this->message('您选择的主题中包含置顶主题，请先取消置顶再进行移动。', 0);
				}
				
				// 更新 digestcate.threads
				$tidnum++;	// 帖子数，用来统计精华数
				
				// 查找原先属于哪些精华分类
				if($thread['digest'] > 0) {
					$digestlist = $this->digest->get_list_by_fid_tid($fid, $tid);
					foreach($digestlist as $digest) {
						$digest['fid'] = $fid2;
						$this->digest->update($digest['digestid'], $digest);
					}
					$digestnum++;
				}
				
				// mypost
				$mypost = $this->mypost->read_by_tid($thread['uid'], $thread['fid'], $thread['tid']);
				if($mypost) {
					// 无法更新主键，只能先删除，再添加，等价于更新
					$this->mypost->_delete($mypost['uid'], $mypost['fid'], $mypost['pid']);
					$mypost['fid'] = $fid2;
					$this->mypost->create($mypost);
				}
				
				// post，分页更新 // 翻页查找所有id,逐个更新
				$pages = ceil($thread['posts'] / $this->conf['pagesize']);
				for($i = 1; $i <=  $pages; $i++) {
					$postlist = $this->post->index_fetch(array('fid'=>$fid, 'tid'=>$tid, 'page'=>$i),  array(), 0, 100);
					foreach($postlist as $_post) {
						// todo: 无法更新主键，只能先删除，再添加，等价于更新
						$this->post->_delete($_post['fid'], $_post['pid']);
						$_post['fid'] = $fid2;
						$this->post->create($fid2, $_post);
						
						// 更新 attach
						$attachlist = $this->attach->index_fetch(array('fid'=>$fid, 'pid'=>$_post['pid']), array(), 0, 100);
						foreach($attachlist as &$attach) {
							$attach['fid'] = $fid2;
							$this->attach->update($attach['aid'], $attach);
						}
					}
				}
				$pidnum += $thread['posts'];
				
				// thread 主键的值改变，需要先删除，再插入
				$thread['fid'] = $fid2;
				$this->thread->_delete($fid, $tid);
				$this->thread->update($fid, $tid, $thread);
				
				// 记录到版主操作日志
				$this->modlog->create(array(
					'uid'=>$this->_user['uid'],
					'username'=>$this->_user['username'],
					'fid'=>$fid,
					'tid'=>$tid,
					'pid'=>0,
					'subject'=>$thread['subject'],
					'credits'=>0,
					'dateline'=>$_SERVER['time'],
					'action'=>'move',
				));
				
				// hook mod_move_loop.php
			}
			
			// 更新 digest
			$forum['digests'] -= $digestnum;
			$forum2['digests'] += $digestnum;
			
			// 更新板块主题数，回复数
			$forum['threads'] -= $tidnum;
			$forum2['threads'] += $tidnum;
			$forum['posts'] -= $pidnum;
			$forum2['posts'] += $pidnum;
			
			$this->forum->update($fid, $forum);
			$this->forum->update($fid2, $forum2);
			
			// 更新缓存
			$this->clear_forum_cache($fid, TRUE);
			$this->clear_forum_cache($fid2, TRUE);
			
			// hook mod_move_succeed.php
			$this->message("操作成功！", 1, '?forum-index-fid-$fid2.htm');
		}
	
	}
	
	public function on_delete() {
		$this->_title[] = '删除主题';
		$this->_nav[] = '删除主题';
		
		$this->check_login();
		
		$fid = intval(core::gpc('fid'));
		$tidarr = (array)core::gpc('tidarr');	// fid_tid
		
		$forum = $this->forum->read($fid);
		$pforum = $this->forum->read($forum['fup']);
		if(!$this->is_mod($forum, $pforum, $this->_user)) {
			$this->message('您没有权限对该板块进行删除操作！');
		}
		
		if(!$this->form_submit()) {
			// tidsurl
			$tidsurl = '';
			foreach($tidarr as $tid) {$tidsurl .= '&tidarr[]='.$tid;}
			$threads = count($tidarr);
			
			$this->view->assign('tidsurl', $tidsurl);
			$this->view->assign('fid', $fid);
			$this->view->assign('threads', $threads);
			
			// hook mod_delete_before.php
			$this->view->display('mod_delete_ajax.htm');
		} else {
			
			// hook mod_delete_after.php
			foreach($tidarr as $v) {
				list($fid, $tid) = explode('-', $v);
				$fid = intval($fid);
				$tid = intval($tid);
				
				// 记录到版主操作日志
				$thread = $this->thread->read($fid, $tid);
				$this->modlog->create(array(
					'uid'=>$this->_user['uid'],
					'username'=>$this->_user['username'],
					'fid'=>$fid,
					'tid'=>$tid,
					'pid'=>0,
					'subject'=>$thread['subject'],
					'credits'=>0,
					'dateline'=>$_SERVER['time'],
					'action'=>'delete',
				));
				
				// hook mod_delete_loop.php
				
				$this->thread->xdelete($fid, $tid, TRUE);
			}
			
			// hook mod_delete_succeed.php
			$this->message('操作成功！');
		}
	}
	
	// copy from thread_control.class.php
	private function tidkeys_to_keys(&$keys, $tidkeys) {
		if($tidkeys) {
			$fidtidlist = explode(' ', trim($tidkeys));
			foreach($fidtidlist as $fidtid) {
				list($fid, $tid) = explode('-', $fidtid);
				$tid && $keys[] = "thread-fid-$fid-tid-$tid";
			}
		}
	}
	
	// 截取前几个字符串，分隔符为
	private function substr_by_sep($string, $sep, $n) {
		$arr = explode($sep, $string);
		$arr2 = array_slice($arr, 0, $n);
		return implode($sep, $arr2);
	}
	
	//hook mod_control.php
}

?>