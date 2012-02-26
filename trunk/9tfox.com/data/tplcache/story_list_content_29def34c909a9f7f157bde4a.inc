<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>">
<title>连载内容列表</title>
<link href='css/base.css' rel='stylesheet' type='text/css'>
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
	<div class="bodytitletxt" style="padding-left:10px;">小说管理 <a href="../book" target="_blank">[查看首页]</a></div>
</div>
<table width="98%" border="0" cellpadding="1" cellspacing="1" align="center" class="tbtitle" style="background:#CFCFCF;">
	<tr>
		<td bgcolor="#EDF9D5" background='images/tbg.gif'>
		  <table width='96%' border="0" cellpadding='0' cellspacing='0'>
        <tr>
          <td width='30%' class="tbtitletxt" style="padding-left:10px;"><strong>连载内容列表</strong></td>
          <td align='right'>
			[<a href="story_books.php"><u>图书管理</u></a>]
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
<table width="98%" border="0" cellpadding="5" cellspacing="1" align="center" style=" border:1px solid #CFCFCF;line-height:31px;margin:auto;border-top:none;">
  <tr>
    <td style="background:#ffffff;padding:3px;" >
    <table border="0" cellspacing="0" cellpadding="0" width="98%">
       <tr>
         <td width='50%'>
    <form name='form1' action='story_list_content.php' method='get'>
    <table border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td style='padding-left:10px;padding-right:10px;'>关键字:</td>
         <td style='padding-right:10px;'>
         	<input name="keyword" type="text" id="keyword" value="<?php echo $keyword; ?>" size="30" class='alltxt' />
         </td>
         <td style='padding-right:10px;'>
         <select name='booktype'>
         <option value='-1'>类型</option>
         <option value='0'>小说</option>
         <option value='1'>漫画</option>
         </select>
         </td>
         <td><input type="submit" name="Submit" value="搜 索"  class='np coolbg' /></td>
        </tr>
      </table>
    </form>  
     </td>
    <td align='right'>
    	<?php
    	if(!empty($bookid)){
    		echo "<a href='story_add_content.php?bookid={$bookid}'>添加内容</a> | ";
    		echo "<a href='story_list_chapter.php?bookid={$bookid}'>本书章节</a> | ";
    		echo "<a href='story_list_content.php'>所有内容</a>";
    	}
    	?>
    </td>
    </tr>
   </table>
   </td>
  </tr>
</table>
<table width="98%" border="0" cellpadding="1" cellspacing="1" align="center" style=" margin-top:3px; margin-bottom:3px; line-height:31px;background:#CFCFCF;">
  <tr align="center" style="background:#FBFCE2;height:26px">
    <td width="24%">文章标题</td>
    <td width="32%" align='left' style="padding-left:8px;">图书名称/章节名称/章节内序号</td>
    <td width="12%">添加时间</td>
    <td>管理项目</td>
  </tr>
<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
?>
  <tr align="center" height='26' bgcolor="#FFFFFF" height="26" align="center" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
    <td align="center"><?php echo $fields['title']; ?></td>
    <td align='left'  style="padding-left:8px;">
	<a href='story_list_content.php?bookid=<?php echo $fields['bookid']; ?>'><?php echo $fields['bookname']; ?></a>
	<a href='story_list_content.php?bookid=<?php echo $fields['bookid']; ?>&chapid=<?php echo $fields['chapterid']; ?>'><?php echo $fields['chaptername']; ?></a><?php echo $fields['sortid']; ?>    </td>
    <td> <?php echo GetDateMK($fields['addtime']); ?></td>
    <td> 
    	<a href='javascript:PreViewCt(<?php echo $fields['id']; ?>,<?php echo $fields['booktype']; ?>);'>预览</a> |
    	<?php
    	if($fields['booktype']==1) $editfile = 'story_photo_edit.php?cid=';
    	else $editfile = 'story_content_edit.php?cid=';
    	?>
    	<a href='<?php echo $editfile.$fields['id']; ?>'>修改</a> |
      <a href='javascript:DelStoryContent(<?php echo $fields['id']; ?>)'>删除</a>    </td>
  </tr>
<?php
}
}?>
</table>
<table width="98%" border="0" cellpadding="5" cellspacing="1" align="center" style=" border:1px solid #CFCFCF;line-height:31px;margin:auto;">
		<tr>
		  <td style="background:#F9FCEF;" >
		  <?php
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