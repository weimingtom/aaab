<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<form action="?action=<?php echo $pageinfo['act'];?>" method="post" id="form" name="form">
  <table class="table2" width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <thead>
      <tr>
        <th colspan="2"><div align="center">修改管理帐号及密码</div></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="120"><div align="right">旧用户名：</div></td>
        <td>
          <input name="old_user" class="input" type="text" id="old_user" value="" size="25" />          </td>
      </tr>
      <tr>
        <td><div align="right">新用户名：</div></td>
        <td><input name="new_user" class="input" type="text" id="new_user" value="" size="25" /></td>
      </tr>
      <tr>
        <td><div align="right">确认用户：</div></td>
        <td><input name="sub_user" class="input" type="text" id="sub_user" value="" size="25" /></td>
      </tr>
      <tr>
        <td><div align="right">旧密码：</div></td>
        <td><input name="old_pass" class="input" type="password" id="old_pass" value="" size="27" /></td>
      </tr>
      <tr>
        <td><div align="right">新密码：</div></td>
        <td><input name="new_pass" class="input" type="password" id="new_pass" value="" size="27" /></td>
      </tr>
      <tr>
        <td><div align="right">确认密码：</div></td>
        <td><input name="sub_pass" class="input" type="password" id="sub_pass" value="" size="27" /></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="submit" id="submit" value="确定修改" />
        </div></td>
      </tr>
    </tfoot>
  </table>
</form>
