function $Showhtml(){
	player = "<embed id='v_player_cctv' width='"+Player.Width+"' height='"+Player.Height+"' flashvars='videoId=20120406121507&filePath=/flvxml/2009/04/06/&isAutoPlay=true&url=http://military.cntv.cn/program/jsbd/20120406/121507.shtml&tai=military&configPath=http://military.cntv.cn/player/config.xml&widgetsConfig=http://js.player.cntv.cn/xml/widgetsConfig/military.xml&languageConfig=http://military.cntv.cn/player/zh_cn.xml&hour24DataURL=&outsideChannelId=channelBugu&videoCenterId="+ Player.Url +"' allowscriptaccess='always' allowfullscreen='true' menu='false' quality='best' bgcolor='#000000' name='v_player_cctv' src='http://player.cntv.cn/standard/cntvOutSidePlayer.swf?v=0.171.5.8.8.9.8' type='application/x-shockwave-flash' lk_mediaid='lk_juiceapp_mediaPopup_1257416656250' lk_media='yes'/>";
//	player = '<object height="'+Player.Height+'" width="100%" type="application/x-shockwave-flash" data="http://player.cntv.cn/standard/cntvplayer.swf?v=0.171.5.7.9.0" id="cntv_player" style="visibility: visible;">';
//	player += '<param name="quality" value="high">';
//	player += '<param name="allowScriptAccess" value="always">';
//	player += '<param name="bgcolor" value="#000000">';
//	player += '<param name="allowfullscreen" value="true">';
//	player += '<param name="allowNetworking" value="all">';
//	player += '<param name="wmode" value="transparent">';
//	player += '<param name="flashvars" value="videoCenterId="'+Player.Url+'"&videoId=20110711">';
//	player += '</object>';
	return player;
}
Player.Show();
if(Player.Second){
	$('buffer').style.position = 'absolute';
	$('buffer').style.height = Player.Height-20;
	$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}