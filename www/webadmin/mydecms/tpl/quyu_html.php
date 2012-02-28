<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<form action="<?php echo $pageinfo['act'];?>" method="post">
    <table width="750" border="0" cellpadding="0" cellspacing="1" class="table2" align="center">
      <thead>
        <tr>
          <th colspan="2"><div align="center">将指定区域文章生成HTML</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">开始ID：</div></td>
        <td><input name="ks_id" type="text" class="input" id="ks_id" value="<?php  echo $ks_id <> "" ? $ks_id :'1'; ?>" size="20" /></td>
      </tr>
      <tr>
        <td><div align="right">结束ID：</div></td>
        <td><input name="js_id" type="text" class="input" id="js_id" value="<?php  echo $js_id <> "" ? $js_id :'100'; ?>" size="20" /></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="确定将指定区域文章生成HTML" />
           <input type="reset" name="button2" id="button2" value="重置" />
        </div></td>
      </tr>
    </table>
</form>