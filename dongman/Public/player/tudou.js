function $Showhtml(){
	player = '<embed allowNetworking="internal" allowFullScreen="true" allowscriptaccess="never" src="http://js.tudouui.com/bin/douwan/douwanPlayer_3.swf?iid='+Player.Url+'" type="application/x-shockwave-flash" width="100%" height="'+Player.Height+'"></embed>';
	return player;
}
Player.Show();
Player.Show();
if(Player.Second){
	$('buffer').style.position = 'absolute';
	$('buffer').style.height = Player.Height-20;
	$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}