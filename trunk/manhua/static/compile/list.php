<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<? include $this->gettpl('header');?>
<div id="war">
	<div id="main">
		<div class="border">
			<div class="status">
			        <strong><a href="<?=GODHOUSE_DOMAIN_WWW?>">小说</a></strong>
			       /<strong>最近更新<a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-<?=$category['id']?>.htm"><?=$category['cate_name']?></a>下面的<?=$subCategory['cate_name']?>小说
				</strong>      
			</div>
			<div id="content">
			        <h4 class="black">
			        <span>按分类查看：</span>
			        <? foreach((array)$subCategorys as $v) {?>
			        <a href="index-list-<?=$category['id']?>-<?=$v['cate_id']?>.htm" <? if($v['cate_id'] == $subCategoryId) { ?>id="Cur"<? } ?>><?=$v['cate_name']?></a>|
			        <? } ?>
			        </h4>
			        <? foreach((array)$novels as $v) {?>
				<div class="Sum Search_line">
					<div class="novel_img">
					<img src="<?=$v['picture']?>" width="96" height="120" alt="<?=$v['novelName']?>"/>
					</div>
					<ul>
						<h2>《<a href="index-detail-<?=$v['novelId']?>.htm" target="_blank"><?=$v['novelName']?></a>》
						[<?=$v['categoryName']?>]       			        		</h2>
						<li>作者：<em class="blue"><?=$v['author']?></em></li>
						<li><P><?=$v['comment']?>...<BR></P></li>
					</ul>
				</div>
				<? } ?>
				<div id="page"><?=$multipage?></div>
				</div>
			</div>
		</div>
		<div id="Sid">
			<div class="Clumn1">
				<div class="S_Title">
				<h2><?=$subCategory['cate_name']?>小说热门排行</h2>
				<span class="more"><a target="_blank" href="<?=GODHOUSE_DOMAIN_WWW?>index-list-<?=$category['id']?>.html">更多</a></span>
			</div>
			<ul class="Cont">
			<? foreach((array)$hotNovels as $v) {?>
				<li>《<a href="index-detail-<?=$v['novelId']?>.htm" target="_blank"><?=$v['novelName']?></a>》</li>
			<? } ?>
			</ul>
		</div>
	</div>
</div>
<? include $this->gettpl('footer');?>