<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="static/img/houtai.css" rel="stylesheet" type="text/css" />
<title>默认首页 - rt798.com CMS管理系统</title>
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
		<span>采集管理</span>
		<span><a href="admin.php?c=spider&a=setting">配置采集工具</a></span>
		<span><a href="admin.php?c=spiderCustom&a=list">一键采集列表</a></span>
		<span><a href="admin.php?c=data&a=index">数据处理</a></span>
	</div>

	<div class="content">
		<div class="c1">
			<form action="admin.php?c=getdata" method="post">
			<div style="text-align:right"></div>
				<table width="100%" border="0" cellspacing="1" cellpadding="2" class="table1" style="text-align:center">
					<tr>
						<th width="5%">ID</th>
						<th width="15%">网址名称</th>
						<th width="35%">采集目标网址</th>
						<th width="7%">数据总数</th>
						<th width="13%">创建时间</th>
						<th width="25%">操作</th>
					</tr>
					<? foreach((array)$spiderList as $k => $spider) {?>
					<tr>
						<td><?=$spider['spiderId']?></td>
						<td><?=$spider['spiderName']?></td>
						<td><?=$spider['url']?></td>
						<td><?=$spider['recordTotal']?></td>
						<td><?=$spider['createdTimeForm']?></td>
						<td>
						<a href="admin.php?c=spider&a=logList&spiderId=<?=$spider['spiderId']?>" target="_blank">数据列表</a> |
						<a href="admin.php?c=spider&a=datalist&spiderId=<?=$spider['spiderId']?>" target="_blank">临时文件</a> |
						<a href="admin.php?c=spider&a=setting&spiderId=<?=$spider['spiderId']?>" target="_blank">编辑</a> |
						<a href="admin.php?c=spider&a=setting&spiderId=<?=$spider['spiderId']?>&mod=copy" target="_blank">复制</a> |
						<a href="admin.php?c=spider&a=delete&spiderId=<?=$spider['spiderId']?>" onclick="if(confirm('您真的要删除吗？下面的临时文件和数据文件都会删除')){location.href=this.href;}else{return false;}">删除</a>
						</td>
					</tr>
					<?}?>
					<tr>
					  <td colspan="6">
						  <table width="100%" border="0" cellpadding="0" cellspacing="0">
							  <tr>
								<td width="21%" style="text-align:left">
								<input name="" type="submit" value="删除" />
								</td>
								<td width="79%" style="text-align:right"></td>
							  </tr>
						  </table>
					  </td>
					</tr>
				</table>
			</form>
		</div>
	</div>
<? include $this->gettpl('admin_floor');?>
</div>
</body>
</html>