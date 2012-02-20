<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title><?php echo C("admin_name");?> 管理面版 v<?php echo C("admin_var");?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="<?php echo C("admin_keywords");?>">
<meta name="description" content="<?php echo C("admin_description");?>">
<link rel='stylesheet' type='text/css' href='__PUBLIC__/css/admin.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="__PUBLIC__/js/admin.js"></script>
</head>
<body>
<div class="right">
  <div class="right_top"><?php echo C("admin_welcome");?></div>
  <div class="right_main">
  <div class="right_main">
    <table width="98%" border="0" cellpadding="4" cellspacing="1" class="tableoutline">
      <form action="index.php?s=Admin-Cache-Del" method="post">
        <tr nowrap  class="tb_head">
          <td><h2>更新系统缓存：</h2></td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="other" type="submit" class="mininput" id="all_sub" value=" 更新全部缓存 " > 清空所有系统缓存</td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="menu" type="submit" class="mininput" id="data_sub" value=" 更新导航缓存 " > 更新导航菜单缓存, 切换运行模式后必须更新</td>
        </tr>        
        <tr nowrap class="firstalt">
          <td><input name="fields" type="submit" class="mininput" id="data_sub" value=" 更新数据缓存 " > 清空数据库缓存, Runtime/Data/_fields</td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="temp" type="submit" class="mininput" id="file_sub" value=" 更新文件缓存 " > 清空文件缓存, Runtime/Temp</td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="cache" type="submit" class="mininput" id="temp_sub" value=" 更新模板缓存 " > 清空模板缓存, Runtime/Cache</td>
        </tr>
      </form>
    </table>
    <table width="98%" border="0" cellpadding="4" cellspacing="1" class="tableoutline">
      <form action="index.php?s=Admin-Cache-Delhtml" method="post" name="myform">
        <tr nowrap  class="tb_head">
          <td><h2>更新静态缓存：</h2></td>
        </tr>
          <tr nowrap class="firstalt">
          <td width="48%"><p><input type="submit" class="mininput" value=" 清空当天静态缓存 " onClick="ChangeAction('index.php?s=Admin-Cache-Vodday');"> 清空当天视频的静态缓存文件</p></td>
        </tr>       
         <tr nowrap class="firstalt">
          <td width="48%"><p><input name="other" type="submit" class="mininput" value=" 清空所有静态缓存 " > 清空所有静态缓存,  Html/*</p></td>
        </tr>
        <tr nowrap class="firstalt">
          <td width="48%"><p><input name="index" type="submit" class="mininput" value=" 更新网站首页缓存 " > 清空首页HTML缓存,  Html/index</p></td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="vodshow" type="submit" class="mininput" value=" 更新视频列表缓存 " > 清空视频列表页HTML缓存, Html/Vod_show</td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="vodread" type="submit" class="mininput" value=" 更新视频内容缓存 " > 清空视频内容页HTML缓存, Html/Vod_read</td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="vodplay" type="submit" class="mininput" value=" 更新视频播放缓存 " > 清空视频播放页HTML缓存, Html/Vod_play</td>
        </tr>       
         <tr nowrap class="firstalt">
          <td><input name="newsshow" type="submit" class="mininput" value=" 更新新闻列表缓存 " > 清空新闻列表页HTML缓存, Html/News_show</td>
        </tr>
        <tr nowrap class="firstalt">
          <td><input name="newsread" type="submit" class="mininput" value=" 更新新闻内容缓存 " > 清空新闻内容页HTML缓存, Html/News_read</td>
        </tr>
         <tr nowrap class="firstalt">
          <td><input name="ajaxshow" type="submit" class="mininput" value=" 更新AJAX自定义缓存 " > 清空自定义模板缓存, Html/Ajax_show</td>
        </tr>                           
      </form>
    </table>
  </div>
</div>
<br /><center>Powered by： <a href="<?php echo C("admin_url");?>" target="_blank"><?php echo C("admin_name");?></a> v<?php echo C("admin_var");?></center>
</body>
</html>