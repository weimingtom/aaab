<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div class="nav">
    <ul>
      <li><a href="?">返回广告列表</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php echo $pageinfo['act'];?>&typeid=<?php echo $typeid;?>" method="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table2" align="center">
      <thead>
        <tr>
          <th colspan="2"><div align="center"><?php  echo $pageinfo['subtitle']; ?>广告</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">广告名称：</div></td>
        <td><input name="name" type="text" class="input" id="name" value="<?php  echo $row['name']; ?>" style="width:35%;" /></td>
      </tr>
      <tr>
        <td><div align="right">备注：</div></td>
        <td><input name="beizhu" type="text" class="input" id="beizhu" value="<?php  echo $row['beizhu']; ?>" style="width:35%;" /> 用于说明广告相关信息</td>
      </tr>
      <tr>
        <td><div align="right">排序：</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="<?php echo $action=='add' ? '100' : $row['sort']; ?>" size="25" />
        数字越小，排得越前面</td>
      </tr>
      <tr>
        <td><div align="right">属性：</div></td>
        <td><?php echo select("type",array('5'=>'启用','0'=>'不启用'),$row['type']); ?></td>
      </tr>
      <tr>
      <td><div align="right">广告代码：</div></td>
      <td><textarea name="content" id="content" class="input" style="width:98%; height:100px;"><?php echo str_replace(array('+=+','-+-'),array('"',"'"), stripslashes($row['content']));?></textarea></td>
    </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>广告" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="重置" />
        </div></td>
      </tr>
    </table>
</form>