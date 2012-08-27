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
		<script type="text/javascript" src="../../../../public/js/jquery.js"></script>
		<script type="text/javascript" src="../../../../public/js/common.js"></script>
		<script type="text/javascript" src="../../../../public/js/tbox/box.js"></script>
	</head>
	<body>
		<div class="so_main">
			<?php
			if (!empty($_POST['cmd_save']) && $_POST['cmd_save']=='保存'){
				if ($_GET['node_id']>0){
					mysql_query("UPDATE ts_plugins_node SET node_seq='".$_POST['node_seq']."',node_name='".$_POST['node_name']."' WHERE node_id='".$_GET['node_id']."'");
					?>
					<Script Language="Javascript">
					alert ("保存成功!");
					document.location.href="index.php";
					</Script>
					<?php
				}else{
					$SQL="SELECT * FROM ts_plugins_node WHERE node_name='".$_POST['node_name']."'";
					$RESULT=mysql_query($SQL);
					if ($RS=mysql_fetch_array($RESULT)){
						?>
						<Script Language="Javascript">
						alert ("结点已存在!");
						</Script>
						<?php
					}else{
						mysql_query("INSERT INTO ts_plugins_node(node_seq,node_name) VALUES ('".$_POST['node_seq']."','".$_POST['node_name']."')");
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
			$SQL=$SQL."		ts_plugins_node ";
			$SQL=$SQL."WHERE ";
			$SQL=$SQL."		node_id='".$_GET['node_id']."' ";
			$RESULT=mysql_query($SQL);
			$RS=mysql_fetch_array($RESULT);
			?>
			<FORM NAME="FRM" METHOD="POST" ACTION="edit.php?node_id=<?php echo $_GET['node_id']?>">
				<div class="list">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="line_l">结点</td>
							<td><input type=text name="node_name" style="width:700px;" value="<?php echo $RS['node_name']?>"/></td>
						</tr>
						<tr>
							<td class="line_l">权重</td>
							<td><input type=text name="node_seq" style="width:700px;" value="<?php echo $RS['node_seq']?>"/></td>
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
			if (document.FRM.node_seq.value==""){
				alert ("请输入权重!");
				return false;
			}
			if (document.FRM.node_name.value==""){
				alert ("请输入结点!");
				return false;
			}
			if (confirm("请确认是否保存结点?")){
				document.FRM.cmd_save.click();
			}else{
				return false;
			}
		}
		</Script>
	</body>
</html>