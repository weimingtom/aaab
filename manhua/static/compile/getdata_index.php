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
		
		subject: '数据管理',
		
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
<span>数据管理</span>
<span><a href="admin.php?c=getdata&a=add">数据抓取</a></span>
<span><a href="admin.php?c=spider">数据采集</a></span>
</div>

<div class="content">
<div class="c1">
<div class="tab_b"> 
<form action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
<tr> 
<td width="25%" align="right">搜索关键字：
<input name="keyname" type="text" class="w02" id="keyname" value="" /></td> 
<td width="47%"> 条件：
<select name="cateid" id="cateid">
<option value="0">作为大类</option>
<? foreach((array)$options as $val) {?>
<option value="<?=$val['cate_id']?>"><?=$val['cate_name']?></option>
<? } ?>
</select> 
<script type="text/javascript"> 
	var keyname = '<?=$serchData['keyname']?>';
	var cateid = '<?=$serchData['cateid']?>';
	$('#cateid').val(cateid);
	$('#keyname').val(keyname);
</script> 
&nbsp;&nbsp;&nbsp;<input name="button" type="submit" class="w02" value="搜索" /></td> 
<td width="28%">&nbsp;</td> 
</tr> 
</table> 
</form> 
</div>
<form action="admin.php?c=getdata" method="post">
<div style="text-align:right"><?=$pageData?></div>
<table width="100%" border="0" cellspacing="1" cellpadding="2" class="table1" style="text-align:center">
<tr>
  <th width="2%"><input type="checkbox" name="" value="all" /></th>
  <th width="5%">ID</th>
  <th width="12%">预览</th>
  <th width="26%">作品名称</th>
  <th width="10%">作品数量</th>
  <th width="10%">点击次数</th>
  <th width="11%">作者</th>
  <th width="15%">时间</th>
  <th width="9%">操作</th>
</tr>
<? foreach((array)$dataArr as $val) {?>
<tr>
  <td><input type="checkbox" name="P[]" value="<?=$val['id']?>" /></td>
  <td><?=$val['id']?></td>
  <td><img src="http://www.rt798.com/getPhoto.php?url=<?=$val['smallpic']?>" width="50" onmouseover="this.width=110" onmouseout="this.width=50" /></td>
  <td><a href="apprt.php?a=info&xxid=<?=$val['xxid']?>&mt=<?=$val['id']?>" target="_blank"><?=$val['title']?></a></td>
  <td><?=$val['num']?></td>
  <td><?=$val['clicknum']?></td>
  <td><?=$val['author']?></td>
  <td><?=$val['addtime']?></td>
  <td>编辑&nbsp;&nbsp;<a href="javascript:if(confirm('确定是否要删除吗?')){location.href='admin.php?c=getdata&mtid=<?=$val['id']?>';}">删除</a></td>
</tr>
<? } ?>
<tr>
  <td colspan="9">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td width="21%" style="text-align:left">
  <label><input type="checkbox" name="" value="all" /> 全选</label>
  <select name="ydcateid" id="ydcateid">
	<option value="0">作为大类</option>
	<? foreach((array)$options as $val) {?>
	<option value="<?=$val['cate_id']?>"><?=$val['cate_name']?></option>
	<? } ?>
</select>
<input name="" type="submit" value="确定移动" />
  </td>
  <td width="79%" style="text-align:right"><?=$pageData?></td>
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