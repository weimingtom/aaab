<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="static/img/houtai.css" rel="stylesheet" type="text/css" />
<title>默认首页 - rt798.com CMS管理系统</title>
<meta content="管理平台" name=keywords />
<meta content="管理平台" name=description />
<script language="javascript" type="text/javascript" src="static/js/jquery-1.3.2.min.js"></script>
<script language="javascript" type="text/javascript" src="static/js/command.js"></script>
<script language="javascript" type="text/javascript">	
$(function(){
	CMSAPP.addkehu = {
		subject: '分类管理',
		init: function(){
			CMSAPP.command.webTitle(this.subject);
			CMSAPP.command.mtabletr();
		}
	}
	CMSAPP.addkehu.init();
});

</script>
<style type="text/css">

.table1 tr.cur td { background:#ECF6FA;}
.table1a td{background:#d1eef9; }
#tongpage div a{ margin-left:2px; margin-right:2px;}
#tongpage div em{ margin-right:5px;}
.iptxt{width:30px;}


.table1 { width: 100%; background:none}
.table1 tbody { padding-left: 20px; }
.table1 tr {border-bottom:1px dotted #CCC;  }
.table1 td { border-bottom: 1px dotted #CCC; text-align: left; }
.table1 th { border-bottom: 1px solid #CCC;  text-align: left; color: #1A5499; }
.table1 th a { text-decoration: none; color:#1A5499; }
.table1 th a:hover { text-decoration: underline; color: #FF6600; }
</style>
</head>
<body>

<? include $this->gettpl('admin_top');?>

<div id="wrap">

<div class="path">
<span>用户管理</span>
<span><a href="?c=cate&a=add">添加分类</a></span></div>

<div class="content">
<div class="c1">
      <table class="table1" id="myDataGrid" cellspacing="0">
        <tr>
			<th width="15">ID</th>
			<th width="390" align="center" class="first-cell" readOnly=false columnName="cate_name" witdh="500"><div align="center">分类名称</div></th>
			<th width="100">是否导航</th>
			<th width="230" readOnly=false columnName="sort_order" dataType="int"><div align="center">分类链接</div></th>
			<th width="180" readOnly=false columnName="sort_order" dataType="int"><div align="center">分类排序</div></th>
			<th width="241" readOnly=true><div align="center">信息统计</div></th>
			<th width="106"><div align="center">添加时间</div></th>
			<th width="248"><div align="center">操作</div></th>
        </tr>
         <? foreach((array)$arr as $list) {?>
        <tr id="" level="<?=$list['level']?>" child="<?=$list['child']?>" >
			<td><div align="center"><?=$list['cate_id']?></div></td>
			<td class="first-cell" ><img src="static/img/<? if($list['child'] != 0) { ?>menu_minus.gif<? } else { ?>menu_minus.gif<? } ?>" style="padding-left:<?=$list['level']?>em;cursor:pointer;" width="9" height="9" border="0" id="cate_<?=$list['cate_id']?>" onclick="js_folding(this, <?=$list['child']?>)" /> <label style="cursor:pointer" for="cate_<?=$list['cate_id']?>"><?=$list['cate_name']?></label></td>
			<td><div align="center"><?=$list['ismenu']?></div></td>
			<td><div align="center"><span><?=$list['menulink']?></span></div></td>
			<td><div align="center"><span><?=$list['sort_order']?></span></div></td>
			<td><div align="center"><span><?=$list['catestat']?></span></div></td>
			<td><div align="center"><?=$list['time']?></div></td>
			<td><div align="center">
			<a href="admin.php?c=cate&a=edit&cateid=<?=$list['cate_id']?>">编辑</a>&nbsp;&nbsp;
			<a href="javascript:if(confirm('确定是否要删除吗?')){location.href='admin.php?c=cate&a=catedel&cateid=<?=$list['cate_id']?>';}">删除</a>&nbsp;&nbsp;
			<a href="admin.php?c=cate&a=truncate&categoryId=<?=$list['cate_id']?>" onclick="javascript:if(confirm('确定是否要删除吗?')){location.href=this.href;}">清空数据</a></div>
			</td>
        </tr>
       <? } ?>
      </table>

	  <script type="text/javascript">
		 
		function setCookie(name, value, days) {
			var exp  = new Date();    //new Date("December 31, 9998");
			exp.setTime(exp.getTime() + days*24*60*60*1000);
			document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
		}
		
		function getCookie(name) {
			var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
			if(arr != null) return unescape(arr[2]); return null;
		}
		
		function js_folding(obj, rowNum)
		{
			var tr = obj.parentNode.parentNode;
			var tbl = tr.parentNode.parentNode;
			var img = tr.getElementsByTagName("IMG")[0];
			
			if (rowNum > 0)
			{
			  var src = img.src.substr(img.src.lastIndexOf('/') + 1);
			  var rowIndex = tr.rowIndex + 1;
			  if (src == "menu_plus.gif"){
				  
				var subLevel = parseInt(tr.getAttribute('level')) + 1;
				for(var i=0;i<rowNum; i++){
				  var row = tbl.rows[rowIndex+i];
				  var level = parseInt(row.getAttribute('level'));
				  if (level == subLevel) row.style.display="";
				}
				img.src = "static/img/menu_minus.gif";
			  }else{
				//fold
				for(var i=0;i<rowNum; i++){
				  var row = tbl.rows[rowIndex+i];
				  var child = parseInt(row.getAttribute('child'));
				  if (child > 0){
					row.getElementsByTagName("IMG")[0].src = "static/img/menu_plus.gif";
				  }
				  row.style.display="none";
				}
				img.src = "static/img/menu_plus.gif";
			  }
			}else{
			  img.src = "static/img/menu_minus.gif";
			}
		}
	</script>
		
    </div>
</div>
  
<? include $this->gettpl('admin_floor');?>
</div>

</body>
</html>