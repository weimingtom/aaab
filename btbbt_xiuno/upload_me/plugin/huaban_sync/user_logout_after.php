//<?php
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;
$error['user']['logouturl'] = $pconf['api_url'].'/userapi/logout.php';