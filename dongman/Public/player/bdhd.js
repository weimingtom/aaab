function $Showhtml(){
	var is_ie = document.all;
	if (is_ie) {
		player = '<object classid="clsid:02E2D748-67F8-48B4-8AB4-0A085374BB99" width="100%" height="' + Player.Height + '" id="BaiduPlayer" name="BaiduPlayer" onerror="Player.Install();" style="display:none;">';
		player += '<param name="URL" value="' + Player.Url + '">';
		player += '<param name="LastWebPage" value="' + Player.LastWebPage + '">';
		player += '<param name="NextWebPage" value="' + Player.NextWebPage + '">';
		player += '<param name="NextCacheUrl" value="' + Player.NextUrl + '">';
		player += '<param name="Autoplay" value="1">';
		player += '</object>';
	} else {
		player = '<object id="BaiduPlayer" name="BaiduPlayer" type="application/player-activex" width="' + Player.Width + '" height="' + Player.Height + '" progid="Xbdyy.PlayCtrl.1" param_url="' + Player.Url + '" param_nextcacheurl="' + Player.NextUrl + '" param_lastwebpage="' + Player.LastWebPage + '" param_nextwebpage="' + Player.NextWebPage + '" param_onplay="onPlay" param_onpause="onPause" param_onfirstbufferingstart="onFirstBufferingStart" param_onfirstbufferingend="onFirstBufferingEnd" param_onplaybufferingstart="onPlayBufferingStart" param_onplaybufferingend="onPlayBufferingEnd" param_oncomplete="onComplete" param_autoplay="1" param_showstartclient="1"></object>';

	}
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