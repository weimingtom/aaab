<?php

// 风格类插件只能安装一个，多个风格可能会带来混乱；
// 风格插件的命名规范为： view_xxx

return 	array (
	'enable' => 0,			// 是否启用?
	'installed' => 0,		// 已经安装?
	'name'=>'SEO 插件(商业版本)',	// 插件名
	'brief'=>'针对搜索引擎进行了优化，对于站点关键字排名提升很有帮助，在发主题贴时会多出一个关键词输入项，经过大型站点测试效果明显。',
	'version'=>'1.0',		// 插件版本
	'bbs_version'=>'2.*',		// 插件支持的 Xiuno BBS 版本
	'icon'=>'',			// 插件的LOGO
	'author'=>'xiuno',		// 插件的作者
	'author_homepage'=>'http://www.xiuno.com/',	// 插件作者的主页
	'email'=>'axiuno@gmail.com',	// 插件的联系EMAIL
	'stars' => 4,			// 官方对插件的评级，1-5星级。
	'verify_code' => '',		// 经过官方安全认证的标志。
);

?>