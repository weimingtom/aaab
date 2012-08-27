<?php
$h='
<div class="mt10">
<div class="taobao_detail feed_img" id="pic_mini_show_{rand}">
<div class="taobao_detail_img">   
<a href="javascript:void(0)" onclick="switchPic({rand},\'open\',\'{data.big_pic}\')"><img class="imgicon" src="{data.picurl}" /></a>
<img class="tag" src="__THEME__/images/goods_tag.png">
</div>

<div class="taobao_detail_text">
<ul>
<span>{data.g_title}</span>
</ul>

<ul>价格：{data.price} 元</ul>

<ul class="mydiscuz"><a rel="nofollow" href="{data.goodsurl}" target="_blank">购买链接</a></ul>

<ul><span class="groupfrom">来自微群</span></ul>
<img class="loadimg" src="__THEME__/images/icon_waiting.gif" style="border: 0px none ; position: absolute; top: 50px; left: 50px; background-color: transparent; width: 16px; height: 16px; z-index: 1001; display: none;">
</div>
</div>
<div class="feed_quote" style="display:none;" id="pic_show_{rand}"> 
	                  <div class="q_tit"><img class="q_tit_l" src="__THEME__/images/zw_img.gif" /></div>
	                  <div class="q_con"> 
	                  
					  <div class="pic_b_hd mygoods_w">
                                                    <div style="left:0" class="g_p">                               <span class="myg_title">{data.g_title}</span>  <span>￥{data.price}</span>.00                           </div>
                            <a href="{data.goodsurl}" ref="nofollow" class="buy_it" target="_blank"></a>
                                            </div>
	                  <a href="javascript:void(0)" onclick="switchPic({rand},\'close\')"><img maxWidth="320" src="" class="imgSmall" ></a>
	                  </div>
	                  <div class="q_btm"><img class="q_btm_l" src="__THEME__/images/zw_img.gif" /></div>
	                </div>


</div>
<ul class="tl"><a w="f" href="javascript:void(0)" onclick="ui.loveok(this,\'1\',\'{favcount}\',\'{weibo_id}\',\''.$_SESSION['mid'].'\',\'{uid}\',\''.getUserFace($_SESSION['mid']).'\')" callback="weibo.deletfav(\'{weibo_id}\')" class="love add_fav fav" id="favloe_{weibo_id}">
<span class="favCount" id="fav_{weibo_id}">{favcount}</span><i></i>
</a>
<span class="tou" id="favicon_{weibo_id}"></span>
</ul>
<div class="clear"></div>
';
                           
               
    



 
                     
return $h;
?>

