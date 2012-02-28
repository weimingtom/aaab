$(function(){
	$("input:text,input:password,textarea").focus(function(){

		$(this).css("border","1px solid #369");												   

	});

	$("input:text,input:password,textarea").blur(function(){

		$(this).css("border","1px solid #999");												   

	});
 });

function CheckAll(form)
{
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.Name != "chkAll")
       e.checked = form.chkAll.checked;
    }
}

function del(form,type,url){
	if(confirm('你确定要执行该操作吗?\n\n操作后将不可恢复！')){
		act_submit(form,type,url);
	}
	return;
}

function act_submit(form,type,url){
	var obj = document.form.elements;
	var j=0;
	for(var i=0;i<obj.length;i++)
		{
			if(obj[i].type=='checkbox')
			{
				if(obj[i].checked)
				{
					j++;
			}
		}
	}
	
	if(j>0 || type==true){
		document.form.action=url;
		document.form.submit();
	}else{
		alert("您还没有选择要操作的ID");
	}
}

function display(id,type){
var obj=document.getElementById(id);
obj.style.display = type;
}


function opendiv(action,divId)
{
	for (i=1;i < 3; i++){
			display("div_" + i,"none");
	}
	if(action=="open")
	{
		display(divId,"block");
	}
	else
	{
		display(divId,"none");
		display("fade","none");
	}
}
function fillfile(urlstr,file)
{
	document.getElementById('movefileurl').value=urlstr;
	document.getElementById('movefilename').value=file;
	opendiv('open','div_3');
}

var drag_=false
var D=new Function('obj','return document.getElementById(obj);')
var oevent=new Function('e','if (!e) e = window.event;return e')
function Move_obj(obj){
	var x,y;
	D(obj).onmousedown=function(e){
		drag_=true;
		with(this){
			style.position="absolute";var temp1=offsetLeft;var temp2=offsetTop;
			x=oevent(e).clientX;y=oevent(e).clientY;
			document.onmousemove=function(e){
				if(!drag_)return false;
				with(this){
					style.left=temp1+oevent(e).clientX-x+"px";
					style.top=temp2+oevent(e).clientY-y+"px";
				}
			}
		}
		document.onmouseup=new Function("drag_=false");
	}
}
