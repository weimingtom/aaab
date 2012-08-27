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
			if (empty($_GET['node_id']))
				$_GET['node_id']='';

			if (!empty($_POST['cmd_save']) && $_POST['cmd_save']=='保存' && $_GET['node_id']>0){
				$I=0;
				foreach ($_POST['list_name'] as $list_name){
					$list_name=trim($list_name);
					if ($list_name!=''){
						if ($_POST['list_id'][$I]>0){
							mysql_query("UPDATE ts_plugins_list SET list_name='".$list_name."', list_seq='".$_POST['list_seq'][$I]."' WHERE list_id='".$_POST['list_id'][$I]."'");
						}else{
							$SQL="SELECT * FROM ts_plugins_list WHERE list_name='".$list_name."'";
							$RESULT=mysql_query($SQL);
							if ($RS=mysql_fetch_array($RESULT)){
							}else{
								mysql_query("INSERT INTO ts_plugins_list(node_id,list_name,list_seq) VALUES ('".$_GET['node_id']."','".$list_name."','".$_POST['list_seq'][$I]."')");
							}
						}
					}
					$I++;
				}
			}
			if (!empty($_POST['cmd_del']) && $_POST['cmd_del']=='删除' && $_GET['node_id']>0){
				foreach ($_POST['chk'] as $list_id){
					if ($list_id>0)
						mysql_query("DELETE FROM ts_plugins_list WHERE list_id='".$list_id."'");
				}
			}
			
			$SQL="";
			$SQL=$SQL."SELECT ";
			$SQL=$SQL."		node_id,node_name ";
			$SQL=$SQL."FROM ";
			$SQL=$SQL."		ts_plugins_node ";
			$SQL=$SQL."WHERE ";
			$SQL=$SQL."		node_id='".$_GET['node_id']."'";
			$RESULT=mysql_query($SQL);
			$RS=mysql_fetch_array($RESULT);
			?>
			<FORM NAME="FRM" METHOD="POST" ACTION="edit.php?node_id=<?php echo $_GET['node_id']?>">
				<div class="list">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan=2>
								<?php echo $RS['node_name']?>
							</td>
						</tr>

						<tr>
							<td colspan=2 style="height:25px;line-height:25px" bgcolor="#E5E5E5" align=center>
								<div class="Toolbar_inbox">
									<a class="btn_a"><span onClick="Javascript:FUN_ADD_CONTENT();">新增</span></a>
									
									<input type=submit name="cmd_save" value="保存" style="display:none;"/>
									<a class="btn_a"><span onClick="Javascript:FUN_SAVE();">保存</span></a>
									
									<input type=submit name="cmd_del" value="删除" style="display:none;"/>
									<a class="btn_a"><span onClick="Javascript:FUN_DELETE();">删除</span></a>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan=2 bgcolor="#ffffff">
								<table cellSpacing=1 cellPadding=0 width="100%" border=0 id="DATA_CONTENT">
								
								<tr>
							<th class="line_l">操作</th>
							<th class="line_l">二级分类</th>
							<th class="line_l">权重</th>
						</tr>
								
									<?php
									$sql="SELECT * FROM ts_plugins_list WHERE node_id='".$_GET['node_id']."' ORDER BY list_id ASC";
									$result=mysql_query($sql);
									while ($rs=mysql_fetch_array($result)){
										?>
										<tr>
											<td><input type="checkbox" name="chk[]" id='chk[]' value="<?php echo $rs['list_id']?>" /><input type=hidden value="<?php echo $rs['list_id']?>" name='list_id[]'/></td>
											<td bgcolor="#FFFFFF"><input type=text value="<?php echo $rs['list_name']?>" name='list_name[]' style='width:100px;'/></td>
											<td bgcolor="#FFFFFF"><input type=text value="<?php echo $rs['list_seq']?>" name='list_seq[]' style='width:50px;'/></td>
										</tr>
										<?php
									}
									?>
								</table>
								<Script Language="Javascript">
								var DATA_CONTENT=document.getElementById("DATA_CONTENT"); 
								function FUN_ADD_CONTENT(){
									var Row = DATA_CONTENT.insertRow();
									var Td = Row.insertCell(); 
									Td.innerHTML = '<input type="checkbox" id="chk[]"  name="chk[]" value="0" /><input type=hidden value="0" name="list_id[]"/>';
									Td = Row.insertCell(); 
									Td.innerHTML = '<input type=text value="" name="list_name[]" style="width:100px;"/>';
									Td = Row.insertCell(); 
									Td.innerHTML = '<input type=text value="0" name="list_seq[]" style="width:50px;"/>';
								} 
								</Script>
							</td>
						</tr>

					</table>
				</div>
			</FORM>
		</div>
		<Script Language="Javascript">
		function FUN_SAVE(){
			if (confirm("请确认是否保存结点?")){
				document.FRM.cmd_save.click();
			}else{
				return false;
			}
		}
		function FUN_DELETE(){
			if (confirm("请确认是否删除结点?")){
				document.FRM.cmd_del.click();
			}else{
				return false;
			}
		}
		</Script>
	</body>
</html>