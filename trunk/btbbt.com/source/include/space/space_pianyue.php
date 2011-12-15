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
	showmessage('Ϊ�������');
}

if(submitcheck('pianyue_submit', 0)) {
	$pianyue = array();
	$pianyue['uid'] = $uid;
	$pianyue['linkman'] = htmlspecialchars($_G['gp_linkman']);
	if(!$pianyue['linkman']) {
		showmessage('��ϵ��δ��д');
	}	
	if(strlen($pianyue['linkman']) > 30) {
		showmessage('��ϵ�����ƹ������������30���ֽ�֮��');
	}
	$pianyue['telnum'] = $_G['gp_telnum'];
	if(!preg_match('/\d+\-*/is', $pianyue['telnum'])) {
		showmessage('��ϵ�绰������������д');
	}
	$pianyue['name'] = htmlspecialchars($_G['gp_name']);
	if(!$pianyue['name']) {
		showmessage('����д��������');
	}
	if(strlen($pianyue['name']) > 40) {
		showmessage('�������ƹ������������40���ֽ�֮��');
	}
	$pianyue['desc'] = htmlspecialchars($_G['gp_desc']);
	if(!$pianyue['desc']) {
		showmessage('������������Ϊ��');
	}
	if(strlen($pianyue['desc']) > 250) {
		showmessage('���������������������250���ֽ�֮��');
	}
	$pianyue['dateline'] = time();
	DB::insert('stonephp_pianyue', $pianyue);
	showmessage('����ƬԼ�ɹ��������ظ�����<script type="text/javascript" reload="1">setTimeout("hideWindow(\'stonephp_pianyue\', 0, 1)", 2000);</script>');
} else {
	include_once template("home/space_pianyue");
}

?>