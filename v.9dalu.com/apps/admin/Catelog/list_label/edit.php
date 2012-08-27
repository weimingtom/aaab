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
			if (!empty($_POST['cmd_save']) && $_POST['cmd_save']=='保存' && $_GET['list_id']>0){
				mysql_query("DELETE FROM ts_plugins_list_label WHERE list_id='".$_GET['list_id']."'");
				foreach($_POST['label_id'] as $label_id){
					mysql_query("INSERT INTO ts_plugins_list_label (list_id,label_id) VALUES ('".$_GET['list_id']."','".$label_id."')");
				}
			}
			?>
			<FORM NAME="FRM" METHOD="POST" ACTION="edit.php?list_id=<?php echo $_GET['list_id']?>">
				<div class="list">
					<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan=3>
								<?php
								$sql="SELECT * FROM ts_plugins_list WHERE list_id='".$_GET['list_id']."'";
								$result=mysql_query($sql);
								if ($rs=mysql_fetch_array($result))
									echo $rs['list_name'];
								?>
							</td>
						</tr>
						<tr>
							<td align="center">
								<select name="unlabel_id[]" id="unlabel_id[]" multiple style="height:300px;width:200px;">
									<?php
									$Sql="SELECT * FROM ts_plugins_label ORDER BY CONVERT( label_name USING gbk )";
									$Result=mysql_query($Sql);
									while ($Rs=mysql_fetch_array($Result)){
										?>
										<option value="<?php echo $Rs['label_id']?>"><?php echo $Rs['label_name']?></option>
										<?php
									}
									?>
								</select>
							</td>
						    <td align="center">
								<div class="Toolbar_inbox">
									<a class="btn_a"><span onClick="Javascript:Fun_Select(0);">-></span></a>
									<BR/>
									<BR/>
									<a class="btn_a"><span onClick="Javascript:Fun_Select(1);">>></span></a>
									<BR/>
									<BR/>
									<a class="btn_a"><span onClick="Javascript:Fun_Select(2);"><-</span></a>
									<BR/>
									<BR/>
									<a class="btn_a"><span onClick="Javascript:Fun_Select(3);"><<</span></a>
								</div>
							</td>
						    <td align="center">
								<select name="label_id[]" id="label_id[]" multiple style="height:300px;width:200px;">
									<?php
									$Sql="SELECT * FROM ts_plugins_label ORDER BY CONVERT( label_name USING gbk )";
									$Result=mysql_query($Sql);
									while ($Rs=mysql_fetch_array($Result)){
										$sql="SELECT * FROM ts_plugins_list_label WHERE list_id='".$_GET['list_id']."' AND label_id='".$Rs['label_id']."'";
										$result=mysql_query($sql);
										if ($rs=mysql_fetch_array($result)){
											?>
											<option value="<?php echo $Rs['label_id']?>" <?php if ($rs['label_id']==$Rs['label_id']) echo 'selected';?>><?php echo $Rs['label_name']?></option>
											<?php
										}
									}
									?>
								</select>
							</td>
						</tr>
					</table>
					
					<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td>温馨提示：按拼音首字母 A-Z 排序</td>
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
//大小类
var TAG_CATEGORY="";
function Fun_Select(Id){
	for (var i=document.getElementById("unlabel_id[]").length-1;i>=0;i--)
		if (document.getElementById("unlabel_id[]").item(i).selected){
			var name=document.getElementById("unlabel_id[]").options[i].text;
		}
	//由左到右（单选）
	if (Id==0){
		var SELECT="";
		for (var i=document.getElementById("unlabel_id[]").length-1;i>=0;i--)
			if (document.getElementById("unlabel_id[]").item(i).selected){
				for (var j=document.getElementById("label_id[]").length-1;j>=0;j--)
					if (document.getElementById("unlabel_id[]").item(i).value==document.getElementById("label_id[]").item(j).value)
						j=-10;
				if (j<=-10)
					document.getElementById("unlabel_id[]").item(i).selected=false;
			}

		for (var i=document.getElementById("unlabel_id[]").length-1;i>=0;i--){
			if (document.getElementById("unlabel_id[]").item(i).selected)
				SELECT=SELECT+document.getElementById("unlabel_id[]").item(i).text+"!";
		}
		if (SELECT!="")
			TAG_CATEGORY=TAG_CATEGORY+SELECT+"^";

		for (var i=document.getElementById("unlabel_id[]").length-1;i>=0;i--){
			if (document.getElementById("unlabel_id[]").item(i).selected){
				document.getElementById("label_id[]").options[document.getElementById("label_id[]").length]=new Option(document.getElementById("unlabel_id[]").item(i).text,document.getElementById("unlabel_id[]").item(i).value);
				document.getElementById("unlabel_id[]").options[i]=null;
			}
		}
	}
	//由左到右（全选）
	if (Id==1){
		for (var i=document.getElementById("unlabel_id[]").length-1;i>=0;i--)
			document.getElementById("unlabel_id[]").item(i).selected=true;
		Fun_Select(0);
	}
	//由右到左（单选）
	if (Id==2){
		R_L_CATEGORY();
	}
	//由右到左（全选）
	if (Id==3){
		for (var i=document.getElementById("label_id[]").length-1;i>=0;i--)
			document.getElementById("label_id[]").item(i).selected=true;
		R_L_CATEGORY();
	}
}
function R_L_CATEGORY(){
	for (var i=document.getElementById("label_id[]").length-1;i>=0;i--){
		if (document.getElementById("label_id[]").item(i).selected){
			if (TAG_CATEGORY!=""){
				var Arr=TAG_CATEGORY.split("^");
				for (var j=0;j<Arr.length-1;j++){
					var Item=Arr[j];
					Item=Item.split("#");
					for (var k=0;k<Item.length-1;k++){
						var Temp=Item[k];
						Temp=Temp.split("!");
						for (var z=0;z<Temp.length-1;z++){

							if (Temp[z]==document.getElementById("label_id[]").item(i).text){

								for (var x=document.getElementById("unlabel_id[]").length-1;x>=0;x--){
									if (document.getElementById("unlabel_id[]").options[x].text!=Temp[Temp.length-1]){
										document.getElementById("unlabel_id[]").options[x+1]=new Option(document.getElementById("unlabel_id[]").options[x].text,document.getElementById("unlabel_id[]").options[x].value);
									}else{
										document.getElementById("unlabel_id[]").options[x+1]=new Option(document.getElementById("label_id[]").item(i).text,document.getElementById("label_id[]").item(i).value);
										x=-1;
									}
								}
							}

						}
					}
				}
			}
			document.getElementById("label_id[]").options[i]=null;
		}
	}
	if (document.getElementById("label_id[]").length==0){
		TAG_CATEGORY="";
	}
}
//过滤掉已选中的选项
for (var i=document.getElementById("label_id[]").length-1;i>=0;i--){
	for (var j=document.getElementById("unlabel_id[]").length-1;j>=0;j--){
		if (document.getElementById("unlabel_id[]").options[j].value==document.getElementById("label_id[]").options[i].value){
			var name=document.getElementById("unlabel_id[]").options[j].text;
			document.getElementById("unlabel_id[]").item(j).selected=true;
			document.getElementById("label_id[]").options[i]=null;
			Fun_Select(0);
		}
	}
}
		
		function FUN_SAVE(){
			for (var i=0;i<document.getElementById("label_id[]").length;i++)
				document.getElementById("label_id[]").options[i].selected=true;
			if (confirm("请确认是否保存结点及标签关联?")){
				document.FRM.cmd_save.click();
			}else{
				return false;
			}
		}
		</Script>
	</body>
</html>