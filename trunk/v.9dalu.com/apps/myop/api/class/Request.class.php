<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: Request.php 13166 2009-08-14 02:36:09Z liguode $
*/

if(!defined('IN_MYOP')) {
	exit('Access Denied');
}

class Request extends MyBase {

	function send($uId, $recipientIds, $appId, $requestName, $myml, $type) {
		$db_prefix	= getDbPrefix();
		$now		= time();
		$result		= array();
		$type		= $type == 'request' ? '1' : '0';
		$fields 	= array('typename'	=> $requestName,
							'appid'		=> $appId,
							'type'		=> $type,
							'fromuid'	=> $uId,
							'dateline'	=> $now,
					   );
		foreach($recipientIds as $key => $val) {
			$myml				= str_replace('space.php?', U('home/Space/index').'&', $myml);
			$myml				= str_replace('userapp.php', MYOP_URL.'/userapp.php', $myml);
			$hash 				= crc32($appId . $val . $now . rand(0, 1000));
			$hash 				= sprintf('%u', $hash);
			$fields['touid']	= intval($val);
			$fields['hash']		= $hash;
			$fields['myml']		= str_replace('{{MyReqHash}}', $hash, $myml);
			$result[] 			= inserttable('myop_myinvite',	$fields, 1);
			
			//TODO: 更新统计
			//doQuery("UPDATE ".tname('space')." SET myinvitenum=myinvitenum+1 WHERE `uid`='{$fields['touid']}'");
		}
		return new APIResponse($result);
	}
}
?>
