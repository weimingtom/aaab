<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'loginStatus.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init();
?>
$(function() {
	var arr = [];
<?php
if($_G['uid']) {
?>
	arr.push('<strong><?=$_G['username']?></strong><span class="pipe">|</span>');
	arr.push('<a href="http://www.yingkong.net/home.php?mod=spacecp">����</a><span class="pipe">|</span>');
	arr.push('<a href="http://www.yingkong.net/member.php?mod=logging&amp;action=logout&amp;formhash=<?=FORMHASH?>">�˳�</a>');
<?php	
} else {
?>
	arr.push('<a href="http://www.yingkong.net/home.php?mod=spacecp&ac=plugin&id=sina_xweibo_x2:home_binding" target="_blank"><img style="vertical-align:-5px;" src="static/image/common/sina_bind_btn.png" /></a>');
	arr.push('<a href="http://www.yingkong.net/member.php?mod=logging&action=login">��¼</a><span class="pipe">|</span>');
	arr.push('<a href="http://www.yingkong.net/member.php?mod=register">ע��</a>');
<?php
}
?>
	$("#head").find(".tm").html(arr.join(''));
});
