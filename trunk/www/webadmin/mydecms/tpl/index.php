<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>���¹���ϵͳ��̨</title>
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
var menu=$("topTags").getElementsByTagName("ul")[0];//�����˵�����
var tags=menu.getElementsByTagName("li");//�����˵�
var ck=$("leftnav").getElementsByTagName("A");//���˵�
var j;
//������˵������±�ǩ
	for(i=0;i<ck.length;i++){
		ck[i].onclick=function(){
		//this.style.background='url(css/tabbg02.gif)';
		//ѭ��ȡ�õ�ǰ����
			for(j=0;j<ck.length;j++){
				if(this==ck[j]){
					if(ck[j].target=="_parent"){
						window.location=ck[j].href;
					}else{
						if(ck[j].target=="_blank") return true;

							if($("p"+j)==null){
								openNew(j,this.innerHTML);//���ñ�ǩ��ʾ����
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
//���ӻ�ɾ����ǩ
function openNew(id,name){
var tagMenu=document.createElement("li");
tagMenu.id="p"+id;
tagMenu.innerHTML=name+"<img src='css/off.gif' style='vertical-align:middle'/>";
//��ǩ����¼�
tagMenu.onclick=function(evt){
//ck[id].style.background='url(css/tabbg02.gif)'
clearStyle();
tagMenu.style.background='url(css/tabbg1.gif)';
clearContent();
$("c"+id).style.display="block";
}
//��ǩ�ڹر�ͼƬ����¼�
tagMenu.getElementsByTagName("img")[0].onclick=function(evt){
evt=(evt)?evt:((window.event)?window.event:null);
if(evt.stopPropagation){evt.stopPropagation()} //ȡ��opera��Safarið����Ϊ;
this.parentNode.parentNode.removeChild(tagMenu);//ɾ����ǰ��ǩ
var color=tagMenu.style.backgroundColor;
//��������ر�һ����ǩʱ�������һ����ǩ�õ�����
if(color=="#ffff00"||color=="yellow"){//�������������ɫ����
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
//����˵���ʽ

//�����ǩ��ʽ
function clearStyle(){
for(i=0;i<tags.length;i++){
menu.getElementsByTagName("li")[i].style.background='url(css/tabbg2.gif)';
  }
}
//�������
function clearContent(){
for(i=0;i<ck.length;i++){
$("c"+i).style.display="none";
 }
$("welcome").style.display="none";//��ӭ��������
}
}

function Fzhonghe(tourl){
	var menu=$("topTags").getElementsByTagName("ul")[0];//�����˵�����
	var tags=menu.getElementsByTagName("li");//�����˵�
	var ck=$("leftnav").getElementsByTagName("A");//���˵�

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
  <div class="logo"><a href="http://www.mydecms.com" target="_blank">�ͳ����¹���ϵͳ</a></div>
  <div id="topTags">
    <ul>
      <li onclick="javascript:Fzhonghe(false);" id="zh">��̨��ҳ</li>
    </ul>
  </div>
</div>
<div class="left" id="left">
  <div class="menu" id="menu">
    <div id="leftnav">
      <div class="nav top10">
        <h3 onclick="dislpay(1);">�ۺϹ���</h3>
        <ul id="nav-ul-1">
          <li class="f"><a href="gotohtml.php?action=index">������ҳ</a></li>
          <li class="f"><a href="setweb.php">ϵͳ����</a></li>
          <li class="f"><a href="setweb.php?action=pass">�޸�����</a></li>
          <li class="f"><a href="spider.php">֩���¼</a></li>
          <li class="f"><a href="fujian.php">��������</a></li>
          <li class="f"><a href="login.php?action=out" target="_parent">�˳�����</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(2);">���¹���</h3>
        <ul id="nav-ul-2">
          <li class="f"><a href="dafenglei.php?action=add">��ӷ���</a></li>
          <li class="f"><a href="dafenglei.php">�����б�</a></li>
          <li class="f"><a href="article.php?action=add">�������</a></li>
          <li class="f"><a href="article.php">�����б�</a></li>
          <li class="clear"><a href="article.php?action=quyu_html">��ָ��������������HTML</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(6);">�����˵�����</h3>
        <ul id="nav-ul-6">
          <li class="f"><a href="nav.php?action=add">��Ӳ˵�</a></li>
          <li class="f"><a href="nav.php">�˵��б�</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(5);">��������</h3>
        <ul id="nav-ul-5">
          <li class="f"><a href="alink.php?action=add">�������</a></li>
          <li class="f"><a href="alink.php">�����б�</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(3);">ģ�����</h3>
        <ul id="nav-ul-3">
          <li class="f"><a href="moban.php?action=add">���ģ��</a></li>
          <li class="f"><a href="moban.php">ģ���б�</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(4);">�������ӹ���</h3>
        <ul id="nav-ul-4">
          <li class="f"><a href="link.php?action=add">�������</a></li>
          <li class="f"><a href="link.php">�����б�</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="nav top10">
        <h3 onclick="dislpay(7);">��վ������</h3>
        <ul id="nav-ul-7">
          <li class="f"><a href="ad.php?action=add">��ӹ��</a></li>
          <li class="f"><a href="ad.php">����б�</a></li>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
<div class="right" id="right">
  <div class="main">
      <div id="welcome" class="content">
        <div style="padding:10px">��ӭ������̨����ϵͳ��</div>
      </div>
	<script type="text/javascript">
	var ck = $("leftnav").getElementsByTagName("A");//���˵�
	var i;
	//������˵������±�ǩ
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
