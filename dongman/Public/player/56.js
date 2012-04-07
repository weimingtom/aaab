function $Showhtml(){
	player = '<embed src="http://player.56.com/v_'+Player.Url+'.swf" type="application/x-shockwave-flash" width="'+Player.Width+'" height="'+Player.Height+'" allowNetworking="all" allowScriptAccess="always" allowFullScreen="true"></embed>';
	return player;
}
Player.Show();
if(Player.Second){
	$('buffer').style.position = 'absolute';
	$('buffer').style.height = Player.Height-20;
	$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}
