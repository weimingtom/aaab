<!--底部开始-->
<div id="page_footer">
	<?php
	$navIds = array(
		'about'=>13,			// 关于大陆
		'dynamic'=>14,			// 大陆动态
		'partners'=>15,			// 合作伙伴
		'copyright'=>16,		// 版权声明
		'link'=>17,			// 友情链接
		'jobs'=>18,			// 招聘信息
		'feedback'=>19,			// 意见反馈
		'help'=>20,			// 帮助中心
		'contact'=>21,			// 联系我们
	);
	$idNavs = array_flip($navIds);
	$navs = $mysql->fetch_all("SELECT id,title FROM -table-article WHERE dafenglei='21'");
	foreach ($navs as $k=>$v) {?>
    	<a href="general.php?a=<?php echo $idNavs[$v['id']]?>" target="_blank"><?php echo $v['title']?></a>
    	<?php 
		if($k != 8) {echo '｜';}
	}?>
	<br />
	九尾狐大陆版权所有 津ICP备12001441号-1
</div>
<!--底部结束-->