<?php
define('WWW.MYDECMS.COM',true);
include 'webadmin/include/config.inc.php';

/*******************************循环分类并加入到数组class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************循环分类并加入到数组*************************************/
/*
$tpl = new templateClass($mydecms['moban']);
$tpl -> assign(array('mysql','mydecms','tpl','class_arr','page'));
$tpl -> display('index');
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><?php echo $mydecms['webtitle']?></title>
<meta name="robots" content="all" />
<meta name="description" content="<?php echo $mydecms['webdescription']?>" />
<meta name="keywords" content="<?php echo $mydecms['webkeywords']?>" />
<meta name="Copyright" content="版权信息" />
<meta name="revisit-after" content="1 days" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/comic.css">
<script type="text/javascript" src="js/core_min.js"></script>
</head>

<body>

<div id="page_main">
<div id="page_container">

	<!--头部开始-->
<div id="page_header">
    	<div class="logobox" title="<?php echo $mydecms['webname']?>">&nbsp;</div>
        <div class="big_img">&nbsp;</div>
      <div class="topmenubox">
        <a href="#"><strong>artery</strong></a><a href="#">退出</a><a href="#">短消息</a><a href="#">统计</a><a href="#">系统设置</a><a href="#">帮助</a>
        </div>
<div class="searchbox">
        	<form action="" method="get">
              <input type="button" value="&nbsp;" class="buttonstyle" />
              <input name="" type="text" class="inputstyle"   value="●●●●●●" onFocus="if(this.value=='●●●●●●')this.value='';" onBlur="if(this.value=='')this.value='●●●●●●';" />
        </form>
        </div>
    </div>
    
    <!--菜单开始-->
<div id="menu">
    	<ul>
		<li class="here"><a href="<?php echo $mydecms['weburl']?>">首页<em>HOMEPAGE</em></a></li>
		<li><a href="#">大陆社区<em>MENU TEXT</em></a></li>
		<li><a href="#">动漫下载<em>MENU TEXT</em></a></li>
		<li><a href="#">新番连载<em>MENU TEXT</em></a></li>
		<li><a href="#">动漫情报<em>MENU TEXT</em></a></li>
		<li><a href="#">COSPLAY<em>MENU TEXT</em></a></li>
        </ul>
    </div>
    <!--菜单结束-->
    <!--头部结束-->
    
    
    <!--内容开始-->
<div id="content">
    
    	<!--左侧边栏开始-->
	<div class="left">
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
	</div>
        <!--左侧边栏结束-->
        
        <!--右侧内容开始-->
		<div class="right">
        
        <!--滚动大图开始-->
        <div id="Tu_commend">
          <h2 class="Tu_title"><span class="title"><img src="images/a_34.gif" width="81" height="5" /></span> <span class="more"><span id="TuList"></span></span></h2>
          <div class="Tu_box"><img src="images/a_35.gif" id="Tu_Left" alt="向左" onMouseDown="TU_GoUp()" onMouseUp="TU_StopUp()" onMouseOut="TU_StopUp()" ondragstart="return false" />
            <div id="TU_Cont">
              <div class="ScrTuCont">
                <div id="Tu_List1">
                <ul>
		<?php
		$news = $mysql->fetch_all("SELECT * FROM `-table-article` where dafenglei='1' ORDER BY id DESC LIMIT 5");
		foreach ($news as $v) { ?>
			<li><A title="<?php echo $v['title']?>" href="<?php echo $v['url']?>" target="_blank"><IMG src="<?php echo $v['img']?>" ></A></li>
		<?php }?>
                </ul>
                </div>
                <div id="Tu_List2"></div>
              </div>
            </div>
            <img src="images/a_35.gif" id="Tu_Right" alt="向右" onMouseDown="TU_GoDown()" onMouseUp="TU_StopDown()" onMouseOut="TU_StopDown()" ondragstart="return false" /></div>
        </div>
        <script src="js/11.js" type="text/javascript"></script>
        <!--滚动大图结束-->
        
        <!--滚动新闻开始-->
        <div id="topnewsbox">
        <div id="andyscroll">
        <div id="scrollmessage">
        <?php
	$news = $mysql->fetch_all("SELECT * FROM `-table-article` WHERE dafenglei='2' ORDER BY id DESC LIMIT 6");
	foreach ($news as $v) { ?>
          <div class="textboxlist">
            <div class="textbox">
            	<a href="<?php echo $v['url']?>" target="_blank"><img src="<?php echo $v['img']?>" /></a>
            	<a href="<?php echo $v['url']?>" target="_blank"><?php echo $v['title']?>...</a> <br />
		<?php echo $v['description']?> ... 
              </div>
          </div>
         <?php }?>
        </div>
      </div>
	<script src="js/22.js" type="text/javascript"></script>      
        </div>
        <!--滚动新闻结束-->
        
        <div id="index_bannerbox">
        	<a href="#"><img src="images/t10.gif" width="830" height="62" /></a>
        </div>
        
        <!--内容部分开始-->
        <div id="container">
        
        	<!--左侧部分开始-->
            <div id="left">
         <?php
	$news = $mysql->fetch_all("SELECT * FROM `-table-article` WHERE dafenglei='3' ORDER BY id DESC LIMIT 5");
	foreach ($news as $k=>$v) { ?>
	<div class="content">
		<div class="timebox"><?php echo substr($k+101, 1,2);?><em><?php echo substr($v['date'], 5, 5)?></em></div>
		<h1><a href="<?php echo $v['url']?>"><?php echo $v['title']?></a></h1>
		<p><?php echo $v['description']?></p>
		<div class="img"><img src="<?php echo $v['img']?>" width="611" height="242" /></div>
	</div>
	<?php }?>
	<div class="morearticle">
		<a href="#">查看更多...</a>
	</div>
            
            </div>
            <!--左侧部分结束-->
            
            <!--右侧部分开始-->
            <div id="right">
            
                <!--标签1开始-->
		<div class="nTab">
			<h1>动漫档期表：</h1>
			<div class="TabTitle">
				<ul id="myTab1">
					<li class="normal" onmouseover="nTabs(this, 0);">周日</li>
					<li class="normal" onmouseover="nTabs(this, 1);">周一</li>
					<li class="normal" onmouseover="nTabs(this, 2);">周二</li>
					<li class="normal" onmouseover="nTabs(this, 3);">周三</li>
					<li class="normal" onmouseover="nTabs(this, 4);">周四</li>
					<li class="normal" onmouseover="nTabs(this, 5);">周五</li>
					<li class="normal" onmouseover="nTabs(this, 6);">周六</li>
				</ul>
			</div>
			<div class="TabContent">
				<?php 
				$categorys = $mysql->fetch_all("SELECT * FROM -table-dafenglei WHERE classid='4' order by `sort` ASC");
				foreach($categorys as $k=>$category) {?>
				<div id="myTab1_Content<?php echo $k?>">
					<div class="text">
						<ul>
							<?php 
							$news = $mysql->fetch_all("SELECT * FROM -table-article WHERE dafenglei='{$category['id']}' ORDER BY id DESC LIMIT 10");
							foreach ($news as $v) {?>
								<li><a href="<?php echo $v['url']?>"><?php echo $v['title']?></a></li>
							<?php }?>
						</ul>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
                <!--标签1结束-->
                
                <!--标签2开始-->
		<div class="day" id="j-tab-b1">
			<div class="title">
				<h4 class="H402">漫画档期表：</h4>
			</div>
			<ul>
				<li style="background:url(images/t17.gif)">周日</li>
				<li style="background:url(images/t17.gif)" class="active">周一</li>
				<li style="background:url(images/t17.gif)" >周二</li>
				<li style="background:url(images/t17.gif)" >周三</li>
				<li style="background:url(images/t17.gif)" >周四</li>
				<li style="background:url(images/t17.gif)" >周五</li>
				<li style="background:url(images/t17.gif)" >周六</li>
			</ul>
			<?php 
				$categorys = $mysql->fetch_all("SELECT * FROM -table-dafenglei WHERE classid='4' order by `sort` ASC");
				foreach($categorys as $k=>$category) {
					$news = $mysql->fetch_all("SELECT * FROM -table-article WHERE dafenglei='{$category['id']}' ORDER BY id DESC LIMIT 10");	
					if ($news) {?>
					<ol>
						<?php 
						
						foreach ($news as $v) {?>
						<li><a href="<?php echo $v['url']?>" title="<?php echo $v['title']?>" target="_blank"><?php echo $v['title']?></a></li>
						<?php }?>
					</ol>
					<?php }
				}?>
		</div>
                <!--标签2结束-->
 		<script src="js/tab.js" type="text/javascript"></script>
                
                <div class="adbox"><img src="images/t20.gif" /></div>
                <div class="adbox"><img src="images/t20.gif" /></div>
                <div class="adbox"><img src="images/t21.gif" /></div>
                <div class="adbox"><img src="images/t21.gif" /></div>

                
            </div>
            <!--右侧部分结束-->
            
            
            
            
            
        </div>
        <!--内容部分结束-->
            
		</div>
        <!--右侧内容结束-->
        
    
	</div>
	<!--内容结束-->



    <!--底部开始-->
    <div id="page_footer">
    <a href="#">关于大陆</a>｜<a href="#">大陆动态</a>｜<a href="#">合作伙伴</a>｜<a href="#">版权声明</a>｜<a href="#">友情链接</a>｜<a href="#">招聘信息</a>｜<a href="#">意见反馈</a>｜<a href="#">帮助中心</a>｜<a href="#">联系方式</a><br />
版权文字部分。。。
    </div>
    <!--底部结束-->
</div>
</div>
<script type="text/javascript" src="js/chl_comic_min.js"></script>
</body>
</html>