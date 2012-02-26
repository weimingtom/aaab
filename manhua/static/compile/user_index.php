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
		
		subject: '用户管理',
		
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
<span>用户管理</span>
<span><a href="?c=user&a=editpass">修改密码</a></span>
<span><a href="?c=user&a=add">添加用户</a></span></div>

<div class="content">
<div class="c1">

<form action="admin.php?m=kehu&c=clstate&tpc=manage" method="post">
      <table width="100%" border="0" cellspacing="1" cellpadding="2" class="table1" style="text-align:center">
        <tr>
          <th width="29%">用户名</th>
          <th width="33%">用户角色</th>
          <th width="20%">编辑</th>
          <th width="18%">删除</th>
          </tr>
		<? foreach((array)$userArr as $col) {?>
        <tr>
          <td><?=$col['username']?></td>
          <td><?=$col['group']?></td>
          <td><a href="?c=user&a=edit&uid=<?=$col['uid']?>">编辑</a></td>
          <td><a href="javascript:del('?c=user&a=del&uid=<?=$col['uid']?>')">删除</a></td>
          </tr>
       	<? } ?>
      </table>
      </form>
  
</div>
</div>
  
<? include $this->gettpl('admin_floor');?>
</div>

</body>
</html>