function pagego($url,$total){
	$page=document.getElementById('page').value;
	if($page>0&&($page<=$total)){
		$url=$url.replace('{!page!}',$page);location.href=$url;
	}
	return false;
}
function ChangeAction(action){
   document.myform.action=action;
   if(action.indexOf("Delall")>1){
       alert('删除后将不能还原，请慎重操作！');
   }
}
function CheckAll(form){
	for (var i=0;i<form.elements.length-4;i++){
		var e = form.elements[i];
		if (e.checked==false){
			e.checked = true;
		}else{
			e.checked = false;
		}
	}
}
//tab切换
function showtab(mid,no,n){
	for(var i=0;i<=n;i++){
		$('#'+mid+i).hide();
	}
	$('#'+mid+no).show();
}
//连载
function showcheckbox(cname,dname){
	if(document.getElementById(cname).checked==true){
	    showThumbnail_RateOrSize(1,dname);
	}else{
	    showThumbnail_RateOrSize(0,dname);
	}
}
function showThumbnail_RateOrSize(param,pname){
	if(param==1){
		document.getElementById(pname).style.display="block";
	}else{
		document.getElementById(pname).style.display="none";
	}
}
/*基于jquery的浮动窗口代码 适用于IE+ff
消息框遮罩层:<iframe id="show_upload_iframe" frameborder=0 scrolling="no" style="display:none; position:absolute;"></iframe><div id="show_upload">nothing...</div>'
页面加载loading中:<div id="body_loading" onClick="loaded();"><img src="__PUBLIC__/images/body_load.gif"></div>
关闭浮动窗口:<a href="javascript:hideupload()">关闭窗口建议用小图片</a>
*/
// 消息框loading
function loading(){
	 var o = $('#body_loading');
     o.css("left",(($(document).width())/2-(parseInt(o.width())/2))+"px");
     o.css("top",(($(document).height()+$(document).scrollTop())/2-(parseInt(o.height())/2))+"px");
	 o.fadeIn("fast");
}
// 消息框消失
function loaded(){
	var o = $('#body_loading');
	o.fadeOut("fast");
}
// 隐藏浮动窗口
function hideupload(){
	$('#show_upload').hide();
	$('#show_upload_iframe').hide();
}
// 弹出浮动窗口
function showupload(ajaxurl){
	loading();
	var o=$('#show_upload');
	var f=$('#show_upload_iframe');
	var top = 200;
	$.ajax({
		url: ajaxurl,
		//cache: false,
		success: function(res){
			loaded();
			o.html(res);
			o.css("left",(($(document).width())/2-(parseInt(o.width())/2))+"px");
			if($(document).scrollTop()>200){
				top = ($(document).height()+$(document).scrollTop())/2-(parseInt(o.height())/2);
			}
     		o.css("top",top+"px");
			f.css({'width':o.width(),'height':o.height(),'left':o.css('left'),'top':o.css('top')});
	 		f.show();
			o.show();
		}
	});
}
// 远程保存图片自动处理
function nextimg(){
	hideupload();
	showgetimg('index.php?s=Admin-Vod-ajaximgdown');
}
function showgetimg(ajaxurl){
	loading();
	var o=$('#show_upload');
	var f=$('#show_upload_iframe');
	var top = 200;
	$.ajax({
		url: ajaxurl,
		//cache: false,
		success: function(res){
			loaded();
			o.html(res);
			o.css("left",(($(document).width())/2-(parseInt(o.width())/2))+"px");
			if($(document).scrollTop()>200){
				top = ($(document).height()+$(document).scrollTop())/2-(parseInt(o.height())/2);
			}
     		o.css("top",top+"px");
			f.css({'width':o.width(),'height':o.height(),'left':o.css('left'),'top':o.css('top')});
	 		f.show();
			o.show();
			if(res=='end'){
				o.html('<h1>已经处理完所有数据 <a href="javascript:history.go(0)">点击这里关闭窗口</a></h1>');
			}else{
				setTimeout("nextimg()",5000);
			}
		}
	});
}