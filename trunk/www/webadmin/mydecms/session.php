<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
if($_SESSION["MYDECMSADMIN_ADMIN"]=="" || !isset($_SESSION["MYDECMSADMIN_ADMIN"])){
	echo '��¼��ʱ';
	echo '<script type="text/javascript">';
	echo 'parent.window.location.href="login.php"';
	echo '</script>';
	exit();
}
?>