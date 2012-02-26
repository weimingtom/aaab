<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<? include $this->gettpl('header');?>
<div id="war">
	<div id="main">
		<div class="border">
			<div class="status">
				<strong><a href="index-index.htm" title="小说">小说</a></strong> /
				<strong><a href="index-list-<?=$parentCategory['id']?>.htm"><?=$parentCategory['cate_name']?></a></strong> /
				<strong><a href="index-list-<?=$category['parent_id']?>-<?=$category['id']?>.htm"><?=$category['cate_name']?></a></strong> /
				<?=$novel['novelName']?>全文阅读
			</div>
			<div id="content" class="blue_bg">
				<h1><a href="/partlist/101678/"><?=$novel['novelName']?></a></h1>
				<div class="novel_msg">
					<span>作者：<?=$novel['author']?></span>
				</div>
				<ul class="cookie">
					<li class="bg_Color" id="ColorSetting">
						<em>背景色：</em><b>
						<a href="javascript:bgColor('red','content')"><i style="background:#CD352C"></i></a>
						<a href="javascript:bgColor('yellow','content')"><i style="background:#FFF99D"></i></a>
						<a href="javascript:bgColor('green','content')"><i style="background:#82B857"></i></a>
						<a href="javascript:bgColor('pink','content')"><i style="background:#DC9AD6"></i></a>
						<a href="javascript:bgColor('gray','content')"><i style="background:#eee"></i></a></b>
					</li>
					<li class="font_Color">
						<em>字体颜色：</em>
						<a href="javascript:fontColor('blue','article')"><i style=" background:#3333FF"></i></a> 
						<a href="javascript:fontColor('yellow','article')"><i style=" background:#ff6600"></i></a>
						<a href="javascript:fontColor('green','article')"><i style=" background:#009900"></i></a>
						<a href="javascript:fontColor('pink','article')"><i style=" background:#e000d8"></i></a>
						<a href="javascript:fontColor('black','article')"><i style=" background:#000000"></i></a>
					</li>
					<li class="font_Size">
						<em>字号：</em><b>
						<A href="javascript:changeSize('small','article')">小</A>
						<A href="javascript:changeSize('middle','article')">中</A>
						<A href="javascript:changeSize('large','article')">大</A></b>
					</li>
					<li class="initial">
						<a href="javascript:void(0);" onclick="default_style();">恢复默认</a>
					</li>
				</ul>
				<div id="article">
					<h2><?=$content['title']?></h2><?=$content['contentText']?>
				</div>
				<p class="shop">
					<a href="javascript:void(0);" class="green" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage(document.URL);">设为主页</a> | 
					<a href="javascript:void(0);" class="green" onclick="window.external.addFavorite(location.href,document.title);" id="add_favorite">加为收藏</a> 
					<span id="favorite_msg" class="favorite_msg"></span>
				</p>
				<div class="menupage">
					<? if($prevContent) { ?>
					<a href="<?=GODHOUSE_DOMAIN_WWW?>index-content-<?=$prevContent['contentId']?>.htm" class="Pre" id="prev_page" title="<?=$prevContent['title']?>">上一页</a>	
					<? } ?>
					<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$content['novelId']?>.htm" class="Ca" id="partlist" title="<?=$novel['novelName']?>">目录</a>
					<? if($nextContent) { ?>
					<a href="<?=GODHOUSE_DOMAIN_WWW?>index-content-<?=$nextContent['contentId']?>.htm" class="Next" id="next_page" title="<?=$nextContent['title']?>">下一页</a>
					<? } ?>
				</div>	
				<div style="display:none" class="menupage">快捷键使用：上一页&ldquo;&larr;&rdquo;，下一页&ldquo;&rarr;&rdquo;，目录页&ldquo;ctrl+backspace&rdquo;。</div>
			</div>
			<div style="display:none;" class="recommend">共<em id="ccl"></em>人推荐《<a href="###"><?=$novel['novelName']?></a>》
				<label>
					<input id="add_recommend" value="我也来投票推荐" type="button" />
					<span id="recommend_msg" class="favorite_msg"></span>
				</label>
			</div>
		</div>
		<div class="other_novel" id="other_fav_books" style="display:none">
			<h2 class="Title">喜欢《<?=$novel['novelName']?>》的人还喜欢</h2>
			<ul class="content" id="other_fav_book_list"></ul>
		</div>
		<div class="pl" id="ztbox" style="display:none"></div>
	    	<div class="pl" id="ztbox2" style="display:none"></div>
	</div>	
	<div id="Sid">
		<div class="Clumn1">
			<div class="S_Title"><h2>章节快速切换</h2></div>
			<ul class="Cont">
				<? foreach((array)$contents as $k => $v) {?>
				<li style="<? if($v['contentId']==$content['contentId']) { ?>background:#EEEEEE;<? } ?>padding-left:10px;"><span class="green"> 第<? echo ++$k?>章节</span> <a href="index-content-<?=$v['contentId']?>.htm"><?=$v['title']?></a></li>
				<?}?>
			</ul>
		</div>
	</div>
</div>
<script>
function bgColor(name){
	$('article').style.background = name;
}
function fontColor(name){
	$('article').style.color = name;
}
function changeSize(name){
	if(name == 'small') {
		name=12;
	} else if(name == 'middle') {
		name=14;
	} else if(name == 'large') {
		name=16;
	}
	$('article').style.fontSize = name+'px';
}
function default_style() {
	$('article').style.background = "#FFFFFF";
	$('article').style.color = "#000000";
	$('article').style.fontSize = '14px';
}
</script>
<? include $this->gettpl('footer');?>