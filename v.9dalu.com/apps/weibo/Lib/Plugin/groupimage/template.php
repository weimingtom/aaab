<?php 
	return '<div class="feed_img" id="pic_mini_show_{rand}"><span class="tem_pic"><a href="javascript:void(0)" onclick="switchPic({rand},\'open\',\'__UPLOAD__/{data.thumbmiddleurl}\')"><img class="imgicon" src="__UPLOAD__/{data.thumburl}" /></a></span>
	<span class="mydiscuz xiangxi"><a href="' . U ( 'home/Book/detail', array ('id' => '{weibo_id}' ) ) . '" target="_blank">详细内容</a></span>
		<img class="loadimg" src="__THEME__/images/icon_waiting.gif" style="border: 0px none ; position: absolute; top: 50px; left: 50px; background-color: transparent; width: 16px; height: 16px; z-index: 1001; display: none;">
		
		</div>
		
	                <div class="feed_quote" style="display:none;" id="pic_show_{rand}"> 
	                  <div class="q_tit"><img class="q_tit_l" src="__THEME__/images/zw_img.gif" /></div>
	                  <div class="q_con"> 
	                  <p style="margin:0;margin-bottom:5px" class="cGray2"><a href="javascript:void(0)" onclick="switchPic({rand},\'close\')"><img class="ico_cls" src="__THEME__/images/zw_img.gif" />收起</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" onclick="revolving({rand},\'left\')" ><img class="ico_turn_l" src="__THEME__/images/zw_img.gif" />左转</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" onclick="revolving({rand},\'right\')" ><img class="ico_turn_r" src="__THEME__/images/zw_img.gif" />右转</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="__UPLOAD__/{data.picurl}" target="_blank"><img class="ico_original" src="__THEME__/images/zw_img.gif" />查看原图</a></p>
	                  <a href="javascript:void(0)" onclick="switchPic({rand},\'close\')"><img maxWidth="320" src="" class="imgSmall" ></a>
	                  </div>
	                  <div class="q_btm"><img class="q_btm_l" src="__THEME__/images/zw_img.gif" /></div>
	                </div>
					<ul class="tl"><a w="f" href="javascript:void(0)" onclick="ui.loveok(this,\'1\',\'{favcount}\',\'{weibo_id}\',\''.$_SESSION['mid'].'\',\'{uid}\',\''.getUserFace($_SESSION['mid']).'\')" callback="weibo.deletfav(\'{weibo_id}\')" class="love add_fav fav" id="favloe_{weibo_id}">
<span class="favCount" id="fav_{weibo_id}">{favcount}</span><i></i>
</a>
<span class="tou" id="favicon_{weibo_id}"></span>
</ul>



					';
?>