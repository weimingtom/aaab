<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div class="nav">
  <ul>
    <li><a href="?action=add&page=<?php  echo $page; ?>&classid=<?php  echo $classid; ?>">��ӷ����б�</a></li>
  </ul>
  <div class="clear"></div>
</div>
<div><a href="?action=">��������</a><?php dafenglei_location($classid,$classid); ?>
	 <?php echo $dafengle_str; ?></div>
<form action="" method="post" id="form" name="form">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table">
    <thead>
      <tr>
        <th width="33">ID</th>
        <th width="425">��������</th>
        <th width="266">url</th>
        <th width="253">ģ���ļ�</th>
        <th width="168">��ʾ����</th>
        <th width="84">�ӷ���</th>
        <th width="66">����</th>
        <th width="87">����</th>
      </tr>
    </thead>
    <?php dafenglei_arr(0,$classid); ?>
    <tr>
      <td colspan="8" height="28"><?php echo $pages -> myde_write(); ?></td>
    </tr>
    <tr>
      <td colspan="8"><input type="checkbox" id="chkAll" onclick="CheckAll(this.form)" value="checkbox">
        ȫѡ
        <input type="button" onclick="del(this.form,false,'?action=del');" value="ɾ��" />
        <input type="button" onclick="act_submit(this.form,false,'gotohtml.php?action=dafenglei');" value="������ѡ����Ŀ¼" />
        <input type="button" onclick="act_submit(this.form,true,'gotohtml.php?action=dafengleiall');" value="�������з���Ŀ¼" />
        <div class="red">�ر�ע�⣺�������ĳ��������в���������������С���༰�������ı���Ҳ���ܲ�����Ӱ�졣����˵�������ɾ��ĳ������࣬����������С���༰�������ı���Ҳ���ᱻɾ�������Բ���Ҫ���أ�</div>      </td>
    </tr>
  </table>
</form>
