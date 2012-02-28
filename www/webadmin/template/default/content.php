<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><?php echo $article['title'];?> - <?php echo $mydecms['webname'];?> - Powered by MydeCms V1.0</title>
<?php
if($article['keywords']){
?>
<meta name="keywords" content="<?php echo $article['keywords']; ?>" />
<?php
}
if($article['description']){
?>
<meta name="description" content="<?php echo $article['description']; ?>" />
<?php
}
?>
<meta name="generator" content="MydeCms V1.0" />
<meta name="copyright" content="www.MydeCms.com" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $mydecms['webname'];?>" href="<?php echo $mydecms['weburl'];?>/rss.php" />
<link rel="stylesheet" type="text/css" href="<?php echo $mydecms['weburl'];?>/<?php echo $mydecms['moban'];?>css/mydecms.css" />
<script type="text/javascript" src="<?php echo $mydecms['weburl'];?>/<?php echo $mydecms['moban'];?>js/jquery.min.js"></script>
<script type="text/javascript">
function TongJiCount(url,id){
	$.post(url+"count.php?id="+id, function(data) {
	  $("#TongJiCount").html(data);
	});
}
</script>
</head>
<body>
<div id="mydecms">
<?php $tpl -> display('header'); ?>
<?php $tpl -> display('nav'); ?>
  <div id="location">
    当前位置：<a href="<?php echo $mydecms['weburl'];?>">网站首页</a> → <?php if($article['class_name']<>""){?><a href="<?php echo $mydecms['weburl'];?>/<?php echo $article['class_dir']<>"" ? $article['class_dir'] :'class.php?id='.$article['dafenglei'] ;?>"><?php echo $article['class_name'];?></a> → <?php } ?><a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($article['date'])).'/'.$article['id'].'.html';?>"><?php echo $article['title'];?></a> <span class="rss"><a href="<?php echo $mydecms['weburl']; ?>/rss.php"><img src="<?php echo $mydecms['weburl'];?>/<?php echo $mydecms['moban'];?>images/rss.png" /></a></span>
    <div id="location-search">
      <form action="<?php echo $mydecms['weburl'];?>/search.php" method="get" target="_parent">
        <input type="text" name="key" class="input" value="请输入关键字" onFocus="if (this.value =='请输入关键字'){this.value =''}" onBlur="if (this.value ==''){this.value='请输入关键字'}" />
        <input type="submit" id="submit" value="搜索" />
      </form>
    </div>
  </div>
  <div id="mid">
    <div id="left">
      <div id="content">
        <h1 class="content-title"><?php echo $article['title'];?></h1>
        <div class="content-word">
        <?php echo $mydecms['webad']['5'] <> "" ? '      <div class="left_content_ad top10">'.$mydecms['webad']['5'].'</div>':'';?>
          <?php echo $article['content'];?>
          <ul class="content-next">
            <li>【文章标题】：<?php echo $article['title'];?></li>
            <li>【发布日期】：<span style="margin-right:20px;"><?php echo date("Y-m-d",strtotime($article['date']));?></span> 【查看：<span class="red" id="TongJiCount">100</span> <script type="text/javascript">TongJiCount('<?php echo $mydecms['weburl'];?>/',<?php echo $article['id'];?>);</script>次】</li>
            <li>【TAG 标签】：<?php 
			$tag = $article['tag'];
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
				echo '<span class="red">无</span>';
			}
			?></li>
            <li>【文章地址】：<a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($article['date'])).'/'.$article['id'].'.html';?>"><?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($article['date'])).'/'.$article['id'].'.html';?></a></li>
<?php
$next = false;
  $sqll  = "select `id`,`title`,`type`,`date` from `-table-article` where `type`=5 and `id`<".$article['id']." order by `id` desc limit 0,1";
  $query = $mysql -> query($sqll);
  while($row = $mysql -> fetch_array($query)){
  $next=true;
?>
            <li>【上 一 篇】：<a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html';?>"><?php echo  $row['title'];?></a></li>
<?php
}
if($next == false) echo '            <li>【上一篇】：<font color="red">没有了！</font></li>';
$next = false;
  $sqll  = "select `id`,`title`,`type`,`date` from `-table-article` where `type`=5 and `id`>".$article['id']." order by `id` asc limit 0,1";
  $query = $mysql -> query($sqll);
  while($row = $mysql -> fetch_array($query)){
  $next=true;
?>
            <li>【下 一 篇】：<a href="<?php echo $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html';?>"><?php echo  $row['title'];?></a></li>
<?php 
}
if($next == false) echo '            <li>【下一篇】：<font color="red">没有了！</font></li>';
?>
          </ul>
        </div>
      </div>
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