<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="static/img/houtai.css" rel="stylesheet" type="text/css" />
<title>默认首页 - godhouse 管理系统</title>
<meta content="管理平台" name=keywords />
<meta content="管理平台" name=description />
<script language="javascript" type="text/javascript" src="static/js/jquery-1.3.2.min.js"></script>
<script language="javascript" type="text/javascript" src="static/js/command.js"></script>
<script language="javascript" type="text/javascript">	

$(function(){
	CMSAPP.addkehu = {
		subject: '采集器',
		init: function(){
			CMSAPP.command.webTitle(this.subject);
			CMSAPP.command.mtabletr();
		}
	}
	CMSAPP.addkehu.init();
	
});
</script>

</head>
<body>
<? include $this->gettpl('admin_top');?>
<div id="wrap">

<div class="path">
	<span><a href="admin.php?c=spider">采集管理</a></span>
	<span><a href="admin.php?c=spider&a=setting">配置采集工具</a></span>
	<span>一键采集列表</span>
</div>

<div class="content">
	<div class="c1">
		<form action="admin.php?c=spider&a=logList" method="post" target="_blank">
		<table width="100%" border="0" cellspacing="1" cellpadding="2" class="table1" style="text-align:center">
			<tr>
				<th width="15%"><input type="checkbox" name="" value="all" /></th>
				<th width="15%">序号</th>
				<th width="25%">标题</th>
				<th width="35%">域名</th>
				<th width="10"></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="P[]" value="" /></td>
				<td>1</td>
				<td style="text-align:left; padding-left:5px;">小说网</td>
				<td style="text-align:left; padding-left:5px;"><a href="http://www.xiaoshuo.com" target="_blank">www.xiaoshuo.com</a></td>
				<td><input type="button" name="submitTo" value="采集" onclick="if(confirm('您确定需要采集吗？')){location.href='admin.php?c=spiderCustom&a=xiaoshuo'}" /></td>
			</tr>
		</table>
		</form>
	</div>
</div>
<? include $this->gettpl('admin_floor');?>
</div>
</body>
</html>