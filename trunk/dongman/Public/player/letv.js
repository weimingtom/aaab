function $Showhtml(){
//	player ='<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="100%" height="'+Player.Height+'" >';
//	player += '<param name="movie" value="http://www.letv.com/player/x'+Player.Url+'.swf">';
//	player += '<param name="quality" value="High">';
//	player += '<embed src="http://www.letv.com/player/x'+Player.Url+'.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj1" quality="High" width="100%" height="'+Player.Height+'"></object>';	
	player ='<object width="100%" height="'+Player.Height+'">';
		player +='<param name="allowFullScreen" value="true">';
		player +='<param name="flashVars"  />';
		player +='<embed src="http://img1.c0.letv.com/ptv/player/swfPlayer.swf?id='+Player.Url+'&autoplay=0&from=column"  width="100%" height="'+Player.Height+'" allowFullScreen="true"  type="application/x-shockwave-flash" />';
	player +='</object>';
	return player;
}
Player.Show();
if(Player.Second){
	$('buffer').style.position = 'absolute';
	$('buffer').style.height = Player.Height-45;
	$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}