<?php

return 	array (
	'enable' => 1,			// 是否启用
	'installed' => 1,		// 已经安装
	'name'=>'暴布流同步登录插件',		// 插件名
	'brief'=>'xiuno一起努力',
	'version'=>'1.0',		// 插件版本
	'bbs_version'=>'2.*',		// 插件支持的 Xiuno BBS 版本
	'icon'=>'',			// 插件的LOGO
	'author'=>'huyao',		// 插件的作者
	'author_homepage'=>'http://www.xiuno.com/',	// 插件作者的主页
	'email'=>'252288762@qq.com',	// 插件的联系EMAIL
	'verify_code' => '',		// 经过官方安全认证的标志。
	'stars' => 3,			// 官方对插件的评级，1-5星级。
	'setting_url' => '',		// 配置的URL。

        // db 相关 --------->
	'db_host' => '122.228.213.140',
	'db_user' => 'dev',
	'db_password' => 'dev',
	'db_name' => 'thinksns',
	'db_charset' => 'utf8',		// 这里固定为utf8! 不管UC为什么编码。
	'db_tablepre' => 'ts_',         // 不要加 dbname.
	'db_engine'=>'MyISAM',
        'cookie_domain' => '9dalu.com',
);
?>