<?php
$z[0]=Array("global","",236,264);
$z[0][4]['name']="cfg_soft_lang";
$z[1]=Array("field","",277,296);
$z[1][4]['name']="title";
$z[2]=Array("global","",297,323);
$z[2][4]['name']="cfg_webname";
$z[3]=Array("field","",364,386);
$z[3][4]['name']="keywords";
$z[4]=Array("field","",426,477);
$z[4][4]['name']="description";
$z[4][4]['function']="html2text(@me)";
$z[5]=Array("global","",495,527);
$z[5][4]['name']="cfg_templets_skin";
$z[6]=Array("global","",611,643);
$z[6][4]['name']="cfg_templets_skin";
$z[7]=Array("global","",771,799);
$z[7][4]['name']="art_shortname";
$z[8]=Array("field","",815,843);
$z[8][4]['name']="nowpage";
$z[9]=Array("field","",862,892);
$z[9][4]['name']="totalpage";
$z[10]=Array("field","",911,940);
$z[10][4]['name']="namehand";
$z[11]=Array("field","",963,995);
$z[11][4]['name']="displaytype";
$z[12]=Array("field","",1565,1588);
$z[12][4]['name']="id";
$z[13]=Array("field","",1741,1764);
$z[13][4]['name']="id";
$z[14]=Array("field","",2027,2050);
$z[14][4]['name']="id";
$z[15]=Array("include","",2133,2169);
$z[15][4]['filename']="head2.htm";
$z[16]=Array("channel","\r\n		<span><a href='[field:typeurl/]'>[field:typename/]</a></span>",2260,2448);
$z[16][4]['type']="self";
$z[16][4]['currentstyle']="<span><a href='~typelink~' class='thisclass'>~typename~</a></span>";
$z[17]=Array("field","",2606,2635);
$z[17][4]['name']="position";
$z[18]=Array("field","",2719,2738);
$z[18][4]['name']="title";
$z[19]=Array("field","",2820,2876);
$z[19][4]['name']="pubdate";
$z[19][4]['function']="MyDate('Y-m-d H:i',@me)";
$z[20]=Array("field","",2898,2918);
$z[20][4]['name']="source";
$z[21]=Array("field","",2941,2961);
$z[21][4]['name']="writer";
$z[22]=Array("field","",2997,3024);
$z[22][4]['name']="phpurl";
$z[23]=Array("field","",3048,3071);
$z[23][4]['name']="id";
$z[24]=Array("field","",3076,3100);
$z[24][4]['name']="mid";
$z[25]=Array("field","\r\n                if(@me==3) {\r\n                	@me = '<ul class=\"e8\">';\r\n                }\r\n                else if(@me==2) {\r\n                	@me = \"\r\n					<center>\r\n						<a href='javascript:dPlayBig();' class='c1'>原始图片</a>\\r\\n\r\n						<a href='javascript:dPlayPre();' class='c1'>上一张</a>\\r\\n\r\n						<a href='javascript:dPlayNext();' class='c1'>下一张</a>\\r\\n\r\n						<a href='javascript:dStopPlay();' class='c1'>自动 / 暂停播放</a>\\r\\n\r\n					</center>\r\n                	\";\r\n                }\r\n                else {\r\n                	@me = '';\r\n                }\r\n             ",3226,3942);
$z[25][4]['name']="pagestyle";
$z[25][4]['alt']="根据不同的页面风格输出不同的头信息";
$z[25][4]['runphp']="yes";
$z[26]=Array("field","\r\n                [field:pagestyle runphp='yes'] @me= (@me==3 ? '<li>' : '');[/field:pagestyle]\r\n                <a href='[field:linkurl/]' target='_blank'  [field:pagestyle runphp='yes'] @me= (@me==3 ? 'class=\"pic\"' : ''); [/field:pagestyle]>\r\n                  <img src='[field:imgsrc/]' id='bigimg' [field:imgwidth /] alt='[field:alttext /]' border='0' />\r\n                </a>\r\n                <!-- 如果使用的是多页单图模式(幻灯)，把href里的链接改为 javascript:dPlayNext(); 表示点击看下一页 -->\r\n                <a href=\"[field:linkurl/]\" [field:pagestyle runphp='yes']@me = (@me==3 ? 'class=\"title\"' : '');[/field:pagestyle]>\r\n                	[field:alttext /]\r\n                </a>\r\n                [field:pagestyle runphp='yes'] @me = (@me==3 ? '</li>' : ''); [/field:pagestyle]\r\n             ",3959,4852);
$z[26][4]['name']="imgurls";
$z[26][4]['alt']="图片输出区";
$z[27]=Array("field","\r\n                if(@me==3) {\r\n                	@me = '</ul>';\r\n                }\r\n                else if(@me==2) {\r\n                	@me = \"<script language='javascript'>dStartPlay();</script>\\r\\n\";\r\n               	}\r\n               	else {\r\n               		@me = '';\r\n                }\r\n             ",4869,5285);
$z[27][4]['name']="pagestyle";
$z[27][4]['alt']="根据不同的风格输出不同的结尾信息";
$z[27][4]['runphp']="yes";
$z[28]=Array("myad","",5429,5464);
$z[28][4]['name']="contentMidPicAD";
$z[29]=Array("field","",5470,5488);
$z[29][4]['name']="body";
$z[30]=Array("pagebreak","",5615,5632);
$z[31]=Array("global","",5687,5712);
$z[31][4]['name']="cfg_phpurl";
$z[32]=Array("field","",5731,5747);
$z[32][4]['name']="id";
$z[33]=Array("prenext","",6001,6026);
$z[33][4]['get']="pre";
$z[34]=Array("prenext","",6043,6069);
$z[34][4]['get']="next";
$z[35]=Array("field","",6186,6213);
$z[35][4]['name']="phpurl";
$z[36]=Array("field","",6227,6243);
$z[36][4]['name']="id";
$z[37]=Array("field","",6310,6337);
$z[37][4]['name']="phpurl";
$z[38]=Array("field","",6357,6373);
$z[38][4]['name']="id";
$z[39]=Array("field","",6380,6399);
$z[39][4]['name']="title";
$z[40]=Array("field","",6466,6493);
$z[40][4]['name']="phpurl";
$z[41]=Array("field","",6512,6528);
$z[41][4]['name']="id";
$z[42]=Array("field","",6790,6817);
$z[42][4]['name']="phpurl";
$z[43]=Array("field","",6840,6856);
$z[43][4]['name']="id";
$z[44]=Array("memberinfos","\r\n			<dl class=\"tbox\">\r\n				<dt><strong>发布者资料</strong></dt>\r\n				<dd>\r\n					<a href=\"[field:spaceurl /]\" class=\"userface\"><img src=\"[field:face/]\" width=\"52\" height=\"52\" /></a>\r\n					<a href='[field:spaceurl /]' class=\"username\">[field:uname/]</a>\r\n					<span class=\"useract\">\r\n						<a href=\"[field:spaceurl /]\" class=\"useract-vi\">查看详细资料</a>\r\n                        <a href=\"[field:spaceurl /]&action=guestbook\" class=\"useract-pm\">发送留言</a>\r\n						<a href=\"[field:spaceurl /]&action=newfriend\" class=\"useract-af\">加为好友</a>\r\n					</span>\r\n					<span class=\"userinfo-sp\"><small>用户等级:</small>[field:rankname /]</span>\r\n					<span class=\"userinfo-sp\"><small>注册时间:</small>[field:jointime function=\"MyDate('Y-m-d H:m',@me)\"/]</span>\r\n					<span class=\"userinfo-sp\"><small>最后登录:</small>[field:logintime function=\"MyDate('Y-m-d H:m',@me)\"/]</span>\r\n				</dd>\r\n			</dl>\r\n			",7585,8549);
$z[45]=Array("arclist","\r\n						<li>\r\n							<a href=\"[field:arcurl/]\" class=\"preview\"><img src=\"[field:litpic/]\" alt=\"[field:title function='html2text(@me)'/]\"/></a>\r\n							<a href=\"[field:arcurl/]\" class=\"title\">[field:title/]</a>\r\n							<span class=\"intro\">更新:[field:pubdate function=\"GetDateMK(@me)\"/]</span>\r\n						</li>\r\n                     ",8709,9104);
$z[45][4]['row']="5";
$z[45][4]['type']="image.";
$z[45][4]['orderby']="click";
$z[46]=Array("myad","",9209,9243);
$z[46][4]['name']="contentRtPicAD";
$z[47]=Array("arclist","\r\n						<li><a href=\"[field:arcurl/]\"><img src=\"[field:litpic/]\" alt=\"[field:title function='html2text(@me)'/]\"/><span class=\"title\">[field:title/]</span></a></li>\r\n                    ",9390,9634);
$z[47][4]['row']="8";
$z[47][4]['type']="image.commend.";
$z[48]=Array("myad","",9738,9773);
$z[48][4]['name']="contentRtPicAD2";
$z[49]=Array("myad","",9877,9911);
$z[49][4]['name']="innerfooterAD2";
$z[50]=Array("include","",9919,9956);
$z[50][4]['filename']="footer.htm";

?>