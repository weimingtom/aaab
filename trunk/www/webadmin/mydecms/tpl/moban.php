<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div class="nav">
  <ul>
    <li><a href="?action=add">���ģ���ļ�</a></li>
  </ul>
  <div class="clear"></div>
</div>
<form action="" method="post" id="form" name="form">
  <table class="table" width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <thead>
      <tr>
        <th width="30"><div align="center">ID</div></th>
        <th><div align="center">�ļ���</div></th>
        <th width="514"><div align="center">�ļ�˵��</div></th>
        <th width="250"><div align="center">����޸�ʱ��</div></th>
        <th width="42"><div align="center">����</div></th>
      </tr>
    </thead>
    <?php foreach($filearr as $k => $v){ ?>
    <tr onmouseover="this.style.backgroundColor='#EEF6FB';" onmouseout="this.style.backgroundColor='';">
      <td><div align="center">
          <input type="checkbox" name="id[]" id="id" value="<?php  echo $v; ?>">
        </div></td>
      <td><a href="?action=edit&amp;filename=<?php  echo $v; ?>">
          <?php  echo $v; ?>
          </a></td>
      <td>
        <div align="left">
        <?php  echo ($mobanfilearr[$v]<>"")? $mobanfilearr[$v]:'<font color="#FF0000">δ֪</font>'; ?>        </div></td>
        <td>
        <div align="center">
        <?php  echo date("Y-m-d H:i:s",filemtime("../".$mydecms['moban'].$v.".php")); ?>        </div></td>
      <td><div align="center"><a href="?action=edit&amp;filename=<?php  echo $v; ?>">�޸�</a></div></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="5"><input type="checkbox" id="chkAll" onclick="CheckAll(this.form)" value="checkbox">
        ȫѡ
        <input type="button" onclick="del(this.form,false,'?action=del');" value="ɾ��" /></td>
    </tr>
  </table>
</form>
