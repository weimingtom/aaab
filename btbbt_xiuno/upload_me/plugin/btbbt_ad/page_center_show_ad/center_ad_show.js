function showBlockLayerAd() {
	// 百分率
	var randnum = Math.floor(Math.random()*100); //随机取一个位置 
	if(randnum > 10) {
		return null;	
	}
	
	var isshow = getcookiead('isshowcenterad');
	if(isshow == 1) {
		return null;
	}
	
	// 加载CSS
	var head = document.getElementsByTagName('HEAD').item(0);
	//var style = document.createElement('link');
	//style.href = 'center_ad_show.css';
	//style.rel = 'stylesheet';
	//style.type = 'text/css';
	style = document.createElement('link');
	style.setAttribute('rel', 'stylesheet');
	style.setAttribute('type', 'text/css');
	style.setAttribute('href', 'http://www.btbbt.com/static/ad/page_center_show_ad/center_ad_show.css');
	head.appendChild(style);
	
	
	/* 创建DIV
	var addiv = document.createElement('div');
	addiv.id = 'layerCenterAd';
	addiv.className = "layer_center_ad";
	addiv.style.position = "absolute";

	var strIMG = "";
	addiv.innerHTML = "<p style=\"font-size:12px;width:100%;text-align:right;\"><a href=\"###\" onclick=\"closeCenterAd('layerCenterAd')\">关闭</a></p><p>"+strIMG+"</p>";*/
	var addiv = document.getElementById('layerCenterAd');
	addiv.style.position = "absolute";
	addiv.style.display = '';
	
	// 定位
	position = 2;
	addiv.style.top = '50%';
	addiv.style.left = '50%';
	
	mybody = document.getElementsByTagName("body").item(0);
	mybody.appendChild(addiv);
	
	var scrolltop = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
	addiv.style.marginLeft = '-' + parseFloat(addiv.offsetWidth/2) + 'px';
	addiv.style.marginTop = scrolltop - parseFloat(addiv.offsetHeight/2) + 'px';
	
	setcookiead('isshowcenterad', 1, 28800000);
	
	setTimeout("closeCenterAd()", 20000);	// 30秒
}

function closeCenterAd() {
	document.getElementById('layerCenterAd').style.display='none';
}

setTimeout("showBlockLayerAd()", 1000);