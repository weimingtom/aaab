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
		
		subject: '生成HTML',
		
		init: function(){
			this.ajaxfrom();
			CMSAPP.command.webTitle(this.subject);	
		},
		
		ajaxfrom: function(){
			$('#publishForm').submit(function(){				
				$.ajax({
					type: "POST",
					url: "?c=create&a=copy&action=add",
					data: $('#publishForm').serialize(true),
					cache: false,
					beforeSend: function(){ $('#apptable').slideToggle(100);$('#wait').show().html('<label style="color:red;font-size:16px;">正在生成HTML,请稍候...</label>'); },
					success: function(html){
						if(html.indexOf('password') > -1)
						{
							location.href='?c=login';
						}
						else
						{
							$('#wait').show().html('<label style="color:red;font-size:16px;">'+ html.replace('Done.', '') +'</label>');
							$('#apptable').slideToggle(200);
						}
					},
					ajaxError: function(){alert('发生错误，请刷新后重试!');}		
				});
				return false;
			});
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
<span><a href="admin.php?c=create">生成HTML</a></span>
</div>

<div class="content">
<div class="c1">

<div id="wait" style="font-size: 16px; display: none; margin-bottom: 5px;"></div>
	<div id="apptable">
		<form action="" method="post" id="publishForm">
		<table width="100%" border="0" cellspacing="2" cellpadding="2" style="text-align:center">
		<tr>
		  <td height="47" align="right">&nbsp;</td>
		  <td align="left">
		  <select name="appgetID">
			 <option value="-1">生成首页</option>
			 <? foreach((array)$options as $val) {?>
			 <option value="<?=$val['cate_id']?>"><?=$val['cate_name']?></option>
			 <? } ?>
		  </select>
		  </td>
		  </tr>
		<tr>
		  <td height="45" align="right">&nbsp;</td>
		  <td align="left"><input type="submit" class="inputsubmit" name="Submit" value="  确认生成HTML  " />
			<input type="hidden" name="action" value="get" /></td>
		</tr>
		</table>
		</form>
	</div>
</div>
</div>
  
<? include $this->gettpl('admin_floor');?>
</div>

</body>
</html>