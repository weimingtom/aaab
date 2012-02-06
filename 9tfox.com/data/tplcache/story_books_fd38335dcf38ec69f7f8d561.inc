<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>">
<title>连载图书列表</title>
<link href="css/base.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/main.js"></script>
<script language="javascript" src="../include/js/dedeajax2.js"></script>
<script language="javascript" src="js/story.js"></script>
<style type="text/css">
<!--
#addCatalog {
	position:absolute;
	left:455px;
	top:64px;
	width:325px;
	height:238px;
	z-index:2;
	background-color: #F2FDDB;
	border:1px solid #889D8F;
	padding:6px;
	display:none
}
#editCatalog {
	position:absolute;
	left:455px;
	top:64px;
	width:325px;
	height:238px;
	z-index:1;
	background-color: #F2FDDB;
	border:1px solid #889D8F;
	padding:6px;
	display:none
}
-->
</style>
</head>
<body background='images/allbg.gif' leftmargin='8' topmargin='8'>
<div class="bodytitle">
	<div class="bodytitleleft"></div>
	<div class="bodytitletxt">小说书目管理 <a href="../book" target="_blank">[查看首页]</a></div>
</div>
<table width="98%" border="0" cellpadding="1" cellspacing="1" align="center" class="tbtitle" style="	background:#CFCFCF;">
	<tr>
		<td bgcolor="#EDF9D5" background='images/tbg.gif'>
		  <table width='96%' cellpadding='0' cellspacing='0'>
        <tr>
          <td width='30%' height="13"><strong>图书列表</strong></td>
          <td align='right'>
          	[<a href="story_books.php?ischeck=1"><u>未审核的图书</u></a>]
          	&nbsp;
			[<a href="story_add.php"><u>新增图书</u></a>]
			&nbsp;
			[<a href="story_catalog.php"><u>栏目管理</u></a>]
					</td>
       </tr>
     </table>
   </td>
  </tr>
</table>
<table width="98%" border="0" cellpadding="5" cellspacing="1" align="center" style=" border:1px solid #CFCFCF;border-top:none;line-height:31px;margin:auto;">
  <tr>
    <td style="background:#ffffff;padding:2px 2px 2px 2px;" >
    <form name='form1' action='story_books.php' method='get'>
    <table width="96%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td width="13%" align="center">关键字:</td>
         <td width="27%"><input name="keyword" type="text" id="keyword" value="<?php echo $keyword; ?>" size="30" class='alltxt' /></td>
         <td width="18%">
         <select name="catid" id="catid">
   <option value='0'>请选择栏目</option>
   <?php
    foreach($btypes as $k=>$v){
    	echo "<option value='$k'>$v</option>\r\n";
    	if(isset($stypes[$k]))
    	{
    		foreach($stypes[$k] as $kk=>$vv)
    		{
    			echo "<option value='$kk'> --$vv</option>\r\n";
    		}
    	}
    }
    ?>
              </select>
              </td>
              <td width="42%"><input type="submit" name="Submit" value="搜 索" class='np coolbg'/></td>
            </tr>
      </table>
    </form>
   </td>
  </tr>
</table>
<form name='form2'>
<table width="98%" border="0" cellpadding="1" cellspacing="1" align="center" style=" margin-top:3px; margin-bottom:3px; line-height:31px;background:#cfcfcf;">
  <tr align="center" height="28" bgcolor="#FBFCE2">
    <td>选择</td>
    <td>图书封面</td>
    <td>图书名称</td>
    <td>隶属栏目</td>
    <td>创建时间</td>
    <td>审核</td>
    <td>管理项目</td>
  </tr>
<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{

	if($fields['ischeck'] == 1){
		$fields['ischeck']='已审核';
	}else{
    	$fields['ischeck']='<font color="red">未审核</font> <a href="story_books.php?action=checked&id='.$fields['bid'].'">审核</a>';
	}
	?>    
  <tr bgcolor="#FFFFFF" height="26" align="center" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';"align="center">
      <td width="8%"> <input type='checkbox' name='ids' value='<?php echo $fields['bid']; ?>' class='np' /> </td>
      <td width="8%"> 
      <?php
    	if($fields['litpic']!=''){
    		echo "<img src='{$fields['litpic']}'  style='padding:2px' height='50'>";
    	}else{
    		echo "<img src='images/booknopic.gif'  style='padding:2px' height='50'>";
    	}
        ?>
      </td>
      <td width="28%">
    	<?php
    	echo "<a href='../book/book.php?bid={$fields['bid']}' target='_blank'>".$fields['bookname']."</a>";
    	if($fields['booktype']!=1) echo "(<font color='#558A20'>小说</font>内容：{$fields['postnum']})";
    	else echo "(<font color='#AB8110'>漫画</font>内容：{$fields['postnum']})";
    	?>
    </td>
    <td width="12%"> <?php echo $fields['classname']; ?></td>
    <td width="12%"><?php echo GetDateMK($fields['senddate']); ?></td>
     <td width="12%"> <?php echo $fields['ischeck']; ?> </td>
    <td align="left">
    <?php  if($fields['booktype']!=1){ ?>
      <a href='story_add_content.php?bookid=<?php echo $fields['bid']; ?>'>添加</a> |
    <?php }else{ ?>
    	<a href='story_add_photo.php?bookid=<?php echo $fields['bid']; ?>'>添加</a> |
    <?php } ?>
      <a href='story_edit.php?bookid=<?php echo $fields['bid']; ?>'>修改</a> |
    	<a href='story_list_content.php?bookid=<?php echo $fields['bid']; ?>'>内容</a> <br />

    	<a href='story_list_chapter.php?bookid=<?php echo $fields['bid']; ?>'>章节</a> |
      <a href='javascript:DelStory(<?php echo $fields['bid']; ?>)'>删除</a>
    </td>
  </tr>
  <?php
}
}?>
<tr>
    <td colspan='7' bgcolor="#ffffff" height='30'>
	&nbsp;
	<a href="javascript:selAll()" class="coolbg">全选</a>
	<a href="javascript:noSelAll()" class="coolbg">取消</a>
	<a href="javascript:DelAllBooks()" class="coolbg">删除</a>
    </td>
  </tr>
</table>
</form>
<table width="98%" border="0" cellpadding="5" cellspacing="1" align="center" style=" border:1px solid #CFCFCF;line-height:31px;margin:auto;">
		<tr>
		  <td bgcolor="#F9FCEF" align="center"><?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
</td>
  </tr>
</table>
</div>
</body>
</html>