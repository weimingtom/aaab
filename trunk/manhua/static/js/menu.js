// JavaScript Document
/*
	普通广告
*/
function Examine()
{
	var v = 0;
	$('input[name=blshow[]]').each(function(k){
		if($.trim($(this).val()) != '')
		{
			ve = $(this).val();
			v = v + parseInt(ve);
		}
	});
	if(v > 100){
		$('#errbl').show();
		$('#adsubmit').attr('disabled', 'disabled');
	} else {
		$('#errbl').hide();
		$('#adsubmit').attr('disabled', '');
	}
}

function adtypehtml(v, t)
{	
	var str = '';
	var lss = '';
	if(v == 1) {
		style = 'noe_tableinfo';
		_style = 'noe_tabput' 
		_style1 = 'noe_tableinfoinput';
	} else {
		style = 'tableinfo';
		_style = 'tabput';
		_style1 = 'tableinfoinput';
	}
	if(v > 1)
	{
		var ls = '轮播显示比例：';		
		for(var s=0; s<v; s++)	{
			lss += '<input type="text" maxlength="3" name="blshow[]" class="whstyle" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\');Examine()" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');Examine()" /> % ';
		}
		$('#bbshow').html(ls+lss);
	} else $('#bbshow').html('');
	if(t == 1)
	{
		for(var i=1; i<=v; i++)
		{			
			str += '<div class="'+style+'">';
			str += '<table border="0" cellpadding="0" cellspacing="0">';
			str += '<tbody>';
			str += '<tr>';
			str += '<td align="right">网站名称：</td>';
			str += '<input name="aditemid[]" type="hidden" />';
			str += '<td><input name="webname[]" type="text" class="'+_style1+'" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">网站链接：</td>';
			str += '<td><input name="weblink[]" type="text" class="'+_style1+'" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">图片/Flash：</td>';
			str += '<td><input name="adaddress[]" type="text" class="'+_style1+'" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">广告尺寸：</td>';
			str += '<td>宽度<strong>:</strong>';
			str += '<input name="adwidth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素 * 高度<strong>:</strong>';
			str += '<input name="adheigth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素</td>';
			str += '</tr>';
			str += '</tbody>';
			str += '</table>';
			str += '</div>';
			if(i%2==0) str+= '<div style="clear:left"></div>';
		}
	}
	else if(t == 2)
	{
		for(var i=1; i<=v; i++)
		{
			str += '<div class="'+style+'">';
			str += '<input name="aditemid[]" type="hidden" />';
			str += '<table border="0" cellpadding="0" cellspacing="0">';
			str += '<tbody>';
			str += '<tr>';
			str += '<td align="right">左右边距：</td>';
			str += '<td>';
			str += '<input type="text" value="" class="whstyle" size="6" name="dladbianju[]" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素';
			str += '</td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">网站名称：</td>';
			str += '<td><input name="webname[]" class="'+_style+'" type="text" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right"><label id="adlink1">左边广告链接</label>：</td>';
			str += '<td><input name="weblink[]" class="'+_style+'" type="text" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right"><label id="adlink2">右边广告链接</label>：</td>';
			str += '<td><input name="weblinkto[]" class="'+_style+'" type="text" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">左边图片/Flash：</td>';
			str += '<td><input name="adaddress[]" class="'+_style+'" type="text" size="80" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">右边图片/Flash：</td>';
			str += '<td><input name="dlggrigthv[]" class="'+_style+'" type="text" size="80" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">广告尺寸：</td>';
			str += '<td>宽度<strong>:</strong>';
			str += '<input name="adwidth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素 * 高度<strong>:</strong>';
			str += '<input name="adheigth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素</td>';
			str += '</tr>';
			str += '</tbody>';
			str += '</table>';
			str += '</div>';
			if(i%2==0) str+= '<div style="clear:left"></div>';
		}		
	}
	else if(t == 3)//弹出广告
	{		
		for(var i=1; i<=v; i++)
		{
			str += '<div class="'+style+'">';
			str += '<input name="aditemid[]" type="hidden" />';
			str += '<table border="0" cellpadding="0" cellspacing="0">';
			str += '<tbody>';
			str += '<tr>';
			str += '<td align="right">边距设置：</td>';
			str += '<td>';
			str += '左边距：<input type="text" class="whstyle" value="" size="6" name="tcadleft[]" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" /> 像素&nbsp;&nbsp;';
			str += '上边距：<input type="text" class="whstyle" value="" size="6" name="tcadtop[]" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" /> 像素';
			str += '</td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">网站名称：</td>';
			str += '<td><input name="webname[]" class="'+_style1+'" type="text" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">网站链接：</td>';
			str += '<td><input name="weblink[]" class="'+_style1+'" type="text" size="50" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">图片/Flash：</td>';
			str += '<td><input name="adaddress[]" class="'+_style1+'" type="text" size="80" /></td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">广告尺寸：</td>';
			str += '<td>宽度<strong>:</strong>';
			str += '<input name="adwidth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素 * 高度<strong>:</strong>';
			str += '<input name="adheigth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素</td>';
			str += '</tr>';
			str += '</tbody>';
			str += '</table>';
			str += '</div>';
			if(i%2==0) str+= '<div style="clear:left"></div>';
		}		
	}
	else if(t == 4)
	{
		for(var i=1; i<=v; i++)
		{
			str += '<div class="'+style+'">';
			str += '<input name="aditemid[]" type="hidden" />';
			str += '<table border="0" cellpadding="0" cellspacing="0">';
			str += '<tbody>';
			str += '<tr>';
			str += '<td align="right">广告代码：</td>';
			str += '<td><input type="hidden" name="webname[]">';
			str += '<textarea name="adcode[]" class="'+_style1+'" cols="80" rows="3"></textarea>';
			str += '</td>';
			str += '</tr>';
			str += '<tr>';
			str += '<td align="right">广告尺寸：</td>';
			str += '<td>宽度<strong>:</strong>';
			str += '<input name="adwidth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素 * 高度<strong>:</strong>';
			str += '<input name="adheigth[]" class="whstyle" type="text" size="6" maxlength="4" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" onblur="this.value=this.value.replace(/[^\\d.]/g,\'\');" />';
			str += '像素</td>';
			str += '</tr>';
			str += '</tbody>';
			str += '</table>';
			str += '</div>';
			if(i%2==0) str+= '<div style="clear:left"></div>';
		}		
	}
	return str;
}