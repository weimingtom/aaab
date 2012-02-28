<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div id="top">
<div class="nav">
  <ul>
    <li><a href="?">全部记录</a></li>
    <li><a href="?zhizhu=Baiduspider">Baiduspider</a></li>
    <li><a href="?zhizhu=Googlebot">Googlebot</a></li>
    <li><a href="?zhizhu=sogou spider">sogou spider</a></li>
    <li><a href="?zhizhu=sosospider">sosospider</a></li>
    <li><a href="?zhizhu=Yahoobot">Yahoobot</a></li>
  </ul>
  <div class="clear"></div>
</div>
<form action="" method="post" id="form" name="form">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="table">
    <thead>
      <tr>
        <th width="31"><div align="center">ID</div></th>
        <th width="77"><div align="center">蜘蛛名称</div></th>
        <th><div align="center">访问地址</div></th>
        <th width="125"><div align="center">访问时间</div></th>
        <th width="110"><div align="center">访问IP</div></th>
      </tr>
    </thead>
<?php while($row = $mysql -> fetch_array($result)){ ?>
    <tr onmouseover="this.style.backgroundColor='#EEF6FB';" onmouseout="this.style.backgroundColor='';">
      <td><div align="center">
          <input type="checkbox" name="id[]" id="id" value="<?php echo $row['id'];?>">
        </div></td>
      <td><div align="center"><?php echo $row['spidername'];?></div></td>
      <td><div align="left"> <?php echo $row['url'];?> </div></td>
      <td><div align="center"> <?php echo $row['date'];?> </div></td>
      <td><div align="center"> <?php echo $row['ip'];?> </div></td>
    </tr>
<?php } ?>
    <tr class="pagetr">
      <td colspan="5"><?php echo $pages -> myde_write();?></td>
    </tr>
    <tr>
      <td colspan="5"><input type="checkbox" id="chkAll" onClick="CheckAll(this.form)" value="checkbox">
        全选
        <input type="button" onClick="del(this.form,false,'?action=del');" value="删除" />
        <input type="button" onClick="del(this.form,true,'?action=qingkong');" value="清空记录" /></td>
    </tr>
  </table>
</form>
