//document.writeln("<div id=\"ad_tc_id\"></div>");
var st_while = getcookie_tc('st_while');			// 时间间隔
var st_value = getcookie_tc('st_value');			// 轮换ID值
var st_ads = new Array();

// 杭州弈天网络
st_ads[0] = '<script src="http://www.adszui.com/page/?s=626&u=1686&t=720&l=1&d=1"></script>';
// 最联盟
st_ads[1] = '<script type="text/javascript" src="http://v.1717gs.com/API/CVT_StartPops.aspx?PosID=2994" charset="gb2312"></script>';

var count_tc = st_ads.length;

if(st_value !== null) {
	if(st_value >= count_tc - 1) {
		st_value = 0;
	} else {
		st_value++;
	}
} else {
	st_value = 0;
}

if(!st_while) {
	setcookie_tc('st_while', 1, 60000);		// 1000一秒,
	setcookie_tc('st_value', st_value);
	
	document.writeln(st_ads[st_value]);
}


function getcookie_tc(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

function setcookie_tc(cookieName, cookieValue, seconds, path, domain, secure) {
	seconds = seconds ? seconds : 8400000;
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

//setTimeout("ad_tc()", 1000);