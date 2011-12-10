<?php
phpinfo();
echo md5(123);
var_dump(function_exists('mysql_connect'));
error_reporting(E_ALL);
$link = mysql_connect('127.0.0.1', 'root', 'root');
var_dump($link); 
?>
