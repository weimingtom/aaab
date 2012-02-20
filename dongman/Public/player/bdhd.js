function $Showhtml(){
	player='<object classid="clsid:02E2D748-67F8-48B4-8AB4-0A085374BB99" width="100%" height="'+Player.Height+'" id="BaiduPlayer" name="BaiduPlayer" onerror="Player.Install();" style="display:none;">';
	player+='<param name="URL" value="'+Player.Url+'">';
	player+='<param name="LastWebPage" value="'+Player.LastWebPage+'">';
	player+='<param name="NextWebPage" value="'+Player.NextWebPage+'">';
	player+='<param name="NextCacheUrl" value="'+Player.NextUrl+'">';
	player+='<param name="Autoplay" value="1">';
	player+='</object>';
	return player;
}
function $AdsStart(){
	$('buffer').style.display = 'block';
	if(BaiduPlayer.IsBuffing()){
		$("buffer").style.height = Player.Height-80;
		BaiduPlayer.height = 80;
	}else{
		$("buffer").style.height = Player.Height-60;
		BaiduPlayer.height = 60;
	}
}
function $AdsEnd(){
	$('buffer').style.display = 'none';
	BaiduPlayer.height = Player.Height;
}
function $Status(){
	if(BaiduPlayer.IsPlaying()){
		$AdsEnd();
	}else{
		$AdsStart();
	}
}
Player.Show();
if(BaiduPlayer.URL != undefined){
	BaiduPlayer.style.display = 'block';
	setInterval("$Status()", 1000);
}