<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<? include $this->gettpl('header');?>
<div id="wrap">
  <div class="content-lr clearfix">
    <!--第二部分 开始-->
    <div class="main w710 content-rl">
      <div class="main w470">
        <div class="mod-1 sortTop">
          <!-- 分类推荐榜 -->
          <div class="mod-hd ">
            <h2>分类推荐榜</h2>
          </div>
          <div class="mod-bd content-lr clearfix">
            <div class="main w210" style="height:278px;">
              <div class="mod-sub">
                <!-- 网络小说 -->
                <h3>网络小说</h3>
                <dl class="picList2">
			<dt>《<a href="index-detail-<?=$network_novels[0]['novelId']?>.htm" title="<?=$network_novels[0]['novelName']?>" target="_blank"><?=$network_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="index-detail-<?=$network_novels[0]['novelId']?>.htm" title="<?=$network_novels[0]['novelName']?>" target="_blank"><img src="<?=$network_novels[0]['picture']?>" alt="《<?=$network_novels[0]['novelName']?>》" /></a></dd>
			<dd class="intro"><?=$network_novels[0]['comment']?>...</dd>
		</dl>
		<ul class="list">
			<? foreach((array)$network_novels as $k => $v) {?>
			<li>
				<a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-<?=$v['categoryId']?>.htm" target="_blank"><span class="sort">[<?=$v['categoryName']?>]</span></a>
				《<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》
			</li>
			<?}?>
		</ul>
                <!-- END 网络小说 -->
              </div>
            </div>
            <div class="sidebar w210" style="height:278px;">
              <div class="mod-sub">
                <!-- 武侠小说 -->
                <h3>武侠小说</h3>
                <dl class="picList2">
			<dt>《<a href="index-detail-<?=$martial_novels[0]['novelId']?>.htm" title="<?=$martial_novels[0]['novelName']?>" target="_blank"><?=$martial_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="index-detail-<?=$martial_novels[0]['novelId']?>.htm" title="<?=$martial_novels[0]['novelName']?>" target="_blank"><img src="<?=$martial_novels[0]['picture']?>" alt="《<?=$martial_novels[0]['novelName']?>》" /></a></dd>
			<dd class="intro"><?=$martial_novels[0]['comment']?>...</dd>
		</dl>
		<ul class="list">
			<? foreach((array)$martial_novels as $k => $v) {?>
			<li>
				<a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-<?=$v['categoryId']?>.htm" target="_blank"><span class="sort">[<?=$v['categoryName']?>]</span></a>
				《<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》
			</li>
			<?}?>
		</ul>
                <!-- END 武侠小说 -->
              </div>
            </div>
            <div class="main w210">
		<div class="mod-sub">
		<!-- 现代文学 -->
		<h3>现代文学</h3>
		<dl class="picList2">
			<dt>《<a href="index-detail-<?=$new_novels[0]['novelId']?>.htm" title="<?=$new_novels[0]['novelName']?>" target="_blank"><?=$new_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="index-detail-<?=$new_novels[0]['novelId']?>.htm" title="<?=$new_novels[0]['novelName']?>" target="_blank"><img src="<?=$new_novels[0]['picture']?>" alt="《<?=$new_novels[0]['novelName']?>》" /></a></dd>
			<dd class="intro"><?=$new_novels[0]['comment']?>...</dd>
		</dl>
		<ul class="list">
			<? foreach((array)$new_novels as $k => $v) {?>
			<li>
				<a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-<?=$v['categoryId']?>.htm" target="_blank"><span class="sort">[<?=$v['categoryName']?>]</span></a>
				《<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》
			</li>
			<?}?>
		</ul>
		<!-- END 现代文学 -->
		</div>
            </div>
            <div class="sidebar w210">
              <div class="mod-sub">
		<!-- 古典文学 -->
		<h3>古典文学</h3>
		<dl class="picList2">
			<dt>《<a href="index-detail-<?=$classical_novels[0]['novelId']?>.htm" title="<?=$classical_novels[0]['novelName']?>" target="_blank"><?=$classical_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="index-detail-<?=$classical_novels[0]['novelId']?>.htm" title="<?=$classical_novels[0]['novelName']?>" target="_blank"><img src="<?=$classical_novels[0]['picture']?>" alt="《<?=$classical_novels[0]['novelName']?>》" /></a></dd>
			<dd class="intro"><?=$classical_novels[0]['comment']?>...</dd>
		</dl>
		<ul class="list">
			<? foreach((array)$classical_novels as $k => $v) {?>
			<li>
				<a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-<?=$v['categoryId']?>.htm" target="_blank"><span class="sort">[<?=$v['categoryName']?>]</span></a>
				《<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》
			</li>
			<?}?>
		</ul>
		<!-- END 古典文学 -->
              </div>
            </div>
          </div>
          <!-- END 分类推荐榜 -->
        </div>
      </div>
      <div class="sidebar w230">
        <div class="tab J_tab">
          <!-- 强力推荐 -->
          <div class="tab-menuWrapper">
            <h2 class="J_tab-menu active"><span>强力推荐</span></h2>
            <!--<h2 class="J_tab-menu"><span>上周推荐</span></h2>-->
            <div class="tab-menu-m"><a href="<?=GODHOUSE_DOMAIN_WWW?>" target="_blank" >更多</a></div>
          </div>
          <div class="tab-contentWrapper">
            	<div class="J_tab-content">
			<ul class="list1">
				<? foreach((array)$newNovels as $v) {?>
				<li><span class="sort"><?=$v['categoryName']?></span>《<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$v['novelId']?>.htm" target="_blank"><?=$v['novelName']?></a>》</li>
				<? } ?>
			  </ul>
            	</div>
		<div class="J_tab-content none">
			  <ul class="list1">
				<li><span class="sort">[游戏]</span>《隐藏的区域》</li>
			  </ul>
		</div>
          </div>
          <!--END 强力推荐 -->
        </div>
      </div>
    </div>
    <div class="sidebar w230">
      <!-- 书友点击榜 -->
      <div class="tab J_tab PVTop">
        <div class="tab-menuWrapper">
          <h2>书友点击榜</h2>
          <h3 id="weekNovel" class="J_tab-menu active" onclick="showTopList(1)"><span>周</span></h3>
          <h3 id="monthNovel" class="J_tab-menu" onclick="showTopList(2)"><span>月</span></h3>
          <h3 id="totalNovel" class="J_tab-menu" onclick="showTopList(3)"><span>总</span></h3>
        </div>
        <div class="tab-contentWrapper">
          <div id="topList1" class="J_tab-content">
            	<ul class="topList1">
            		<? foreach((array)$weekNovels as $k => $v) {?>
            		<? ++$k?>
							<li class="No<?=$k?>">《<a target="_blank" href="index-detail-<?=$v['novelId']?>.htm"><?=$v['novelName']?></a>》</li>
					<?}?>
				</ul>
          </div>
          <div id="topList2" class="J_tab-content none">
				<ul class="topList1">
					<? foreach((array)$monthNovels as $k => $v) {?>
            		<? ++$k?>
							<li class="No<?=$k?>">《<a target="_blank" href="index-detail-<?=$v['novelId']?>.htm"><?=$v['novelName']?></a>》</li>
					<?}?>
				</ul>
          </div>
          <div id="topList3" class="J_tab-content none">
				<ul class="topList1">
					<? foreach((array)$totalNovels as $k => $v) {?>
            		<? ++$k?>
							<li class="No<?=$k?>">《<a target="_blank" href="index-detail-<?=$v['novelId']?>.htm"><?=$v['novelName']?></a>》</li>
					<?}?>
				</ul>
          </div>
          <!--END  书友点击榜 -->
        </div>
      </div>
    </div>
    <!--第二部分 结束-->
  </div>
  <div class="ad">
    <!--通栏广告-->
	<img src="images/ad_01.jpg" width="950" height="88" />
  </div>
  <div class="content-lr clearfix">
    <!--第三部分 开始-->
    <div class="main w230 mr10">
      <div class="mod-1">
        <!-- 言情小说 -->
        <div class="mod-hd">
          <h2>言情小说</h2>
          <div class="mod-hd-m"><a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-5.htm" title="更多" target="_blank">更多</a></div>
        </div>
        <div class="mod-bd">
		<dl class="picList3">
			<dt>《<a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$romance_novels[0]['novelId']?>.htm" target="_blank" title="<?=$romance_novels[0]['novelName']?>"><?=$romance_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="<?=GODHOUSE_DOMAIN_WWW?>index-detail-<?=$romance_novels[0]['novelId']?>.htm" target="_blank" title="<?=$romance_novels[0]['novelId']?>">
				<? if($romance_novels[0]['picture']) { ?>
				<img src="<?=$romance_novels[0]['picture']?>" alt="《<?=$romance_novels[0]['novelId']?>》" />
				<? } else { ?>
				<img src="images/untitled.bmp" alt="《<?=$romance_novels[0]['novelId']?>》" />
				<? } ?>
			</a></dd>
			<dd class="author">作者：<?=$romance_novels[0]['author']?></dd>
			<dd class="intro"><?=$romance_novels[0]['comment']?></dd>
		</dl>
		<ul class="topList3">
			<? foreach((array)$romance_novels as $k => $v) {?>
			<? ++$k?>
			<li class="No<?=$k?>"><span>《<a href="index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》</span><span class="author"><?=$v['author']?></span></li>
			<?}?>
		</ul>
        </div>
        <!-- END 言情小说 -->
      </div>
    </div>
    <div class="main w230 mr10">
      <div class="mod-1">
        <!-- 玄幻小说 -->
        <div class="mod-hd">
          <h2>玄幻小说</h2>
          <div class="mod-hd-m"><a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-6.htm" title="更多" target="_blank">更多</a></div>
        </div>
        <div class="mod-bd">
		<dl class="picList3">
			<dt>《<a href="index-detail-<?=$fantasy_novels[0]['novelId']?>.htm" target="_blank" title="<?=$fantasy_novels[0]['novelName']?>"><?=$fantasy_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="index-detail-<?=$fantasy_novels[0]['novelId']?>.htm" target="_blank" title="<?=$fantasy_novels[0]['novelId']?>">
				<? if($fantasy_novels[0]['picture']) { ?>
				<img src="<?=$fantasy_novels[0]['picture']?>" alt="《<?=$fantasy_novels[0]['novelId']?>》" />
				<? } else { ?>
				<img src="images/untitled.bmp" alt="《<?=$fantasy_novels[0]['novelId']?>》" />
				<? } ?>
			</a></dd>
			<dd class="author">作者：<?=$fantasy_novels[0]['author']?></dd>
			<dd class="intro"><?=$fantasy_novels[0]['comment']?></dd>
		</dl>
		<ul class="topList3">
			<? foreach((array)$fantasy_novels as $k => $v) {?>
			<? ++$k?>
			<li class="No<?=$k?>"><span>《<a href="index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》</span><span class="author"><?=$v['author']?></span></li>
			<?}?>
		</ul>
        </div>
        <!-- END 玄幻小说 -->
      </div>
    </div>
    <div class="main w230 mr10">
      <div class="mod-1">
        <!-- 侦探小说 -->
        <div class="mod-hd">
          <h2>侦探小说</h2>
          <div class="mod-hd-m"><a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-7.htm" title="更多" target="_blank">更多</a></div>
        </div>
	<div class="mod-bd">
		<dl class="picList3">
			<dt>《<a href="index-detail-<?=$detective_novels[0]['novelId']?>.htm" target="_blank" title="<?=$detective_novels[0]['novelName']?>"><?=$detective_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="index-detail-<?=$detective_novels[0]['novelId']?>.htm" target="_blank" title="<?=$detective_novels[0]['novelId']?>">
				<? if($detective_novels[0]['picture']) { ?>
				<img src="<?=$detective_novels[0]['picture']?>" alt="《<?=$detective_novels[0]['novelId']?>》" />
				<? } else { ?>
				<img src="images/untitled.bmp" alt="《<?=$detective_novels[0]['novelId']?>》" />
				<? } ?>
			</a></dd>
			<dd class="author">作者：<?=$detective_novels[0]['author']?></dd>
			<dd class="intro"><?=$detective_novels[0]['comment']?></dd>
		</dl>
		<ul class="topList3">
			<? foreach((array)$detective_novels as $k => $v) {?>
			<? ++$k?>
			<li class="No<?=$k?>"><span>《<a href="index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》</span><span class="author"><?=$v['author']?></span></li>
			<?}?>
		</ul>
        </div>
        <!-- END 侦探小说 -->
      </div>
    </div>
    <div class="sidebar w230">
      <div class="mod-1">
        <!-- 少儿读物 -->
        <div class="mod-hd">
          <h2>少儿读物</h2>
          <div class="mod-hd-m"><a href="<?=GODHOUSE_DOMAIN_WWW?>index-list-9.htm" title="更多" target="_blank">更多</a></div>
        </div>
        <div class="mod-bd">
		<dl class="picList3">
			<dt>《<a href="index-detail-<?=$children_novels[0]['novelId']?>.htm" target="_blank" title="<?=$children_novels[0]['novelName']?>"><?=$children_novels[0]['novelName']?></a>》</dt>
			<dd class="pic"><a href="index-detail-<?=$children_novels[0]['novelId']?>.htm" target="_blank" title="<?=$children_novels[0]['novelId']?>">
				<? if($children_novels[0]['picture']) { ?>
				<img src="<?=$children_novels[0]['picture']?>" alt="《<?=$children_novels[0]['novelId']?>》" />
				<? } else { ?>
				<img src="images/untitled.bmp" alt="《<?=$children_novels[0]['novelId']?>》" />
				<? } ?>
			</a></dd>
			<dd class="author">作者：<?=$children_novels[0]['author']?></dd>
			<dd class="intro"><?=$children_novels[0]['comment']?></dd>
		</dl>
		<ul class="topList3">
			<? foreach((array)$children_novels as $k => $v) {?>
			<? ++$k?>
			<li class="No<?=$k?>"><span>《<a href="index-detail-<?=$v['novelId']?>.htm" target="_blank" ><?=$v['novelName']?></a>》</span><span class="author"><?=$v['author']?></span></li>
			<?}?>
		</ul>
        </div>
        <!-- END 少儿读物 -->
      </div>
    </div>
    <!--第三部分 结束-->
  </div>
  <div class="content-lr clearfix" style="display:none;">
    <!--第五部分 开始-->
    <div class="main w710 content-rl">
      <div class="main w470">
        <div class="tab J_tab">
          <!-- 普通小说今日更新章节/VIP小说今日更新章节 -->
          <div class="tab-menuWrapper">
            <h2 class="J_tab-menu active"><span>普通小说今日更新章节</span></h2>
            <h2 class="J_tab-menu"><span>VIP小说今日更新章节</span></h2>
            <div class="tab-menu-m">更多</div>
          </div>
          <div class="tab-contentWrapper">
            <div class="J_tab-content">
              <ul class="list4">
		<li>
		《<a href="http://www.readnovel.com/partlist/102787/" target="_blank" title="逍遥神途">逍遥神途</a>》：
		<a href="http://www.readnovel.com/novel/102787/56.html" target="_blank" title="第五十五章！高手云集！">
		第五十五章！高手云集！</a> <span class="pub_time">23:14</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/101972/" target="_blank" title="北乔峰异界重生">北乔峰异界重生</a>》：
		<a href="http://www.readnovel.com/novel/101972/91.html" target="_blank" title="第九十一章  剑域来人">
		第九十一章  剑域来人</a> <span class="pub_time">23:07</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/86676/" target="_blank" title="新白话聊斋">新白话聊斋</a>》：
		<a href="http://www.readnovel.com/novel/86676/58.html" target="_blank" title="第五十八章">
		第五十八章</a> <span class="pub_time">23:00</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/96832/" target="_blank" title="背着本本穿越">背着本本穿越</a>》：
		<a href="http://www.readnovel.com/novel/96832/167.html" target="_blank" title="第七十二章 修道者的心！求收藏！">
		第七十二章 修道者的心...</a> <span class="pub_time">22:58</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/99279/" target="_blank" title="魔武真君">魔武真君</a>》：
		<a href="http://www.readnovel.com/novel/99279/37.html" target="_blank" title="三十五章 总督有点……">
		三十五章 总督有点……</a> <span class="pub_time">22:57</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/97927/" target="_blank" title="玄脉神剑">玄脉神剑</a>》：
		<a href="http://www.readnovel.com/novel/97927/287.html" target="_blank" title="第三十一章 杀出重围（贰）">
		第三十一章 杀出重围（贰）</a> <span class="pub_time">22:53</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/97431/" target="_blank" title="封神阁">封神阁</a>》：
		<a href="http://www.readnovel.com/novel/97431/174.html" target="_blank" title="出其不意！">
		出其不意！</a> <span class="pub_time">22:44</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/104625/" target="_blank" title="混宇天道">混宇天道</a>》：
		<a href="http://www.readnovel.com/novel/104625/38.html" target="_blank" title="第三十八章水冰心">
		第三十八章水冰心</a> <span class="pub_time">22:41</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/104727/" target="_blank" title="异能神偷">异能神偷</a>》：
		<a href="http://www.readnovel.com/novel/104727/76.html" target="_blank" title="072 大家一起震精，互相震精..">
		072 大家一起震精，互相震精..</a> <span class="pub_time">22:34</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/78932/" target="_blank" title="妈妈给我找了一小妹">妈妈给我找了一小妹</a>》：
		<a href="http://www.readnovel.com/novel/78932/90.html" target="_blank" title="第十八章 爱上我们在一起1">
		第十八章 爱上我...</a> <span class="pub_time">22:29</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/106159/" target="_blank" title="无情圣诀">无情圣诀</a>》：
		<a href="http://www.readnovel.com/novel/106159/10.html" target="_blank" title="第十章 兄弟修神">
		第十章 兄弟修神</a> <span class="pub_time">22:26</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/100674/" target="_blank" title="古剑傲尘">古剑傲尘</a>》：
		<a href="http://www.readnovel.com/novel/100674/111.html" target="_blank" title="第四十四章 坚持">
		第四十四章 坚持</a> <span class="pub_time">22:26</span></li>
		<li>
		《<a href="http://www.readnovel.com/partlist/95326/" target="_blank" title="楚汉长歌之江山美人">楚汉长歌之江山美人</a>》：
		<a href="http://www.readnovel.com/novel/95326/79.html" target="_blank" title="第一章 天子之气">
		第一章 天子之气</a> <span class="pub_time">22:24</span></li>
		</ul>
            </div>
            <div class="J_tab-content none">
				<ul class="list4">
				<li>《傲神》：339章别以为穿上马甲，我就不认... <span class="pub_time">23:19</span></li>
				</ul>
            </div>
          </div>
          <!--END 普通小说今日更新章节/VIP 小说今日更新章节 -->
        </div>
      </div>
      <div class="sidebar w230">
        <div class="mod">
          <!-- 签约作家新书榜 -->
          <div class="mod-hd">
            <h2>签约作者新书榜</h2>
            <div class="mod-hd-m">更多</div>
          </div>
          <div class="mod-bd">
		<ul class="list1">
			<li><span class="sort">[网游]</span>《网游之唤魔骑士》</li>
			<li><span class="sort">[游戏]</span>《网游之流氓高手》</li>
			<li><span class="sort">[异能]</span>《狼行都市》</li>
			<li><span class="sort">[生活]</span>《超级美女保镖》</li>
			<li><span class="sort">[异能]</span>《疯狂复制》</li>
			<li><span class="sort">[异能]</span>《猎艳逍遥》</li>
			<li><span class="sort">[仙侠]</span>《武破乾坤》</li>
			<li><span class="sort">[异能]</span>《超级帅哥》</li>
			<li><span class="sort">[异能]</span>《纨绔邪少》</li>
			<li><span class="sort">[修真]</span>《武神重生》</li>
			<li><span class="sort">[历史]</span>《疯言疯语》</li>
			<li><span class="sort">[奇幻]</span>《飞天九部》</li>
			<li><span class="sort">[异世]</span>《坏公子》</li>
		</ul>
          </div>
          <!-- END 签约作家新书榜 -->
        </div>
      </div>
    </div>
    <div class="sidebar w230">
      <div class="mod">
        <!-- 风向标新闻 -->
        <div class="mod-hd">
          <h2>风向标新闻</h2>
          <div class="mod-hd-m"><a href="http://jiaoliu.readnovel.com/forum-77-1.html" title="更多" target="_blank">更多</a></div>
        </div>
	<div class="mod-bd">
		<ul class="list3">
			<li>首届中国写作者大会征集现场热心</li>
			<li>盛大账号安全特别提醒</li>
			<li>RN书评申请专区</li>
			<li>小说阅读网男生版招聘兼职签约编</li>
		</ul>
		<div class="line mb10 mt10"></div>
		<ul class="list3">
			<li>军事《死神十字》出版上市</a></li>
			<li>手机小说阅读网更换域名的通知</a></li>
			<li>《冷刺》出版上市</li>
		</ul>
	</div>
        <!-- END 风向标新闻 -->
      </div>
      <div class="mod mod-lx">
        <h2>男频签约咨询：</h2>
        <p>邮箱：banquan@.com
		</br>
		</p>
      </div>
    </div>
    <!--第五部分 结束-->
  </div>
	<div class="mod Friend_link">
		<div class="mod-hd">
			<h2>友情链接</h2>
			<div class="mod-hd-m"><a href="###" class="more" target="_blank">更多</a></div>
		</div>
		<div class="mod-bd"> 
			<a target="_blank" href="http://www.baidu.com">百度</a>
			<a target="_blank" href="http://www.google.com">GOOGLE</a>
		</div>
	</div>
</div>
<script>
function showTopList(currentId) {
	var hots = Array();
	hots[1] = 'weekNovel';
	hots[2] = 'monthNovel';
	hots[3] = 'totalNovel';
	
	for(var i=1; i<hots.length; i++) {
		if(i==currentId) {
			$(hots[i]).className = 'J_tab-menu active';
			$('topList'+i).className = 'J_tab-content';
		} else {
			$(hots[i]).className = 'J_tab-menu';
			$('topList'+i).className = 'J_tab-content none';
		}
	}
}
</script>
<? include $this->gettpl('footer');?>