<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div class="nav">
    <ul>
      <li><a href="?">���ع���б�</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php echo $pageinfo['act'];?>&typeid=<?php echo $typeid;?>" method="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table2" align="center">
      <thead>
        <tr>
          <th colspan="2"><div align="center"><?php  echo $pageinfo['subtitle']; ?>���</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">������ƣ�</div></td>
        <td><input name="name" type="text" class="input" id="name" value="<?php  echo $row['name']; ?>" style="width:35%;" /></td>
      </tr>
      <tr>
        <td><div align="right">��ע��</div></td>
        <td><input name="beizhu" type="text" class="input" id="beizhu" value="<?php  echo $row['beizhu']; ?>" style="width:35%;" /> ����˵����������Ϣ</td>
      </tr>
      <tr>
        <td><div align="right">����</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="<?php echo $action=='add' ? '100' : $row['sort']; ?>" size="25" />
        ����ԽС���ŵ�Խǰ��</td>
      </tr>
      <tr>
        <td><div align="right">���ԣ�</div></td>
        <td><?php echo select("type",array('5'=>'����','0'=>'������'),$row['type']); ?></td>
      </tr>
      <tr>
      <td><div align="right">�����룺</div></td>
      <td><textarea name="content" id="content" class="input" style="width:98%; height:100px;"><?php echo str_replace(array('+=+','-+-'),array('"',"'"), stripslashes($row['content']));?></textarea></td>
    </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>���" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="����" />
        </div></td>
      </tr>
    </table>
</form>