<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title><?php echo C("admin_name");?> 管理面版 v<?php echo C("admin_var");?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="<?php echo C("admin_keywords");?>">
<meta name="description" content="<?php echo C("admin_description");?>">
<meta http-equiv='Refresh' content='<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>'>
<link rel='stylesheet' type='text/css' href='__PUBLIC__/css/admin.css'>
</head>
<body>

<TABLE align="center" cellpadding=0 cellspacing=0 class="message" >
<tr>
    <td height='5'  class="topTd" ></td>
</tr>
<TR class="row" >
    <th class="tCenter space"><?php echo ($msgTitle); ?></th>
</TR>
<TR class="row">
    <TD style="color:blue"><?php echo ($message); ?></TD>
</TR>
<TR class="row">
    <TD>系统将在 <span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="<?php echo ($jumpUrl); ?>">这里</A> 跳转</TD>
</TR>
<tr>
    <td height='5' class="bottomTd"></td>
</tr>
</TABLE>
</body>
</html>