<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div class="nav">
    <ul>
      <li><a href="?page=<?php echo $page."&classid=".$classid;?>">���ط����б�</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php  echo $pageinfo['act']; ?>&page=<?php  echo $page; ?>&classid=<?php  echo $classid; ?>" method="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table2">
      <thead>
        <tr>
          <th colspan="2"><div align="center"><?php  echo $title; ?>����</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">�������ƣ�</div></td>
        <td><input name="name" type="text" class="input" id="name" value="<?php  echo $row['name']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">���⣺</div></td>
        <td><input name="title" type="text" class="input" id="title" value="<?php  echo $row['title']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">�ؼ��֣�</div></td>
        <td><input name="keywords" type="text" class="input" id="keywords" value="<?php  echo $row['keywords']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">������</div></td>
        <td><input name="description" type="text" class="input" id="description" value="<?php  echo $row['description']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">URL��</div></td>
        <td><input name="dir" type="text" class="input" id="dir" value="<?php  echo $row['dir']; ?>" size="60" />
        ǰ�治Ҫ�ӡ�/��,�����һ��Ҫ�ӡ�/��</td>
      </tr>
      <tr>
        <td><div align="right">ģ���ļ���</div></td>
        <td><input name="mobanname" type="text" class="input" id="mobanname" value="<?php  echo $row['mobanname']; ?>" size="60" />
        ������д��׺������ģ���ļ��ǡ�test.php���Ļ���ֻҪ��д��test��������</td>
      </tr>
      <tr>
        <td><div align="right">��������ID��</div></td>
        <td><select name="classid" id="classid">
        <option value="0">-----��������-----</option>
        <?php if($row['classid']<>""){ ?>
        <?php dafenglei_select(0,0,$row['classid']); ?>
        <?php }else{ ?>
        <?php dafenglei_select(0,0,$classid); ?>
        <?php } ?>
        </select>
        </td>
      </tr>
      <tr>
        <td><div align="right">��ʾ���ԣ�</div></td>
        <td><select name="type">
          <?php seleted(array('1'=>'����ʾ','5'=>'��ʾ'),$row['type']); ?>
        </select>
        �ǲ���ǰ̨ҳ����ʾ</td>
      </tr>
      <tr>
        <td><div align="right">����</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="<?php  echo $action=='add' ? '100' : $row['sort']; ?>" size="25" />
        ����ԽС���ŵ�Խǰ</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>����" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="����" />
        </div></td>
      </tr>
    </table>
</form>