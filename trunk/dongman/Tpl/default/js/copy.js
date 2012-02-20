
// 自动 COPY 代码开始
function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}
function JM_cc(ob){
	var obj=MM_findObj(ob); if (obj) { 
	obj.select();js=obj.createTextRange();js.execCommand("Copy");}
	alert("已经复制成功，你可以在任何地方粘贴了！");
}

// 自动 COPY 代码结束
document.write('发给好友：<input name="page_url" style="border:1px #ABD2EA solid; PADDING: 1px; CURSOR: text; COLOR: #333333; " onmouseover="this.focus()" onfocus="this.select()" value="'+window.location.href+'" size="36"><input type="hidden" name="page_url2" value="'+window.location.href+' ——'+window.document.title+'"> <input style="border:1px #ABD2EA solid; FONT-SIZE: 12px;  WIDTH: 100px; color:#4C87AC; HEIGHT: 19px; background:#E9F6FE;" type="button" name="Button" class="button1" value="复制地址给好友!" onClick=JM_cc("page_url")>');