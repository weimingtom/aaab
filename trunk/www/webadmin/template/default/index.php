<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><?php echo $mydecms['webtitle'];?></title>
<?php
if($mydecms['webkeywords']){
?>
<meta name="keywords" content="<?php echo $mydecms['webkeywords']; ?>" />
<?php
}
if($mydecms['webdescription']){
?>
<meta name="description" content="<?php echo $mydecms['webdescription']; ?>" />
<?php
}
?>
<meta name="generator" content="MydeCms V1.0" />
<meta name="copyright" content="www.MydeCms.com" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $mydecms['webname'];?>" href="<?php echo $mydecms['weburl'];?>/rss.php" />
<link rel="stylesheet" type="text/css" href="<?php echo $mydecms['weburl'];?>/<?php echo $mydecms['moban'];?>css/mydecms.css" />
</head>
<body>
<div id="mydecms">
  <?php $tpl -> display('header'); ?>
  <?php $tpl -> display('nav'); ?>
  <div id="location">
    当前位置：<?php echo $mydecms['webname'];?>首页 <span class="rss"><a href="<?php echo $mydecms['weburl']; ?>/rss.php"><img src="<?php echo $mydecms['weburl'];?>/<?php echo $mydecms['moban'];?>images/rss.png" /></a></span>
    <div id="location-search">
      <form action="<?php echo $mydecms['weburl'];?>/search.php" method="get" target="_parent">
        <input type="text" name="key" class="input" value="请输入关键字" onFocus="if (this.value =='请输入关键字'){this.value =''}" onBlur="if (this.value ==''){this.value='请输入关键字'}" />
        <input type="submit" id="submit" value="搜索" />
      </form>
    </div>
  </div>
  <div id="mid">
    <div id="left">
      <?php
$i=$j=1;
$sql  = "select `id`,`name`,`type`,`sort`,`dir` from `-table-dafenglei` where `type`=5 and `id` in($mydecms[classid]) order by find_in_set(id,'$mydecms[classid]')";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
if($i>2) $i=1;
?>
      <div class="left-box box330x250<?php echo $i==2 ? ' floatright':' floatleft';?>">
        <h3><a href="<?php echo $mydecms['weburl'];?>/<?php echo $row['dir']<>"" ? $row['dir'] : 'class.php?id='.$row['id'];?>" title="更多<?php echo $row['name'];?>文章"><?php echo $row['name'];?></a></h3>
        <ul>
          <?php
$sqll  = "select `id`,`title`,`content`,`type`,`date`,`dafenglei` from `-table-article` where `type`=5 and `dafenglei`=".$row['id']." order by `id` desc limit 0,10";
$query1 = $mysql -> query($sqll);
while($rs = $mysql -> fetch_array($query1)){
?>
          <li><span><?php echo date("Y-m",strtotime($rs['date']));?></span><a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($rs['date'])).'/'.$rs['id'].'.html';?>" title="<?php echo $rs['title']; ?>" target="_blank"><?php echo $rs['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php
if($j==2){
	if($mydecms['webad']['6']<>"") echo '<div class="left-box-ad-658x60">'.$mydecms['webad']['6'].'</div>';
}
if($j==4){
	if($mydecms['webad']['7']<>"") echo '<div class="left-box-ad-658x60">'.$mydecms['webad']['7'].'</div>';
}
if($i==2){
?>
      <div style="clear:both;border-top:10px solid #FFF;"></div>
      <?php
}
$i++;
$j++;
}
?>
    </div>
    <div id="right">
      <div class="right-list">
        <h3>分类导航</h3>
        <ul>
<?php
if(count($class_arr)>1){
	foreach($class_arr as $k => $v){
		if($v[5]=="5"){?>
  <li class="right-class-nav"><span class="rss"><a href="<?php echo $mydecms['weburl']; ?>/rss.php?id=<?php echo $v[0];?>"><img src="<?php echo $mydecms['weburl'];?>/<?php echo $mydecms['moban'];?>images/rss.png" /></a></span> <a href="<?php echo $mydecms['weburl']."/".($v[4]<>""?$v[4]:'class.php?id='.$v[0]);?>" title="<?php echo $v[1]; ?>"><?php echo $v[1]; ?></a></li>
          <?php
		  }
	}
} ?>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="right-list top10">
        <h3>最新文章</h3>
        <ul>
          <?php
$sql  = "select `id`,`title`,`content`,`type`,`date` from `-table-article` where `type`=5 order by `id` desc limit 0,10";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
?>
          <li><a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html';?>" title="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
<?php echo $mydecms['webad']['3'] <> "" ? '      <div class="right_ad top10">'.$mydecms['webad']['3'].'</div>':'';?>
      <div class="right-list top10">
        <h3>热门文章</h3>
        <ul>
          <?php
$sql  = "select `id`,`title`,`content`,`type`,`count`,`date` from `-table-article` where `type`=5 order by `count` desc,`id` desc limit 0,10";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
?>
          <li><a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html';?>" title="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php echo $mydecms['webad']['4'] <> "" ? '      <div class="right_ad top10">'.$mydecms['webad']['4'].'</div>':'';?>
    </div>
    <div class="clear"></div>
    <?php
$mydecms['LinkType'] = true;
$tpl -> display('link'); ?>
   </div>
  <?php $tpl -> display('footer'); ?>
</div>
</body>
</html>
