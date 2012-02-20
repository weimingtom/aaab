function $Showhtml(){
	player ='<OBJECT id=flvplayer1 classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="100%" height="'+Player.Height+'" align="middle">';
	player +='<PARAM NAME="Movie" VALUE="http://www.qiyi.com/player/20110218183154/qiyi_player.swf ">';
	player +='<param name="allowFullScreen" value="true" />';
	player +='<param name="wmode" value="transparent" />';
	player +='<param name="AllowScriptAccess" value="always" />';
	player +='<param name="quality" value="high" />';
	player +='<PARAM NAME="FlashVars" VALUE="flag=0&vid='+Player.Url+'">';
	player +='<embed allowfullscreen="true" wmode="transparent" quality="high" src="http://www.qiyi.com/player/20110218183154/qiyi_player.swf ?flag=1&vid='+Player.Url+'" quality="high" bgcolor="#000" width="100%" height="'+Player.Height+'" name="player" id="playerr" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">';
	player +='</object>';
	return player;
}
Player.Show();
if(Player.Second){
	$('buffer').style.position = 'absolute';
	$('buffer').style.height = Player.Height-28;
	$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}