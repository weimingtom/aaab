<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div class="nav">
    <ul>
      <li><a href="?page=<?php echo $page."&classid=".$classid;?>">返回分类列表</a></li>
    </ul>
    <div class="clear"></div>
</div>
<form action="?action=<?php  echo $pageinfo['act']; ?>&page=<?php  echo $page; ?>&classid=<?php  echo $classid; ?>" method="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table2">
      <thead>
        <tr>
          <th colspan="2"><div align="center"><?php  echo $title; ?>分类</div></th>
        </tr>
      </thead>
      <tr>
        <td width="130"><div align="right">分类名称：</div></td>
        <td><input name="name" type="text" class="input" id="name" value="<?php  echo $row['name']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">标题：</div></td>
        <td><input name="title" type="text" class="input" id="title" value="<?php  echo $row['title']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">关键字：</div></td>
        <td><input name="keywords" type="text" class="input" id="keywords" value="<?php  echo $row['keywords']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">描述：</div></td>
        <td><input name="description" type="text" class="input" id="description" value="<?php  echo $row['description']; ?>" size="60" /></td>
      </tr>
      <tr>
        <td><div align="right">URL：</div></td>
        <td><input name="dir" type="text" class="input" id="dir" value="<?php  echo $row['dir']; ?>" size="60" />
        前面不要加“/”,最后面一定要加“/”</td>
      </tr>
      <tr>
        <td><div align="right">模板文件：</div></td>
        <td><input name="mobanname" type="text" class="input" id="mobanname" value="<?php  echo $row['mobanname']; ?>" size="60" />
        不用填写后缀，比如模板文件是“test.php”的话，只要填写“test”就行了</td>
      </tr>
      <tr>
        <td><div align="right">所属分类ID：</div></td>
        <td><select name="classid" id="classid">
        <option value="0">-----顶级分类-----</option>
        <?php if($row['classid']<>""){ ?>
        <?php dafenglei_select(0,0,$row['classid']); ?>
        <?php }else{ ?>
        <?php dafenglei_select(0,0,$classid); ?>
        <?php } ?>
        </select>
        </td>
      </tr>
      <tr>
        <td><div align="right">显示属性：</div></td>
        <td><select name="type">
          <?php seleted(array('1'=>'不显示','5'=>'显示'),$row['type']); ?>
        </select>
        是不在前台页面显示</td>
      </tr>
      <tr>
        <td><div align="right">排序：</div></td>
        <td><input name="sort" type="text" class="input" id="sort" value="<?php  echo $action=='add' ? '100' : $row['sort']; ?>" size="25" />
        数字越小，排得越前</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
          <input type="submit" name="button" id="button" value="<?php  echo $pageinfo['subtitle']; ?>分类" />
          <input type="hidden" id="id" name="id" value="<?php  echo $row['id']; ?>" />
           <input type="reset" name="button2" id="button2" value="重置" />
        </div></td>
      </tr>
    </table>
</form>