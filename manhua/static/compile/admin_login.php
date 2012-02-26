<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<mate http-equiv=Content-Type content="text/html; charset=utf-8" />
<title>用户登录 - rt798.com CMS管理系统</title>
<style type="text/css">
body{
background:url(static/img/bg3.jpg) repeat-x #F7FAFD;margin:0;
}
#main{
width:722px;
height:320px;overflow:hidden;
margin:60px auto auto auto;
background:#E2EEFA;border:1px solid #A3C5E2
}
#bg_b{
width:722px;
height:58px;
background:url(static/img/bg4.jpg) repeat-x;border-bottom:1px solid #88BEFC;padding-top:44px
}
#bg_b div{height:30px;width:306px;background:url(static/img/bg2.jpg) repeat-x;margin:0 auto}
#border{margin-top:1px;height:10px;overflow:hidden;background:#3C85D1}
img{border:none}
#bg_c{
height:226px;background:url(static/img/bg5.jpg) right bottom no-repeat;
}
:root body #bg_c{height:206px}
#bg_c table{margin:40px 0 0 230px}
.t1{font-size:14px}
.ipt1{background:#fff;border:1px solid #24418C;height:20px;line-height:20px;}
.dl{width:76px;height:20px;background:url(static/img/bg2.jpg) -307px 0 no-repeat;border:none}
</style>
<script language="javascript" type="text/javascript" src="static/js/jquery-1.3.2.min.js"></script>
<script language="javascript" type="text/javascript" src="static/js/login_admin.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
<div id="main">
<div id="bg_b"><div></div></div><div id="border"></div>
<div id="bg_c">
<form id="form1" name="form1" method="post" action="?c=login">
<input type="hidden" name="action" value="login" />
<table width="50%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" ><span class="t1">用户登录：&nbsp;&nbsp;</span><input name="username" type="text" id="username" size="16" maxlength="20" style="width:130px;" class="ipt1" /> <label></label></td>
</tr>
<tr>
<td height="41" colspan="2"><span class="t1">用户密码：&nbsp;&nbsp;</span><input type="password" name="password" id="password" size="16" maxlength="20" style="width:130px;" class="ipt1" /> <label></label></td>
</tr>
<tr>
<td width="23%"></td>
<td width="77%"><input name="提交" type="submit" class="dl" value=""></td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>