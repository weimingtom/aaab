<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

//hook attach_control.php

class attach_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// 列表
	public function on_index() {
		//
	}
	
	
	// ajax 弹出下载对话框内容
	public function on_dialog() {
		$this->check_login();
		$uid = $this->_user['uid'];
		
		$aid = intval(core::gpc('aid'));
		$attach = $this->attach->read($aid);
		$this->attach->format($attach);
		
		$user = $this->user->read($uid);
		$this->_user['golds'] = $user['golds'];
		
		$this->view->assign('attach', $attach);
		// hook attach_dialog.php
		$this->view->display('attach_dialog_ajax.htm');
	}
	
	public function on_download() {
		$this->check_login();
		$uid = $this->_user['uid'];
		
		$aid = intval(core::gpc('aid'));
		$attach = $this->attach->read($aid);
		if(empty($attach)) {
			$this->message('附件不存在。');
		}
		$attach['downloads']++;
		$this->attach->update($aid, $attach);
		
		// 版主
		$forum = $this->forum->read($attach['fid']);
		$pforum = $this->forum->read($forum['fup']);
		$havepriv = $this->is_mod($forum, $pforum, $this->_user) || $attach['uid'] == $uid;	// 是否有权限下载。
		
		$user = $this->user->read($uid);
		if($user['golds'] < $attach['golds'] && !$havepriv) {
			$this->message("此附件需要(<b>$attach[golds]</b>)金币，您的金币($user[golds])不足，您可以<a href=\"?pay-select.htm\">充值</a>来增加金币。");
		}
		
		$attachpath = $this->conf['upload_path'].'attach/'.$attach['filename'];
		if(!is_file($attachpath)) {
			$this->message('附件不存在，如果有问题请联系管理员。');
		}
		$filesize = filesize($attachpath);
		
		// 扣除金币
		if($attach['golds'] > 0) {
			$down = $this->attach_download->read($uid, $aid);
			if(empty($down) && !$havepriv) {
				// 扣除金币
				$user['golds'] -= $attach['golds'];
				
				// 如果购买过，可以一直有权下载, uid, aid 为唯一索引
				$this->attach_download->create(array(
					'aid' => $aid,
					'uid' => $uid,
					'uploaduid' => $attach['uid'],
					'dateline' => $_SERVER['time'],
					'golds' => $attach['golds'],
				));
				
				// 更新用户金币数
				$this->user->update($uid, $user);
				
				// 所有者加金币
				$owner = $this->user->read($attach['uid']);
				$owner['golds'] += $attach['golds'];
				$this->user->update($owner['uid'], $owner);
			}
			
			// 不允许多线程下载
			
			ob_end_clean();
			ob_start();
			core::ob_start();
    
			// 头部
			header('Date: '.gmdate('D, d M Y H:i:s', $attach['dateline']).' GMT');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $attach['dateline']).' GMT');
			header('Content-Encoding: none');
			header('Expiries: 0');
			header('Cache-control: must-revalidate, post-check=0, pre-check=0');
			header('Content-transfer-encoding: binary');
			header('Content-Encoding: none');	// gzip
			header('Content-Length: '.ob_get_length());	// ob_get_length(), not $filesize
			if($attach['filetype'] == 'image') {
				header('Content-Disposition: inline; filename='.$attach['orgfilename']);
				header('Content-Type: image/pjpeg');
			} else {
				header('Content-Disposition: attachment; filename='.$attach['orgfilename']);
				header('Content-Type: application/octet-stream');
			}
			
			// hook attach_download_gold.php
			readfile($this->conf['upload_path'].'attach/'.$attach['filename']);
			exit;
		} else {
			
			// hook attach_download_free.php
			$this->attach->format($attach);
			header('Location: '.$this->conf['upload_url'].'attach/'.$attach['filename']);
			exit;
		}
	}
	
	
	// 接受所有文件 swfupload post ajax
	public function on_uploadimage() {
		$fid = intval(core::gpc('fid'));
		$pid = intval(core::gpc('pid'));
		$uid = $this->_user['uid'];
		$this->check_forbidden_group();
		
		$forum = $this->forum->read($fid);
		$pforum = $this->forum->read($forum['fup']);
		$user = $this->user->read($uid);
		
		$this->check_forum_exists($forum);
		$this->check_forum_access($forum, $pforum, 'attach');
		$this->check_user_access($user, 'attach');

		if(isset($_FILES['Filedata']['tmp_name']) && is_file($_FILES['Filedata']['tmp_name'])) {
			$file = $_FILES['Filedata'];
			$filetype = $this->attach->get_filetype($file['name']);
			if($filetype != 'image') {
				$allowtypes = $this->attach->get_allow_filetypes();
				$this->message('请选择图片格式的文件，后缀名为：.gif, .jpg, .png, .bmp！', 0);
			}
			
			$arr = array (
				'fid'=>$fid,
				'pid'=>$pid,
				'filesize'=>0,
				'width'=>0,
				'height'=>0,
				'filename'=>'',
				'orgfilename'=>$file['name'],
				'filetype'=>$filetype,
				'dateline'=>$_SERVER['time'],
				'comment'=>'',
				'downloads'=>0,
				'isimage'=>1,
				'golds'=>0,
				'uid'=>$this->_user['uid'],
			);
			$aid = $this->attach->create($arr);
			if($pid == 0) {
				$this->save_aid($aid, $uid);
			} else {
				$this->post->update_imagenum($fid, $pid, +1);
			}
			
			$uploadpath = $this->conf['upload_path'].'attach/';
			$uploadurl = $this->conf['upload_url'].'attach/';
			
			// 处理文件
			// 判断文件类型，如果为图片文件，缩略，否则直接保存。
			$imginfo = getimagesize($file['tmp_name']);
			// 如果为 GIF, 直接 copy
			if($imginfo[2] == 1) {
				$fileurl = image::set_dir($aid, $uploadpath).'/'.$aid.'.gif';
				$thumbfile = $uploadpath.$fileurl;
				copy($file['tmp_name'], $thumbfile);
				$r['filesize'] = filesize($file['tmp_name']);
				$r['width'] = $imginfo[0];
				$r['height'] = $imginfo[1];
				$r['fileurl'] = $fileurl;
			} else {
				$r = image::safe_thumb($file['tmp_name'], $aid, '.jpg', $uploadpath, $this->conf['upload_image_max_width'], 240000);	// 1210 800
				$thumb = image::safe_thumb($file['tmp_name'], $aid, '_thumb.jpg', $uploadpath, $this->conf['thread_icon_middle'], 2256);
				$thumbfile = $uploadpath.$thumb['fileurl'];
				image::clip($thumbfile, $thumbfile, 0, 0, $this->conf['thread_icon_middle'], $this->conf['thread_icon_middle']);	// 对付金箍棒图片
			}
			
			$arr['filesize'] = $r['filesize'];
			$arr['width'] = $r['width'];
			$arr['height'] = $r['height'];
			$arr['filename'] = $r['fileurl'];
			$this->attach->update($aid, $arr);
			
			// hook attach_uploadimage.php
			$this->message('<img src="'.$uploadurl.$r['fileurl'].'" width="'.$arr['width'].'" height="'.$arr['height'].'"/>');
			
		} else {
			if($_FILES['Filedata']['error'] == 1) {
				$this->message('上传文件( '.htmlspecialchars($_FILES['Filedata']['name']).' )太大，超出了 php.ini 的设置：'.ini_get('upload_max_filesize'), 0);
			} else {
				$this->message('上传文件失败，错误编码：'.$_FILES['Filedata']['error'], 0); // .print_r($_FILES, 1)
			}
		}
	}
	
	// 接受所有文件 swfupload post ajax
	public function on_uploadfile() {
		$fid = intval(core::gpc('fid'));
		$pid = intval(core::gpc('pid'));	// 如果新发帖子，那么 pid 为 0
		$uid = $this->_user['uid'];
		$this->check_forbidden_group();
		
		$forum = $this->forum->read($fid);
		$pforum = $this->forum->read($forum['fup']);
		$user = $this->user->read($uid);
		
		$this->check_forum_exists($forum);
		$this->check_forum_access($forum, $pforum, 'attach');
		$this->check_user_access($user, 'attach');

		if(isset($_FILES['Filedata']['tmp_name']) && is_file($_FILES['Filedata']['tmp_name'])) {
			$file = $_FILES['Filedata'];
			$file['name'] = htmlspecialchars($file['name']);
			$filetype = $this->attach->get_filetype($file['name']);
			// 多后缀名以最后一个 . 为准。文件名舍弃，避免非法文件名。
			$ext = strrchr($file['name'], '.');
			if($filetype == 'unknown') {
				$ext = $this->attach->safe_ext($ext);
			}
			
			$arr = array (
				'fid'=>$fid,
				'pid'=>$pid,
				'filesize'=>0,
				'width'=>0,
				'height'=>0,
				'filename'=>'',
				'orgfilename'=>$file['name'],
				'filetype'=>$filetype,
				'dateline'=>$_SERVER['time'],
				'comment'=>'',
				'downloads'=>0,
				'isimage'=>0,
				'golds'=>0,
				'uid'=>$uid,
			);
			$aid = $this->attach->create($arr);
			
			// $aid 保存到临时文件，每个用户一个文件，里面记录 aid。在读取后删除该文件。
			// 如果tmp为内存，则在用户未完成期间，可能会导致垃圾数据产生。可以通过 uid=123 and pid=0，来判断附件归属，不过这个查询未建立索引，可以定期清理，一般不需要。
			if($pid == 0) {
				$this->save_aid($aid, $uid);
			} else {
				$this->post->update_attachnum($fid, $pid, +1);
			}
			
			$uploadpath = $this->conf['upload_path'].'attach/';
			$uploadurl = $this->conf['upload_url'].'attach/';
			
			// 处理文件
			$pathadd = image::set_dir($aid, $uploadpath);
			$filename = md5($aid.'_'.$this->conf['public_key'].$aid).$ext;
			$destfile = $uploadpath.$pathadd.'/'.$filename;
			$desturl = $uploadurl.$pathadd.'/'.$filename;

			$arr['aid'] = $aid;
			$arr['filename'] = $pathadd.'/'.$filename;
			$arr['filesize'] = filesize($file['tmp_name']);
			$this->attach->update($aid, $arr);
			
			if(move_uploaded_file($file['tmp_name'], $destfile)) {
				
				// hook attach_uploadfile.php
				$arr['desturl'] = $desturl;
				$this->message($arr);
			} else {
				// 回滚
				$this->attach->_delete($aid);
				$this->message('保存失败！', 0);
			}
			
		} else {
			$this->message('上传文件失败，可能文件太大。', 0);
		}
	}

	// 更新一个文件，文件名不变！
	public function on_updatefile() {
		$aid = core::gpc('aid');
		$uid = $this->_user['uid'];
		$attach = $this->attach->read($aid);
		if($attach['uid'] != $this->_user['uid']) {
			$this->message('您不能更新别人的附件！');
		}
		if(isset($_FILES['Filedata']['tmp_name']) && is_file($_FILES['Filedata']['tmp_name'])) {
			$file = $_FILES['Filedata'];
			$attach['filesize'] = filesize($file['tmp_name']);
			$this->attach->update($aid, $attach);
			if(move_uploaded_file($file['tmp_name'], $this->conf['upload_path'].'attach/'.$attach['filename'])) {
				
				// hook attach_updatefile.php
				$this->message($attach);
			} else {
				$this->message('保存失败！', 0);
			}
		} else {
			$this->message('上传文件失败，可能文件太大。', 0);
		}
	}
	
	// 编辑器弹出层，删除一个附件文件
	public function on_deletefile() {
		$this->check_login();
		
		$aid = intval(core::gpc('aid'));
		
		// 权限判断
		$attach = $this->attach->read($aid);
		if($attach['uid'] != $this->_user['uid']) {
			$this->message('您不能删除别人的附件！');
		}
		
		// 如果附件没有归属，那么可能存在于 tmp/uid_aids.tmp 文件中
		if($attach['pid'] == 0) {
			$this->remove_aid($aid, $this->_user['uid']);
		} else {
			// 附件数--
			$this->post->update_attachnum($attach['fid'], $attach['pid'], -1);
		}
		
		// 下载（购买）历史，如果最后一次购买的时间在24小时以内，附件不能被删除。保护购买人的权利，否则还没来得及下载，已经被删除。
		$this->attach->_delete($aid);
		
		// hook attach_deletefile.php
		$this->message('删除成功');
	}
	
	// 更新附件的售价
	public function on_updategold() {
		$uid = $this->_user['uid'];
		$gold = core::gpc('gold', 'P');
		foreach($gold as $aid=>$golds) {
			$aid = intval($aid);			
			$golds = intval($golds);
			$attach = $this->attach->read($aid);
			if($attach['uid'] != $uid) continue;
			if($attach['golds'] != $golds) {
				$attach['golds'] = $golds;
				$this->attach->update($aid, $attach);
			}
		}
		
		// hook attach_updategold.php
		$this->message('更新附件售价成功。');
	}

	private function save_aid($aid, $uid) {
		$file = $this->conf['tmp_path'].$uid.'_aids.tmp';
		$aids = is_file($file) ? trim(file_get_contents($file)).' '.$aid : $aid;
		file_put_contents($file, $aids);
	}
	
	private function remove_aid($aid, $uid) {
		$file = $this->conf['tmp_path'].$uid.'_aids.tmp';
		$aidarr = explode(' ', trim(file_get_contents($file)));
		$newarr = array_diff($aidarr, array($aid));
		$aids = implode(' ', $newarr);
		file_put_contents($file, $aids);
	}
	
}

?>