<?php

// 风格类插件只能安装一个，多个风格可能会带来混乱；
// 风格插件的命名规范为： view_xxx

return 	array (
	'enable' => 1,			// 是否启用?
	'installed' => 1,		// 已经安装?
	'name'=>'Xiuno BBS 蓝色风格插件',		// 插件名
	'brief'=>'',
	'version'=>'1.0',		// 插件版本
	'bbs_version'=>'2.*',		// 插件支持的 Xiuno BBS 版本
	'icon'=>'../plugin/view_blue/icon.jpg',			// 插件的LOGO
	'icon_big'=>'http://custom.xiuno.com/view/image/style_blue.jpg',			// 插件的LOGO
	'author'=>'xiuno',		// 插件的作者
	'author_homepage'=>'http://www.xiuno.com/',	// 插件作者的主页
	'email'=>'axiuno@gmail.com',	// 插件的联系EMAIL
	'verify_code' => '',		// 经过官方安全认证的标志。
);

?>