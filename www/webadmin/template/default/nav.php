<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
  <div id="nav">
    <ul class="menu">
      <li class="menu_left"></li>
<?php
$sql  = "select * from `-table-nav` where `type`=5 order by `sort` asc,`id` desc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
?>
        <li><a href="<?php echo $row['url'];?>"<?php echo $row['target'] <> "NULL" && $row['target'] <> "" ? ' target="'.$row['target'].'"' :'';?>><?php echo $row['name'];?></a></li>
<?php } ?>
      <li class="menu_right"></li>
    </ul>
    <div class="clear"></div>
  </div>