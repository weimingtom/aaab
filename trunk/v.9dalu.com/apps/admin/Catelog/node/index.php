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
			if (empty($_POST['node_name']))
				$_POST['node_name']='';
			if (empty($_POST['PAGESIZE']))
				$_POST['PAGESIZE']=20;
			if (empty($_POST['PAGE']))
				$_POST['PAGE']=1;			
			if (!empty($_POST['cmd_delete']) && $_POST['cmd_delete']=="删除"){
				foreach ($_POST['checkbox'] as $node_id){
					mysql_query("DELETE FROM ts_plugins_node WHERE node_id='".$node_id."'");
				}
			}
			
			$SQL="";
			$SQL=$SQL."SELECT ";
			$SQL=$SQL."		COUNT(*) AS CNT ";
			$SQL=$SQL."FROM ";
			$SQL=$SQL."		ts_plugins_node ";
			$SQL=$SQL."WHERE ";
			$SQL=$SQL."		INSTR(node_name,'".$_POST['node_name']."')>0 ";
			$COUNT_RECORD=0;
			$RESULT=mysql_query($SQL);
			if ($RS=mysql_fetch_array($RESULT))
				$COUNT_RECORD=$RS['CNT'];
			
			if ($_POST["PAGESIZE"]<1)
				$_POST["PAGESIZE"]=20;
			
			$COUNT_PAGE=ceil($COUNT_RECORD/$_POST["PAGESIZE"]);
			if ($_POST["PAGE"]>$COUNT_PAGE)
				$_POST["PAGE"]=$COUNT_PAGE;
			if ($_POST["PAGE"]<1)
				$_POST["PAGE"]=1;
			?>
			<FORM NAME="FRM" METHOD="POST" ACTION="index.php">
				<div class="Toolbar_inbox">
					<div class="page right">
						共 <FONT COLOR=RED><?php echo $COUNT_RECORD?></FONT> 条记录
						<?php
						if ($_POST['PAGE']!=1){
						?>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(1);">第一页</span>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $_POST["PAGE"]-1?>);">上一页</span>
						<?php
						}
						for($I=$_POST['PAGE']-5;$I<=$_POST['PAGE']+5;$I++){
							if ($I>=1 && $I<=$COUNT_PAGE){
								if ($I==$_POST['PAGE']){
						?>
									<span class='current'><?php echo $I?></span>
						<?php
								}else{
						?>
									<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $I?>);"><?php echo $I?></span>
						<?php
								}
							}
						}
						if ($_POST["PAGE"]<$COUNT_PAGE){
						?>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $_POST["PAGE"]+1?>);">下一页</span>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $COUNT_PAGE?>);">最后一页</span>
						<?php
						}
						?>
					</div>
					<input type="hidden" name="PAGE" value="<?php echo $_POST['PAGE']?>"/>
					<input type="hidden" name="PAGESIZE" value="<?php echo $_POST['PAGESIZE']?>"/>
					<input type="hidden" name="checkbox[]" value="0">

					结点：<input type="text" name="node_name" value="<?php echo $_POST['node_name']?>" style="width:100px;"/>
					<input type=submit name="cmd_search" value="搜索" style="display:none;"/>
					<a class="btn_a"><span onClick="Javascript:FUN_PAGE(1);">搜索</span></a>
				</div>
				<div class="list">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th>
								<input type="checkbox" id="checkbox_handle" onclick="checkAll(this)" value="0">
								<label for="checkbox"></label>
							</th>
							<th class="line_l">ID</th>
							<th class="line_l">结点</th>
							<th class="line_l">权重</th>
							<th class="line_l">操作</th>
						</tr>
						<?php
						$SQL="";
						$SQL=$SQL."SELECT ";
						$SQL=$SQL."		* ";
						$SQL=$SQL."FROM ";
						$SQL=$SQL."		ts_plugins_node ";
						$SQL=$SQL."WHERE ";
						$SQL=$SQL."		INSTR(node_name,'".$_POST['node_name']."')>0 ";
						$SQL=$SQL."ORDER BY ";
						$SQL=$SQL."		node_seq DESC ";
						$SQL=$SQL."LIMIT ";
						$SQL=$SQL.(($_POST["PAGE"]-1)*$_POST["PAGESIZE"]).",".$_POST["PAGESIZE"];
						$RESULT=mysql_query($SQL);
						while ($RS=mysql_fetch_array($RESULT)){
						?>
							<tr overstyle='on' id="credit_<?php echo $RS['id']?>">
								<td>
									<?php
									if ($RS['node_name']=='逛街啦' OR $RS['node_name']=='搭配秀' OR $RS['node_name']=='晒货' OR $RS['node_name']=='潮STYLE'){
										echo '&nbsp;';
									}else{
										?>
										<input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $RS['node_id']?>">
										<?php
									}
									?>
								</td>
								<td>
									<?php echo $RS['node_id']?>
								</td>
								<td>
									<?php echo $RS['node_name']?>
									<br/>
									<?php
									$sql="SELECT * FROM ts_plugins_list WHERE node_id='".$RS['node_id']."'";
									$result=mysql_query($sql);
									while ($rs=mysql_fetch_array($result)){
									?>
										<font color=red><?php echo $rs['list_name']?></font>　
									<?php
									}
									?>
								</td>
								<td>
									<?php echo $RS['node_seq']?>
								</td>
								<td>
									<?php
									if ($RS['node_name']=='逛街啦' OR $RS['node_name']=='搭配秀' OR $RS['node_name']=='晒货' OR $RS['node_name']=='潮STYLE'){
										echo '&nbsp;';
									}else{
										?>
										<?php if ($_SESSION['mid']!=1){ ?>
									<span style="color:grey;">编辑</span>

									<?php }else{ ?>
									<a href="edit.php?node_id=<?php echo $RS['node_id']?>">编辑</a> 
									<?php
									}
									?>
									<?php
									}
									?>
									<?php if ($_SESSION['mid']!=1){ ?>
									<span style="color:grey;">二级分类</span>

									<?php }else{ ?>
									<a href="../list/edit.php?node_id=<?php echo $RS['node_id']?>">二级分类</a>
									<?php
									}
									?>
								</td>
							</tr>
						<?php
						}
						?>
					</table>
				</div>
				<div class="Toolbar_inbox">
					<div class="page right">

						共 <FONT COLOR=RED><?php echo $COUNT_RECORD?></FONT> 条记录
						<?php
						if ($_POST['PAGE']!=1){
						?>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(1);">第一页</span>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $_POST["PAGE"]-1?>);">上一页</span>
						<?php
						}
						for($I=$_POST['PAGE']-5;$I<=$_POST['PAGE']+5;$I++){
							if ($I>=1 && $I<=$COUNT_PAGE){
								if ($I==$_POST['PAGE']){
						?>
									<span class='current'><?php echo $I?></span>
						<?php
								}else{
						?>
									<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $I?>);"><?php echo $I?></span>
						<?php
								}
							}
						}
						if ($_POST["PAGE"]<$COUNT_PAGE){
						?>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $_POST["PAGE"]+1?>);">下一页</span>
							<span class=disabled style="cursor:hand;" onclick="Javascript:FUN_PAGE(<?php echo $COUNT_PAGE?>);">最后一页</span>
						<?php
						}
						?>
					</div>
					<input type=submit name="cmd_delete" value="删除" style="display:none;"/>
					<?php if ($_SESSION['mid']==1){ ?>
					<a class="btn_a"><span onClick="Javascript:FUN_DEL();">删除</span></a>

					<a class="btn_a" href="edit.php?node_id=0"><span>新增</span></a>
					<?php
									}
									?>
				</div>
			</FORM>
		</div>
		<Script Language="Javascript">
		$(document).ready(function(){
			$("tr[overstyle='on']").hover(
				function () {
					$(this).addClass("bg_hover");
				},
				function () {
					$(this).removeClass("bg_hover");
				}
			);
		});
		function checkAll(o){
			if( o.checked == true ){
				$('input[name="checkbox[]"]').attr('checked','true');
				$('tr[overstyle="on"]').addClass("bg_on");
			}else{
				$('input[name="checkbox[]"]').removeAttr('checked');
				$('tr[overstyle="on"]').removeClass("bg_on");
			}
		}

		function FUN_PAGE(PAGE){
			document.FRM.PAGE.value=PAGE;
			document.FRM.cmd_search.click();
		}
		function FUN_DEL(){
			var Find=0;
			for (var i=0;i<document.FRM.elements.length;i++){
				var e = document.FRM.elements[i];
				if (e.name=='checkbox[]' && e.checked){
					Find=1;
					i=100000;
				}
			}
			if (Find==0){
				alert ("请选择需要删除的结点!");
				return false;
			}
			if (confirm("请确认是否真的删除这些结点?")){
				document.FRM.cmd_delete.click();
			}else{
				return false;
			}
		}
		</Script>
	</body>
</html>