<!--- 表单数据 -->
<style type="text/css">
dd {text-indent: 8px;}
div.error {height: auto;}
</style>
<form action="index.php?step=checkdb&type=<?php echo $type;?>" method="post" id="checkdbform">
<input type="hidden" name="FORM_HASH" value="123" />
<div style="width: 700px; margin: auto;">
	<h1>3. 数据库信息</h1>
	<div class="div">
		<dl>
			<dt>DB 类型:</dt>
			<dd>
				<input type="radio" name="type" id="type_mysql" value="mysql"<?php echo $type == 'mysql'  ? 'checked="checked"' : '';?> <?php echo !$mysql_support ? 'disabled="disabled" onclick="alert(\'当前环境不支持MySQL\')"' : ''; ?> />MySQL
				<input type="radio" name="type" id="type_pdo" value="pdo"<?php echo $type == 'pdo' ? 'checked="checked"' : '';?> <?php echo !$pdo_support ? 'disabled="disabled" onclick="alert(\'当前环境不支持PDO\')"' : ''; ?> />PDO
				<input type="radio" name="type" id="type_mongodb" value="mongodb"<?php echo $type == 'mongodb' ? 'checked="checked"' : '';?> <?php echo !$mongodb_support ? 'disabled="disabled" onclick="alert(\'当前环境不支持MongoDB\')"' : ''; ?> />MongoDB
			</dd>
			<dt>主机: </dt><dd><input type="text" size="24" name="host" value="<?php echo isset($_POST['host']) ? core::gpc('host', 'P') : $master['host'];?>" /><span class="grey"> 端口号格式：127.0.0.1:10123</span></dd>
			<dt>用户名: </dt><dd><input type="text" size="24" name="user" value="<?php echo isset($_POST['user']) ? core::gpc('user', 'P') : $master['user'];?>" /></dd>
			<dt>密码: </dt><dd><input type="text" size="24" name="pass" value="<?php echo isset($_POST['pass']) ? core::gpc('pass', 'P') : $master['password'];?>" /></dd>
			<dt>数据库: </dt><dd><input type="text" size="24" name="name" value="<?php echo isset($_POST['name']) ? core::gpc('name', 'P') : $master['name'];?>" /></dd>
			<?php if($type != 'mongodb') { ?>
				<dt>表前缀:   </dt><dd><input type="text" size="24" name="tablepre" value="<?php echo isset($_POST['tablepre']) ? core::gpc('tablepre', 'P') : $master['tablepre'];?>" /></dd>
			<?php }?>
		</dl>
	</div>
	<p style="text-align: center;">
		<input type="button" value=" 上一步" onClick="history.back();"/>
		<input type="submit" value=" 下一步" name="formsubmit" /><!-- onclick="return window.confirm('请仔细确认数据库信息，系统将会以覆盖的方式安装！');" -->
	</p>
	<?php if(core::gpc('FORM_HASH', 'P')) { ?>
		<?php if($error) { ?>
			<div class="error"><?php echo $error;?></div>
		<?php } else {?>
			<script type="text/javascript">setTimeout(function() {window.location='?step=plugin'}, 100);</script>
		<?php }?>
	<?php }?>
</div>
</form>
<script type="text/javascript">
function getid(id) {
	return document.getElementById(id);
}

getid('type_mysql').onclick = function() {
	window.location = 'index.php?step=checkdb&type=mysql';
	//getid('db_div').style.display = '';
}
getid('type_pdo').onclick = function() {
	window.location = 'index.php?step=checkdb&type=pdo';
	//getid('db_div').style.display = '';
}
getid('type_mongodb').onclick = function() {
	window.location = 'index.php?step=checkdb&type=mongodb';
	//getid('db_div').style.display = 'none';
}
</script>