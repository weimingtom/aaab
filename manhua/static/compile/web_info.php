<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8"> 
<title><?=$prArr['title']?> -- 人体艺术|人体艺术摄影|人体艺术婚纱照|欧美人体艺术|美女人体艺术|人体艺术图片|日本美女人体艺术--最好的人体艺术网站</title> 
<meta content="人体艺术,人体艺术摄影,人体艺术婚纱照,欧美人体艺术,美女人体艺术,人体艺术图片 日本美女人体艺术" name="Keywords">
<meta content="798人体艺术网提供了人体艺术摄影、人体艺术婚纱照、欧美人体艺术、美女人体艺术欣赏，人体艺术图片就是一种以人体为媒介，以认识美、探索美、和谐美为宗旨的一个自我发现和愉悦的过程和行为。日常为我们熟知的如舞蹈、绘画人体、艺术体操、健美操等都是人体艺术的范畴。" name="Description">
<LINK href="static/img/i/Command.css" type=text/css rel=stylesheet> 
<LINK media=all href="static/img/i/page.css" type=text/css rel=stylesheet>
<link href="static/img/webpage_common.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="static/js/jquery-1.3.2.min.js"></script>
<style type="text/css">
.pages{text-align:center}
</style>
</head> 
 
<body> 

<? include $this->gettpl('web_top');?>
 
 
<div id=b-map class="pageBox"><span>当前位置:</span><a href="/">首页</a> - <label class="selectMenu"></label> - <?=$prArr['title']?></div> 
<div id=b-show class="pageBox"> 
<div class=left> 
<div class="box_top"><div class="l"></div><div class="r"></div></div> 
<div class="box_inner"> 
<div class="JSadvbox"></div> 
<div class="title"><?=$prArr['title']?></div> 
<div class="sPage">更新时间：<?=$prArr['addtime']?> 作者：<?=$prArr['author']?><a href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title.substring(0,76))+'&iu='+encodeURIComponent(location.href)+'&fr=ien#nw=1','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" style="color:#000000;text-decoration:none;font-size:12px;font-weight:normal"><SPAN style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; FONT-SIZE: 12px; PADDING-BOTTOM: 0px; MARGIN-LEFT: 10px; CURSOR: pointer; PADDING-TOP: 5px"><IMG alt=添加到百度搜藏 src="static/img/i/fav1.jpg" align=absMiddle border=0> 添加到百度搜藏</SPAN></a></div> 
<div class="JSadvbox"></div> 
<div class=b> 
<div class=str> 
<? foreach((array)$mtArr as $val) {?>
<P align=center><IMG  src="http://www.rt798.com/getPhoto.php?url=<?=$val['imgaddress']?>" border=0></P>
<? } ?>
<p align='center' style="text-align:center; width:600px;">
<!-- STARTPAGE <?=$PageCount?> ENDPAGE -->
<?=$pageData?>
</p> 
</div> 
</div> 
<div class="JSadvbox"></div> 
 
<div class="JSadvbox">
<!-- ad Content -->
</div> 

</div> 
<div class="box_bottom"><div class="l"></div><div class="r"></div></div> 
</div> 
<div class=right> 
<div class="box_top"></div> 
<div class="box_inner"> 
<div class="box_title">本类推荐</div> 
<ul class="Neighbor"> 
<LI><span class="t1"></span><a href="apprt.php?a=info&xxid=<?=$tjArr['cateid']?>&mt=<?=$tjArr['id']?>" target="_blank"><img class='pic1' src='http://www.rt798.com/getPhoto.php?url=<?=$tjArr['smallpic']?>'  width='120' height='16' border='0' align="<?=$tjArr['title']?>"><?=$tjArr['title']?></a></LI> 
</ul> 
</div> 
<div class="box_bottom"></div> 
<div class="box_top"></div> 
<div class="box_inner"><div class="t">本类热门</div> 
<UL class="text"> 
<? foreach((array)$hotData as $val) {?>
<li>&nbsp;<a href="apprt.php?a=info&xxid=<?=$xxid?>&mt=<?=$val['id']?>" title="<?=$val['title']?>" target="_blank"><?=$val['title']?></a></li>
<? } ?>
</UL> 
</div> 
<div class="box_bottom"></div> 
</div> 
</div> 

<? include $this->gettpl('web_floor');?>

<script language="javascript">
$('.selectMenu').each(function(i){$(this).prepend($('#b-menu .on').find('a').text());});
</script>

</body> 
</html>