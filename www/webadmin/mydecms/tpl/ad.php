<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div class="nav">
  <ul>
    <li><a href="?action=add">��ӹ��</a></li>
    <li><a href="?action=&page=1&typeid=">ȫ�����</a></li>
    <li><a href="?action=&page=1&typeid=5">����</a></li>
    <li><a href="?action=&page=1&typeid=0">������</a></li>
  </ul>
  <div class="clear"></div>
</div>
<form action="" method="post" id="form" name="form">
  <table class="table" width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <thead>
      <tr>
        <th width="35"><div align="center">ID</div></th>
        <th width="300"><div align="center">�������</div></th>
        <th><div align="center">��汸ע</div></th>
        <th width="90"><div align="center">����</div></th>
        <th width="50"><div align="center">����</div></th>
        <th width="42"><div align="center">����</div></th>
      </tr>
    </thead>
    <?php while($row = $mysql -> fetch_array($result)){ ?>
    <tr onmouseover="this.style.backgroundColor='#EEF6FB';" onmouseout="this.style.backgroundColor='';">
      <td><div align="center">
          <input type="checkbox" name="id[]" id="id" value="<?php  echo $row['id']; ?>"> <input type="hidden" name="idd[]" value="<?php  echo $row['id']; ?>" />
        </div></td>
      <td><a href="?action=edit&amp;id=<?php  echo $row['id']; ?>">
          <?php  echo $row['name']; ?>
          </a></td>
      <td><div align="left">
          <?php  echo $row['beizhu']; ?>
        </div></td>
      <td><div align="center">
          <?php echo select("type[]",array('5'=>'����','0'=>'������'),$row['type']); ?>
        </div></td>
      <td><div align="center">
          <input name="sort[]" type="text" id="sort[]" size="4" class="input" value="<?php  echo $row['sort']; ?>" />
        </div></td>
      <td><div align="center"><a href="?action=edit&amp;id=<?php  echo $row['id']; ?>">�޸�</a></div></td>
    </tr>
    <?php } ?>
    <tr class="pagetr">
      <td colspan="7"><?php echo $pages -> myde_write(); ?></td>
    </tr>
    <tr>
      <td colspan="7"><input type="checkbox" id="chkAll" onclick="CheckAll(this.form)" value="checkbox">
        ȫѡ
        <input type="button" onclick="del(this.form,false,'?action=del');" value="ɾ��" /> <input type="button" onclick="act_submit(this.form,true,'?action=piliang<?php echo "&page=".$page;?>&typeid=<?php echo $typeid;?>');" value="�����޸�" /> </td>
    </tr>
  </table>
</form>
