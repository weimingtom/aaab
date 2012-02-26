// ------------------------> 常量，保持跟 style_1.php 一致
var COLOR1 = '#FFFFFF';
var COLOR2 = '#F2F2F2';
var COLOR3 = '#D6D6D6';
var COLOR5 = '#616161';

// ------------------------> 兼容性判断
var is_ie = document.all;

// ------------------------> 常用的函数
function $(id) {
	return document.getElementById(id);
}

Array.prototype.push = function(value) {
	this[this.length] = value;
	return this.length;
}

function _attachEvent(obj, evt, func) {
	 if(is_ie) {
		obj.detachEvent("on" + evt, func);
		obj.attachEvent("on" + evt, func);
	} else {
		obj.removeEventListener(evt, func, false);
		obj.addEventListener(evt, func, false);
	}
}

function _cancelBubble(e, returnValue) {
	if(!e) return ;
	try {
		if(is_ie) {
			if(!returnValue && e) e.returnValue = false;
			if(e)e.cancelBubble = true;
		} else {
			if(e.stopPropagation)e.stopPropagation();
			if(!returnValue && e.preventDefault) e.preventDefault();
		}
	} catch(e) {/*alert('_cancelBubble:' + e.message)*/}
}

// 得到某个对象的绝对定位
function getposition(obj) {
	var r = new Array();
	r['x'] = obj.offsetLeft;
	r['y'] = obj.offsetTop;
	while(obj = obj.offsetParent) {
		r['x'] += obj.offsetLeft;
		r['y'] += obj.offsetTop;
	}
	return r;
}

// ------------------------> setdata() savedata(), 2M
if(is_ie) document.documentElement.addBehavior("#default#userdata");

//private
function getdata(key) {

	var value = null;
	if(is_ie) {
		with(document.documentElement) {
			load(key);
			value = getAttribute("value");
		}
	} else {
		value = sessionStorage.getItem(key) && sessionStorage.getItem(key).toString().length == 0 ? '' : (sessionStorage.getItem(key) == null ? '' : sessionStorage.getItem(key));
	}

	value = value + '';
	if(value + ""){
		var dateline = Math.round(new Date().getTime()/1000);
		var strp = value.indexOf("\t|\t");

		if(strp>-1){
			var vtime = parseInt(value.split("\t|\t")[1]);
			if(dateline > vtime){
				deldata(key);
				return null;
			}
			return value.substr(0, strp);
		}
	}
	return value;
}

function setdata(key, value, life){
	if(life != null){
		value = value+"\t|\t"+life;
	}
	if(is_ie) {
		with(document.documentElement) {
			load(key);
			setAttribute("value", value);
			save(key);
			return  getAttribute("value");
		}
	} else {
		sessionStorage.setItem(key, value);
	}
}
function deldata(key){
	if(is_ie) {
		with(document.documentElement) {
			load(key);
			expires = new Date(315532799000).toUTCString();
			save(key);
		}
	} else if(window.sessionStorage){
		sessionStorage.removeItem(key)
	}
}

// -----------------------------> ajax.js
function getdomain(url) {
	/(\w+\.(\w+\.\w+(:\d*)?))\//.exec(url);
	return new Array(RegExp.$1, RegExp.$2);
}

function htmlspecialchars_decode(s) {
	s = s.replace(/\&amp\;/g, '&');
	s = s.replace(/\&lt\;/g, '<');
	s = s.replace(/\&gt\;/g, '>');
	return s;
}

//避免重复创建
function Ajax(waitId){
	// 加载进度条
	var ajax = new Object();
	ajax.loading = '<img src="images/loading.gif" style="margin: 3px; vertical-align: middle" height="16" /><span>请稍候，正在处理...</span>';
	var waitdiv = null;
	if(waitId == 'default') {
		if(!$('ajax_default_waitid')) {
			var waitdiv = document.createElement('div');
			waitdiv.id = 'ajax_default_waitid';
			waitdiv.className = "ajaxloading";
			$('append').appendChild(waitdiv);
		} else {
			waitdiv = $('ajax_default_waitid');
		}

	} else if(waitId){
		var waitdiv = $(waitId);
	}
	if(waitdiv) {
		waitdiv.orgdisplay = waitdiv.style.display;
		waitdiv.style.display = '';
		waitdiv.innerHTML = ajax.loading;
	}

	var that = ajax;
	ajax.get = function(url, recall) {
		var script = document.createElement("script");
	       	script.src = url;
		if(!is_ie) {
			script.onload = function() {
		       		var json_return2 = htmlspecialchars_decode(json_return);
		       		json_return2 = parsexml(json_return2);
		       		evalscript(json_return2.message.text);// later
				recall(json_return2, that);
				if(waitdiv && waitdiv.id == 'ajax_default_waitid') {
					waitdiv.style.display = 'none';
		     		}
		     		if(json_return2.message)evalscript(json_return2.message.text, true);
			}
		} else {
			// 闭包函数可以用到caller的局部变量（最后的状态）
			script.onreadystatechange = function() {
				//var b = a;
				if(script.readyState=='complete'||script.readyState== 'loaded') {
					// 需要定义一个局部变量，防止重复点击，导致错误。
					var json_return2 = htmlspecialchars_decode(json_return);
					json_return2 = parsexml(json_return2);
					evalscript(json_return2.message.text);
					recall(json_return2, that);
			 		if(waitdiv && waitdiv.id == 'ajax_default_waitid') {
						waitdiv.style.display = 'none';
			     		}
			     		// IE 需要延迟
			     		setTimeout(function(){evalscript(json_return2.message.text, true)}, 100);
				}
			}

		}
	   	document.getElementsByTagName('head')[0].appendChild(script);
	}
	return ajax;
}

function show(id, display) {
	if(!$(id)) return false;
	if(display == 'auto') {
		$(id).style.display = $(id).style.display == '' ? 'none' : '';
	} else {
		$(id).style.display = display;
	}
}

function ajaxget(url, showId, waitId, display, recall) {
	try {
		var e = is_ie ? event : (ajaxget.caller == null ? null : ajaxget.caller.arguments[0]);
	} catch(error) {
		var e = null;
	}
	ajaxget2(e, url, showId, waitId, display, recall);
}

function ajaxget2(e, url, showId, waitId, display, recall) {
	var target = e ? (is_ie ? e.srcElement : e.target) : null;
	var display = display ? display : '';
	var x = new Ajax(waitId);
	x.waitId = waitId;
	x.showId = showId;
	x.display = display;
	var sep = url.indexOf('?') != -1 ? '&' : '?';
	x.target = target;
	x.recall = recall;
	x.get(url+sep+'inajax=1', function(s, x) {
		if(x.display == 'auto' && x.target) {
			x.target.onclick = function() {
				show(x.showId, 'auto');
			}
		}
		if(x.showId) {
			show(x.showId, x.display);// tbody
			show(x.showId+'tbody', x.display);// tbody
			if(s && s.message) {
				ajax_innerhtml($(x.showId), s.message.text);
			}
		}
		if(x.recall) {
			// 调试用, 上线后注掉
			if(s.error) {
				//alert(s.message.text);
			}
			x.recall(s);
		}
	});
	_cancelBubble(e);
}

/* append:
 *	false 替换(默认)
 * 	true 追加
 */
function ajax_innerhtml(showid, s, append) {
	if(showid.tagName != 'TBODY') {
		showid.innerHTML = s;
	} else {
		if(!append) {
			while(showid.firstChild) {
				showid.firstChild.parentNode.removeChild(showid.firstChild);
			}
		}
		var div1 = document.createElement('DIV');
		div1.id = showid.id+'_div';
		div1.innerHTML = '<table><tbody id="'+showid.id+'_tbody">'+s+'</tbody></table>';
		$('append').appendChild(div1);
		var trs = div1.getElementsByTagName('TR');
		var l = trs.length;
		for(var i=0; i<l; i++) {
			showid.appendChild(trs[0]);
		}
		div1.parentNode.removeChild(div1);
	}
}

function stripscript(s) {
	return s.replace(/<script.*?>[^\x00]*?<\/script>/ig, '');
}

var evalscripts = new Array();
function evalscript(s, later) {
	if(typeof s == 'undefined') return '';
	if(!s || typeof s == 'number' || s.indexOf('<script') == -1) return s;
	var p = /<script[^>]*?src="([^\x00]+?)"[^>]*( reload="1")?><\/script>/ig;
	var arr = new Array();
	while(arr = p.exec(s)) appendscript(arr[1], '', arr[2]);
	s = s.replace(p, '');
	p = /<script([^>]*?)>([^\x00]+?)<\/script>/ig;
	while(arr = p.exec(s)) {
		var reloadflag = arr[1].indexOf('reload="1"') != -1;
		var laterflag = arr[1].indexOf('later="1"') != -1;
		if(!later && !laterflag || later && laterflag)appendscript('', arr[2], reloadflag);
	}
	p = /<style([^>]*?)>([^\x00]+?)<\/style>/ig;
	while(arr = p.exec(s)) {
		appendstyle(arr[2]);
	}
	return s;
}

function appendscript(src, text, reload) {
	var id = hash(src + text);
	if(!reload && in_array(id, evalscripts)) return;
	if(reload && $(id)) {
		$(id).parentNode.removeChild($(id));
	}
	evalscripts.push(id);
	var scriptNode = document.createElement("script");
	scriptNode.type = "text/javascript";
	scriptNode.id = id;
	if(src) {
		scriptNode.src = src;
	} else if(text){
		scriptNode.text = text;
	}
	$('append').appendChild(scriptNode);
}

// css
function appendstyle(css) {
	var styleid = hash(css);
	if($(styleid)) return;
	if(is_ie) {
		window.style = css;
		document.createStyleSheet("javascript:style");
	} else {
		var style = document.createElement('style');
		style.type = 'text/css';
		style.innerHTML=css;
		document.getElementsByTagName('HEAD').item(0).appendChild(style);
	}
}

// 得到一个定长的 hash 值， 依赖于 stringxor()
function hash(string, length) {
	var length = length ? length : 32;
	var start = 0;
	var i = 0;
	var result = '';
	filllen = length - string.length % length;
	for(i = 0; i < filllen; i++){
		string += "0";
	}
	while(start < string.length) {
		result = stringxor(result, string.substr(start, length));
		start += length;
	}
	return result;
}

//note 将两个字符串进行异或运算，结果为英文字符组合
function stringxor(s1, s2) {
	var s = '';
	var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var max = Math.max(s1.length, s2.length);
	for(var i=0; i<max; i++) {
		var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
		s += hash.charAt(k % 52);
	}
	return s;
}

function in_array(needle, haystack) {
	for(var i in haystack) 	{if(haystack[i] == needle) return true;}
	return false;
}

var currmenuid = null;
// attr 窗口属性 {confirm:recall,cose:yes}
function ajaxmenu(url, position, timeout, recall, attr, level) {
	// 判断层是否存在。
	e = is_ie ? event : (ajaxmenu.caller ? ajaxmenu.caller.arguments[0] : null);
	controlid = e == null ? null : (is_ie ? e.srcElement : e.target);

	var menuid = hash(url);// 使每个 url 对应一个弹出层，避免重复请求
	currmenuid = menuid;
	createmenu(menuid, attr, level);
	menubodyid = menuid + 'body';
	showmenu2(e, menuid, position, controlid, timeout);

	if(!recall) {
		recall = function() {setposition(menuid, position, controlid);}
	}

	var clientrecall = '';
	var recallhref = controlid ? (is_ie ? controlid.href : controlid.getAttribute('href')) : '';
	var s = window.location.toString();
	if(recallhref && recallhref != '' && recallhref != s.replace(/^(http:\/\/[^\/]+?\/).*$/ig, "$1")) {
		clientrecall = escape(recallhref);
	}

	url = url + (url.indexOf('?') == -1 ? '?' : '&')+'menuid='+menuid+'&clientrecall='+clientrecall;// 通过 URL 传递 menuid
	if(controlid && !controlid.id) {
		controlid.id = Math.random();
	}

	ajaxget2(e, url, menubodyid, menubodyid, '', recall);

	_cancelBubble(e);
}

function ajaxserverconfirm(url, recall, level) {
	ajaxmenu(url, 1, 0, null, {confirm:recall}, level);
}

function ajaxconfirm(title, url, recall, level) {
	e = is_ie ? event : (ajaxconfirm.caller ? ajaxconfirm.caller.arguments[0] : null);
	_cancelBubble(e);
	var menuid = createmenu(hash(url+title), {confirm:null}, level);
	title = '<div><img src="images/confirm.gif" /> &nbsp; '+title+'</div>'
	showmenu(menuid, 1);
	url += (url.indexOf('?') == -1 ? '?' : '&') + 'menuid='+menuid;
	if(!recall) {
		recall = function (s) {
			$(s.menuid.text + 'body').innerHTML = s.message.text;
			ajaxalert(s.message.text);
		}
	}
	$(menuid+'confirm').onclick = function(){ajaxget(url, null, menuid+'body', null, recall);this.style.display = 'none';};
	$(menuid + 'body').innerHTML = title;
}

function isset(v) {
	return typeof v != 'undefined';
}

var ajax_post_onload = new Array();
var ajax_post_k = 0;
function ajaxpost(formid, showid, recall) {
	var ajaxframeid = 'ajaxframe' + formid;
	var ajaxframe = $(ajaxframeid);

	if(ajaxframe == null) {
		if (is_ie) {
			ajaxframe = document.createElement("<iframe name='" + ajaxframeid + "' id='" + ajaxframeid + "'></iframe>");
		} else {
			ajaxframe = document.createElement("iframe");
			ajaxframe.name = ajaxframeid;
			ajaxframe.id = ajaxframeid;
		}
		ajaxframe.style.display = 'none';
		$('append').appendChild(ajaxframe);
	}
	// 判断是否跨域
	if($(formid).action.indexOf('inajax') == -1) {
		$(formid).action += ($(formid).action.indexOf('?') != -1 ? '&' : '?') + 'inajax=1';
	}
	var formdomain = getdomain($(formid).action);
	if(formdomain[0] != document.domain) {
		$(formid).action = $(formid).action.replace(/inajax=1/, "inajax=2");
	}

	$(formid).target = ajaxframeid;
	// 默认的回调函数

	ajax_post_onload[++ajax_post_k] = function() {

		//var s = (is_ie && $(ajaxframeid)) ? $(ajaxframeid).contentWindow.document.XMLDocument.text : $(ajaxframeid).contentWindow.document.documentElement.firstChild.nodeValue;
		var s = is_ie ? $(ajaxframeid).contentWindow.document.body.innerHTML : $(ajaxframeid).contentWindow.document.body.textContent;

		s = htmlspecialchars_decode(s);

		eval(s);

		s = json_return;
		if(s) {
			evalscript(s);
			// 必须去除 <script> 标签和里面内容
			s = stripscript(s);
			s = parsexml(s);
			var menuid = showid ? showid.substring(0, showid.length - 4) : '';
			if(recall) {
				// 调试用, 上线之后注掉
				if(s.error) {
					//alert(s.message.text);
				}
				recall(s);
			} else {
				if($(showid) && s.message)$(showid).innerHTML = s.message.text;
				setposition(menuid, 1);
				hidemenu(menuid, 2);
			}
		} else {
			alert('服务端返回的数据为空。');
			//setTimeout("hidemenu();", 2000);
		}
	}

	// 取消绑定。
	 if(is_ie) {
		if(ajax_post_onload[ajax_post_k-1])ajaxframe.detachEvent("onload", ajax_post_onload[ajax_post_k-1]);
		ajaxframe.attachEvent("onload", ajax_post_onload[ajax_post_k]);
	} else {
		if(ajax_post_onload[ajax_post_k-1])ajaxframe.removeEventListener('load', ajax_post_onload[ajax_post_k-1], false);
		ajaxframe.addEventListener('load', ajax_post_onload[ajax_post_k], false);
	}

	$(formid).submit();
}

//-----------------------------> menu.js
var Menus = new Array();

/**
	只是创建层和关闭按钮，层的位置和显示由 showmenu() 来完成。
	id: 菜单id，通常为 hash(url)
	attr: 菜单外观属性，回调函数
	level: 菜单浮动的级别，默认为第一层
	remove: 1,清除节点 2,隐藏节点
*/
function createmenu(menuid, attr, level, remove) {
	if(typeof level == 'undefined') level = 1;
	if(typeof remove == 'undefined') remove = 1;
	// 清除同一层的menu，及子层的menu
	for(var k in Menus) {
		if(Menus[k].level >= level) hidemenufunc(k);
	}
	Menus[menuid] = {"level":level,"timeout":0,"remove":remove}

	var div = document.createElement('DIV');
	div.className = 'ajaxmenu';

	div.onclick = function(e) {
		// 隐藏子层menuid
		for(var k in Menus) {
			if(Menus[k].level > level) hidemenufunc(k);

		}
		_cancelBubble(e ? e : event, true);
	}
	div.id = menuid;
	div.innerHTML = '<div style="border:1px solid '+COLOR5+'; background:'+COLOR1+'; padding: 10px; margin:10px;" id="'+menuid+'border"><div id="'+menuid+'body"></div></div>';
	$('append').appendChild(div);
	if(attr) {
		var confirm = isset(attr.confirm);
		var myalert = isset(attr.myalert);
		s = '<div style="text-align: center; margin-top: 1em;"><input type="button" id="'+menuid+'confirm" value=" 确 定 " class="submit fillet" />　';
		if(confirm) s += '<input type="button" id="'+menuid+'close" value=" 关 闭 " onclick="hidemenu(\''+menuid+'\')" class="submit fillet" /></div>';
		$(menuid+'border').innerHTML += s;
		$(menuid+'confirm').onclick = confirm ? attr.confirm : (myalert ? attr.myalert : null);
		if(myalert) {
			_attachEvent($(menuid+'confirm'), 'click', function(){hidemenu(menuid)});
		}
	}
	return menuid;
}

/*
	title: 内容
	level: 层的级别，类似于 z-index，默认为 1
	timeout: 层消失的时间，默认为  3 秒
	recall: 点击确定的时候的回调函数
*/
function ajaxalert(title, level, timeout, recall) {
	var level = level ? level : 1;
	var recall = isset(recall) ? recall : null;
	var timeout = isset(timeout) ? timeout : 3;
	var menuid = createmenu(hash(title), {myalert:recall}, level);
	//title = '<div><img src="image/common/confirm_2.gif" /> &nbsp; '+title+'</div>'
	title = '<div> '+title+' </div>'
	$(menuid + 'body').innerHTML = title;
	showmenu(menuid, 1);
	if(timeout) hidemenu(menuid, timeout);
}

/**
	menuid: 菜单 id
	position: 默认为 0 相对链接，1 绝对居中，2 位置不变(自定义层位置时)
*/
function showmenu(menuid, position, timeout) {
	e = is_ie ? event : showmenu.caller.arguments[0];
	controlid = e ? (is_ie ? e.srcElement : e.target ) : null;
	showmenu2(e, menuid, position, controlid, timeout);
}

/**
	event: 事件
	controlid: 可选参数，不填时，表示当前鼠标事件点击的元素，通常就是 A
*/
function showmenu2(e, menuid, position, controlid, timeout) {
	// 隐藏同级菜单
	// 清除同一层的menu，及子层的menu
	for(var k in Menus) {
		if(Menus[k].level >= Menus[menuid].level) {
			// 此处为解决方案，以后需要修正，并未真正remove节点，可能会有隐患。（remove节点的时候出现错误，待查）
			show(k, 'none');
		}
	}

	position = position ? position : 0;
	$(menuid).style.display = 'block';// 只有 display 显示后，div 才会有 clientHeight clientWidth 等属性
	if($(menuid).className.indexOf('ajaxmenu') == -1) $(menuid).className += ' ajaxmenu';
	setposition(menuid, position, controlid);
	_cancelBubble(e);
}

function hidemenufunc(k){
	// 清除层的内容
	if(!$(k)) return true;
	if(Menus[k].remove) {
		$(k).innerHTML = '';
		$(k).parentNode.removeChild($(k));
		delete Menus[k];
	} else {
		show(k, 'none');
	}
};

function hidemenu(menuid, timeout) {
	// 隐藏所有menu
	if(!$(menuid)) { for(var k in Menus) hidemenufunc(k); return;}
	menu = Menus[menuid];
	for(var k in Menus) {
		// 关闭所有高的子层
		if(Menus[k].level >= menu.level) {
			setTimeout('hidemenufunc(\''+k+'\');', (timeout ? timeout * 1000 : 0));
		}
	}
}

function setposition(menuid, position, controlid) {
	var menuid = $(menuid);
	if(!menuid) return;
	// 相对定位
	if(position == 0) {
		var controlpos = getposition(controlid);
		menuid.style.left = controlpos['x'] + 'px';
		menuid.style.top = controlpos['y'] + controlid.offsetHeight + 'px';
		menuid.style.marginLeft = '0px';
		menuid.style.marginTop = '0px';
	// 绝对居中定位
	} else if(position == 1 || position == 2) {
		menuid.style.top = '50%';
		menuid.style.left = '50%';
		var scrolltop = parseInt(document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) - (position == 1 ? 50 : 0);
		menuid.style.marginLeft = '-' + parseFloat(menuid.offsetWidth / 2) + 'px';
		menuid.style.marginTop = scrolltop - parseFloat(menuid.offsetHeight / 2) + 'px';
	}
}

function parsexml_childnodes(oNode) {
	var newNode = new Object();//oNode为只读属性，所以只能产生一个新的对象来存贮数据
	if(oNode.childNodes) {
		for(var i=0; i<oNode.childNodes.length; i++) {
			if(oNode.childNodes[i].nodeName) {
				newNode[oNode.childNodes[i].nodeName] = oNode.childNodes[i];
				if(!is_ie) newNode[oNode.childNodes[i].nodeName].text = newNode[oNode.childNodes[i].nodeName].textContent;
			}
		}
	}
	return newNode;
}

function parsexml(xmlString) {
	if(is_ie) {
		doc = new ActiveXObject("Microsoft.XMLDOM")
		doc.async="false"
		doc.loadXML(xmlString)
	} else {
		var parser = new DOMParser()
		doc = parser.parseFromString(xmlString, "text/xml")
	}
	var newNode = parsexml_childnodes(doc.firstChild);
	return newNode;
}

_attachEvent(document, 'click', function(event){if(event.button != 2) {hidemenu()}});

var loginrecall = function(menuid) {
	return function(s) {
		// 中断性错误
		if(s.servererror) {
			alert('服务端发生错误：\n'+s.servererror.text);
		}
		if(s.error && s.error.text == 1) {
			if(s.message.text)alert(s.message.text);
			$('username').focus();
		} else {
			// 不中断流程
			if(s.error && s.error.text == 2) {
				alert(s.message.text);
			}
			if(s.clientrecall && s.clientrecall.text != '') {
				ajaxmenu(s.clientrecall.text, 1);
				$('navuser').innerHTML = s.navuser.text;
			} else {
				window.location.reload();
			}
		}
	}
}

function checkall(o, name) {
	var inputs = document.getElementsByTagName("INPUT");
	var checked = o.checked;
	var len = inputs.length;

	for(var i=0; i<len; i++) {
		if(inputs[i].type=='checkbox' && inputs[i].name.slice(0, name.length) == name) {
			inputs[i].checked = checked;
		}
	}
}

function getcookie(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	seconds = seconds ? seconds : 8400000;
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

/* 查询输入框显示文字 */
function checkKeywords(e, isfocus) {
	if(isfocus) {
		if(e.value == '输入关键字查找') {
			e.value = '';
		}
	} else {
		if(e.value == '') {
			e.value = '输入关键字查找';
		}
	}
}
/* JS复制代码 */
function copy2Clipboard(txt){ 
	if(window.clipboardData){ 
		window.clipboardData.clearData(); 
		window.clipboardData.setData("Text",txt); 
	}else if(navigator.userAgent.indexOf("Opera")!=-1){ 
		window.location=txt; 
	}else if(window.netscape){ 
	try{ 
		netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect"); 
	} 
	catch(e){ 
		alert("您的firefox安全限制限制您进行剪贴板操作，请打开'about:config'将signed.applets.codebase_principal_support'设置为true'之后重试，相对路径为firefox根目录/greprefs/all.js"); 
		return false; 
	} 
	var clip=Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard); 
	if(!clip)return; 
	var trans=Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable); 
	if(!trans)return; 
	trans.addDataFlavor('text/unicode'); 
	var str=new Object(); 
	var len=new Object(); 
	var str=Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString); 
	var copytext=txt;str.data=copytext; 
	trans.setTransferData("text/unicode",str,copytext.length*2); 
	var clipid=Components.interfaces.nsIClipboard; 
	if(!clip)return false; 
		clip.setData(trans,null,clipid.kGlobalClipboard); 
		return true;
	} 
}
/* 搜索条选择 */
function set_condit(value){
		if(!value) {
			if($('ss_item').style.display == 'none') {
				$('ss_item').style.display = 'block';
			} else {
				$('ss_item').style.display = 'none';
			}
		} else {
			if(value == 'novelName') {
					$('searchType').value = 'novelName';
					$('search_select').value = '小说';
					$('ss_item').style.display = 'none';
			} else if(value == 'author') {
					$('searchType').value = 'author';
					$('search_select').value = '作者';
					$('ss_item').style.display = 'none';
			}
		}
}
function searchSubmit() {
	if($('keywords2').value == '' || $('keywords2').value=='输入关键字查找') {
		ajaxalert('请输入搜索的关键字');
		return false;
	}
	return true;
}
