<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div class="nav">
    <ul>
      <li><a href="moban.php">����ģ���б�</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php  echo $pageinfo['act']; ?>" method="post">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="table2">
      <thead>
        <tr>
          <th colspan="4"><div align="center"><?php  echo $title; ?>ģ���ļ�</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">�ļ�����</div></td>
        <td>
        <?php
		if($action == "edit"){
		?>
		<input name="filename" type="hidden" class="input" id="filename" value="<?php echo $fileinfo['filename']; ?>" size="40" /><?php echo $fileinfo['filename']; ?>
		<?php
		}else{ ?>
        <input name="filename" type="text" class="input" id="filename" value="test" size="40" />
          Ĭ�Ϻ�׺Ϊ��.php��
        <?php } ?>  
        </td>
        <td width="137"><div align="right">ģ�屸ע˵����</div></td>
        <td width="300"><input name="mobantext" type="text" class="input" id="mobantext" value="<?php echo $mobanfilearr[$fileinfo['filename']]; ?>" size="40" /></td>
      </tr>
      <tr>
        <td><div align="right">���ݣ�</div></td>
        <td colspan="3"><textarea name="content" id="content" style="width:99%;" rows="28"><?php echo $fileinfo['filecontent']; ?></textarea></td>
      </tr>
      
      <tr>
        <td colspan="4"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>ģ���ļ�" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="����" />
        </div></td>
      </tr>
    </table>
</form>