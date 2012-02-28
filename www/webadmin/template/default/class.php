<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><?php echo $dafenglei['title'] <>"" ? $dafenglei['title'] :$dafenglei['name'];?> - Powered by MydeCms V1.0</title>
<?php
if($dafenglei['keywords']){
?>
<meta name="keywords" content="<?php echo $dafenglei['keywords']; ?>" />
<?php
}
if($dafenglei['description']){
?>
<meta name="description" content="<?php echo $dafenglei['description']; ?>" />
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
    当前位置：<a href="<?php echo $mydecms['weburl'];?>">网站首页</a> → <a href="<?php echo $mydecms['weburl']; ?>/<?php echo $dafenglei['dir'] <> "" ? $dafenglei['dir'] : 'class.php?id='.$dafenglei['id'];?>"><?php echo $dafenglei['name'];?></a> <span class="rss"><a href="<?php echo $mydecms['weburl']; ?>/rss.php?id=<?php echo $dafenglei['id'];?>"><img src="<?php echo $mydecms['weburl'];?>/<?php echo $mydecms['moban'];?>images/rss.png" /></a></span>
    <div id="location-search">
      <form action="<?php echo $mydecms['weburl'];?>/search.php" method="get" target="_parent">
        <input type="text" name="key" class="input" value="请输入关键字" onFocus="if (this.value =='请输入关键字'){this.value =''}" onBlur="if (this.value ==''){this.value='请输入关键字'}" />
        <input type="submit" id="submit" value="搜索" />
      </form>
    </div>
  </div>
  <div id="mid">
    <div id="left">
      <div id="list">
      <h1><?php echo $dafenglei['name'];?></h1>
          <?php
$topid = "";
if($page == 1){
	$sqll  = "select `id`,`title`,`type`,`date`,`top` from `-table-article` where `type`=5 and `top`=10 order by `sort` asc, `id` desc";
	$query = $mysql -> query($sqll);
	while($row = $mysql -> fetch_array($query)){
		$topid = $topid <> "" ? $topid.",".$row['id'] : $row['id'];
?>
          <h2><span class="top_article" title="总置顶文章"></span> <a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html';?>"><?php echo  $row['title'];?></a></h2>
          <?php } ?>
          <?php
	$sql  = "select `id`,`title`,`type`,`date`,`top` from `-table-article` where (`type`=5 and `top`=5)".($topid <>"" ? "and `id` not in (".$topid.")":"")." order by `sort` asc, `id` desc";
	$query = $mysql -> query($sql);
	while($row = $mysql -> fetch_array($query)){
	$topid = $topid<>"" ? $topid.",".$row['id'] : $row['id'];
?>
          <h2><span class="topic_top" title="分类置顶文章"></span> <a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html';?>"><?php echo  $row['title'];?></a></h2>
          <?php
	}
}
?>
<dl class="clear">
<?php
$sql  = "select `id`,`title`,`type`,`date`,`dafenglei`,`content`,`tag` from `-table-article` where `type`=5 and `dafenglei` in (".$dafenglei['idstr'].") ";
if($topid <>"") $sql  .= " and `id` not in (".$topid.") ";
$pages = new PageClass($mysql -> num_rows($mysql -> query($sql)),10,$page,$mydecms['weburl'].'/class.php?id='.$dafenglei['id'].'&page={page}',$mydecms['weburl'].'/'.$dafenglei['dir']);
$sql  .= " order by `id` Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
?>
          <dt><a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html';?>"><?php echo  $row['title'];?></a></dt>
          <dd>
            <p><?php echo  str_len(strip_tags($row['content']),200,2);?></p>
            <div>所属分类：<a href="<?php echo $mydecms['weburl'];?>/<?php echo $class_arr[$row['dafenglei']][4] <>"" ? $class_arr[$row['dafenglei']][4] : 'class.php?id='.$row['dafenglei'];?>"><?php echo $class_arr[$row['dafenglei']][1];?></a> 发布日期：<?php echo date("Y-m-d",strtotime($row['date']));?> TAG标签：
              <?php
        $tag = $row['tag'];
			if($tag<>""){
				$tagarr = explode(",",$tag);
				$count = count($tagarr);
				if($count>1){
					foreach($tagarr as $v){
						echo '<a href="'.$mydecms['weburl'].'/search.php?key='.urlencode($v).'">'.$v.'</a> ';	
					}
				}else{
					echo '<a href="'.$mydecms['weburl'].'/search.php?key='.urlencode($tag).'">'.$tag.'</a> ';	
				}
			}else{
				echo '无';
			}
		?>
            </div>
          </dd>
          <?php } ?>
        </dl>
        <?php echo $pages -> myde_write();?> </div>
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
    <?php $tpl -> display('link'); ?>
    </div>
  <?php $tpl -> display('footer'); ?>
</div>
</body>
</html>
