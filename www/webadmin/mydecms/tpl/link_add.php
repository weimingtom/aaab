<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div class="nav">
    <ul>
      <li><a href="?">�������������б�</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php echo $pageinfo['act'];?>&typeid=<?php echo $typeid;?>" method="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table2" align="center">
      <thead>
        <tr>
          <th colspan="2"><div align="center"><?php  echo $pageinfo['subtitle']; ?>��������</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">�������֣�</div></td>
        <td><input name="name" type="text" class="input" id="name" value="<?php  echo $row['name']; ?>" size="40" /></td>
      </tr>
      <tr>
        <td><div align="right">���ӵ�ַ��</div></td>
        <td><input name="url" type="text" class="input" id="url" value="<?php  echo $row['url']; ?>" size="40" /></td>
      </tr>
      <tr>
        <td><div align="right">�������ԣ�</div></td>
        <td><?php echo select("type",array('5'=>'----��ҳ��ʾ----','10'=>'----��ҳ��ʾ----','15'=>'��ҳ��ҳ����ʾ','0'=>'----������ʾ----'),$row['type']); ?></td>
      </tr>
      <tr>
        <td><div align="right">target���ԣ�</div></td>
        <td><?php echo select("target",array('NULL'=>'��ǰ����','_blank'=>'_blank �´���','_parent'=>'_parent ������'),$row['target']); ?></td>
      </tr>
      <tr>
        <td><div align="right">����</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="<?php echo $action=='add' ? '100' : $row['sort']; ?>" size="25" />
        ����ԽС���ŵ�Խǰ��</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>��������" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="����" />
        </div></td>
      </tr>
    </table>
</form>