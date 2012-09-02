<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class forum_control extends admin_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// 列表
	public function on_index() {
		$this->on_list();
	}	
	
	public function on_list() {
		$this->_title[] = '板块列表';
		$this->_nav[] = '<a href="./">板块列表</a>';
		
		$error = array();
		if($this->form_submit()) {
			
			// 修改
			$namearr = core::gpc('name', 'P');
			$rankarr = core::gpc('rank', 'P');
			
			// hook admin_forum_list_gpc_after.php
			
			if(!empty($namearr)) {
				foreach($namearr as $fid=>$name) {
					$fid = intval($fid);
					$forum = $this->forum->read($fid);
					$forum['rank'] = intval($rankarr[$fid]);
					$forum['name'] = $namearr[$fid];
					$this->forum->update($fid, $forum);
					$this->mcache->clear('forum', $fid);
				}
				$this->mcache->clear('forumarr');
				$this->mcache->clear('forumlist');
				$this->mcache->clear('miscarr');
			}
			
			// 新增
			$newnamearr = core::gpc('newname', 'P');
			$newrankarr = core::gpc('newrank', 'P');
			$newfuparr = core::gpc('newfup', 'P');
			if(!empty($newnamearr)) {
				
				foreach($newnamearr as $fid=>$name) {
					$fid = intval($fid);
					!isset($newfuparr[$fid]) && $newfuparr[$fid] = 0;
					!isset($newrankarr[$fid]) && $newrankarr[$fid] = 0;
					$forum = array(
						'name'=>$name,
						'rank'=>intval($newrankarr[$fid]),
						'fup'=>intval($newfuparr[$fid]),
						'threads'=>0,
						'posts'=>0,
						'digests'=>0,
						'todayposts'=>0,
						'tops'=>0,
						'lastpost'=>0,
						'lastsubject'=>0,
						'lastuid'=>0,
						'lastusername'=>0,
						'brief'=>'',
						'rule'=>'',
						'icon'=>0,
						'accesson'=>0,
						'modids'=>'',
						'modnames'=>'',
						'toptids'=>'',
						'lastcachetime'=>0,
						'listtype'=>0,
						'orderby'=>0,
						'indexforums'=>0,
						'status'=>1,
						'seo_title'=>'',
						'seo_keywords'=>'',
					);
					
					// hook admin_forum_create_before.htm
					
					$forum = $this->forum->create($forum);
				}
			}
			
		}
		
		$page = misc::page();
		$forums = $this->forum->count();
		$forumlist = $this->forum->get_forum_list();
		foreach($forumlist as &$forum) {
			$this->forum->format($forum);
		}
		
		// hook admin_forum_list_view_before.php
		
		$this->view->assign('error', $error);
		$this->view->assign('forumlist', $forumlist);
		$this->view->display('forum_list.htm');
	}

	// 合并板块
	public function on_merge() {
		$this->_title[] = '合并板块';
		$this->_nav[] = '合并板块';
		
		$fid1 = intval(core::gpc('fid1', 'R'));
		$fid2 = intval(core::gpc('fid2', 'R'));

		$forumoptions = $this->forum->get_forum_options();
		$this->view->assign('forumoptions', $forumoptions);
		
		$input = $error = array();
		if($fid1 && $fid2) {
			
			$forum1 = $this->forum->read($fid1);
			$forum2 = $this->forum->read($fid2);
			$this->check_forum_exists($forum1);
			$this->check_forum_exists($forum2);
			
			if($forum1['fup'] == 0 || $forum2['fup'] == 0) {
				$this->message('分类不能合并，必须为论坛。');
			}
			
			// 合并完毕。
			if($forum2['threads'] <= 0) {
				
				$this->clear_forum_cache($fid1, TRUE);
				$this->clear_forum_cache($fid2, TRUE);
				
				$this->forum->_delete($fid2);
				
				// hook admin_forum_merge_succeed.php
				
				$this->message('合并完毕！', 1, '?forum-merge.htm');
			}
			
			// 每次处理1000
			$pagesize = 100;
			
			// --------------->修改 所有涉及到 fid 的表！
			
			// 清理 forum_access
			$this->forum_access->delete_by_fid($fid2);
			
			// 更新主题分类，合并。
			$typelist = $this->thread_type->index_fetch(array('fid'=>$fid), array(), 0, 1000);
			foreach($typelist as $type) {
				$type['fid'] = $fid1;
				$this->thread_type->update($type['typeid'], $type);
			}
			
			// 清空 modlog
			$n = $this->modlog->delete_by_fid($fid2);
			if($n > 100000) {
				//log::write('合并板块时， modlog 可能没有清理干净，需要手工清理。');
			}
			
			// thread
			$tidkeys = $this->thread->index_fetch_id(array('fid'=>$fid2), array(), 0, $pagesize);
			$threads = $posts = $digests = 0;
			foreach($tidkeys as $key) {
				list($table, $_, $_, $_, $_tid) = explode('-', $key);
				$_tid = intval($_tid);
				$thread = $this->thread->read($fid2, $_tid);
				
				// digest 分类总数未改变
				if($thread['digest'] > 0) {
					$digestlist = $this->digest->get_list_by_fid_tid($fid2, $_tid);
					foreach($digestlist as $digest) {
						$digest['fid'] = $fid1;
						$this->digest->update($digest['digestid'], $digest);
					}
				}
				
				// mypost
				$mypost = $this->mypost->read_by_tid($thread['uid'], $thread['fid'], $thread['tid']);
				if($mypost) {
					// 无法更新主键，只能先删除，再添加，等价于更新
					$this->mypost->_delete($mypost['uid'], $mypost['fid'], $mypost['pid']);
					$mypost['fid'] = $fid1;
					$this->mypost->create($mypost);
				}
				
				// post，分页更新 // 翻页查找所有id,逐个更新
				$pages = ceil($thread['posts'] / $this->conf['pagesize']);
				for($i = 1; $i <=  $pages; $i++) {
					$postlist = $this->post->index_fetch(array('fid'=>$fid2, 'tid'=>$_tid, 'page'=>$i),  array(), 0, 100);
					foreach($postlist as $_post) {
						// todo: 无法更新主键，只能先删除，再添加，等价于更新
						$this->post->_delete($_post['fid'], $_post['pid']);
						$_post['fid'] = $fid1;
						$this->post->create($fid1, $_post);
						
						// 更新 attach
						$attachlist = $this->attach->index_fetch(array('fid'=>$fid2, 'pid'=>$_post['pid']), array(), 0, 100);
						foreach($attachlist as &$attach) {
							$attach['fid'] = $fid1;
							$this->attach->update($attach['aid'], $attach);
						}
					}
				}
				
				// todo: 无法更新主键，只能先删除，再添加，等价于更新
				$this->thread->_delete($fid2, $_tid);
				$thread['fid'] = $fid1;
				$thread['top'] = 0;
				$this->thread->create($fid1, $thread);
				
				$threads++;
				$posts += $thread['posts'];
				$thread['digest'] > 0 && $digests ++;
			}
			
			// hook admin_forum_merge_ing.php
			
			$forum1['posts'] += $forum2['posts'];
			$forum1['threads'] += $forum2['threads'];
			$forum1['digests'] += $forum2['digests'];
			$this->forum->update($fid1, $forum1);
			
			$forum2['threads'] -= count($tidkeys);
			$this->forum->update($fid2, $forum2);
			
			$this->message("正在更新，还剩：$forum2[threads] 主题", 1, "?forum-merge-fid1-$fid1-fid2-$fid2.htm");
		}
		
		$this->view->assign('error', $error);
		$this->view->display('forum_merge.htm');
	}
	
	// 修改
	public function on_update() {
		$this->_title[] = '修改板块';
		$this->_nav[] = '修改板块';
		
		$fid = intval(core::gpc('fid'));

		$forum = $this->forum->get($fid);
		$this->check_forum_exists($forum);
		
		$input = $error = array();
		if($this->form_submit()) {
			
			// 处理主题分类
			$this->process_typeid($fid);
			
			// 准备更新数据
			$post = array();
			
			$post['fup'] = intval(core::gpc('fup', 'P'));
			$post['name'] = core::gpc('name', 'P');
			$post['rank'] = intval(core::gpc('rank', 'P'));
			$post['status'] = intval(core::gpc('status', 'P'));
			$post['orderby'] = intval(core::gpc('orderby', 'P'));
			$post['listtype'] = intval(core::gpc('listtype', 'P'));
			$post['indexforums'] = intval(core::gpc('indexforums', 'P'));
			//$post['threads'] = intval(core::gpc('threads', 'P'));
			//$post['posts'] = intval(core::gpc('posts', 'P'));
			//$post['todayposts'] = intval(core::gpc('todayposts', 'P'));
			//$post['lastpost'] = intval(core::gpc('lastpost', 'P'));
			$post['brief'] = core::gpc('brief', 'P');
			$post['rule'] = core::gpc('rule', 'P');
			$post['modnames'] = trim(core::gpc('modnames', 'P'));
			$post['seo_title'] = trim(core::gpc('seo_title', 'P'));
			$post['seo_keywords'] = trim(core::gpc('seo_keywords', 'P'));
			
			
			// 权限
			$post['accesson'] = intval(core::gpc('accesson', 'P'));
			$groupids = core::gpc('groupids', 'P');
			$allowreads = (array)core::gpc('allowread', 'P');// 是数组
			$allowposts = (array)core::gpc('allowpost', 'P');
			$allowthreads = (array)core::gpc('allowthread', 'P');
			$allowattachs = (array)core::gpc('allowattach', 'P');
			$allowdowns = (array)core::gpc('allowdown', 'P');
			
			// 版主
			$modids = $modnames = '';
			$post['modnames'] = str_replace(array('　', "\t", '  '), ' ', $post['modnames']);
			$modnamearr = explode(' ', $post['modnames']);
			$modnamearr = array_unique($modnamearr);
			$modnamearr = array_slice($modnamearr, 0, 4);	// 最多四个
			foreach($modnamearr as $modname) {
				$_user = $this->user->get_user_by_username($modname);
				if($_user) {
					$_user && $modids .= ' '.$_user['uid'];
					$modnames .= ' '.$_user['username'];
					// 调整用户组
					$groupid = $forum['fup'] == 0 ? 3 : 4;
					$user = $this->user->read($_user['uid']);
					$user['groupid'] = $groupid > $user['groupid'] ? $user['groupid'] : $groupid;// 提升版主权限
					$this->user->update($_user['uid'], $user);
				}
			}
			$post['modids'] = trim($modids);
			$post['modnames'] = trim($modnames);
			
			if($post['accesson']) {
				foreach($groupids as $groupid) {
					$groupid = intval($groupid);
					!isset($allowreads[$groupid]) && $allowreads[$groupid] = 0;
					!isset($allowposts[$groupid]) && $allowposts[$groupid] = 0;
					!isset($allowthreads[$groupid]) && $allowthreads[$groupid] = 0;
					!isset($allowattachs[$groupid]) && $allowattachs[$groupid] = 0;
					!isset($allowdowns[$groupid]) && $allowdowns[$groupid] = 0;
					$access = $this->forum_access->read($groupid, $fid);
					$access['allowread'] = intval($allowreads[$groupid]);
					$access['allowpost'] = intval($allowposts[$groupid]);
					$access['allowthread'] = intval($allowthreads[$groupid]);
					$access['allowdown'] = intval($allowdowns[$groupid]);
					$access['allowattach'] = intval($allowattachs[$groupid]);
					$access['allowdown'] = intval($allowdowns[$groupid]);
					$access['fid'] = $fid;
					$access['groupid'] = intval($groupid);
					$this->forum_access->update($fid, $groupid, $access);
				}
			} else {
				// 清除权限
				$this->forum_access->delete_by_fid($fid);
			}
			
			$error['fup'] = $this->forum->check_fup($fid, $post['fup']);
			$error['name'] = $this->forum->check_name($post['name']);

			if(misc::values_empty($error)) {
				$error = array();

				$forum = array_merge($forum, $post);
				
				// hook admin_forum_update_after.php
				
				$this->forum->update($fid, $forum);
			}
			
			// 继承
			$forum = $this->forum->read($fid);
			$applyson = core::gpc('applyson', 'P');
			if(empty($forum['fup']) && $applyson) {
				$this->appy_to_son($fid);
			}
			
			// 清除缓存
			$this->clear_forum_cache($fid);
		}
		
		$grouplist = $this->group->get_grouplist();
		$accesslist = $this->forum_access->get_list_by_fid($fid);
		if(empty($accesslist)) {
			$this->forum_access->set_default($accesslist, $grouplist);
		}
		
		$forumoptions = $this->forum->get_forum_options($forum['fup']);
		$this->view->assign('forumoptions', $forumoptions);
		
		$listtypearr = array(0=>'标题列表排列', 1=>'图文排列');
		$orderbyarr = array(0=>'顶贴时间排序', 1=>'发帖时间排序');
		
		// hook admin_forum_update_listtype_before.php
		
		$input = array();
		$input['status'] = form::get_radio_yes_no('status', $forum['status']);
		$input['orderby'] = form::get_radio('orderby', $orderbyarr, $forum['orderby']);
		$input['listtype'] = form::get_radio('listtype', $listtypearr, $forum['listtype']);
		$input['indexforums'] = form::get_text('indexforums', $forum['indexforums'], 32);
		$this->forum->format($forum);
		$forum['typelist'] = $this->thread_type->get_typelist_by_fid($fid);	// 这里需要完全格式
		
		$this->view->assign('input', $input);
		$this->view->assign('grouplist', $grouplist);
		$this->view->assign('accesslist', $accesslist);
		$this->view->assign('fid', $forum['fid']);
		$this->view->assign('forum', $forum);
		$this->view->assign('error', $error);
		
		// hook admin_forum_update_before.php
		
		$this->view->display('forum_update.htm');
	}
	
	// 向下继承
	private function appy_to_son($fid) {
		// 批量复制本版的设置
		$forum = $this->forum->read($fid);
		$sonforumlist = $this->forum->get_forumlist_by_fup($fid);
		foreach($sonforumlist as $sonforum) {
			// 最新发帖
			$sonforum['accesson'] = $forum['accesson'];
			
			$this->forum_access->delete_by_fid($sonforum['fid']);
			
			if($forum['accesson']) {
				$accesslist = $this->forum_access->get_list_by_fid($fid);
				foreach($accesslist as $access) {
					$this->forum_access->update($sonforum['fid'], $access['groupid'], $access);
				}
			}
			// hook admin_forum_appy_to_son_update_before.php
			
			$this->forum->update($sonforum['fid'], $sonforum);
			
			// hook admin_forum_appy_to_son_after.php
		}
	}
	
	public function on_delete() {
		$this->_title[] = '删除板块';
		$this->_nav[] = '删除板块';
		
		$fid = intval(core::gpc('fid'));
		$fids = core::gpc('fids');	// _ 隔开
		$starttid = intval(core::gpc('starttid'));//tid
		$startfid = intval(core::gpc('startfid'));//fid
		$threads = intval(core::gpc('threads'));//fid
		
		$limit = 200;

		$forum = $this->forum->get($fid);
		if(empty($forum)) {
			$this->message('板块已经被删除。', 1, '?forum-list.htm');
		}
		
		// 查找子版块，删除帖子，附件...
		if(empty($fids)) {
			$fids = '';
			$forumlist = $this->forum->index_fetch(array('fup'=>$fid), array(), 0, 1000);
			foreach($forumlist as $_forum) {
				$fids .= $_forum['fid'].'_';
			}
			$fids .= $fid;
		}
		$fidarr = explode('_', $fids);
		foreach($fidarr as $k=>$_fid) {
			$k = intval($k);
			$_fid = intval($_fid);
			if($k == $startfid) {
				if(empty($threads)) {
					$forum = $this->forum->read($_fid);
					$threads = $forum['threads'];
				}
				if($starttid >= $threads) {
					$startfid++;
					// fid++ tid=0 跳转
					// hook admin_forum_delete_complete.php
					$this->forum->_delete($_fid);
					$this->message("删除 fid: $_fid 完毕 ...", 1, "?forum-delete-fid-$fid-startfid-$startfid-fids-$fids.htm");
				} else {
					// fid tid+=2000 跳转
					$tidkeys = $this->thread->index_fetch_id(array('fid'=>$_fid), array(), 0, $limit);
					$return = array();
					foreach($tidkeys as $key) {
						list($table, $_, $_, $_, $_tid) = explode('-', $key);
						$_tid = intval($_tid);
						// $_fid $_tid
						// hook admin_forum_delete_tid_before.php
						$return2 = $this->thread->xdelete($_fid, $_tid, FALSE);
						$this->thread->xdelete_merge_return($return, $return2);
					}
					$this->thread->xdelete_update($return);
					$starttid += $limit;
					$this->message("删除 fid: $_fid, tid: $starttid ...", 1, "?forum-delete-fid-$fid-threads-$threads-startfid-$startfid-starttid-$starttid-fids-$fids.htm");
				}
			} else {
				continue;
			}
		}
		
		// hook admin_forum_delete_after.php
		
		// 删除首页的缓存
		$this->message('删除完毕', 1, '?forum-list.htm');
		
	}

	public function on_uploadicon() {
		
		$uid = $this->_user['uid'];
		$this->check_forbidden_group();
		$user = $this->user->read($uid);
		
		$fid = intval(core::gpc('fid'));
		$destfile = $this->conf['upload_path']."forum/{$fid}_tmp.jpg";
		$desturl = $this->conf['upload_url']."forum/{$fid}_tmp.jpg";
		$arr = image::thumb($_FILES['Filedata']['tmp_name'], $destfile, 800, 600);
		$json = array('width'=>$arr['width'], 'height'=>$arr['height'], 'body'=>$desturl);
		
		// hook admin_forum_updateicon_after.php
		
		$this->message($json);
	}
	
	public function on_clipicon() {
		$fid = intval(core::gpc('fid'));
		$forum = $this->forum->read($fid);
		$this->check_forum_exists($forum);
		
		$x = intval(core::gpc('x', 'P'));
		$y = intval(core::gpc('y', 'P'));
		$w = intval(core::gpc('w', 'P'));
		$h = intval(core::gpc('h', 'P'));
		$srcfile = $this->conf['upload_path']."forum/{$fid}_tmp.jpg";
		$tmpfile = $this->conf['upload_path']."forum/{$fid}_tmp_clip.jpg";
		$smallfile = $this->conf['upload_path']."forum/{$fid}_small.gif";
		$middlefile = $this->conf['upload_path']."forum/{$fid}_middle.gif";
		$bigfile = $this->conf['upload_path']."forum/{$fid}_big.gif";
		$bigurl = $this->conf['upload_url']."forum/{$fid}_big.gif";
		image::clip($srcfile, $tmpfile, $x, $y, $w, $h);
		image::thumb($tmpfile, $bigfile, $this->conf['forumicon_width_small'], $this->conf['forumicon_width_small']);
		image::thumb($tmpfile, $bigfile, $this->conf['forumicon_width_middle'], $this->conf['forumicon_width_middle']);
		image::thumb($tmpfile, $bigfile, $this->conf['forumicon_width_big'], $this->conf['forumicon_width_big']);
		unlink($srcfile);
		unlink($tmpfile);
		
		if(is_file($bigfile)) {
			$forum['icon'] = $_SERVER['time'];
			$this->forum->update($fid, $forum);
			$this->mcache->clear('forum', $fid);
			$this->mcache->clear('forumlist');
			
			// hook admin_forum_clipicon_after.php
			
			$this->message($bigurl);
		} else {
			$this->message('保存失败', 0);
		}
	}
	
	private function process_typeid($fid) {
		$typenames = (array)core::gpc('typename', 'P');
		$ranks = (array)core::gpc('rank', 'P');
		if(!empty($typenames)) {
			foreach($typenames as $typeid=>$typename) {
				// 修改
				$type = $this->thread_type->read($typeid);
				$type['typename'] = $typename;
				$type['rank'] = $ranks[$typeid];
				$this->thread_type->update($typeid, $type);
			}
		}
		
		$newranks = (array)core::gpc('newrank', 'P');
		$newtypenames = (array)core::gpc('newtypename', 'P');
		if(!empty($newtypenames)) {
			foreach($newtypenames as $typeid=>$typename) {
				if(empty($typename)) continue;
				// 新增
				$type = array(
					'fid'=>$fid,
					'typename'=>$typename,
					'rank'=>$newranks[$typeid],
				);
				$type = $this->thread_type->create($type);
			}
		}
	}
	
	// 删除主题分类
	public function on_deletetype() {
		$typeid = intval(core::gpc('typeid'));
		$this->thread_type->_delete($typeid);
		
		// 解除关联的主题分类，避免超时。最多1000篇
		$threadlist = $this->thread->index_fetch_id(array('typeid'=>$typeid), array(), 1, 1000);
		if(count($threadlist) == 1000) {
			$this->message('该主题分类下主题过多，不能直接删除改分类，建议您改名。', 0);
		} else {
			foreach($threadlist as $key) {
				$thread = $this->thread->get($key);
				$thread['typeid'] = 0;
				$thread['typename'] = '';
				$this->thread->set($key, $thread);
			}
			
			$this->message('删除成功。');
		}
	}
	
	/*
	// 更新 所有的 tid 点击数, 1亿 个 tid = 大约需要 400M 缓存数据。
	public function on_clickd() {
		$maxtid = $this->thread->count();
		
		$start = intval(core::gpc('start'));
		$limit = intval(core::gpc('limit'));
		$limittid = intval(core::gpc('limittid'));
		
		$limittid = max(1000000, $maxtid * 5);	// 下限，最小 4M, 最大400M
		$limittid = min($limittid, 100000000);	// 上限
		
		$clicktmpfile = $this->conf['tmp_path'].'click_server.data';
		$clickdatafile = $this->conf['upload_path'].'click_server.data';
		
		// 创建大文件
		if(!is_file($clicktmpfile)) {
			$fp = fopen($clicktmpfile, 'wb');
			fseek($fp, $limittid * 4);
			fwrite($fp, pack("L*", 0x00), 4);
			fclose($fp);
		}
		
		// 每次取一千，不排序，按照存储顺序取，磁盘一千次读写！最好放在内存里面，否则很费磁盘
		$fp = fopen($clicktmpfile, 'rb+');
		$threadlist = $this->thread->index_fetch(array(), array(), $start, $limit);
		foreach($threadlist as $thread) {
			$pos = $thread['tid'] * 4;
			$tidpack = pack("L", $thread['views']);
			fseek($fp, $pos);
			fwrite($fp, $tidpack, 4);
		}
		fclose($fp);
		
		// 完毕
		if($start + $limit >= $maxtid) {
			copy($clicktmpfile, $clickdatafile);
		}
		$this->message('ok');
	}*/
	
	//hook forum_control_after.php
	
}
?>