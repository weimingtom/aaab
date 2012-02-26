<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$header['title']?></title>
<meta name="Keywords" content="<?=$header['keyword']?>" />
<meta name="Description" content="<?=$header['description']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<? if($_GET['a']=='index') { ?>
<link rel="stylesheet" href="<?=GODHOUSE_DOMAIN_IMAGE?>css/lhl_base.css" />
<link rel="stylesheet" href="<?=GODHOUSE_DOMAIN_IMAGE?>css/lhl_fy.css" />
<? } else { ?>
<link rel="stylesheet" href="<?=GODHOUSE_DOMAIN_IMAGE?>css/fy_base.css" />
<link rel="stylesheet" href="<?=GODHOUSE_DOMAIN_IMAGE?>css/fy_cont.css" />
<link rel="stylesheet" href="<?=GODHOUSE_DOMAIN_IMAGE?>css/comment.css" />
<? } ?>
<link rel="stylesheet" href="<?=GODHOUSE_DOMAIN_IMAGE?>css/lhl_top.css" />
<script type="text/javascript" src="<?=GODHOUSE_DOMAIN_IMAGE?>js/common.js"></script>
</head>
<body>
<span id="append"></span>
<div class="top">
	<div class="topBlk1">
		<div id="pagetop2">
			<ul class="login"id="headManage">
				 <li><a href="<?=GODHOUSE_DOMAIN_WWW?>">免费小说网</a>就在木耳屋！</li>
			</ul>
			<div class="tB04">
				<ul style="padding-left:130px">
					<li><a href="javascript:void(0);" onclick="window.external.addFavorite('<?=GODHOUSE_DOMAIN_WWW?>','木耳屋小说网');" id="Collection">加为收藏</a></li>
					<li><a href="<?=GODHOUSE_DOMAIN_WWW?>" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?=GODHOUSE_DOMAIN_WWW?>');return(false);" id="HomePage" >设为主页</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div id="pagetop">
	<div class="logonav">
		<div class="logo">
			<a href="<?=GODHOUSE_DOMAIN_WWW?>" title="<?=GODHOUSE_SITENAME?>"><img src="images/logo.jpg" title="<?=GODHOUSE_SITENAME?>" alt="<?=GODHOUSE_SITENAME?>"/></a>
		</div>
		<div class="nav">
			
		</div>
	</div>
	<div class="tagdh boy">
		<div class="tagdh_02">
			<div class="left"><a title="木耳屋小说网首页" href="<?=GODHOUSE_DOMAIN_WWW?>">首页</a></div>
			<div class="center">
				<ul>
					<? foreach((array)$navCategorys as $v) {?>
					<li><a href="index-list-<?=$v['id']?>.htm"><?=$v['cate_name']?></a></li>
					<? } ?>
				</ul>
			</div>
		</div>
		<div class="tagdh_03">
			<form id="searcharticle" name="searcharticle" method="get" action="novel-search.htm" onsubmit="return searchSubmit();">
			    <div class="new_search" style="width:730px;">
					<div class="diyselect">
						<input type="button" value="小说" name="search_select" id="search_select" onclick="set_condit()"/>
						<input type="hidden" name="searchType" value="novelName" />
						<ul id="ss_item" style="display: none;">
							<!--<li id="allbook" class="diyselect_current">小说</li>-->
							<li id="bookname"><a href="javascript:void(0);" onclick="set_condit('novelName')">小说</a></li>
							<li id="author"><a href="javascript:void(0);" onclick="set_condit('author')">作者</a></li>
						</ul>
					</div>
			    	<input name="keywords" id="keywords2" value="输入关键字查找" size="28" onclick="this.focus();checkKeywords(this,1)" onblur="checkKeywords(this,0)" type="text"/>
			   	 	<input class="button1" name="button" id="button" value="搜 索" type="submit" onclick="return searchSubmit();"/>&nbsp;&nbsp;&nbsp;&nbsp;热门搜索：<a href="novel-search.htm?keywords=武侠&searchType=novelName" style="color:#FF0000;">武侠</a></span>
					<span id="hot_keywords" style="width:230px; overflow:hidden; margin-left:5px;"></span>	  	 	
				</div>
			</form>
			<div class="public" id="searchbar_bulletin"></div>
		</div>
	</div>
</div>