function showBlockLayerAd() {
	// 百分率
	var randnum = Math.floor(Math.random()*100); //随机取一个位置 
	if(randnum > 20) {
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
    style.setAttribute('href', 'center_ad_show.css');
	head.appendChild(style);
	
	// 创建DIV
	var addiv = document.createElement('div');
	addiv.id = 'layerCenterAd';
	addiv.className = "layer_center_ad";
	addiv.style.position = "absolute";
	var strIMG = "<img src='test.jpg' />";
	addiv.innerHTML = "<p style=\"font-size:12px;width:100%;text-align:right;\"><a href=\"###\" onclick=\"closeCenterAd('layerCenterAd')\">关闭</a></p><p>"+strIMG+"</p>";
	
	// 定位
	position = 2;
	addiv.style.top = '50%';
	addiv.style.left = '50%';
	
	mybody = document.getElementsByTagName("body").item(0);
	mybody.appendChild(addiv);
	
	var scrolltop = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
	addiv.style.marginLeft = '-' + parseFloat(addiv.offsetWidth/2) + 'px';
	addiv.style.marginTop = scrolltop - parseFloat(addiv.offsetHeight/2) + 'px';
	
	setcookiead('isshowcenterad', 1, 86400);
}

function closeCenterAd(strid) {
	document.getElementById(strid).style.display='none';
	return false;
}

function getcookiead(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

function setcookiead(cookieName, cookieValue, seconds, path, domain, secure) {
	seconds = seconds ? seconds : 8400000;
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

setTimeout("showBlockLayerAd()", 1000);