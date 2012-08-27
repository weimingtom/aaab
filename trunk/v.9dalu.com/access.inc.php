<?php
/*
 * 游客访问的黑/白名单
 */
return array(
	"access"	=>	array(
		'home/Widget/renderWidget' 	=> true,
		'home/User/countNew'		=> true,
		'home/Public/*'				=> true,
		'home/Space/*'      		=> true,
		'phptest/*/*'				=> true,
		'api/*/*'					=> true,
		'wap/*/*'					=> true,
		'wap_sz/*/*'				=> true,
		'admin/*/*'					=> true, // 管理后台的权限由它自己控制
		'weibo/Plugins/init'		=> true, // 个人主页时引入动态生成的JS
		'home/Square/*'				=> true, // 微博广场的权限由管理后台控制
		'home/User/search'			=> true, // 搜索的权限由管理后台控制
		'home/User/searchuser'		=> true,
		'home/User/searchtag'		=> true,
		'home/User/topics'	     	=> true,
		'home/Welcome/*'            => true,//网站首页
		'home/Shopping/*'           => true,//shopping
		'home/Video/*'              => true,//视频
		'home/From/*'               => true,//web
		'home/Boards/*'             => true,//热门图格
		'home/Caiji/*'              => true,//采集工具
		//blog游客访问 
                'blog/Index/index'       =>true,
                'blog/Index/news'         =>true,
                'blog/Index/show'         =>true,
				'blog/Index/personal'         =>true,
		//vote游客访问 
                'vote/Index/index'       =>true,
                'vote/Index/pollDetail'         =>true,
				'vote/Index/personal'         =>true,
//相册游客访问 
        'photo/Index/index'       =>true,
                'photo/Index/album'       =>true,
                'photo/Index/albums'       =>true,
				'photo/Index/all_photos'       =>true,
                'photo/Index/photos'         =>true,
                'photo/Index/photo'         =>true,
                'photo/Index/all_albums'         =>true,
//活动游客访问
				'event/Index/index'       =>true,
                'event/Index/eventDetail'       =>true,
				'event/Index/member'       =>true,
//招贴游客访问
				'poster/Index/index'       =>true,
                'poster/Index/posterDetail'       =>true,
				'poster/Index/personal'         =>true,
				
 //微群游客访问
                'group/Index/index'       =>true,  
				'group/Index/find'       =>true,    
				'group/Index/search'       =>true, 
				'group/Group/index'       =>true, 
				'group/Group/detail'       =>true, 
				'group/Dir/index'       =>true, 
				'group/Member/index'       =>true, 
				'group/Topic/index'       =>true,
				'group/Topic/topic'       =>true,                   
	)
);