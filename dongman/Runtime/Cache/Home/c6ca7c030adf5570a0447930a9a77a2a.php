<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($title); ?></title>
<meta name="robots" content="all" />
<meta name="keywords" content="<?php echo ($keywords); ?>">
<meta name="description" content="<?php echo ($description); ?>">
<meta name="Copyright" content="版权信息" />
<meta name="revisit-after" content="1 days" />
<?php echo ($css); ?>
</head>

<body>
<?php $vod_news=$pplist->ppvod('num:46;order:vod_addtime desc');
$vod_stars=$pplist->ppvod('num:20;order:vod_stars desc,vod_hits desc'); ?>

<div id="page_main">
<div id="page_container">

	<!--头部开始-->
<div id="page_header">
    	<div class="newlogobox" title="九尾狐大陆">&nbsp;</div>
        <div class="list_big_img">&nbsp;</div>
     

		
    <!--菜单开始-->
<div id="menu" style="width: 650px; float:right; margin-top:30px; margin-bottom:15px;">
    	<ul>
        	<li class="here"><a href="#">首页<em>HOMEPAGE</em></a></li>
            <li><a href="#">大陆社区<em>MENU TEXT</em></a></li>
            <li><a href="#">动漫下载<em>MENU TEXT</em></a></li>
            <li><a href="#">新番连载<em>MENU TEXT</em></a></li>
            <li><a href="#">动漫情报<em>MENU TEXT</em></a></li>
            <li><a href="#">COSPLAY<em>MENU TEXT</em></a></li>
        </ul>
</div>

<div class="searchbox_list" style=" margin-bottom:5px;">
        
           <form name="form1" method="post" action="<?php echo ($root); ?>index.php?s=vod-search" class="search_form">
    	<input type="text" name="id" value="海贼王" onclick="this.value = ''" maxLength="26" class="input" />
    	<button type="submit" value="&nbsp;" class="buttonstyle_list" id="searchbutton"/></button></form>
         

        </div>
	
    </div>
    

    <!--菜单结束-->
    <!--头部结束-->
    <!--导航开始-->
	<div id="nav">
	    <div class="nav_top w980">
		   <div class="nav_menu w980">
			   <ul>
				   <li><a href="#">首页</a></li>
				   <li><a href="#">日本动画片</a></li>
				   <li><a href="#">国产动画片</a></li>
				   <li><a href="#">欧美动画片</a></li>
				   <li><a href="#">TV版</a></li>
				   <li><a href="#">OVA版</a></li>
				   <li><a href="#">剧场版</a></li>
				   <li><a href="#">动画片大全</a></li>
				   <li><a href="#">完结连载</a></li>
				   <li><a href="#">排行榜</a></li>
				   <li><a href="#">最新更新</a></li>
			 </ul>
		   	</div>
		   <div class="nav_letter w980">
			   <img src="images/anime_15.jpg" />
			   <a href="/?s=vod-search-id-A-x-letter.html">A</a>
			   <a href="/?s=vod-search-id-B-x-letter.html">B</a>
			   <a href="/?s=vod-search-id-C-x-letter.html">C</a>
			   <a href="/?s=vod-search-id-D-x-letter.html">D</a>
			   <a href="/?s=vod-search-id-E-x-letter.html">E</a>
			   <a href="/?s=vod-search-id-F-x-letter.html">F</a>
			   <a href="/?s=vod-search-id-G-x-letter.html">G</a>
			   <a href="/?s=vod-search-id-H-x-letter.html">H</a>
			   <a href="/?s=vod-search-id-I-x-letter.html">I</a>
			   <a href="/?s=vod-search-id-J-x-letter.html">J</a>
			   <a href="/?s=vod-search-id-K-x-letter.html">K</a>
			   <a href="/?s=vod-search-id-L-x-letter.html">L</a>
			   <a href="/?s=vod-search-id-M-x-letter.html">M</a>
			   <a href="/?s=vod-search-id-N-x-letter.html">N</a>
			   <a href="/?s=vod-search-id-O-x-letter.html">O</a>
			   <a href="/?s=vod-search-id-P-x-letter.html">P</a>
			   <a href="/?s=vod-search-id-Q-x-letter.html">Q</a>
			   <a href="/?s=vod-search-id-R-x-letter.html">R</a>
			   <a href="/?s=vod-search-id-S-x-letter.html">S</a>
			   <a href="/?s=vod-search-id-T-x-letter.html">T</a>
			   <a href="/?s=vod-search-id-U-x-letter.html">U</a>
			   <a href="/?s=vod-search-id-V-x-letter.html">V</a>
			   <a href="/?s=vod-search-id-W-x-letter.html">W</a>
			   <a href="/?s=vod-search-id-X-x-letter.html">X</a>
			   <a href="/?s=vod-search-id-Y-x-letter.html">Y</a>
			   <a href="/?s=vod-search-id-Z-x-letter.html">Z</a>
		   </div>
		</div>
	<!--按时间浏览开始-->  
		<div class="nav_type">
		<dl style="width:373px;"><dt><strong>剧情</strong></dt><dd><a href="/donghua/rexue/">热血</a> <a href="/donghua/jizhan/">机战</a> <a href="/donghua/yundong/">运动</a> <a href="/donghua/tuili/">推理</a> <a href="/donghua/maoxian/">冒险</a> <a href="/donghua/bl/">耽美</a> <a href="/donghua/gaoxiao/">搞笑</a> <a href="/donghua/war/">战争</a> <a href="/donghua/shenmo/">神魔</a> <a href="/donghua/renzhe/">忍者</a> <a href="/donghua/jinji/">竞技</a> <a href="/donghua/xuanyi/">悬疑</a> <a href="/donghua/shehui/">社会</a> <a href="/donghua/loli/">萝莉</a> <a href="/donghua/lianai/">爱情</a> <a href="/donghua/chongwu/">宠物</a> <a href="/donghua/xiaoyuan/">校园</a> <a href="/donghua/hougong/">后宫</a> <a href="/donghua/kehuang/">科幻</a> <a href="/donghua/shaonv/">少女</a></dd>
		</dl>
		<dl style="width:250px;">
		<dt><strong>年代</strong></dt><dd><a href="/donghua/y2012/">2012</a> <a href="/donghua/y2011/">2011</a> <a href="/donghua/y2010/">2010</a> <a href="/donghua/y2009/">2009</a> <a href="/donghua/y2008/">2008</a> <a href="/donghua/y2007/">2007</a> <a href="/donghua/y2006/">2006</a> <a href="/donghua/y2005/">2005</a> <a href="/donghua/y2004/">2004</a> <a href="/donghua/y2003/">2003</a> <a href="/donghua/y2002/">2002</a> <!--<a href="/donghua/y2001/">2001</a>--> <a href="/donghua/y2000/">以前</a></dd>
		</dl>
		<dl style="width:98px;"><dt><strong>对白</strong></dt><dd> <a href="/donghua/riyu/">日语</a> <a href="/donghua/guoyu/">国语</a> <a href="/donghua/yueyu/">粤语</a> <a href="/donghua/yingyu/">英语</a></dd>
		</dl>
		
		<dl style="width:166px;" class="none"><dt><strong>版本</strong></dt><dd> <a href="/donghua/tv/">TV版</a> <a href="/donghua/ova/">OVA版本</a> <a href="/donghua/juchang/">剧场版</a> <a href="/donghua/zhenren/">真人版</a> <a href="/donghua/tedian/">特典版</a> <a href="/donghua/xuanchuang/">宣传版</a></dd></dl></div>
	<!--按时间浏览开始-->  
	</div>
	<!--导航结束-->
    <div style=" line-height:10px;"></div>
    
    <!--内容开始-->
	<div class="content">
		<div class="content_left ">
			<div class="xiaobian_tj boder">
			<div class="dh_images"><img src="./images/anime_19.jpg"/></div>
			<div class="tj_mh">
				<ul>
                <?php if(is_array($vod_stars)): $i = 0; $__LIST__ = $vod_stars;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><img src="<?php echo (getpicurl($ppvod["vod_pic"])); ?>" /><a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (msubstr($ppvod["vod_name"],0,6)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			
			</div>
			</div>
			<!--广告-->
			<div class="gg_pic"><img src="./images/gg.jpg" width="716" /></div>
			<!--热门连载-->
			<div class="xiaobian_tj boder">
			<div class="dh_images"><img src="./images/anime_22.jpg"/></div>
			<div class="tj_mh">
				<ul>
					<li><img src="./images/tjmh.jpg" /><a href="#">海王五</a></li>
					<li><img src="./images/tjmh.jpg" />群众好</li>
					<li><img src="./images/tjmh.jpg" />群你好</li>
					<li><img src="./images/tjmh.jpg" />海王五</li>
					<li><img src="./images/tjmh.jpg" />群众好</li>
					<li><img src="./images/tjmh.jpg" />群你好</li>
					<li><img src="./images/tjmh.jpg" />海王五</li>
					<li><img src="./images/tjmh.jpg" />群众好</li>
					<li><img src="./images/tjmh.jpg" />群你好</li>
					<li><img src="./images/tjmh.jpg" />海王五</li>
					<li><img src="./images/tjmh.jpg" />群众好</li>
					<li><img src="./images/tjmh.jpg" />群你好</li>
					<li><img src="./images/tjmh.jpg" />海王五</li>
					<li><img src="./images/tjmh.jpg" />群众好</li>
					<li><img src="./images/tjmh.jpg" />群你好</li>
					<li><img src="./images/tjmh.jpg" />海王五</li>
					<li><img src="./images/tjmh.jpg" />群众好</li>
					<li><img src="./images/tjmh.jpg" />群你好</li>
						<li><img src="./images/tjmh.jpg" />群众好</li>
					<li><img src="./images/tjmh.jpg" />群你好</li>
				</ul>
			
			</div>
			</div>
			
		</div>
		
		<div class="content_right">
			<div class="update boder">
				 <div class="update_title">动画片更新时间周期目录</div>
				 <div class="update_date">
				 	<ul>
					<li class="first">今天</li><li>周一</li><li class="select_time">周二</li><li>周三</li><li>周四</li><li>周五</li><li class="last">周六</li>
					</ul>
				 </div>
				 <div class="update_info">
				 	<ul>
						<li><span><img src="./images/sj.jpg"/></span> <a href="#">爱杀宝贝</a></li>
						<li><span><img src="./images/sj.jpg"/></span> <a href="#">要听爸爸的话</a></li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 要听爸爸的话</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 要听爸爸的话</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 要听爸爸的话</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 要听爸爸的话</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 要听爸爸的话</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
						<li><span><img src="./images/sj.jpg"/></span> 要听爸爸的话</li>
						<li><span><img src="./images/sj.jpg"/></span> 爱杀宝贝</li>
					</ul>
				 </div>
			
			</div>
			
			<div style="height:10px;"></div>
			
			<div class="update boder">
				 <div class="update_new_title">最近更新动画片<span><a href="#">更多>></a></span></div> 
				 <div class="update_list">
				 	<ul>
                    <?php if(is_array($vod_news)): $i = 0; $__LIST__ = $vod_news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><li><span><img src="./images/sj.jpg"/></span> <a href="<?php echo ($ppvod["vod_url"]); ?>"><?php echo (getcolor(msubstr($ppvod["vod_name"],0,12),$ppvod['vod_color'])); ?></a><span class="time"><?php echo (getcolordate('m-d',$ppvod["vod_addtime"])); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
 					</ul>
				 </div>
			
			</div>
			
		
		</div>
	</div>
	<!--内容结束-->
	
<!--友情链接开始-->
<div id="friends">
	<div class="friend_title"><h1>　友情链接</h1><span>（欢迎和我们交换友情，要求收录正常，百度权重值7以上，PR欢迎和我们交换友情）</span></div>
	<div class="friend_text">
	<?php if(is_array($pplink)): $i = 0; $__LIST__ = $pplink;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ppvod): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($ppvod["link_url"]); ?>" target="_blank"><?php echo ($ppvod["link_name"]); ?></a> │<?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
<p class="thread2"><img src="http://www.kejixun.com/statics/images/dot.jpg"></p>
<div class="message">
邮箱：jwhdl@163.com
</div>
</div>

<!--友情链接结束-->


    <!--底部开始-->
    <div id="page_footer">
    <a href="#">关于大陆</a>｜<a href="#">大陆动态</a>｜<a href="#">合作伙伴</a>｜<a href="#">版权声明</a>｜<a href="#">友情链接</a>｜<a href="#">招聘信息</a>｜<a href="#">意见反馈</a>｜<a href="#">帮助中心</a>｜<a href="#">联系方式</a><br />
版权文字部分。。。
    </div>
    <!--底部结束-->





</div>
</div>

</body>
</html>