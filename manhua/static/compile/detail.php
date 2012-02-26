<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<? include $this->gettpl('header');?>
<div id="war">
	<div id="main">
		<div class="border">
			<div class="status">
				<strong><a href="index-index.htm" title="小说">小说</a></strong> / 
				<strong><a href="index-list-<?=$category['parent_id']?>.htm"><?=$parentCategory['cate_name']?></a></strong> /
				<strong><a href="index-list-<?=$category['parent_id']?>-<?=$category['id']?>.htm"><?=$category['cate_name']?></a></strong> /
				<?=$novel['novelName']?>全文阅读			
			</div>
			<div id="content" class="blue_bg">
				<h1><a href="###"><?=$novel['novelName']?></a></h1>
				<div class="Sum">
					<div class="novel_img">
						<? if($novel['picture']) { ?>
						<img src="<?=$novel['picture']?>" width="120" height="150" alt="<?=$novel['novelName']?>" />
						<? } else { ?>
						<img src="images/untitled.bmp" width="120" height="150" alt="<?=$novel['novelName']?>" />
						<? } ?>
					</div>
					<div class="undercover"><span style="display:inline-block;width:120px;height:20px;text-align:center; overflow:hidden;"></span></div>
					<ul>
						<li>
							<ul class="novel_msg">
								<li>作者：<?=$novel['author']?></li>
								<li></li>
								<li>加入时间：2010-09-15</li>
								<li></li>
								<li>类型：推理侦探</li><li>点击：<em id="vc"><?=$novel['views']?></em>次</li><li class="id_ycb"></li>
								<li></li>
							</ul>
						</li>
						<li id="zhuantie" style="display:none">
							一键转帖：
							<a id="kaixin" target="_blank" href="javascript:void(0);">开心</a>
							<a id="renren" target="_blank" href="javascript:void(0);">豆瓣</a>
							<a id="bai" target="_blank" href="javascript:void(0);">新浪</a>
						</li>
						<li class="button2 white">
							<? if($novelChapters && $novelChapters[0]['contents'] && $novelChapters[0]['contents'][0] && $novelChapters[0]['contents'][0]['contentId']) { ?>
							<a href="<?=GODHOUSE_DOMAIN_WWW?>index-content-<?=$novelChapters[0]['contents'][0]['contentId']?>.htm">点击阅读</a>
							<? } else { ?>
							<a href="javascript:void(0);" onclick="alert('对不起，暂无小说文章章节。')">点击阅读</a>
							<? } ?>
							<a href="javascript:void(0);" onclick="window.external.addFavorite(location.href, document.title);" id="add_favorite">收藏此书</a>
							<a href="javascript:void(0);" onclick="copy2Clipboard(location.href);alert('已成功复制此小说文章网址，发送给您的好友。');" id="add_recommend">我要推荐</a>
							<!--<a href="javascript:void(0);" onclick="javascript:addPublish(101678);" id="add_publish" class="chuban_b">&nbsp;</a>
							<a href="javascript:void(0);" target="_blank" class="wap_b">&nbsp;</a>-->
						</li>
					</ul>
				</div>
			        <div class="update">
					<div class="ico_shupi">
						<ul class="article_icon_info">
							<li>赠送本书金牌、红包、鲜花等道具，点亮牛人标识，获得作者关注。</li>
						</ul>
					</div>
					<h3 class="navi"><span id="Part1" class="menu2">内容简介</span></h3>
					<dl id="vip" >
						<dd>
							<?=$novel['comment']?>
						</dd>
					</dl>
				</div>
				<div class="volume">
					<? foreach((array)$novelChapters as $i => $chapter) {?>
					<div class="volume">
						<h2><span><? echo ++$i?></span><?=$chapter['title']?></h2>
						<p>卷介绍： </p>
						<p class="space2"><?=$chapter['comment']?></p>
						<table width="100%" class="line_2" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<? foreach((array)$chapter['contents'] as $k => $v) {?>
									<? if(++$k % 2 == 0) { ?>
									<td width="40%"><?=$k?>. <a id="vip_text_3863311" href="index-content-<?=$v['contentId']?>.htm"><?=$v['title']?></a></td>
									<td width="8%">&nbsp;</td>
									</tr><tr>
									<? } else { ?>
									<td width="40%"><?=$k?>. <a id="vip_text_3861965" href="index-content-<?=$v['contentId']?>.htm"><?=$v['title']?></a></td>
									<td width="8%">&nbsp;</td>
									<td class="line_none" width="4%"></td>
									<? } ?>
								<?}?>
							</tr>
						</table>
					</div>
					<?}?>
				</div>
			</div>
		</div>
	</div>
	<div id="Sid">
		<div class="Clumn1">
		      <div class="S_Title">
		        <h2>热门小说推荐</h2>
		      </div>
		      <ul class="Cont">
				<? foreach((array)$hotNovels as $v) {?>
				<li>《<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$v['novelId']?>.htm" target="_blank"><?=$v['novelName']?></a>》</li>
				<? } ?>
		      </ul>
		</div>
	</div>
</div>
<? include $this->gettpl('footer');?>