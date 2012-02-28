<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div class="nav">
  <ul>
    <li><a href="?action=add">添加友情链接</a></li>
    <li><a href="?action=&page=1&typeid=">全部链接</a></li>
    <li><a href="?action=&page=1&typeid=5">首页链接</a></li>
    <li><a href="?action=&page=1&typeid=10">内页链接</a></li>
    <li><a href="?action=&page=1&typeid=0">首页和内页都不显示的链接</a></li>
  </ul>
  <div class="clear"></div>
</div>
<form action="" method="post" id="form" name="form">
  <table class="table" width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <thead>
      <tr>
        <th width="35"><div align="center">ID</div></th>
        <th width="200"><div align="center">链接文字</div></th>
        <th><div align="center">链接地址</div></th>
        <th width="160"><div align="center">链接属性</div></th>
        <th width="140"><div align="center">target属性</div></th>
        <th width="50"><div align="center">排序</div></th>
        <th width="42"><div align="center">操作</div></th>
      </tr>
    </thead>
    <?php while($row = $mysql -> fetch_array($result)){ ?>
    <tr onmouseover="this.style.backgroundColor='#EEF6FB';" onmouseout="this.style.backgroundColor='';">
      <td><div align="center">
          <input type="checkbox" name="id[]" id="id" value="<?php  echo $row['id']; ?>"> <input type="hidden" name="idd[]" value="<?php  echo $row['id']; ?>" />
        </div></td>
      <td><a href="?action=edit&amp;id=<?php  echo $row['id']; ?>">
          <?php  echo $row['name']; ?>
          </a></td>
      <td><div align="left">
          <?php  echo $row['url']; ?>
        </div></td>
      <td><div align="center">
          <?php echo select("type[]",array('5'=>'----首页显示----','10'=>'----内页显示----','15'=>'首页内页都显示','0'=>'----都不显示----'),$row['type']); ?>
        </div></td>
      <td><div align="center">
          <?php echo select("target[]",array('NULL'=>'当前窗口','_blank'=>'_blank 新窗口','_parent'=>'_parent 父窗口'),$row['target']); ?>
        </div></td>
      <td><div align="center">
          <input name="sort[]" type="text" id="sort[]" size="4" class="input" value="<?php  echo $row['sort']; ?>" />
        </div></td>
      <td><div align="center"><a href="?action=edit&amp;id=<?php  echo $row['id']; ?>">修改</a></div></td>
    </tr>
    <?php } ?>
    <tr class="pagetr">
      <td colspan="7"><?php echo $pages -> myde_write(); ?></td>
    </tr>
    <tr>
      <td colspan="7"><input type="checkbox" id="chkAll" onclick="CheckAll(this.form)" value="checkbox">
        全选
        <input type="button" onclick="del(this.form,false,'?action=del');" value="删除" /> <input type="button" onclick="act_submit(this.form,true,'?action=piliang<?php echo "&page=".$page;?>&typeid=<?php echo $typeid;?>');" value="批量修改" /> </td>
    </tr>
  </table>
</form>
