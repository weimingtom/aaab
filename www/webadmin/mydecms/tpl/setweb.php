<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<form id="form1" name="form1" action="?action=<?php echo $pageinfo['act'];?>" method="post">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table2" align="center">
    <thead>
      <tr>
        <th colspan="2"><div align="center">�޸�ϵͳ����</div></th>
      </tr>
    </thead>
    <tr>
      <td width="167"><div align="right">��վ���ƣ�</div></td>
      <td><input name="webname" type="text" class="input" id="webname" value="<?php echo $mydecms['webname'];?>" style="width:98%" /></td>
    </tr>
    <tr>
      <td><div align="right">��վ��ַ��</div></td>
      <td><input name="weburl" type="text" class="input" id="weburl" value="<?php echo $mydecms['weburl'];?>" style="width:98%" /></td>
    </tr>
    <tr>
      <td><div align="right">��վ���⣺</div></td>
      <td><input name="webtitle" type="text" class="input" id="webtitle" value="<?php echo $mydecms['webtitle'];?>" style="width:98%" /></td>
    </tr>
    <tr>
      <td><div align="right">�ؼ��֣�</div></td>
      <td><input name="webkeywords" type="text" class="input" id="webkeywords" value="<?php echo $mydecms['webkeywords'];?>" style="width:98%" /></td>
    </tr>
    <tr>
      <td><div align="right">������</div></td>
      <td><input name="webdescription" type="text" class="input" id="webdescription" value="<?php echo $mydecms['webdescription'];?>" style="width:98%" /></td>
    </tr>
    <tr>
      <td><div align="right">��ҳ��ʾ����ID��</div></td>
      <td><input name="classid" type="text" class="input" id="classid" value="<?php echo $mydecms['classid'];?>" style="width:98%" /></td>
    </tr>
    <tr>
      <td><div align="right">ģ��Ŀ¼��</div></td>
      <td>
      <?php echo select("moban",$mydecms['mobanarr'],$mydecms['moban']); ?></td>
    </tr>
    <tr>
      <td><div align="right">���¾�̬Ŀ¼��</div></td>
      <td><input name="html" type="text" class="input" id="html" value="<?php echo $mydecms['html'];?>" size="20" /></td>
    </tr>
    <tr>
      <td><div align="right">ICP�����ţ�</div></td>
      <td><input name="icp" type="text" class="input" id="icp" value="<?php echo $mydecms['icp'];?>" size="20" /></td>
    </tr>
    <tr>
      <td><div align="right">֩��ͳ�������أ�</div></td>
      <td><?php echo select("openspider",array('1'=>'�ر�','5'=>'����'),$mydecms['openspider']); ?></td>
    </tr>
    <tr>
      <td><div align="right">����ͳ�������룺</div></td>
      <td><textarea name="tongji" id="tongji" class="input" style="width:98%; height:50px;"><?php echo $mydecms['tongji'];?></textarea></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="�޸�����" />
          <input type="reset" name="button2" id="button2" value="����" />
        </div></td>
    </tr>
  </table>
</form>
