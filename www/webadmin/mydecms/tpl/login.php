<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>管理系统后台登录</title>
<link href="css/css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/mydecms.js"></script>
<style type="text/css">
html {padding:0px;margin:0px;width:100%;height:100%;}
body {padding:0px;margin:0px;width:100%;height:100%;position:relative; background:#02101B url(css/login_bg.jpg) no-repeat center top;}
#layer_body{position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: black;z-index:1001;filter: alpha(opacity=60);-moz-opacity: 0.5;opacity: 0.5;}
#login{position: absolute;top: 25%;left: 0px;width: 100%;z-index:1002;}
#login #form1{width: 350px;padding: 15px;background-color: #FFF;margin:0px auto;border:10px solid #0A2C3A;}
#login td{ padding:5px 10px;}
#login th{ font-size:15px;}
.copy{ text-align:center; font-size:12px; color:#2679AA;}
</style>
</head>
<body>
<div id="layer_body"></div>
<div id="login">
  <form id="form1" name="form1" method="post" action="?action=act_login">
    <table width="100%" cellpadding="0" cellspacing="1" class="table">
      <tr><th colspan="2">管理系统后台管理入口</th>
      <tr>
        <td width="30%"><div align="right">管理帐号:</div></td>
        <td width="70%"><input type="text" name="webadmin" id="webadmin" class="input" style="width:200px;" />
        </td>
      </tr>
      <tr>
        <td><div align="right">管理密码:</div></td>
        <td><input type="password" name="webpass" id="webpass" class="input"  style="width:200px;" /></td>
      </tr>
      <tr>
        <td><div align="right">验 证 码:</div></td>
        <td><input name="nzm" type="text" class="input" id="nzm" size="15" />
          <img id="nzm_img" alt="点击刷新验证码" src="nzm.php" onclick="this.src='nzm.php';" style="cursor:pointer;" /></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
            <input type="submit" name="button" id="button" value="登录" />
            <input type="reset" name="button2" id="button2" value="重设" />
          </div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
