<?php

return 	array (
	'enable' => 0,			// 是否启用?
	'installed' => 0,		// 已经安装?
	'name'=>'UCenter',		// 插件名
	'brief'=>'通过UCenter 整合其他应用的用户，同步注册，登录，退出。',
	'version'=>'1.0',		// 插件版本
	'bbs_version'=>'2.*',		// 插件支持的 Xiuno BBS 版本
	'icon'=>'',			// 插件的LOGO
	'author'=>'xiuno',		// 插件的作者
	'author_homepage'=>'http://www.xiuno.com/',	// 插件作者的主页
	'email'=>'axiuno@gmail.com',	// 插件的联系EMAIL
	'verify_code' => '',		// 经过官方安全认证的标志。
	'stars' => 5,			// 官方对插件的评级，1-5星级。
	'setting_url' => '',		// 配置的URL。
	
	// uc 相关 --------->
	'uc_host' => 'localhost',
	'uc_user' => 'root',
	'uc_password' => 'root',
	'uc_name' => 'btbbt_xiuno',
	'uc_charset' => 'utf8',		// 这里固定为utf8! 不管UC为什么编码。
	'uc_tablepre' => 'cdb_uc_',	// 不要加 dbname.
	'uc_engine'=>'MyISAM',
	'uc_appid' => 3,
	'uc_appkey' => '1234567890abcdefg',
);

?>