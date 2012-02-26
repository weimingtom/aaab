$(document).ready(function(){

	$("body").append("<div id='carlendar_div'></div>");

	$(document).click(function(event){
		
		var element = event.target;
		var emeCls = $(element).attr("bj");
		
		if(emeCls != "cBj")
		{
			$("#carlendar_div").css("display", "none");
			$('select[name=wz7[]]').show();
			if(typeof adprice == 'function')
			{
				if($('#statime').attr('disabled') == false)adprice($('#xyjk').val(), $('#yhje').val());
				wzrmbjs();
			}
		}
		
 	 });
	
});

$.fn.ShowTime = function(options){
	
	var defaults = {
		ipt : this,
		evt : "click"
	};
	
	if(options) $.extend(defaults,options);
	
	//限制开始时间范围
	var startDate = defaults.sd;
	
	//限制结束时间范围
	var endDate = defaults.ed;
	
	var e = defaults.evt;
	var obj = defaults.ipt;
	
	if(e == "click") $(this).click(showCar);
	if(e == "mouseover") $(this).mouseover(showCar);
	
	//在页面上显示日期选择界面
	function showCar()
	{			
		document.getElementById('carlendar_div').onmouseover = function() {bOnWin = 1;}
		var iptId = $(obj).attr("id");
		var thisDate = $(obj).val();
		var dateReg = /^[12]\d{3}-(0?[1-9]|1[0-2])-((0?[1-9])|((1|2)[0-9])|30|31)$/;
		var dateRst = dateReg.test(thisDate);
		
		if(dateRst) var d = new Date(Date.parse(thisDate.replace(/-/g, '/')));
		else var d = new Date();
		
		//取得文本框的左边距位置
		var tLeft = $(obj).offset().left;
		
		//取得方本框的顶边距位置
		var tTop = $(obj).offset().top + 18;
		
		//取得年月日
		var syear	= d.getFullYear();
		var smonth	= d.getMonth() + 1;
		var sdate	= d.getDate();
		if(smonth < 10) smonth = "0" + smonth;		
		
		carHtml(syear, smonth, sdate, iptId);
		
		//设置显示日历层的位置
		$("#carlendar_div").css({display:"block", left:tLeft, top:tTop});
		
	}
	
	//生成日历html代码
	function carHtml(vYear, vMonth, vDay, vipt)
	{		
		var vDate = vYear + "-" + vMonth + "-01";
		
		//取得某个时间是星期几
		var vWeek = getWeek(vDate);
		
		//取得某个月的天数
		var vDayNum = getDayNum(vYear, vMonth);
		
		var tmpNum = Number(vDayNum) - 7 + Number(vWeek);

		if((tmpNum % 7) == 0 ) var line = tmpNum / 7;
		else var line = parseInt(tmpNum / 7) +1; 
		
		var cstr = '';
		cstr += "<table border='0' cellspacing='0' cellpadding='0'>";
		cstr += "<tr><td colspan='7'><table border='0' cellspacing='0' cellpadding='0'>";
		cstr += "<tr><td width='13'>&nbsp;</td><td width='20'><span id='ylow' bj='cBj'>&lt;&lt;</span></td><td width='44'><input name='inYear' id='inYear' type='text' value='"+ vYear +"' maxlength='4' bj='cBj' /></td>";
		cstr += "<td width='20'>年</td><td width='25' height='30'><input name='inMonth' type='text' id='inMonth' value='"+ vMonth +"' maxlength='2' bj='cBj' /></td>";
		cstr += "<td width='20'>月</td><td width='20'><span id='yadd' bj='cBj'>&gt;&gt;</span></td><td width='13'>&nbsp;</td>";
		cstr += "</tr></table></td></tr>";
		cstr += "<tr id='cssWeek'><td>日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td>六</td></tr>";
		
		for(var i = 0; i <= line; i++)
		{			
			cstr += "<tr>";
			
			for(var j = 0; j < 7; j++)
			{				
				var tDay = Number(i*7) + Number(j) - Number(vWeek) + 1;
				if(tDay < 1 || tDay > vDayNum) cstr += "<td>&nbsp;</td>";
				else if(tDay == vDay) cstr += "<td><a href='#this' id='cur' ipt='"+ vipt +"'>"+ tDay +"</a></td>";
				else cstr += "<td><a href='#this' ipt='"+ vipt +"'>"+ tDay +"</a></td>";
				
			}
			
			cstr += "</tr>";
			
		}
		cstr += "</table>";
				
		//显示出日历内容
		$("#carlendar_div").html(cstr);
		cstr = '';
		
		//手动输入框的年份和月份框的样式
		$("#carlendar_div #inYear").bind("click", showipt);
		$("#carlendar_div #inMonth").bind("click", showipt);
		
		//移开后设置的样式
		$("#carlendar_div #inYear").bind("blur", hideipt);
		$("#carlendar_div #inMonth").bind("blur", hideipt);
		
		//输入年份和月份后显示对应的日历
		$("#carlendar_div #inYear").bind("keyup", changeipt);
		$("#carlendar_div #inMonth").bind("keyup", changeipt);
		
		//上月和下月的翻页功能
		$("#carlendar_div #ylow").bind("click", lowMonth);
		$("#carlendar_div #yadd").bind("click", addMonth);
		
		//把选中的日期显示在日期框里
		$("#carlendar_div a").bind("click", setDateValue);
	}
	
	//返回星期
	function getWeek(dayValue)
	{
		var day = new Date(Date.parse(dayValue.replace(/-/g, '/'))); //将日期值格式化
		var week = day.getDay();
		return week;
	}
	//返回某个月天数
	function getDayNum(vYear, vMonth)
	{
		var Num = new Date(vYear, vMonth, 0).getDate();
		return Num;
	}
	//改变input样式
	function showipt()
	{
		$(this).css("border", "1px solid #993300")
	}
	function hideipt()
	{
		$(this).css("border", "1px solid #9BDF70");
	}
	//日期改变时重新生成日历html代码
	function changeipt(event)
	{		
		 if($.browser.msie) var keyStr = event.keyCode;
		 else var keyStr = event.which;
		 
		 if((keyStr >= 48 && keyStr <= 57) || (keyStr >= 96 && keyStr <= 105))
		 {
			var vYear = $("#inYear").val();
			var vMonth = $("#inMonth").val();
			var vDate = $("#carlendar_div #cur").text();
			if(vYear.length == 4 && vMonth.length == 2)
			{
				var cipt = $("#carlendar_div a").eq(0).attr("ipt");
				if(vYear >= 1900 && Number(vMonth) >=1 && Number(vMonth) <= 12) carHtml(vYear, vMonth, vDate, cipt);
				else alert("年份要大于1900\n月份要在01和12之间");
			}
		 }
		 
	}
	//点击向左向右箭头
	function lowMonth()
	{		
		var yVal = $("#inYear").val();
		var mVal = $("#inMonth").val()-1;
		var dVal = $("#carlendar_div #cur").text();
		var lipt = $("#carlendar_div a").eq(0).attr("ipt");
		if(mVal <= 0)
		{
			mVal = "12";
			yVal = yVal - 1;
		}
		if(mVal < 10) mVal = "0" + mVal;
		carHtml(yVal, mVal, dVal, lipt);
		
	}
	//点击向右向左箭头
	function addMonth()
	{		
		var yVal = $("#inYear").val();
		var mVal = Number($("#inMonth").val()) + 1;
		var dVal = $("#carlendar_div #cur").text();
		var aipt = $("#carlendar_div a").eq(0).attr("ipt");
		if(mVal > 12)
		{
			mVal = "1";
			yVal = Number(yVal) + 1;
		}
		if(mVal < 10) mVal = "0" + mVal;
		carHtml(yVal, mVal, dVal, aipt);
		
	}
	
	//设置选择的日期
	function setDateValue()
	{		
		var vipt = $(this).attr("ipt");
		var yVal = $("#inYear").val();
		var mVal = $("#inMonth").val();
		
		if(yVal.length == 4 && yVal > 1900 && mVal.length == 2 && Number(mVal) >= 1 && Number(mVal) <= 12)
		{
			var dVal = "0" + $(this).text();
			mVal = "0" + mVal;

			var currDate = yVal + "-" + mVal.slice(mVal.length - 2) + "-" + dVal.slice(dVal.length - 2);
			var cp1 = true;
			var cp2 = true;
			
			//检查选中的日期是否是规定的范围之内
			if(startDate != undefined) cp1=(new Date(Date.parse(currDate.replace(/-/g, '/'))) >= new Date(Date.parse(startDate.replace(/-/g, '/')))) ? true : false;
			if(endDate	 != undefined) cp2=(new Date(Date.parse(currDate.replace(/-/g, '/'))) <= new Date(Date.parse(endDate.replace(/-/g, '/')))) ? true : false;
			
			if(cp1 && cp2)
			{				
				$("#"+vipt).val(currDate);
				$("#carlendar_div").css("display","none");
				
				
			} else alert("您选择的时间不在允许的范围内");
			
		} else alert("年份要大于1900\n月份要在01和12之间");		
	}	
}