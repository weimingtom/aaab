<?php
/*
 * author:huyao
 * email:252288762@qq.com
 * qq:252288762
 * description:勋章插件和xiuno一起努力，做到最好
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class medaladmin_control extends admin_control {
	
	function __construct() {
		parent::__construct();
		
		$this->view->assign('pluginurl', $this->conf['plugin_url']);
		echo $this->conf['plugin_url'];
	}
	
	// 列表
	public function on_index() {
		$this->on_list();
	}	
	
	public function on_list() {
		$this->_title[] = '勋章列表';
		$this->_nav[] = '<a href="">友情链接列表</a>';
		
		$pagesize = 30;
		$page = misc::page();
		$num = $this->medal->count();
		$medallist = $this->medal->get_medallist_orderby_seq(1, $page, $pagesize);
		$this->medal->medallist_format($medallist);
		
		$pages = misc::pages("?medaladmin-list.htm", $num, $page, $pagesize);
		
		$this->view->assign('medallist', $medallist);
		$this->view->assign('pages', $pages);
		$this->view->assign('page', $page);
		$this->view->assign('receivetypelist', $this->medal->receivetypelist);
		$this->view->display('medaladmin_list.htm');
	}
	
	public function on_create() {
		$page  =core::gpc('page');
		if($this->form_submit()) {
			$seqarr = core::gpc('seq', 'P');
			$medalnamearr = core::gpc('medalname', 'P');
			$receivetypearr = core::gpc('receivetype', 'P');
			$descriptionarr = core::gpc('description', 'P');
			$picturearr = core::gpc('picture', 'P');
			
			$logopath = $this->conf['upload_path'].'medal/';
			if (!file_exists($logopath)) {
				mkdir($logopath, 0777);
			}
			
			foreach ($medalnamearr as $k=>$medalname) {
				$arr = array(
					'medalname'=>$medalname,
					'receivetype'=>$receivetypearr[$k],
					'description'=>$descriptionarr[$k],
					'seq'=>$seqarr[$k],
					'uid'=>$this->_user['uid'],
					'createdtime'=>time(),
				);
				
				$medalid = $this->medal->create($arr);
				if(!$medalid) continue;
				
				$tmpfile = empty($_FILES['picture']['tmp_name'][$k]) ? '' : $_FILES['picture']['tmp_name'][$k];
				if($tmpfile) {
					$filename = $_FILES['picture']['name'][$k];
					$ext = strrchr($filename, '.');
					// 防止传马
					if(!in_array($ext, array('.jpg', '.gif', '.png', '.bmp'))) continue;
					$logofile = $logopath.$medalid.$ext;
					$arr['picture'] = 'medal/'.$medalid.$ext;
					if(is_file($tmpfile) && move_uploaded_file($tmpfile, $logofile)) {
						image::thumb($logofile, $logofile, 88, 31);
						$this->medal->update($medalid, $arr);
					}
				}
			}
		}
		$this->location("?medaladmin-list-page-$page.htm");
	}
	
	public function on_update() {
		$this->_title[] = '勋章修改';
		$page = core::gpc('page');
		
		$medalid = intval(core::gpc('medalid'));
		$medal = $this->medal->get($medalid);
		
		if ($this->form_submit()) {
			$medalid = core::gpc('medalid');
			$medalarr = core::gpc('medal', 'P');
			
			$logopath = $this->conf['upload_path'].'medal/';
			$tmpfile = empty($_FILES['picture']['tmp_name']) ? '' : $_FILES['picture']['tmp_name'];
			if($tmpfile) {
				$filename = $_FILES['picture']['name'];
				$ext = strrchr($filename, '.');
				// 防止传马
				if(!in_array($ext, array('.jpg', '.gif', '.png', '.bmp'))) continue;
				$logofile = $logopath.$medalid.$ext;
				$medalarr['picture'] = 'medal/'.$medalid.$ext;
				if(is_file($tmpfile) && move_uploaded_file($tmpfile, $logofile)) {
					image::thumb($logofile, $logofile, 88, 31);
				}
				
				//$medal['picture'] && unlink($logopath.$medal['picture']);
			} else {
				$medalarr['picture'] = $medal['picture'];
			}
			$this->medal->update($medalid, $medalarr);
			
			$this->message('编辑成功', true, "?medaladmin-list-page-$page.htm");
		}
				
		if ($medal && $medal['picture']) {
			$medal['picture'] = $this->conf['upload_path'].$medal['picture'];
		}
		
		$this->view->assign('page', $page);
		$this->view->assign('medal', $medal);
		$this->view->assign('autograntlist', $this->medal->autograntlist);
		$this->view->assign('receivetypelist', $this->medal->receivetypelist);
		$this->view->display('medaladmin_update.htm');
	}
	
	public function on_delete() {
		$this->_title[] = '删除勋章';
		$this->_nav[] = '删除勋章';
		
		$page = intval(core::gpc('page'));
		$medalid = intval(core::gpc('medalid'));
		$medalids = empty($medalid) ? core::gpc('medalids', 'P') : array($medalid);
		foreach((array)$medalids as $medalid) {
			$medalid = intval($medalid);
			$this->medal->_delete($medalid);
		}
		
		$this->location("?medaladmin-list-page-$page.htm");
	}
	
	// 勋章发放
	public function on_grant() {
		$page = misc::page();
		
		if ($this->form_submit()) {
			$medalid = core::gpc('medalid', 'P');
			$medal = $this->medal->get($medalid);
			if (!$medal) {
				$this->message('错误，勋章不存在。', false);
			}
			$username = core::gpc('username', 'P');
			$uid = 0;
			if (!$username) {
				$this->message('错误，用户名不能为空。', false);
			} else {
				$user = $this->user->get_user_by_username($username);
				if (!$user) {
					$this->message('错误，用户名不存在。', false);
				} else {
					$uid = $user['uid'];
				}
			}
			$medaluser = $this->medal_user->get_medaluser_by_uid_medalid($uid, $medalid);
			if ($medaluser) {
				$this->message('勋章存在，不能重复颁发。', false);
			}
			
			$expiredtime = core::gpc('expiredtime', 'P');
			if ($expiredtime) {
				$expiredtime = time()+86400*intval($expiredtime);
			}
			
			$arr = array(
				'medalid'=>$medalid,
				'username'=>$username,
				'uid'=>$uid,
				'receivetype'=>$medal['receivetype'],
				'expiredtime'=>$expiredtime,
				'isapply'=>1,
				'fuid'=>$this->_user['uid'],
				'createdtime'=>time()
			);
			$this->medal_user->create($arr);
			
			$this->message('勋章发放成功。', true, "?medaladmin-grant-page-$page.htm");
		}
		
		$pagesize = 10;
		$num = $this->medal_user->count();
		$medaluserlist = $this->medal_user->get_medaluserlist_by_isapply(1, $page, $pagesize);
		$this->medal_user->medaluserlist_format($medaluserlist);
		
		$pages = misc::pages("?medaladmin-grant.htm", $num, $page, $pagesize);
		
		// 勋章列表
		$medallist = $this->medal->get_medallist_orderby_seq(1);
		
		$this->view->assign('medallist', $medallist);
		$this->view->assign('medaluserlist', $medaluserlist);
		$this->view->assign('pages', $pages);
		$this->view->assign('page', $page);
		
		$this->view->display('medaladmin_grant.htm');
	}
	
	// 回收勋章
	public function on_recover() {
		$page = intval(core::gpc('page'));
		$muid = intval(core::gpc('muid'));
		$muids = empty($muid) ? core::gpc('muids', 'P') : array($muid);
		foreach((array)$muids as $muid) {
			$muid = intval($muid);
			$this->medal_user->_delete($muid);
		}
		
		$this->location("?medaladmin-grant-page-$page.htm");
	}
	
	// 审核列表
	public function on_apply() {
		$page = misc::page();
		$pagesize = 10;
		$num = $this->medal_user->count();
		$medaluserlist = $this->medal_user->get_medaluserlist_by_isapply(0, $page, $pagesize);
		$this->medal_user->medaluserlist_format($medaluserlist);
		
		$pages = misc::pages("?medaladmin-apply.htm", $num, $page, $pagesize);
				
		$this->view->assign('medaluserlist', $medaluserlist);
		$this->view->assign('pages', $pages);
		$this->view->assign('page', $page);
		
		$this->view->display('medaladmin_apply.htm');
	}
	
	// 通过或者否决
	public function on_modapply() {
		$page = misc::page();
		$status = intval(core::gpc('status', 'P'));
		$muids = core::gpc('muids', 'P');
		
		$message = '';
		switch($status) {
			case 1:
				foreach ($muids as $muid) {
					$arr = $this->medal_user->get($muid);
					if ($arr) {
						$arr['isapply'] = 1;
						$this->medal_user->update($arr['muid'], $arr);
					}
				}
				$message = '通过操作成功。';
				break;
			default:
				foreach ($muids as $muid) {
					$this->medal_user->_delete($muid);
				}
				$message = '否决操作成功。';
				break;
		}
		$this->message($message, true, "?medaladmin-apply-page-$page.htm");
	}
}