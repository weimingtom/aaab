<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div class="nav">
    <ul>
      <li><a href="moban.php">返回模板列表</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php  echo $pageinfo['act']; ?>" method="post">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="table2">
      <thead>
        <tr>
          <th colspan="4"><div align="center"><?php  echo $title; ?>模板文件</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">文件名：</div></td>
        <td>
        <?php
		if($action == "edit"){
		?>
		<input name="filename" type="hidden" class="input" id="filename" value="<?php echo $fileinfo['filename']; ?>" size="40" /><?php echo $fileinfo['filename']; ?>
		<?php
		}else{ ?>
        <input name="filename" type="text" class="input" id="filename" value="test" size="40" />
          默认后缀为“.php”
        <?php } ?>  
        </td>
        <td width="137"><div align="right">模板备注说明：</div></td>
        <td width="300"><input name="mobantext" type="text" class="input" id="mobantext" value="<?php echo $mobanfilearr[$fileinfo['filename']]; ?>" size="40" /></td>
      </tr>
      <tr>
        <td><div align="right">内容：</div></td>
        <td colspan="3"><textarea name="content" id="content" style="width:99%;" rows="28"><?php echo $fileinfo['filecontent']; ?></textarea></td>
      </tr>
      
      <tr>
        <td colspan="4"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>模板文件" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="重置" />
        </div></td>
      </tr>
    </table>
</form>