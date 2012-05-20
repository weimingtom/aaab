window.onunload = function() {
	loadPop();
}

function loadPop() {
	/* 百分率
	var randnum = Math.floor(Math.random()*100); //随机取一个位置 
	if(randnum > 10) {
		return null;	
	}
	*/
	
	var isshow = getcookiead('AD_ispop');
	if(isshow == 1) {
		return null;
	}

	popwhenshut();
	
	setcookiead('AD_ispop', 1, 43200000);
}

var flwindow = 0;

function popwhenshut(){
	if(flwindow){
		if(!flwindow.closed) flwindow.close();
	}
	flwindow = window.open('http://www.yingkong.net');
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

//setTimeout("loadPop()", 1000);
//loadPop();