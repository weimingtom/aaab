<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<link rel="stylesheet" type="text/css" href="editor/comm.css" />
<script language="javascript" src="editor/all.js"></script>
<script language="javascript" src="editor/editor.js"></script>
<script language="javascript" src="editor/editor_toolbar.js"></script>
<div class="nav">
  <ul>
    <li><a href="article.php">返回文章列表</a></li>
  </ul>
  <div class="clear"></div>
</div>
<form id="form1" name="form1" action="?action=<?php echo $pageinfo['act']."&page=".$page."&classid=".$classid."&type=".$type."&key=".$key; ?>" method="post" onSubmit="return AttachSubmit();">
  <table class="table2" width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <thead>
      <tr>
        <th colspan="8"><div align="center">
            <?php  echo $pageinfo['title']; ?></div></th>
      </tr>
    </thead>
    <tr>
      <td width="72"><div align="right">标题：</div></td>
      <td colspan="7"><input name="title" type="text" class="input" id="title" value="<?php  echo $row['title']; ?>" style="width:65%;" /></td>
    </tr>
    <tr>
	<td width="72"><div align="right">跳转URL：</div></td>
	<td colspan="7"><input name="url" type="text" class="input" id="url" value="<?php  echo $row['url']; ?>" style="width:65%;" /></td>
    </tr>
    <tr>
      <td><div align="right">关键字：</div></td>
      <td colspan="7"><input name="keywords" type="text" class="input" id="keywords" value="<?php  echo $row['keywords']; ?>" style="width:65%;" />
      多个关键词用英文的逗号“,”分开</td>
    </tr>
    <tr>
      <td><div align="right">描述：</div></td>
      <td colspan="7"><input name="description" type="text" class="input" id="description" value="<?php  echo $row['description']; ?>" style="width:65%;" /></td>
    </tr>
    <tr>
      <td><div align="right">图片：</div></td>
      <td colspan="7"><input name="img" type="text" class="input" id="img" value="<?php  echo $row['img']; ?>" style="width:65%;" /><iframe src="upfile.php?lx=1&input_id=img&form_id=form1&iframe_id=web1" frameBorder="0" scrolling="No" style="height:23px;width:350px; margin:8px 0px 0px 5px;"></iframe></td>
    </tr>
    <tr>
      <td><div align="right">TAG：</div></td>
      <td colspan="7"><input name="tag" type="text" class="input" id="tag" value="<?php  echo $row['tag']; ?>" size="80" />
        多个TAG用“,”分开</td>
    </tr>
    <tr>
      <td width="72"><div align="right">所属分类：</div></td>
      <td><select id="dafenglei" name="dafenglei">
          <?php dafenglei_select(0,0,$row['dafenglei']); ?>
      </select></td>
      <td width="90"><div align="right">综合属性：</div></td>
      <td width="120"><?php echo select("top",array('1'=>'普通文章','5'=>'分类置顶文章','10'=>'总置顶文章'),$row['top']); ?></td>
      <td width="60"><div align="right">排序：</div></td>
      <td width="80"><input name="sort" type="text" class="input" id="sort" value="<?php echo $action=='add' ? '100' : $row['sort']; ?>" size="10" />&nbsp;</td>
      <td width="60"><div align="right">属性：</div></td>
    <td width="91">
          <?php echo select("type",array('1'=>'未显示','5'=>'已显示'),$row['type']); ?>      </td>
    </tr>
    <tr>
      <td><div align="right">介绍：</div></td>
      <td colspan="7"><textarea name="content" id="content" cols="45" rows="5" class="none"><?php  echo $row['content']; ?>
</textarea>
		<script language="javascript">
		gFrame = 1;//1-在框架中使用编辑器
		gContentId = "content";//要载入内容的content ID
		OutputEditorLoading();
		var edit = document.getElementById(gContentId);
		function AttachSubmit() { 
			edit.value = GetContent();
		}
		</script>
		<iframe id="HtmlEditor" class="editor_frame" frameborder="0" marginheight="0" marginwidth="0" style="width:100%;height:400px;overflow:visible;"></iframe>
        <iframe src="upfile.php?lx=content" frameBorder="0" scrolling="No" style="height:50px;width:650px"></iframe></td>
    </tr>
    <tr>
      <td colspan="8"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>文章" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
          <input type="hidden" name="editdate" id="editdate" value="<?php echo date("Y-m-d H:i:s"); ?>" />
          <input type="reset" name="button2" id="button2" value="重置" />
        </div></td>
    </tr>
  </table>
</form>
