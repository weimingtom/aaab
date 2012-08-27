

;(function($){
	$.fn.extend({
		"Albums_hp":function(options){//定义"幻灯片"jQuery扩展方法
			options=$.extend({//设置默认值
				speed:400,//图片轮换速度
				eventFirType:"mouseover",
				eventSecType:"mouseout",
				time:3000,//图片轮换时间
				timer:"",
				auto:true//是否制动转换图片
			},options);
			var $ulNum = $(this).children("ul");
			var $ul_frist = $($ulNum[0]);
			var $ul_frist_li = $("li", $ulNum[0]);
			var Num = $ul_frist_li.length;
			var img_height =302;/* $("img", $ul_frist_li[0]).height();*/
			var img_width = $("img", $ul_frist_li[0]).width();
			var $ul_second_li = $("li", $ulNum[1]);
			var currentImg = 0;
			//var description_bar_top= $("#description_bar").offset().top;
			//$ul_frist_li.eq(Num - 1).clone().addClass("clone").prependTo($ul_frist);
			$ul_frist_li.eq(0).clone().addClass("clone").appendTo($ul_frist);
			$ul_second_li.each(function(index){
				$(this).bind(options.eventFirType, function(){
					currentImg = index;
					$("span", $ul_second_li).remove();
					$(this).append("<span></span>");
					clearInterval(options.timer);
					//clearTimeout(options.timer);					
					$ul_frist.stop().animate({top: -currentImg*img_height}, options.speed);
					$("li", "#description_bar").text($ul_frist_li.eq(currentImg).children("a").children("img").attr("alt"));
				}).bind(options.eventSecType,function(){
					autoStart();
				});
			});
			$ul_frist_li.each(function(index){
				$(this).bind(options.eventFirType, function(){
					clearInterval(options.timer);
					//clearTimeout(options.timer);
				}).bind(options.eventSecType,function(){
					autoStart();
				});
			});
			function autoStart(){
				if(options.auto){
					options.timer = setInterval(function(){
					//options.timer = setTimeout(function(){
						//$("#description_bar").animate({top: description_bar_top - 25}, options.speed);
						currentImg = currentImg >= Num  ? 0 : currentImg + 1;
						$("span", $ul_second_li).remove();
						var description_title = $ul_frist_li.eq(currentImg).children("a").children("img").attr("alt");
						if(currentImg == Num){
							$("li", "#description_bar").text($ul_frist_li.eq(0).children("a").children("img").attr("alt"));
						}else{
							$("li", "#description_bar").text(description_title);
						}
						$ul_second_li.eq(currentImg).append("<span></span>");
						options.time = 3000;
						if( currentImg != 0){
							//$("#description_bar").animate({top: description_bar_top}, options.speed / 2);
							//options.time = 3000;
							$ul_frist.animate({top: -currentImg*img_height}, options.speed);
							if( currentImg == Num - 1 ){								
								//$ul_second_li.eq(0).append("<span></span>");
								options.time = 0;
							}
							if( currentImg == Num ){								
								$ul_second_li.eq(0).append("<span></span>");
								options.time = 0;
							}
						}else{
							//options.time = 3000;
							$ul_frist.css({top: 0});
							clearInterval(options.timer);
							autoStart();
						}
						
					},options.time);
				}
			};
			autoStart();
			return this;  //返回this，使方法可链。
		}
	});
})(jQuery);


$(function(){
	$("#albums_hp").Albums_hp({speed:400, time:3000, auto:true});
});