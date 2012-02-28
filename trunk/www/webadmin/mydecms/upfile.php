<?php
include 'session.php';
define('WWW.MYDECMS.COM',true);
include 'config.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<style type="text/css">
*{padding:0; margin:0;}
</style>
<title>上传图片</title>
</head>
<body>
<form enctype="multipart/form-data" method="post" action="act_upfile.php">
  <input type="file" name="upload_file" />
  <input type="submit" value="上传" />
  <input type="hidden" name="input_id" id="input_id" value="<?php echo $_GET['input_id'];?>" />
  <input type="hidden" name="lx" id="lx" value="<?php echo $_GET['lx'];?>" />
  <input type="hidden" name="form_id" id="form_id" value="<?php echo $_GET['form_id'];?>" />
  <input type="hidden" name="iframe_id" id="iframe_id" value="<?php echo $_GET['iframe_id'];?>" />
</form>
</body>
</html>
<?php
unset($mysql);
?>
