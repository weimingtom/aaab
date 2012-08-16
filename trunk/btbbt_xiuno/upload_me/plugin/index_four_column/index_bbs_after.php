
		// 结果3分钟更新一次。json格式
		$cachefile = $this->conf['tmp_path'].'four_column_cache.js';
		if(is_file($cachefile) && $_SERVER['time'] - filemtime($cachefile) < 180) {
			$arr = json_decode(file_get_contents($cachefile), true);
			$newthreadlist = $arr['newthreadlist'];
			$newpostlist = $arr['newpostlist'];
			$newdigestlist = $arr['newdigestlist'];
			$newuserlist = $arr['newuserlist'];
		} else {
		
			// 禁止访问的论坛!
			$accessfids = array();
			foreach($catelist as &$cate) {
				foreach($cate['forumlist'] as &$forum) {
					if($forum['accesson']) {
						$access = $this->forum_access->read($forum['fid'], 0);
						if(!$access['allowread']) $accessfids[$forum['fid']] = $forum['fid'];
					}
				}
			}
			
			// 最新主题
			$newthreadlist = array();
			$newthreadlist = $this->thread->index_fetch(array(), array('tid'=>-1), 0, 20);
			foreach($newthreadlist as $k=>$v) {
				if(isset($accessfids[$v['fid']])) {
					unset($newthreadlist[$k]);
				} else {
					$newthreadlist[$k] = array (
						'fid'=> $v['fid'],
						'tid'=> $v['tid'],
						'subject'=> $v['subject'],
					);
				}
			}
			$newthreadlist = array_slice($newthreadlist, 0, 10);
			
			// 最新回复，回复的时候缓存到 new_post_tids.txt 了，直接读取即可
			$newpostlist = array();
			$file = $this->conf['tmp_path'].'new_post_tids.txt';
			if(is_file($file)) {
				$s = trim(file_get_contents($file));
				$keys = explode(' ', $s);
				$newpostlist = $this->thread->get($keys);
				foreach($newpostlist as &$v) {
					$v = array(
						'fid'=> $v['fid'],
						'tid'=> $v['tid'],
						'subject'=> $v['subject'],
					);
				}
				$newpostlist = array_reverse($newpostlist);
			}
			
			// 精华主题
			$newdigestlist = array();
			$newdigestlist = $this->digest->get_newlist(10);
			foreach($newdigestlist as &$v) {
				$v = array (
					'fid'=> $v['fid'],
					'tid'=> $v['tid'],
					'subject'=> $v['subject'],
					'digest'=> $v['digest'],
				);
			}
			
			// 最新注册用户
			$newuserlist = array();
			$newuserlist = $this->user->index_fetch(array(), array('uid'=>-1), 0, 10);
			foreach($newuserlist as &$v) {
				$v = array (
					'uid'=> $v['uid'],
					'username'=> $v['username'],
					'groupid'=> $v['groupid'],
				);
			}
			
			$arr = array();
			$arr['newthreadlist'] = $newthreadlist;
			$arr['newpostlist'] = $newpostlist;
			$arr['newdigestlist'] = $newdigestlist;
			$arr['newuserlist'] = $newuserlist;
			
			file_put_contents($cachefile, core::json_encode($arr));
		}
		
		$this->view->assign('newthreadlist', $newthreadlist);
		$this->view->assign('newpostlist', $newpostlist);
		$this->view->assign('newdigestlist', $newdigestlist);
		$this->view->assign('newuserlist', $newuserlist);
		