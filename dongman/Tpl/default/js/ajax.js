var XmlHttp;
function $FF(id){return document.getElementById(id);}
function CreateXMLHttpRequest(){
	if (window.XMLHttpRequest) { // Non-IE browsers
		XmlHttp = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE
		XmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}		
}
function plus_cm_list(sid,root,id){
	plus_cm_get(root+'index.php?s=Cm-index-id-'+id+'-cm_sid-'+sid+'-p-1');
}
function plus_cm_get(url){
	CreateXMLHttpRequest();
	XmlHttp.open("GET",url,true);
	XmlHttp.onreadystatechange = function(){
		if(XmlHttp.readyState == 4){
			if(XmlHttp.status == 200){
				$FF('ajax_vod_cm').innerHTML=XmlHttp.responseText;
			}else{alert("加载评论信息异常!");} 
		}else{
			$FF('ajax_vod_cm').innerHTML='loading...';
		}
	};
	XmlHttp.send(null);
}
function plus_cm_post(sid,root,id){
	var $$=document.plus_cm_vod;
	if($$.cm_user.value==''){alert("用户呢称不能为空！");return false;}
	if($$.cm_content.value==''){alert("评论内容不能为空！");return false;}	
	if($$.cm_code){if($$.cm_code.value=='') {alert("请填写验证码！");return false;}}
	if($$.cm_user.value.length > 15){alert("你的网名是不是太另类了？");return false;}
	if($$.cm_content.value.length > 500){alert("你的评论是不是太长了？请填写500字以内的评论。");return false;}
	param='cm_cid='+id+'&cm_sid='+sid+'&cm_face='+getRadio('cm_face')+'&cm_code='+$$.cm_code.value+'&cm_user='+encodeURIComponent($$.cm_user.value)+'&cm_content='+encodeURIComponent($$.cm_content.value)+'&__ppvod__='+$$.__ppvod__.value+'';
	CreateXMLHttpRequest();
	XmlHttp.open("POST",root+'index.php?s=Cm-Add',true);
	XmlHttp.onreadystatechange = function(){
		if(XmlHttp.readyState == 4){
			if(XmlHttp.status == 200){
				var res=XmlHttp.responseText;
				if(res.indexOf("验证码不正确")>0){
					alert('您填写的验证码不正确,请重输入!');
				}else if(res.indexOf("坐下来稍息一下")>0){
					alert('您评论的动作太快了,休息一下再评论!');
				}else if(res.indexOf("数据写入错误")>0){
					alert('提交评论信息出错,请谅解!');
				}else if(res.indexOf("表单令牌错误")>0){
					alert('表单提交出错,请谅解!');
				}else{
					$FF('ajax_vod_cm').innerHTML=res;
					alert('发表评论成功!');
				}
			}
		}
	};  
    XmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	XmlHttp.send(param);
	return false;
}
function plus_cm_ud(cmid,root,tid){
	CreateXMLHttpRequest();
	XmlHttp.open("GET",root+'index.php?s=Cm-ajaxud-id-'+cmid+'-tid-'+tid,true);
	XmlHttp.onreadystatechange = function(){
		if(XmlHttp.readyState == 4){
			if(XmlHttp.status == 200){
				var res=XmlHttp.responseText;
				if(res==0){
					alert('您已经为该评论投过票了,感谢您的参与!');
				}else{
					$FF(tid+cmid).innerHTML=res;
				}
			}
		}
	};
	XmlHttp.send(null);
}
function getRadio(objName) { 
	var objs = document.getElementsByName(objName); 
	for(var i=0; i<objs.length; i++) { 
		if(objs[i].tagName.toLowerCase()=='input' && objs[i].checked) return objs[i].value;
	} 
	return null; 
}
//顶踩与评分-----主要用到ajax的get方式
function $(id){return document.getElementById(id);}
function $ajaxff84(url){
	var req;
	if (window.XMLHttpRequest) { // Non-IE browsers
		req = new XMLHttpRequest();
		try {
			req.open("GET", url, false);
		} catch (e) {
			alert(e);
		}
		req.send(null);
	} else if (window.ActiveXObject) { // IE
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.open("GET", url, false);
			req.send();
		}
	}
	if (req.readyState == 4) { 
		if (req.status == 200) { 
			return req.responseText;
		}
	} else {
		return -1;
	}
}
function plus_news_ud(id,type,root){
	var url = root+'index.php?s=news-ajax-id-'+id+'-t-'+type;
	var ajaxhtml = $ajaxff84(url);
	if (ajaxhtml == -1) {
		msg = "暂时不能进行投票!";
	}else if(ajaxhtml == 0){
		msg = "您已经投过票了，感谢您的参与!";
	}else{
		var diggs = ajaxhtml.split(':');
		var sUp = parseInt(diggs[0]);
		var sDown = parseInt(diggs[1]);
		var sTotal = sUp+sDown;
		var spUp=(sUp/sTotal)*100;
		spUp=Math.round(spUp*10)/10;
		var spDown=100-spUp;
		spDown=Math.round(spDown*10)/10;
		if(sTotal!=0){
			$('s1').innerHTML=sUp;
			$('s2').innerHTML=sDown;
			$('sp1').innerHTML=spUp+'%';
			$('sp2').innerHTML=spDown+'%';
			$('eimg1').style.width = parseInt((sUp/sTotal)*55);
			$('eimg2').style.width = parseInt((sDown/sTotal)*55);
		}
		msg = "投票成功！";
	}
	if(type!=0){alert(msg);}
}
function plus_vod_ud(id,type,root){
	var url = root+'index.php?s=vod-ajax-id-'+id+'-t-'+type;
	var ajaxhtml = $ajaxff84(url);
	if (ajaxhtml == -1) {
		msg = "暂时不能进行投票!";
	}else if(ajaxhtml == 0){
		msg = "您已经投过票了，感谢您的参与!";
	}else{
		var diggs = ajaxhtml.split(':');
		var sUp = parseInt(diggs[0]);
		var sDown = parseInt(diggs[1]);
		$('s1').innerHTML=sUp;
		$('s2').innerHTML=sDown;
		msg = "投票成功！";
	}
	if(type!=0){alert(msg);}
}
function plus_gold(tid){
	var url = gold_root+'index.php?s='+gold_model+'-gold-id-'+gold_id+'-t-'+tid;
	var ajaxhtml = $ajaxff84(url);
	if (ajaxhtml == -1) {
		msg = "暂时不能进行评分！";
	}else if(ajaxhtml == 0){
		msg = "您已经评过分了，感谢您的参与！";
	}else{
		var diggs = ajaxhtml.split(':');
		$('gold').innerHTML=parseFloat(diggs[0]);
		$('golder').innerHTML=parseInt(diggs[1]);
		if($('goldall')){
			$('goldall').innerHTML=parseInt(diggs[0]*diggs[1]);
		}
		plus_gold_img(parseFloat(diggs[0]));
		msg = "评分成功，感谢您的参与！";
	}
	if(tid!=0){alert(msg);}
}
function plus_gold_img(num) {
	var stars = document.getElementById('plus_gold').getElementsByTagName("img");
	for (var i = 0; i < 10; i++) {
		stars[i].src = gold_root+"Tpl/default/images/x2.png";
	}
	for (var i = 0; i < num; i++) {
		stars[i].src = gold_root+"Tpl/default/images/x1.png";
	}
}
function plus_gold_show() {
	plus_gold_img($('gold').innerHTML);
}