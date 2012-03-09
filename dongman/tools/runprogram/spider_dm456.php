<?php
define('ROOT_PATH', dirname(__FILE__));

include ROOT_PATH.'/config.php';
include ROOT_PATH.'/../include/db.php';
include ROOT_PATH.'/../include/function.php';

$db = new db();
$db->connect(DBHOST, DBUSER, DBPWD, DBNAME, DBCHARSET);

$spiderMainUrl = 'http://www.dm456.com/donghua/update.html';

$s = getContentUrl($spiderMainUrl);

?>