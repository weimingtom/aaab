<?php

/*
 * @author:huyao
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class medal_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	// 列表
	public function on_index() {
		$this->on_list();
	}	
	
	public function on_list() {
		$this->_title[] = '勋章列表';
		$this->_nav[] = '<a href="">友情链接列表</a>';
		
		$pagesize = 10;
		$page = misc::page();
		$num = $this->medal->count();
		$medallist = $this->medal->index_fetch(array(), array('seq'=>1), ($page - 1) * $pagesize, $pagesize);
		foreach ($medallist as &$v) {
			$v['picture'] = $this->conf['upload_path'].$v['picture'];
			if ($v['receiveType'] == 1) {
				$v['receiveType'] = '自动发放';
			} elseif ($v['receiveType'] == 2) {
				$v['receiveType'] = '手动发放';
			} else {
				$v['receiveType'] = '未定义';
			}
		}
		
		$pages = misc::pages("?medal-list.htm", $num, $page, $pagesize);
		
		$this->view->assign('medallist', $medallist);
		$this->view->assign('pages', $pages);
		$this->view->assign('page', $page);
		$this->view->display('medal_list.htm');
	}
	
	public function on_create() {
		$page  =core::gpc('page');
		if($this->form_submit()) {
			$seqArr = core::gpc('seq', 'P');
			$medalNameArr = core::gpc('medalName', 'P');
			$receiveTypeArr = core::gpc('receiveType', 'P');
			$descriptionArr = core::gpc('description', 'P');
			$pictureArr = core::gpc('picture', 'P');
			
			$logopath = $this->conf['upload_path'].'medal/';
			
			foreach ($medalNameArr as $k=>$medalName) {
				$arr = array(
					'medalName'=>$medalName,
					'receiveType'=>$receiveTypeArr[$k],
					'description'=>$descriptionArr[$k],
					'seq'=>$seqArr[$k],
					'userId'=>$this->_user['uid'],
					'createdTime'=>date('Y-m-d H:i:s'),
				);
				
				$medalId = $this->medal->create($arr);
				if(!$medalId) continue;
				
				$tmpfile = empty($_FILES['picture']['tmp_name'][$k]) ? '' : $_FILES['picture']['tmp_name'][$k];
				if($tmpfile) {
					$filename = $_FILES['picture']['name'][$k];
					$ext = strrchr($filename, '.');
					// 防止传马
					if(!in_array($ext, array('.jpg', '.gif', '.png', '.bmp'))) continue;
					$logofile = $logopath.$medalId.$ext;
					$arr['picture'] = 'medal/'.$medalId.$ext;
					if(is_file($tmpfile) && move_uploaded_file($tmpfile, $logofile)) {
						image::thumb($logofile, $logofile, 88, 31);
						$this->medal->update($medalId, $arr);
					}
				}
			}
		}
		$this->location("?medal-list-page-$page.htm");
	}
	
	public function on_update() {
		$this->_title[] = '勋章修改';
		$page = core::gpc('page');
		
		$medalId = intval(core::gpc('medalId'));
		$medal = $this->medal->get($medalId);
		
		if ($this->form_submit()) {
			$medalId = core::gpc('medalId');
			$medalArr = core::gpc('medal', 'P');
			
			$logopath = $this->conf['upload_path'].'medal/';
			$tmpfile = empty($_FILES['picture']['tmp_name']) ? '' : $_FILES['picture']['tmp_name'];
			if($tmpfile) {
				$filename = $_FILES['picture']['name'];
				$ext = strrchr($filename, '.');
				// 防止传马
				if(!in_array($ext, array('.jpg', '.gif', '.png', '.bmp'))) continue;
				$logofile = $logopath.$medalId.$ext;
				$medalArr['picture'] = 'medal/'.$medalId.$ext;
				if(is_file($tmpfile) && move_uploaded_file($tmpfile, $logofile)) {
					image::thumb($logofile, $logofile, 88, 31);
				}
				
				//$medal['picture'] && unlink($logopath.$medal['picture']);
			} else {
				$medalArr['picture'] = $medal['picture'];
			}
			$this->medal->update($medalId, $medalArr);
			
			$this->message('编辑成功', true, "?medal-list-page-$page.htm");
		}
				
		if ($medal && $medal['picture']) {
			$medal['picture'] = $this->conf['upload_path'].$medal['picture'];
		}
		
		$this->view->assign('page', $page);
		$this->view->assign('medal', $medal);
		$this->view->display('medal_update.htm');
	}
	
	public function on_delete() {
		$this->_title[] = '删除勋章';
		$this->_nav[] = '删除勋章';
		
		$page = intval(core::gpc('page'));
		$medalId = intval(core::gpc('medalId'));
		$medalIds = empty($medalId) ? core::gpc('medalIds', 'P') : array($medalId);
		foreach((array)$medalIds as $medalId) {
			$medalId = intval($medalId);
			$this->medal->_delete($medalId);
		}
		//$this->mcache->clear('medal');
		
		$this->location("?medal-list-page-$page.htm");
	}
	
	// 勋章发放
	public function on_grant() {
		$page = misc::page();
		
		if ($this->form_submit()) {
			$medalId = core::gpc('medalId', 'P');
			$medal = $this->medal->get($medalId);
			if (!$medal) {
				$this->message('错误，勋章不存在。', false);
			}
			$userName = core::gpc('userName', 'P');
			$userId = 0;
			if (!$userName) {
				$this->message('错误，用户名不能为空。', false);
			} else {
				$user = $this->user->get_user_by_username($userName);
				if (!$user) {
					$this->message('错误，用户名不存在。', false);
				} else {
					$userId = $user['uid'];
				}
			}
			
			$expiredTime = core::gpc('expiredTime', 'P');
			if ($expiredTime) {
				$expiredTime = time()+86400*intval($expiredTime);
			}
			
			$arr = array(
				'medalId'=>$medalId,
				'userName'=>$userName,
				'userId'=>$userId,
				'receiveType'=>$medal['receiveType'],
				'expiredTime'=>$expiredTime,
				'isApply'=>1,
				'fuserId'=>$this->_user['uid'],
				'createdTime'=>time()
			);
			$this->medal_user->create($arr);
			
			$this->message('勋章发放成功。', true, "?medal-grant-page-$page.htm");
		}
		
		$pagesize = 10;
		$num = $this->medal_user->count();
		$medal_user_list = $this->medal_user->index_fetch(array('isApply'=>1), array('medalUserId'=>-1), ($page - 1) * $pagesize, $pagesize);
		foreach ($medal_user_list as &$v) {
			$medal = $this->medal->get($v['medalId']);
			if ($medal) {
				$v['medalName'] = $medal['medalName'];
				$v['picture'] = $medal['picture'] ? $this->conf['upload_path'].$medal['picture'] : '';
				if ($medal['receiveType'] == 1) {
					$v['receiveType'] = '自动发放';
				} elseif ($v['receiveType'] == 2) {
					$v['receiveType'] = '手动发放';
				} else {
					$v['receiveType'] = '未定义';
				}
			}
		}
		
		$pages = misc::pages("?medal-grant.htm", $num, $page, $pagesize);
		
		// 勋章列表
		$medal_list = $this->medal->index_fetch();
		
		$this->view->assign('medal_list', $medal_list);
		$this->view->assign('medal_user_list', $medal_user_list);
		$this->view->assign('pages', $pages);
		$this->view->assign('page', $page);
		
		$this->view->display('medal_grant.htm');
	}
	
	// 回收勋章
	public function on_recover() {
		$page = intval(core::gpc('page'));
		$medalUserId = intval(core::gpc('medalUserId'));
		$medalUserIds = empty($medalUserId) ? core::gpc('medalUserIds', 'P') : array($medalUserId);
		foreach((array)$medalUserIds as $medalUserId) {
			$medalUserId = intval($medalUserId);
			$this->medal_user->_delete($medalUserId);
		}
		
		$this->location("?medal-grant-page-$page.htm");
	}
	
	// 审核列表
	public function on_apply() {
		$page = misc::page();
		$pagesize = 10;
		$num = $this->medal->count();
		$medal_user_list = $this->medal_user->index_fetch(array('isApply'=>0), array('medalUserId'=>-1), ($page - 1) * $pagesize, $pagesize);
		foreach ($medal_user_list as &$v) {
			$medal = $this->medal->get($v['medalId']);
			if ($medal) {
				$v['medalName'] = $medal['medalName'];
				$v['picture'] = $medal['picture'] ? $this->conf['upload_path'].$medal['picture'] : '';
				$v['createdTime'] = date('Y-m-d', $v['createdTime']);
			}
		}
		
		$pages = misc::pages("?medal-apply.htm", $num, $page, $pagesize);
				
		$this->view->assign('medal_user_list', $medal_user_list);
		$this->view->assign('pages', $pages);
		$this->view->assign('page', $page);
		
		$this->view->display('medal_apply.htm');
	}
	
	// 通过或者否决
	public function on_modApply() {
		$page = misc::page();
		$status = intval(core::gpc('status', 'P'));
		$medalUserIds = core::gpc('medalUserIds', 'P');
		
		$message = '';
		switch($status) {
			case 1:
				foreach ($medalUserIds as $medalUserId) {
					$arr = $this->medal_user->get($medalUserId);
					if ($arr) {
						$arr['isApply'] = 1;
						$this->medal_user->update($arr['medalUserId'], $arr);
					}
				}
				$message = '通过操作成功。';
				break;
			default:
				foreach ($medalUserIds as $medalUserId) {
					$this->medal_user->_delete($medalUserId);
				}
				$message = '否决操作成功。';
				break;
		}
		$this->message($message, true, "?medal-apply-page-$page.htm");
	}
}