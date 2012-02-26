<?php
define('DBHOST', 'localhost');

if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{	
	define('DBUSER', 'root');
	define('DBPW', 'root');
	define('DBNAME', 'muerwu');
	
	define('GODHOUSE_DOMAIN_WWW', 'http://www.muerwu.cn/');
	define('GODHOUSE_DOMAIN_IMAGE', 'http://www.muerwu.cn/');
}
else
{
	define('DBUSER', 'muerwu');
	define('DBPW', 'muerwu@321');
	define('DBNAME', 'muerwu');
	
	define('GODHOUSE_DOMAIN_WWW', 'http://www.muerwu.com/');
	define('GODHOUSE_DOMAIN_IMAGE', 'http://www.muerwu.com/');
}

define('TIMEOUT', 7200);
define('DBCHAR', 'utf8');

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
define('KEY', '2Ofw5ydjeia693cY591H4O1O5pcmsklkewom78sdfsdfsdklamkl' );

define('GODHOUSE_SITENAME', '木耳屋免费小说网');
define('GODHOUSE_HEADER_KEYWORK', '免费小说网');									// 网站关键字
define('GODHOUSE_HEADER_DESCRIPTION', '免费小说网');								// 网站关键字
define('GODHOUSE_ROOT', str_replace('\\', '/', getcwd()).'/');
define('GODHOUSE_PPP', 10); 													// 一页显示多少记录
define('GODHOUSE_PPP3', 30); 													// 一页显示多少记录
define('GODHOUSE_WAIT_TIME', 30); 												// 等待时间

	
?>