<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="static/img/houtai.css" rel="stylesheet" type="text/css" />
<title>默认首页 - rt798.com CMS管理系统</title>
<meta content="管理平台" name=keywords />
<meta content="管理平台" name=description />
<script language="javascript" type="text/javascript" src="static/js/jquery-1.3.2.min.js"></script>
<script language="javascript" type="text/javascript" src="static/js/command.js"></script>
<script language="javascript" type="text/javascript">	
$(function(){
	
	CMSAPP.addkehu = {
		
		subject: '采集器',
		
		init: function(){
			
			CMSAPP.command.webTitle(this.subject);
			
			CMSAPP.command.mtabletr();
			
		}
	}

	CMSAPP.addkehu.init();
	
});

</script>

<style type="text/css">
<!--
.STYLE1 {color: #0000FF}
.STYLE2 {color: #FF0000}
.STYLE4 {color: #000000}
-->
</style>
</head>
<body>
<? include $this->gettpl('admin_top');?>
<div id="wrap">
	<div class="path">
		<span><a href="admin.php?c=spider">采集管理</a></span>
		<span>配置采集工具</span>
	</div>

	<div class="content">
		<div class="c1">
			<div class="tab_b"> 
				<form action="admin.php?c=spider&a=setting" method="post" target="_blank"> 
				<table width="100%" class="table1" border="0" cellpadding="0" cellspacing="1">
					<tr>
						<td>项目名称</td>
						<td style="text-align:left" colspan="2"><input type="text" name="spiderName" id="spiderName" value="<?=$spider['spiderName']?>" style="width:230px" />*</td>
					</tr> 
					<tr>
						<td>编码转换</td>
						<td style="text-align:left"><input type="checkbox" name="cutCode" id="cutCode" value="gb2312" <? if($spider['cutCode']=='gb2312') { ?>checked="checked"<? } ?> />GBK转UTF8</td>
						<td></td>
					</tr> 
					<tr>
						<td width="14%">目标网址：</td> 
						<td width="40%" style="text-align:left">
						普通URL：<input name="normalUrl" type="text" class="w02" id="normalUrl" value="http://yy.17k.com/list/81463.html" style="width:230px" /><br/>
						分页URL：<input name="pageUrl" type="text" class="w02" id="pageUrl" value="" style="width:230px" /><br/>
						<br/>
						起始页：<input name="startPage" value="<?=$spider['startPage']?>" style="width:40px" /> 结束页：<input name="endPage" value="<?=$spider['endPage']?>" style="width:40px" /> 步长值：<input name="stepPage" value="<?=$spider['stepPage']?>" style="width:40px" />
						</td>
						<td width="46%" style="text-align:left">
						请填写正确的格式一定要以“http://”开头<br/>
						如果你想抓取多个分页请在URL中修改分页数字替换为[page]<br/>
						特殊URL是指起始页没有按规律的页面，放入起始页的URL
						</td> 
					</tr>
					<tr>
						<td>列表采集规则：</td>
						<td><textarea name="listRule" id="listRule" rows="7" cols="50"><?=$spider['listRule']?></textarea></td>
						<td align="left"><p>自定义正则语法规则(<span class="STYLE1">即用通配符替换原文内容</span>):<br />
						第一步,随意查看一个要采集的标题列表页HTML网页源代码.<br />
						第二步,在源代码里,随意找一篇文章的标题与网址,只能是一篇文章中的一小段代码,不能是两篇.<br />
						举例:比如要采集的某个列表页中的任何一篇文章的标题html代码大致如下<br />
						&lt;tr&gt;&lt;td&gt;&lt;a href=&quot;<span class="STYLE2">文章URL地址</span>&quot; title=&quot;<span class="STYLE2">文章标题</span>&quot;&gt;文章标题&lt;/a&gt;&lt;/td&gt;&lt;/tr&gt;<br />
						那么左边输入<br />
						&lt;tr&gt;&lt;td&gt;&lt;a href=&quot;<span class="STYLE2">{url=*}</span>&quot; title=&quot;<span class="STYLE2">{*}</span>&quot;&gt;<span class="STYLE2">{title=*}</span>&lt;/a&gt;&lt;/td&gt;&lt;/tr&gt;<br />
						<br />
						以上只是举例,其中：<br />
						<span class="STYLE2">{url=*}</span>代表标题网址通配符<br />
						<span class="STYLE2">{title=*}</span>代表标题通配符<br />
						<span class="STYLE2">{*}</span>代表不需要的内容通配符，比如当链接地址中有title描述的时候，就必须要使用到它,不能同时使用两个标题通配符的。</p>									</td>
					</tr>
					<tr>
						<td height="96">内容采集规则 ： </td>
						<td><textarea name="contentRule" id="contentRule" rows="7" cols="50"><?=$spider['contentRule']?></textarea></td>
						<td align="left">自定义正则语法规则<span class="STYLE1">(即用通配符替换原文内容):</span><br />
						第一步,打开查看任意一篇要采集的文章详细内容页HTML网页源代码.<br />
						第二步,查看网页源代码,找到文章内容,删除他,用通配符{content=*}替换,然后再把他前面与后面的一小段代码也复制过来即可.<br />
						<br />
						简单举例(常用):比如被采集的网站内容页的html代码如下<br />
						&lt;tr&gt;&lt;td&gt;<span class="STYLE2">文章内容部分</span>&lt;/td&gt;&lt;/tr&gt;&lt;table&gt; <br />
						那么右边输入<br />
						&lt;tr&gt;&lt;td&gt;<span class="STYLE2">{content=*}</span>&lt;/td&gt;&lt;/tr&gt;&lt;table&gt; <br />
						<br />
						说明:通配符的前后有一小段HTML代码,是不可少的,目的是为了找规则,不需要太多,也不要太少,达到唯一性即可.<br />
						<br />
						复杂举例(少用):比如内容的html代码如下<br />
						时间:2008-12-24 12:13abc不相关内容作者:张三abc不相关内容来源网站abc不相关内容&lt;tr&gt;&lt;td
						class=&quot;asc&quot;&gt;文章内容部分&lt;/td&gt;&lt;/tr&gt;&lt;table&gt; <br />
						那么右边输入<br />
						时间:<span class="STYLE2">{posttime=*}</span>abc<span class="STYLE2">{*}</span>作者:<span class="STYLE2">{author=*}</span>abc<span class="STYLE2">{*}</span>来源:<span class="STYLE2">{copyfrom=*}</span>abc<span class="STYLE2">{*}</span>&lt;tr&gt;&lt;td
						class=&quot;asc&quot; &gt;<span class="STYLE2">{content=*}</span>&lt;/td&gt;&lt;/tr&gt;&lt;table&gt; <br />
						注意：除内容外，其它每个参数后面，一般都带有<span class="STYLE4">{*}</span>非相关内容的通配符,通配符前面都有一个固定的字符，不能缺少固定的字符，如abc</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2" align="left">
							<input name="submit" type="submit" class="w02" value="提交" />
							<input name="spiderId" type="hidden" class="w02" value="<?=$spider['spiderId']?>" />
							<input name="mod" type="hidden" class="w02" value="<?=$mod?>" />
						</td>
					</tr>
				</table> 
				</form> 
			</div>
		</div>
	</div>
<? include $this->gettpl('admin_floor');?>
</div>
</body>
</html>