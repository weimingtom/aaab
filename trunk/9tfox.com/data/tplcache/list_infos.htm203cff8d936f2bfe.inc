<?php
$z[0]=Array("global","",236,264);
$z[0][4]['name']="cfg_soft_lang";
$z[1]=Array("field","",277,296);
$z[1][4]['name']="title";
$z[2]=Array("global","",297,323);
$z[2][4]['name']="cfg_webname";
$z[3]=Array("field","",364,393);
$z[3][4]['name']="keywords";
$z[4]=Array("field","",433,491);
$z[4][4]['name']="description";
$z[4][4]['function']="html2text(@me)";
$z[5]=Array("global","",509,541);
$z[5][4]['name']="cfg_templets_skin";
$z[6]=Array("include","",648,683);
$z[6][4]['filename']="head.htm";
$z[7]=Array("field","",845,874);
$z[7][4]['name']="position";
$z[8]=Array("infolink","\r\n<table id='dedeinfolink'>\r\n<tr>\r\n	<td colspan='3' class='iftitle'>\r\n		信息附加条件：\r\n	</td>\r\n</tr>\r\n<tr>\r\n	<td width='50'>&nbsp;地 区：</td>\r\n	<td align='center' nowrap='yes'>\r\n		<div>[field:linkallplace/]</div>\r\n	</td>\r\n	<td>\r\n  	[field:nativeplace /]&nbsp;\r\n	</td>\r\n</tr>\r\n<tr><td colspan='3' class='spline'>&nbsp;</td></tr>\r\n<tr>\r\n	<td>&nbsp;类 型：</td>\r\n  <td align='center' nowrap='yes'>\r\n  	<div>[field:linkalltype/]</div>\r\n  </td>\r\n  <td>\r\n  	[field:infotype /]&nbsp;\r\n  </td>\r\n</tr>\r\n<tr><td colspan='3' class='spline'>&nbsp;</td></tr>\r\n</table>\r\n",906,1509);
$z[9]=Array("list","\r\n            <li><a href=\"[field:arcurl/]\" class=\"title\">[field:title/]</a><span class=\"date\">[field:pubdate function=\"GetDateTimeMK(@me)\"/]</span></li>\r\n            ",1585,1789);
$z[9][4]['pagesize']="20";
$z[10]=Array("pagelist","",1896,1967);
$z[10][4]['listitem']="info,index,end,pre,next,pageno";
$z[10][4]['listsize']="5";
$z[11]=Array("channel","\r\n		<li><a href='[field:typeurl/]'>[field:typename/]</a></li>",2231,2410);
$z[11][4]['type']="son";
$z[11][4]['currentstyle']="<li><a href='~typelink~' class='thisclass'>~typename~</a></li>";
$z[12]=Array("myad","",2515,2546);
$z[12][4]['name']="listRtPicAD";
$z[13]=Array("infoguide","\r\n                    <form name='infoguide' method='get' action='/plus/list.php'>\r\n                    [field:nativeplace /]\r\n                    [field:infotype /]\r\n                    <input type='hidden' name='tid' value='[field:typeid /]' />\r\n                    <input type='hidden' name='channelid' value='-8' />\r\n                    <span class='infosearchtxt'>关键字：</span><span><input type='text' name='keyword' value='' class=\"ipt-txt\" /></span>\r\n                    <input type='submit' value='搜索信息' class=\"btn-2\" style='cursor:pointer' />\r\n                    &nbsp;\r\n                    <input type='button' value='发布信息' class=\"btn-2\" \r\n                    onclick=\"location='/member/archives_sg_add.php?channelid=-8';\" style='cursor:pointer' />\r\n                    </form>\r\n                ",2679,3540);
$z[14]=Array("arclistsg","\r\n                        <li><span>[field:typename/]</span><a href=\"[field:arcurl/]\">[field:title/]</a></li>\r\n                    ",3741,3941);
$z[14][4]['orderby']="id";
$z[14][4]['titlelen']="60";
$z[14][4]['row']="10";
$z[15]=Array("arclistsg","\r\n                    	<li><span>[field:typename/]</span><a href=\"[field:arcurl/]\">[field:title/]</a></li>\r\n                    ",4165,4362);
$z[15][4]['sort']="click";
$z[15][4]['titlelen']="30";
$z[15][4]['row']="10";
$z[16]=Array("myad","",4476,4508);
$z[16][4]['name']="listRtPicAD2";
$z[17]=Array("myad","",4609,4642);
$z[17][4]['name']="innerfooterAD";
$z[18]=Array("include","",4650,4687);
$z[18][4]['filename']="footer.htm";

?>