<?php
include_once("../db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>管理后台</title>
		<link href="../../../../public/admin/style.css" rel="stylesheet" type="text/css">
		<link href="../../../../public/js/tbox/box.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="so_main">
			<?php
			if (!empty($_POST['cmd_save']) && $_POST['cmd_save']=='保存'){
				if ($_GET['label_id']>0){
					mysql_query("UPDATE ts_plugins_label SET label_seq='".$_POST['label_seq']."',label_hot='".$_POST['label_hot']."',label_name='".$_POST['label_name']."' WHERE label_id='".$_GET['label_id']."'");
					?>
					<Script Language="Javascript">
					alert ("保存成功!");
					document.location.href="index.php";
					</Script>
					<?php
				}else{
					$SQL="SELECT * FROM ts_plugins_label WHERE label_name='".$_POST['label_name']."'";
					$RESULT=mysql_query($SQL);
					if ($RS=mysql_fetch_array($RESULT)){
						?>
						<Script Language="Javascript">
						alert ("标签已存在!");
						</Script>
						<?php
					}else{
						mysql_query("INSERT INTO ts_plugins_label(label_name,label_seq,label_hot) VALUES ('".$_POST['label_name']."','".$_POST['label_seq']."','".$_POST['label_hot']."')");
						?>
						<Script Language="Javascript">
						alert ("保存成功!");
						document.location.href="index.php";
						</Script>
						<?php
					}
				}
			}
			
			$SQL="";
			$SQL=$SQL."SELECT ";
			$SQL=$SQL."		* ";
			$SQL=$SQL."FROM ";
			$SQL=$SQL."		ts_plugins_label ";
			$SQL=$SQL."WHERE ";
			$SQL=$SQL."		label_id='".$_GET['label_id']."' ";
			$RESULT=mysql_query($SQL);
			$RS=mysql_fetch_array($RESULT);
			?>
			<FORM NAME="FRM" METHOD="POST" ACTION="edit.php?label_id=<?php echo $_GET['label_id']?>">
				<div class="list">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="line_l">标签</td>
							<td><input type=text name="label_name" style="width:700px;" value="<?php echo $RS['label_name']?>"/></td>
						</tr>
						<tr>
							<td class="line_l">权重</td>
							<td><input type=text name="label_seq" style="width:50px;" value="<?php echo $RS['label_seq']?>"/></td>
						</tr>
						<tr>
							<td class="line_l">热门</td>
							<td>
								<select name="label_hot">
									<?php
									if ($RS['label_hot']==1){
									?>
										<option value="1" selected>是</option>
										<option value="0">否</option>
									<?php
									}else{
									?>
										<option value="1">是</option>
										<option value="0" selected>否</option>
									<?php
									}
									?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div class="Toolbar_inbox">
					<input type=submit name="cmd_save" value="保存" style="display:none;"/>
					<a class="btn_a"><span onClick="Javascript:FUN_SAVE();">保存</span></a>
				</div>
			</FORM>
		</div>
		<Script Language="Javascript">
		function FUN_SAVE(){
			if (document.FRM.label_seq.value==""){
				alert ("请输入权重!");
				return false;
			}
			if (document.FRM.label_name.value==""){
				alert ("请输入标签!");
				return false;
			}
			if (confirm("请确认是否保存标签?")){
				document.FRM.cmd_save.click();
			}else{
				return false;
			}
		}
		</Script>
	</body>
</html>