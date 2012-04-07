<?php
$config=require 'setting.php';
$array=array(
	'USER_AUTH_KEY'=>'ppvod',// 用户认证SESSION标记
    'NOT_AUTH_MODULE'=>'Login,Install',// 默认无需认证模块
	'REQUIRE_AUTH_MODULE'=>'Index,List,Vod,News,User,Collect,Tool,Upload,Link,Ads,Cache,Html,Tpl,Cm,Gb,Tag',// 默认需要认证模块
	'NOT_AUTH_ACTION'=>'show,add,index,top,left,main',// 默认无需认证操作
    'URL_PATHINFO_DEPR'=>'-',
	'APP_GROUP_LIST'=>'Home,Admin,Plus',//项目分组
	'TMPL_FILE_DEPR'=>'_',//模板文件MODULE_NAME与ACTION_NAME之间的分割符，只对项目分组部署有效
	'LANG_SWITCH_ON'=>false,// 多语言包功能
	'LANG_AUTO_DETECT'=>false,//是否自动侦测浏览器语言
	'URL_CASE_INSENSITIVE'=>true,//URL是否不区分大小写 默认区分大小写
    'DB_FIELDTYPE_CHECK'=>true, //是否进行字段类型检查
	'TMPL_DETECT_THEME'=>true,// 自动侦测模板主题
	'DATA_CACHE_SUBDIR'=>true,//哈希子目录动态缓存的方式
	'DATA_PATH_LEVEL'=>2,
	'admin_name'=>'九尾狐大陆',
	'admin_var'=>'1.9',
	'admin_url'=>'http://dm.9dalu.com/',
	'admin_urlvar'=>'http://union.ff84.com/up/ppvod_version.js',
	'admin_keywords'=>'九尾狐大陆系统',
	'admin_description'=>'九尾狐大陆影视系统!',		
	'admin_welcome'=>'欢迎光临九尾狐大陆管理后台',			
	'play_player' =>array (
		'qvod' => '快播高清',
		'baofeng' => '暴风影音',
		'yuku' => '优酷视频',
		'tudou' => '土豆视频',
		'bdhd' => '百度影音',
		'baidu' => '百度影音',
		'qiyi' => '奇艺高清',
		'pvod' => '皮皮高清',	
		'sinahd' => '新浪视频',
		'sohu' => '搜狐视频',
		'ku6' => '酷六视频',
		'qq' => '腾讯视频',				
		'web9' => '久久影音',
		'pipi' => '闪播影音',
		'gvod' => '迅播高清',
		'down' => '影片下载',
		'swf' => 'Swf动画',
		'flv' => 'Flv视频',
		'pptv' => 'PPTV视频',
		'letv' => '乐视视频',
		'media' => 'Media Player',
		'real' => 'Real Player',
		'cntv'=>'中国网络电视台',
		'56'=>'56视频'
	),
);
return array_merge($config,$array);
?>