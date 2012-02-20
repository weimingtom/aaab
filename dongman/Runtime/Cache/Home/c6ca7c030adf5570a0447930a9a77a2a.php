<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($title); ?></title>
<meta name="keywords" content="<?php echo ($keywords); ?>">
<meta name="description" content="<?php echo ($description); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<?php echo ($css); ?>
</head>
<body>
<?php $vod_stars=$pplist->ppvod('num:10;order:vod_stars desc,vod_hits desc');
$vod_news=$pplist->ppvod('num:15;order:vod_addtime desc');
$vod_movie_pic=$pplist->ppvod('pid:1;num:2');
$vod_movie_con=$pplist->ppvod('pid:1;num:1');
$vod_movie_list=$pplist->ppvod('pid:1;num:2,30');
$vod_tv_pic=$pplist->ppvod('pid:2;num:2');
$vod_tv_con=$pplist->ppvod('pid:2;num:1');
$vod_tv_list=$pplist->ppvod('pid:2;num:2,30');
$vod_kt_pic=$pplist->ppvod('pid:3;num:2');
$vod_kt_con=$pplist->ppvod('pid:3;num:1');
$vod_kt_list=$pplist->ppvod('pid:3;num:2,30');
$vod_zy_pic=$pplist->ppvod('pid:4;num:2');
$vod_zy_con=$pplist->ppvod('pid:4;num:1');
$vod_zy_list=$pplist->ppvod('pid:4;num:2,30');
$vod_hot_80=$pplist->ppvod('num:80;order:vod_golder,vod_hits desc'); ?>
﻿<div class="top">
<div class="header">
	<div class="logo"><a href="<?php echo ($root); ?>" title="首页"></a></div>
	<div class="banner"><?php echo getadsurl('top46860');?></div>
</div>
<div class="search">
    <div class="nav">
       <ul>
        <li class="no_bg <?php if(($model)  ==  "index"): ?>on<?php endif; ?>"><a href="<?php echo ($root); ?>" title="<?php echo ($sitename); ?>">首 页</a></li>
        <?php if(is_array($ppmenu)): $i = 0; $__LIST__ = $ppmenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li class="<?php if(($ppvod["list_id"])  ==  $list_id): ?>no_bg on<?php endif; ?><?php if(($ppvod["list_id"])  ==  $list_pid): ?>no_bg on<?php endif; ?>"><a href="<?php echo ($ppvod["list_url"]); ?>"><?php echo ($ppvod["list_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
    </div>
    <div class="form"><?php if(($list_id)  ==  "20"): ?><form name="form1" method="post" action="<?php echo ($root); ?>index.php?s=news-search"><?php else: ?><form name="form1" method="post" action="<?php echo ($root); ?>index.php?s=vod-search"><?php endif; ?>
    	<input type="text" name="id" value="请在此处输入关键字" onclick="this.value = ''" maxLength="26" />
    	<button type="submit" id="searchbutton" name="Submit" >全站搜索</button></form>
    </div>
    <div class="up"><ul><?php $tag_list=$pplist->pptag('sid:1;num:8;'); ?>
    	<?php if(is_array($ppmenu)): $i = 0; $__LIST__ = $ppmenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><?php if($ppvod["list_id"] == 1): ?><li>电　影：<?php if(is_array($ppvod["son"])): $i = 0; $__LIST__ = $ppvod["son"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$son): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($son["list_url"]); ?>"><?php echo ($son["list_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></li>
        <?php elseif($ppvod["list_id"] == 2): ?>
        <li>电视剧：<?php if(is_array($ppvod["son"])): $i = 0; $__LIST__ = $ppvod["son"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$son): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($son["list_url"]); ?>"><?php echo ($son["list_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
        <li>标　签：<a href="<?php echo ppvodurl('ajax/show',false,false,false,true);?>">最近更新</a><?php if(is_array($tag_list)): $i = 0; $__LIST__ = $tag_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($ppvod["tag_url"]); ?>"><?php echo (msubstr($ppvod["tag_name"],0,4)); ?></a><?php endforeach; endif; else: echo "" ;endif; ?></li>
    </ul></div>
</div>
</div>
<div class="main">
<div class="bd clear"><?php echo getletter('vod');?></div>
<div class="bd960 clear"><?php echo getadsurl('top960');?></div>
<div class="hbd1">
    <div class="hbd1lf">
        <dl class="tit clear"><dt><a href="<?php echo ($root); ?>">本周热播，敬请欣赏</a></dt><dd class="f14">本站共有影片<?php echo getcount(0);?>部 今日更新<?php echo getcount(999);?>部</dd></dl>
        <div class="hbd11 ssv">
        <ul><?php if(is_array($vod_stars)): $i = 0; $__LIST__ = $vod_stars;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($ppvod["vod_url"]); ?>"><img src="<?php echo (getpicurl($ppvod["vod_pic"])); ?>" alt="<?php echo ($ppvod["vod_name"]); ?>" onerror="javascript:this.src='__PUBLIC__/images/nophoto.jpg';"/></a><p><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,6)); ?></a></p></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
        </div>
    </div>
    <div class="hbd1ri">
      	<dl class="tit"><dt><a href="<?php echo ppvodurl('ajax/show',false,false,false,true);?>">最近更新影片</a></dt><dd class="f14"><a href="<?php echo ppvodurl('ajax/show',false,false,false,true);?>">更多>></a></dd></dl>
        <div class="hbd12 ssv">
        <ul><?php if(is_array($vod_news)): $i = 0; $__LIST__ = $vod_news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><span><?php echo (getcolordate('m-d',$ppvod["vod_addtime"])); ?></span><a href="<?php echo ($ppvod["list_url"]); ?>" target="_blank"><?php echo ($ppvod["list_name"]); ?></a> <a href="<?php echo ($ppvod["vod_url"]); ?>" title="<?php echo ($ppvod["vod_name"]); ?>"><?php echo (getcolor(msubstr($ppvod["vod_name"],0,12),$ppvod['vod_color'])); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
        </div>
    </div>
</div>
<div class="hsa">
	<div class="hsalf">
		<dl class="tit"><dt><a href="<?php echo getlistname(1,'list_url');?>">国产动画片</a></dt><dd><a href="<?php echo getlistname(1,'list_url');?>"><img src="<?php echo ($tpl); ?>images/more.gif" /></a></dd></dl>
        <!--电影大片左侧2张-->
        <div class="hbd1a ssv">
        	<?php if(is_array($vod_movie_pic)): $i = 0; $__LIST__ = $vod_movie_pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dl><dt><a href="<?php echo ($ppvod["vod_url"]); ?>" title="<?php echo ($ppvod["vod_name"]); ?>"><img src="<?php echo (getpicurl($ppvod["vod_pic"])); ?>" alt="<?php echo ($ppvod["vod_name"]); ?>" onerror="javascript:this.src='__PUBLIC__/images/nophoto.jpg';"/></a></dt><dd><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,7)); ?></a></dd></dl><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="hbdces">
        	<!--电影大片一部简介-->
        	<dl class="hbdcet"><?php if(is_array($vod_movie_con)): $i = 0; $__LIST__ = $vod_movie_con;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dt class="ssv1"><?php echo (msubstr($ppvod["vod_name"],0,20)); ?></dt><dd class="ssv"><?php echo (msubstr(h($ppvod["vod_content"]),0,45)); ?> <a href="<?php echo ($ppvod["vod_url"]); ?>">>>查看详细</a></dd><?php endforeach; endif; else: echo "" ;endif; ?></dl>
        	<!--电影大片列表-->
        	<ul><?php if(is_array($vod_movie_list)): $i = 0; $__LIST__ = $vod_movie_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,8)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
        </div>
	</div>
    <div class="hsari">
        <dl class="tit"><dt><a href="<?php echo getlistname(2,'list_url');?>">日本动画片</a></dt><dd><a href="<?php echo getlistname(2,'list_url');?>"><img src="<?php echo ($tpl); ?>images/more.gif" /></a></dd></dl>
        <div class="hbd1a ssv">
            <?php if(is_array($vod_tv_pic)): $i = 0; $__LIST__ = $vod_tv_pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dl><dt><a href="<?php echo ($ppvod["vod_url"]); ?>" title="<?php echo ($ppvod["vod_name"]); ?>"><img src="<?php echo (getpicurl($ppvod["vod_pic"])); ?>" alt="<?php echo ($ppvod["vod_name"]); ?>" onerror="javascript:this.src='__PUBLIC__/images/nophoto.jpg';"/></a></dt><dd><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,7)); ?></a></dd></dl><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="hbdces">
            <dl class="hbdcet"><?php if(is_array($vod_tv_con)): $i = 0; $__LIST__ = $vod_tv_con;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dt class="ssv1"><?php echo (msubstr($ppvod["vod_name"],0,20)); ?></dt><dd class="ssv"><?php echo (msubstr(h($ppvod["vod_content"]),0,45)); ?> <a href="<?php echo ($ppvod["vod_url"]); ?>">>>查看详细</a></dd><?php endforeach; endif; else: echo "" ;endif; ?></dl>
        <ul><?php if(is_array($vod_tv_list)): $i = 0; $__LIST__ = $vod_tv_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($ppvod["vod_url"]); ?>" title="<?php echo ($ppvod["vod_name"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,8)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
        </div>
    </div>
</div>
<div class="hsa">
	<div class="hsalf">
		<dl class="tit"><dt><a href="<?php echo getlistname(3,'list_url');?>">欧美动画片</a></dt><dd><a href="<?php echo getlistname(3,'list_url');?>"><img src="<?php echo ($tpl); ?>images/more.gif" /></a></dd></dl>
		<div class="hbd1a ssv">
        	<?php if(is_array($vod_kt_pic)): $i = 0; $__LIST__ = $vod_kt_pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dl><dt><a href="<?php echo ($ppvod["vod_url"]); ?>"><img src="<?php echo (getpicurl($ppvod["vod_pic"])); ?>" alt="<?php echo ($ppvod["vod_name"]); ?>" onerror="javascript:this.src='__PUBLIC__/images/nophoto.jpg';"/></a></dt><dd><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,7)); ?></a></dd></dl><?php endforeach; endif; else: echo "" ;endif; ?>
    	</div>
		<div class="hbdces">
			<dl class="hbdcet"><?php if(is_array($vod_kt_con)): $i = 0; $__LIST__ = $vod_kt_con;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dt class="ssv1"><?php echo (msubstr($ppvod["vod_name"],0,20)); ?></dt><dd class="ssv"><?php echo (msubstr(h($ppvod["vod_content"]),0,45)); ?> <a href="<?php echo ($ppvod["vod_url"]); ?>" title="<?php echo ($ppvod["vod_name"]); ?>">>>查看详细</a></dd><?php endforeach; endif; else: echo "" ;endif; ?></dl>
			<ul><?php if(is_array($vod_kt_list)): $i = 0; $__LIST__ = $vod_kt_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,8)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
		</div>
	</div>
    <div class="hsari">
        <dl class="tit"><dt><a href="<?php echo getlistname(4,'list_url');?>">综艺娱乐</a></dt><dd><a href="<?php echo getlistname(4,'list_url');?>"><img src="<?php echo ($tpl); ?>images/more.gif" /></a></dd></dl>
        <div class="hbd1a ssv">
            <?php if(is_array($vod_zy_pic)): $i = 0; $__LIST__ = $vod_zy_pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dl><dt><a href="<?php echo ($ppvod["vod_url"]); ?>"><img src="<?php echo (getpicurl($ppvod["vod_pic"])); ?>" alt="<?php echo ($ppvod["vod_name"]); ?>" onerror="javascript:this.src='__PUBLIC__/images/nophoto.jpg';"/></a></dt>
            <dd><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,7)); ?></a></dd></dl><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="hbdces">
            <dl class="hbdcet"><?php if(is_array($vod_zy_con)): $i = 0; $__LIST__ = $vod_zy_con;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><dt class="ssv1"><?php echo (msubstr($ppvod["vod_name"],0,20)); ?></dt><dd class="ssv"><?php echo (msubstr(h($ppvod["vod_content"]),0,45)); ?> <a href="<?php echo ($ppvod["vod_url"]); ?>" title="<?php echo ($ppvod["vod_name"]); ?>">>>查看详细</a></dd><?php endforeach; endif; else: echo "" ;endif; ?></dl>
            <ul><?php if(is_array($vod_zy_list)): $i = 0; $__LIST__ = $vod_zy_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,8)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
        </div>
    </div>
</div>
<div class="hsae">
	<div class="hsae1">
        <dl class="tit"><dt>影片排行榜</dt><dd><img src="<?php echo ($tpl); ?>images/more.gif" /></dd></dl>
        <div class="hbae11 ssv"><ul>
            <?php if(is_array($vod_hot_80)): $i = 0; $__LIST__ = $vod_hot_80;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($ppvod["vod_url"]); ?>" title="<?php echo ($ppvod["vod_name"]); ?>" target="_blank"><?php echo ($ppvod["list_name"]); ?> <?php echo (msubstr($ppvod["vod_name"],0,12)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul></div>
	</div>
</div>
<div class="friend">
	<dl class="tit"><dt>友情链接</dt><dd></dd></dl>
	<ul><?php if(is_array($pplink)): $i = 0; $__LIST__ = $pplink;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($ppvod["link_url"]); ?>" target="_blank"><?php echo ($ppvod["link_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?></ul>
</div>
<div class="footer">
  <ul>
    <li>Copyright&copy;2008-2009 <a href="<?php echo ($siteurl); ?>"><?php echo ($siteurl); ?></a>. all rights reserved. <?php echo ($copyright); ?> <a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo ($icp); ?></a> <?php echo ($tongji); ?></li>
  </ul>
</div>
         
</div>
</body>
</html>