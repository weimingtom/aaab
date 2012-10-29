lastScrollY = 0;
function heartBeat(){
var diffY;
if (document.documentElement && document.documentElement.scrollTop)
diffY = document.documentElement.scrollTop;
else if (document.body)
diffY = document.body.scrollTop
else
{/*Netscape stuff*/}
//alert(diffY);
percent=.1*(diffY-lastScrollY);
if(percent>0)percent=Math.ceil(percent);
else percent=Math.floor(percent);
document.getElementById("leftDiv").style.top = parseInt(document.getElementById("leftDiv").style.top)+percent+"px";
document.getElementById("rightDiv").style.top = parseInt(document.getElementById("rightDiv").style.top)+percent+"px";
lastScrollY=lastScrollY+percent;
//alert(lastScrollY);
}
//下面这段删除后，对联将不跟随屏幕而移动。
window.setInterval("heartBeat()", 1);
//-->
//关闭按钮
function close_left1(){
    document.getElementById('left1').style.visibility='hidden';
	document.getElementById('leftDiv').style.display="none";
}
function close_left2(){
     document.getElementById('left2').style.visibility='hidden';
	 document.getElementById('leftDiv').style.display="none";
}
function close_right1(){
    document.getElementById('right1').style.visibility='hidden';
	document.getElementById('leftDiv').style.display="none";
}
function close_right2(){
      document.getElementById('right2').style.visibility='hidden';
	document.getElementById('leftDiv').style.display="none";
}

var duilianrandnum = Math.floor(Math.random()*100); //随机取一个位置 

// 百度广告
var baiduad = '<script type="text/javascript">';
baiduad += 'var cpro_id = "u1094212";';
baiduad += '</script>';
baiduad += '<script src="http://cpro.baidustatic.com/cpro/ui/f.js" type="text/javascript"></script>';

//显示样式
document.writeln("<style type=\"text\/css\">");
document.writeln("#leftDiv,#rightDiv{width:120px;height:500px;background-color:none;position:absolute;}");
document.writeln(".itemFloat{width:100px;height:auto;line-height:5px}");
document.writeln("<\/style>");
//以下为主要内容
document.writeln("<div id=\"leftDiv\" style=\"top:0px;left:0px\">");
//------左侧各块开始
//---L1
document.writeln("<div id=\"left1\" class=\"itemFloat\">");

if(duilianrandnum > 50) {
	document.writeln('<iframe src="http://www.yingkong.net/a/parent20120205.html" height="500" width="120" frameborder="0" scrolling="no"></iframe>')
} else {
	document.writeln(baiduad);
}

//document.writeln("<embed src=flash/100x300-left.swf width=100 height=300></embed>");
document.writeln("<br><a href=\"javascript:close_left1();\" title=\"关闭\">×<\/a><br><br><br><br>");
document.writeln("<\/div>");
//---L2
//document.writeln("<div id=\"left2\" class=\"itemFloat\">");
//document.writeln("<a target=_blank href=http://www.lanrentuku.com/><img border=0 src=images/100x100-left.gif></a>");
//document.writeln("<br><a href=\"javascript:close_left2();\" title=\"关闭上面的广告\">×<\/a>");
//document.writeln("<\/div>");
//------左侧各块结束
document.writeln("<\/div>");
document.writeln("<div id=\"rightDiv\" style=\"top:0px;right:0px\">");
//------右侧各块结束

//---R1
document.writeln("<div id=\"right1\" class=\"itemFloat\">");

if(duilianrandnum > 50) {
	document.writeln('<iframe src="http://www.yingkong.net/a/parent20120205.html" height="500" width="120" frameborder="0" scrolling="no"></iframe>')
} else {
	document.writeln(baiduad);
}
//document.writeln("<embed src=flash/100x300-right.swf width=100 height=300></embed>");
document.writeln("<br><a href=\"javascript:close_right1();\" title=\"关闭\">×<\/a><br><br><br><br>");
document.writeln("<\/div>");
//---R2
//document.writeln("<div id=\"right2\" class=\"itemFloat\">");
//document.writeln("<a target=_blank href=http://www.lanrentuku.com/><img border=0 src=images/100x100-right.gif></a>");
//document.writeln("<br><a href=\"javascript:close_right2();\" title=\"关闭上面的广告\">×<\/a>");
//document.writeln("<\/div>");
//------右侧各块结束
document.writeln("<\/div>");