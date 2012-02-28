<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
    <div class="link left-box top10">
      <h3>友情链接</h3>
      <dl>
<?php
if(!$mydecms['LinkType']){
	$sql  = "select * from `-table-link` where `type`=10 or `type`=15 order by `sort` asc,`id` desc";
}else{
	$sql  = "select * from `-table-link` where `type`=5 or `type`=15 order by `sort` asc,`id` desc";
}
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
?>
        <dd><a href="<?php echo $row['url'];?>"<?php echo $row['target'] <> "NULL" && $row['target'] <> "" ? ' target="'.$row['target'].'"' :'';?>><?php echo $row['name'];?></a></dd>
<?php } ?>
      </dl>
    </div>