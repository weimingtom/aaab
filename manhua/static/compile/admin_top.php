<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<link href="static/img/webpage_common.css" rel="stylesheet" type="text/css" />
<div id="head">
	<div id="logo"><a href="?"><img src="static/img/logo.jpg" border="0" /></a></div>
	<ul id="nav">	
		<li class="nav_t">
		<a href="?c=cate">分类管理</a>
		<a href="?c=getdata">数据管理</a>
		<a href="?c=novel&a=admin">小说管理</a>
		<a href="?c=create">生成HTML</a>
		<a href="?c=spider">采集器</a>
		<a href="?c=user">用户管理</a>
		</li>		
		<li class="nav_b" id="zimenu">
		</li>
	</ul>
	<div class="userid">
		<span class="userid_icon"></span>
		<span class="userid_name"><?=$username?></span>
		<a href="?c=login&a=logout"></a>
	</div>
</div>

<script language="javascript">

	if(window.location.href.indexOf('publish') > -1)
	{
		var menustr = '';
		menustr += '<a href="?c=publish">发布产品</a>';
		menustr += '<a href="?c=publish&a=svn">从SVN更新</a>';
		menustr += '<a href="?c=publish&a=pack">导出打包</a>';
		menustr += '<a href="?c=publish&a=unlock">超时解锁</a>';
		$('#zimenu').html(menustr);
	}

</script>