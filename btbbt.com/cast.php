<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'cast.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init();
require_once './config/config_ucenter.php';

$type = in_array($_G['gp_type'], array('lady', 'man', 'comment', 'newvip', 'normal', 'newnormal', 'broker', 'recommend')) ? $_G['gp_type'] : 'lady';

$page = max(1, $_G['page']);
$num = 20;
$start = ($page - 1) * $num;
$cache_key = 'star_'.$type.$_G['page'];
$cururl = 'star.php';
if(!$data = memory('get', $cache_key)) {
	$data = array();
	
}

extract($data);

include template('diy:star/cast');


?>