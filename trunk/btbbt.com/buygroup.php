<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'buygroup.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = array('usergroups');
$discuz->init();
require_once './config/config_ucenter.php';

if(!$_G['uid']) {
	showmessage('请先登陆之后再购买用户组', NULL, array(), array('login' => 1));
}

require './config/config_buygroup.php';

if(submitcheck('buygroupbtn', 0)) {

	$type = max(0, intval($_G['gp_type']));
	if(!is_array($prices[$type])) {
		showmessage('为定义操作');
	}
	$amount = $prices[$type]['price'];

	$price = round($amount, 2);
	$orderid = '';

	$apitype = $_G['gp_apitype'];
	require_once libfile('function/trade');
	$requesturl = credit_payurl($price, $orderid);

	$query = DB::query("SELECT orderid FROM ".DB::table('forum_order')." WHERE orderid='$orderid'");
	if(DB::num_rows($query)) {
		showmessage('credits_addfunds_order_invalid');
	}

	DB::query("INSERT INTO ".DB::table('forum_order')." (orderid, status, uid, amount, price, submitdate,buygroup)
		VALUES ('$orderid', '1', '$_G[uid]', '$amount', '$price', '$_G[timestamp]', '$type')");
	showmessage('credits_addfunds_succeed', $requesturl, array(), array('showdialog' => 1, 'locationtime' => true));

} else {

	include template('buygroup/index');

}

?>