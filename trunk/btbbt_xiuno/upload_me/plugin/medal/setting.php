<?php
$conf = include BBS_PATH.'conf/conf.php';
$url = $conf['app_url'].($conf['urlrewrite'] ? '' : '?').'medaladmin-list.htm';
header("location:$url");
?>