function $Showhtml(){
	player = '<embed allowfullscreen="true" src="http://vhead.blog.sina.com.cn/player/bn_topic.swf?vid='+Player.Url+'&clip_id=&imgurl=&auto=1&vblog=1&type=0&tabad=1" quality="high" bgcolor="#000" width="100%" height="'+Player.Height+'" name="player" id="playerr" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">';
	return player;
}
Player.Show();
if(Player.Second){
	$('buffer').style.position = 'absolute';
	$('buffer').style.height = Player.Height-45;
	$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}