<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_wall.php 16680 2010-09-13 03:01:08Z stonephp $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//print_r($_POST);die;
if($space['groupid'] != 21) {
	showmessage('为定义操作');
}

if(submitcheck('pianyue_submit', 0)) {
	$pianyue = array();
	$pianyue['uid'] = $uid;
	$pianyue['linkman'] = htmlspecialchars($_G['gp_linkman']);
	if(!$pianyue['linkman']) {
		showmessage('联系人未填写');
	}	
	if(strlen($pianyue['linkman']) > 30) {
		showmessage('联系人名称过长，请控制在30个字节之内');
	}
	$pianyue['telnum'] = $_G['gp_telnum'];
	if(!preg_match('/\d+\-*/is', $pianyue['telnum'])) {
		showmessage('联系电话错误，请重新填写');
	}
	$pianyue['name'] = htmlspecialchars($_G['gp_name']);
	if(!$pianyue['name']) {
		showmessage('请填写剧组名称');
	}
	if(strlen($pianyue['name']) > 40) {
		showmessage('剧组名称过长，请控制在40个字节之内');
	}
	$pianyue['desc'] = htmlspecialchars($_G['gp_desc']);
	if(!$pianyue['desc']) {
		showmessage('剧组描述不能为空');
	}
	if(strlen($pianyue['desc']) > 250) {
		showmessage('剧组描述过长，请控制在250个字节之内');
	}
	$pianyue['dateline'] = time();
	DB::insert('stonephp_pianyue', $pianyue);
	showmessage('发送片约成功，切勿重复发送<script type="text/javascript" reload="1">setTimeout("hideWindow(\'stonephp_pianyue\', 0, 1)", 2000);</script>');
} else {
	include_once template("home/space_pianyue");
}

?>