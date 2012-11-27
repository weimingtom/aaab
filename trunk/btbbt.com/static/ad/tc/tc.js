//document.writeln("<div id=\"ad_tc_id\"></div>");
var st_while = getcookie_tc('st_while');			// 时间间隔
var st_value = getcookie_tc('st_value');			// 轮换ID值
var st_ads = new Array();

// 杭州弈天网络
st_ads[0] = '<script type="text/javascript" language="javascript" src="http://play.unionsky.cn/show/?placeid=150651"></script>';
// 最联盟
st_ads[1] = '<script src="http://www.adszui.com/page/?s=626&u=1686&t=720&l=1&d=1"></script>';
// 易告
st_ads[2] = '<script src="http://new.egooad.com/show/?placeId=12721"></script>';
// 联告网盟
st_ads[3] = '<script type="text/javascript" src="http://lg3457.565882.com/pShow.php?PID=3507"></script>';
// 推告
st_ads[4] = '<script type="text/javascript" charset="utf-8" src="http://js.tuigoo.com/AShow.aspx?AID=8109"></script>';

// 六度
var tc_6dad = '<script type="text/javascript">';
tc_6dad += 'u_a_client="10726";';
tc_6dad += 'u_a_width="0";';
tc_6dad += 'u_a_height="0";';
tc_6dad += 'u_a_zones="45304";';
tc_6dad += 'u_a_type="1"';
tc_6dad += '</script>';
tc_6dad += '<script src="http://js.6dad.com/i.js"></script>';
st_ads[5] = tc_6dad;
// 新月联盟网
st_ads[6] = '<script src="http://t.ssjpx.com/page/?s=960"></script>';
// 麒推广告网络
st_ads[7] = '<script src="http://c1.keytui.com/js/0/481.js"></script><script src="http://c1.keytui.com/ok.php?xs=720&user=btbbtcom"></script>';

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
	setcookie_tc('st_while', 1, 1000);		// 1000一秒,
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