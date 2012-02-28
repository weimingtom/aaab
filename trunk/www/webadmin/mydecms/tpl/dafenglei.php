<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div class="nav">
  <ul>
    <li><a href="?action=add&page=<?php  echo $page; ?>&classid=<?php  echo $classid; ?>">添加分类列表</a></li>
  </ul>
  <div class="clear"></div>
</div>
<div><a href="?action=">顶级分类</a><?php dafenglei_location($classid,$classid); ?>
	 <?php echo $dafengle_str; ?></div>
<form action="" method="post" id="form" name="form">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table">
    <thead>
      <tr>
        <th width="33">ID</th>
        <th width="425">分类名称</th>
        <th width="266">url</th>
        <th width="253">模板文件</th>
        <th width="168">显示属性</th>
        <th width="84">子分类</th>
        <th width="66">排序</th>
        <th width="87">操作</th>
      </tr>
    </thead>
    <?php dafenglei_arr(0,$classid); ?>
    <tr>
      <td colspan="8" height="28"><?php echo $pages -> myde_write(); ?></td>
    </tr>
    <tr>
      <td colspan="8"><input type="checkbox" id="chkAll" onclick="CheckAll(this.form)" value="checkbox">
        全选
        <input type="button" onclick="del(this.form,false,'?action=del');" value="删除" />
        <input type="button" onclick="act_submit(this.form,false,'gotohtml.php?action=dafenglei');" value="更新所选分类目录" />
        <input type="button" onclick="act_submit(this.form,true,'gotohtml.php?action=dafengleiall');" value="更新所有分类目录" />
        <div class="red">特别注意：如果您对某个分类进行操作，那所属它的小分类及所属它的宝贝也会受操作的影响。比如说：如果你删除某个大分类，那所属它的小分类及所属它的宝贝也将会被删除！所以操作要慎重！</div>      </td>
    </tr>
  </table>
</form>
