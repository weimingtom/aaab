var CMSAPP = {};

CMSAPP.command = {
	
	webTitle: function(subject){
		
		$('#nav a').filter(function(){return $.trim($(this).text()) == subject}).addClass('on');
		
	},
	adTitle: function(subject){
		
		$('#zimenu a').filter(function(){return $.trim($(this).text()) == subject}).css({'color':'#000000'});
		
	},
	dataTitle: function(subject)
	{
		$('.path a').filter(function(){return $.trim($(this).text()) == subject}).css({'color':'#000000', 'font-weight':'bold'});
	},
	
	mtabletr: function(){
	
		$(".table1 tr").each(function(){
			if(!$("th",this).length)
			{
				$(this).mousemove(function(){$(this).addClass('cur')});
				$(this).mouseout(function(){$(this).removeClass('cur')});
			}
		}); 
		$('input[name=P[]]').each(function(){
			$(this).bind('click',function(){
				if($(this).attr('checked'))
				{
					$(this).parent().parent().removeClass('cur');
					$(this).parent().parent().mousemove(function(){$(this).removeClass('cur');});
					$(this).parent().parent().addClass('table1a');
				}
				else
				{
					$(this).parent().parent().mousemove(function(){$(this).addClass('cur');});
					$(this).parent().parent().removeClass('table1a');
				}
			});
		});
	}
	
}

$(function(){	

	$('input[value="all"]').click( function(){
		if($(this).attr('checked'))
		{
			$('input[name=P[]]').each(function(){if($(this).attr('disabled')==false){$(this).attr('checked', true);} $(this).parent().parent().addClass('table1a'); $(this).parent().parent().mousemove(function(){$(this).removeClass('cur');});})
		}
		else
		{
			$('input[name=P[]]').each(function(){$(this).attr('checked', false); $(this).parent().parent().removeClass('table1a');$(this).parent().parent().mousemove(function(){$(this).addClass('cur');}); });
		}
		
		if($(this).attr('id') == 'boxall_2')
		{
			$(this).attr('checked') ? $('#boxall_1').attr('checked',true) : $('#boxall_1').attr('checked',false);
		}
		else if($(this).attr('id') == 'boxall_1')
		{
			$(this).attr('checked') ? $('#boxall_2').attr('checked',true) : $('#boxall_2').attr('checked',false);
		}
	});
	
	$('#inputdel').click(function(){if(!confirm('确定是否要删除吗?')){return false;}else{$('#state').val('del');}});
	$('#inputyjf').click(function(){if(!confirm('是否确认已缴费了?')){return false;}else{$('#state').val('yjf');}});
	$('#inputshibai').click(function(){if(!confirm('确认没有洽谈成功吗?')){return false;}else{$('#state').val('shibai');}});
	
});

function del(href){
	
	if(confirm('确定是否要删除吗?'))
	{		
		window.location.href = href;	
	}
		
}