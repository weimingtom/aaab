<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div class="nav">
    <ul>
      <li><a href="?">返回内链列表</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php echo $pageinfo['act'];?>" method="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table2" align="center">
      <thead>
        <tr>
          <th colspan="2"><div align="center"><?php  echo $pageinfo['subtitle']; ?>内链</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">关键词：</div></td>
        <td><input name="name" type="text" class="input" id="name" value="<?php  echo $row['name']; ?>" style="width:80%;" /></td>
      </tr>
      <tr>
        <td><div align="right">地址：</div></td>
        <td><input name="url" type="text" class="input" id="url" value="<?php  echo $row['url']; ?>" style="width:80%;" /></td>
      </tr>
      <tr>
        <td><div align="right">target属性：</div></td>
        <td><?php echo select("target",array('NULL'=>'当前窗口','_blank'=>'_blank 新窗口','_parent'=>'_parent 父窗口'),$row['target']); ?></td>
      </tr>
      <tr>
        <td><div align="right">排序：</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="<?php echo $action=='add' ? '100' : $row['sort']; ?>" size="25" />
        数字越小，排得越前面</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>内链" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="重置" />
        </div></td>
      </tr>
    </table>
</form>