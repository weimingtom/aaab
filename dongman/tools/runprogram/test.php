<?php 
$str = 'ssaaa<a href="http://www.baidu.com">huyao</a>dff<a href="http://www.baidu2.com">huyao</a>';
$arr = array();

preg_match_all('/href="(.*)"/isU', $str, $arr);

print_r($arr);