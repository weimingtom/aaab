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

function verSubmit() {
	document.form1.submit();
}
</script>

</head>
<body>

<? include $this->gettpl('admin_top');?>

<div id="wrap">

<div class="path">
	<span><a href="admin.php?c=spider">采集管理</a></span>
	<span><a href="admin.php?c=spider&a=setting">配置采集工具</a></span>
</div>

<div class="content">
	<div class="c1">
		<form id="formContent" action="admin.php?c=spider&a=content" method="post" target="_blank">
		<table width="100%" border="0" cellspacing="1" cellpadding="2" class="table1" style="text-align:center">
			<tr>
				<th width="10%"><input type="checkbox" name="" value="all" /></th>
				<th width="10%">序号</th>
				<th width="40%">标题</th>
				<th width="40%">URL</th>

			</tr>
			<? foreach((array)$data as $k => $value) {?>
			<tr>
				<td><input type="checkbox" name="P[]" value="<?=$k?>" /></td>
				<td><?=$k?></td>
				<td style="text-align:left; padding-left:5px;"><?=$value['title']?></td>
				<td style="text-align:left; padding-left:5px;"><a href="<?=$value['url']?>" target="_blank"><?=$value['url']?></a></td>
			</tr>
			<?}?>
			<tr>
				<td colspan="9">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="21%" style="text-align:left">
						<label><input type="checkbox" name="" value="all" /> 全选</label>
						&nbsp;&nbsp;&nbsp;
						<input name="submit" type="submit" value="提交" />
						<input type="radio" name="mod" value="test" checked="checked" /> 测试
						<input type="radio" name="mod" value="normal" /> 采集
						<input name="spiderId" type="hidden" value="<?=$spiderId?>" />
						</td>
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