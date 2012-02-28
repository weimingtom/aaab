<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>文章管理系统后台</title>
<link href="css/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
if(self!=top){window.parent.location="./index.php";}
function dislpay(i){
	var obj = document.getElementById("nav-ul-"+i);
	if(obj.style.display == "block" || obj.style.display == ""){
		obj.style.display="none";
	}else{
		obj.style.display = "block";
	}
}
function qihuan(img){
	var obj = document.getElementById("left");
	var right = document.getElementById("right");
	if(obj.style.display == ""){
		obj.style.display ="none";
		img.src="css/right.gif";
		right.style.left="20px";
		img.style.left="-6px";
	}else{
		obj.style.display = "";
		img.src="css/left.gif";
		right.style.left=obj.style.width;
		img.style.left=obj.style.width;
	}
}

window.onload=function(){
function $(id){return document.getElementById(id)}
var menu=$("topTags").getElementsByTagName("ul")[0];//顶部菜单容器
var tags=menu.getElementsByTagName("li");//顶部菜单
var ck=$("leftnav").getElementsByTagName("A");//左侧菜单
var j;
//点击左侧菜单增加新标签
	for(i=0;i<ck.length;i++){
		ck[i].onclick=function(){
		//this.style.background='url(css/tabbg02.gif)';
		//循环取得当前索引
			for(j=0;j<ck.length;j++){
				if(this==ck[j]){
					if(ck[j].target=="_parent"){
						window.location=ck[j].href;
					}else{
						if(ck[j].target=="_blank") return true;

							if($("p"+j)==null){
								openNew(j,this.innerHTML);//设置标签显示文字
								$("c"+j).innerHTML='<iframe src="'+ ck[j].href +'" width="100%" height="100%" frameborder="0"></iframe>';
							 }else{
							 	if($("c"+j).innerHTML !='<iframe src="'+ ck[j].href +'" width="100%" height="100%" frameborder="0"></iframe>'){
									$("c"+j).innerHTML='<iframe src="'+ ck[j].href +'" width="100%" height="100%" frameborder="0"></iframe>';
								}
							 }
							clearStyle();
							$("p"+j).style.background='url(css/tabbg1.gif)';
							clearContent();
							$("c"+j).style.display="block";
					}
				}
			
				
			}
		return false;
	}
	//morenhome.click();
}
//增加或删除标签
function openNew(id,name){
var tagMenu=document.createElement("li");
tagMenu.id="p"+id;
tagMenu.innerHTML=name+"<img src='css/off.gif' style='vertical-align:middle'/>";
//标签点击事件
tagMenu.onclick=function(evt){
//ck[id].style.background='url(css/tabbg02.gif)'
clearStyle();
tagMenu.style.background='url(css/tabbg1.gif)';
clearContent();
$("c"+id).style.display="block";
}
//标签内关闭图片点击事件
tagMenu.getElementsByTagName("img")[0].onclick=function(evt){
evt=(evt)?evt:((window.event)?window.event:null);
if(evt.stopPropagation){evt.stopPropagation()} //取消opera和Safari冒泡行为;
this.parentNode.parentNode.removeChild(tagMenu);//删除当前标签
var color=tagMenu.style.backgroundColor;
//设置如果关闭一个标签时，让最后一个标签得到焦点
if(color=="#ffff00"||color=="yellow"){//区别浏览器对颜色解释
if(tags.length-1>=0){
clearStyle();
tags[tags.length-1].style.background='url(css/tabbg1.gif)';
clearContent();
var cc=tags[tags.length-1].id.split("p");
$("c"+cc[1]).style.display="block";
//ck[cc[1]].style.background='url(css/tabbg1.gif)';
 }
else{
clearContent();
$("welcome").style.display="block"
   }
  }
}
menu.appendChild(tagMenu);
}
//清除菜单样式

//清除标签样式
function clearStyle(){
for(i=0;i<tags.length;i++){
menu.getElementsByTagName("li")[i].style.background='url(css/tabbg2.gif)';
  }
}
//清除内容
function clearContent(){
for(i=0;i<ck.length;i++){
$("c"+i).style.display="none";
 }
$("welcome").style.display="none";//欢迎内容隐藏
}
}

function Fzhonghe(tourl){
	var menu=$("topTags").getElementsByTagName("ul")[0];//顶部菜单容器
	var tags=menu.getElementsByTagName("li");//顶部菜单
	var ck=$("leftnav").getElementsByTagName("A");//左侧菜单

	for(i=0;i<tags.length;i++){
		menu.getElementsByTagName("li")[i].style.background='url(css/tabbg2.gif)';
	}
	for(i=0;i<ck.length;i++){
		$("c"+i).style.display="none";
	}
	var zh = $("zh").style.background='url(css/tabbg1.gif)';
	$("welcome").style.display="block";
}
function $(id){return document.getElementById(id)}
</script>
</head>
<body>
<div class="head">
  <div class="logo"><a href="http://www.mydecms.com" target="_blank">贤诚文章管理系统</a></div>
  <div id="topTags">
    <ul>
      <li onclick="javascript:Fzhonghe(false);" id="zh">后台主页</li>
    </ul>
  </div>
</div>
<div class="left" id="left">
  <div class="menu" id="menu">
    <div id="leftnav">
      <div class="nav top10">
        <h3 onclick="dislpay(1);">综合管理</h3>
        <ul id="nav-ul-1">
          <li class="f"><a href="gotohtml.php?action=index">生成首页</a></li>
          <li class="f"><a href="setweb.php">系统设置</a></li>
          <li class="f"><a href="setweb.php?action=pass">修改密码</a></li>
          <li class="f"><a href="spider.php">蜘蛛记录</a></li>
          <li class="f"><a href="fujian.php">附件管理</a></li>
          <li class="f"><a href="login.php?action=out" target="_parent">退出管理</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(2);">文章管理</h3>
        <ul id="nav-ul-2">
          <li class="f"><a href="dafenglei.php?action=add">添加分类</a></li>
          <li class="f"><a href="dafenglei.php">分类列表</a></li>
          <li class="f"><a href="article.php?action=add">添加文章</a></li>
          <li class="f"><a href="article.php">文章列表</a></li>
          <li class="clear"><a href="article.php?action=quyu_html">将指定区域文章生成HTML</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(6);">导航菜单管理</h3>
        <ul id="nav-ul-6">
          <li class="f"><a href="nav.php?action=add">添加菜单</a></li>
          <li class="f"><a href="nav.php">菜单列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(5);">内链管理</h3>
        <ul id="nav-ul-5">
          <li class="f"><a href="alink.php?action=add">添加内链</a></li>
          <li class="f"><a href="alink.php">内链列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(3);">模板管理</h3>
        <ul id="nav-ul-3">
          <li class="f"><a href="moban.php?action=add">添加模板</a></li>
          <li class="f"><a href="moban.php">模板列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(4);">友情链接管理</h3>
        <ul id="nav-ul-4">
          <li class="f"><a href="link.php?action=add">添加友链</a></li>
          <li class="f"><a href="link.php">友链列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(7);">网站广告管理</h3>
        <ul id="nav-ul-7">
          <li class="f"><a href="ad.php?action=add">添加广告</a></li>
          <li class="f"><a href="ad.php">广告列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
<div class="right" id="right">
  <div class="main">
      <div id="welcome" class="content">
        <div style="padding:10px">欢迎来到后台管理系统。</div>
      </div>
	<script type="text/javascript">
	var ck = $("leftnav").getElementsByTagName("A");//左侧菜单
	var i;
	//点击左侧菜单增加新标签
	for(i=0;i<ck.length;i++){
		document.write('<div id="c'+i+'" class="content">'+i+'</div>');
	}
	$("welcome").style.display ="block";
	</script>
  </div>
</div>
<img src="css/left.gif" id="qhh" name="qhh" onclick="qihuan(this);" />
</body>
</html>
