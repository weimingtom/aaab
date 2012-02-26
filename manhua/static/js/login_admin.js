// JavaScript Document

$(function(){
		   
	   $('form').submit(function(){
			
			if($('#username').val() == ''){				
				$('label').eq(0).html('<font color=red>用户名不能为空</font>');
				$('#username').focus();
				return false;				
			}else{
				$('label').eq(0).html('');
			}
			
			if($('#password').val() == ''){
				$('label').eq(1).html('<font color=red>密码不能为空</font>');
				$('#password').focus();
				return false;
			}else{
				$('label').eq(1).html('');
			}
								 
		});
		   
})