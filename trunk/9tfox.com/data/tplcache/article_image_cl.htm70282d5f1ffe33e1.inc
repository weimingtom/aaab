<?php
$z[0]=Array("global","",236,264);
$z[0][4]['name']="cfg_soft_lang";
$z[1]=Array("field","",277,296);
$z[1][4]['name']="title";
$z[2]=Array("global","",299,325);
$z[2][4]['name']="cfg_webname";
$z[3]=Array("field","",366,388);
$z[3][4]['name']="keywords";
$z[4]=Array("field","",428,479);
$z[4][4]['name']="description";
$z[4][4]['function']="html2text(@me)";
$z[5]=Array("global","",497,529);
$z[5][4]['name']="cfg_templets_skin";
$z[6]=Array("global","",616,648);
$z[6][4]['name']="cfg_templets_skin";
$z[7]=Array("global","",778,803);
$z[7][4]['name']="cfg_cmsurl";
$z[8]=Array("global","",895,927);
$z[8][4]['name']="cfg_templets_skin";
$z[9]=Array("global","",1024,1056);
$z[9][4]['name']="cfg_templets_skin";
$z[10]=Array("global","",1321,1346);
$z[10][4]['name']="cfg_cmsurl";
$z[11]=Array("field","",2186,2213);
$z[11][4]['name']="phpurl";
$z[12]=Array("global","",3054,3079);
$z[12][4]['name']="cfg_phpurl";
$z[13]=Array("include","",3787,3823);
$z[13][4]['filename']="head2.htm";
$z[14]=Array("field","",3915,3944);
$z[14][4]['name']="position";
$z[15]=Array("field","",3944,3963);
$z[15][4]['name']="title";
$z[16]=Array("field","",4126,4145);
$z[16][4]['name']="title";
$z[17]=Array("field","",6261,6317);
$z[17][4]['name']="pubdate";
$z[17][4]['function']="MyDate('Y-m-d H:i',@me)";
$z[18]=Array("field","",6339,6366);
$z[18][4]['name']="phpurl";
$z[19]=Array("field","",6390,6406);
$z[19][4]['name']="id";
$z[20]=Array("field","",6411,6428);
$z[20][4]['name']="mid";
$z[21]=Array("field","",6570,6586);
$z[21][4]['name']="id";
$z[22]=Array("sql","[field:total runphp='yes']@me=empty(@me)? 0 : @me;[/field:total]",6604,6755);
$z[22][4]['sql']="SELECT count(id) as total FROM #@__feedback where aid=~id~;";
$z[23]=Array("myad","",7095,7130);
$z[23][4]['name']="contentMidPicAD";
$z[24]=Array("field","",7136,7154);
$z[24][4]['name']="body";
$z[25]=Array("prenext","",7439,7467);
$z[25][4]['get']="preimg";
$z[26]=Array("prenext","",8098,8127);
$z[26][4]['get']="nextimg";
$z[27]=Array("field","",8376,8426);
$z[27][4]['name']="id";
$z[27][4]['function']="litimgurls(@me)";
$z[28]=Array("field","",8582,8601);
$z[28][4]['name']="title";
$z[29]=Array("field","",8695,8716);
$z[29][4]['name']="alttext";
$z[30]=Array("global","",11038,11070);
$z[30][4]['name']="cfg_templets_skin";
$z[31]=Array("include","",13025,13065);
$z[31][4]['file']="ajaxfeedback.htm";
$z[32]=Array("channel","\r\n		<li><a href='[field:typeurl/]'>[field:typename/]</a></li>",13292,13471);
$z[32][4]['type']="son";
$z[32][4]['currentstyle']="<li><a href='~typelink~' class='thisclass'>~typename~</a></li>";
$z[33]=Array("myad","",13580,13614);
$z[33][4]['name']="contentRtPicAD";
$z[34]=Array("memberinfos","\r\n      <dl class=\"tbox\">\r\n        <dt><strong>发布者资料</strong></dt>\r\n        <dd> <a href=\"[field:spaceurl /]\" class=\"userface\"><img src=\"[field:face/]\" width=\"52\" height=\"52\" /></a> <a href='[field:spaceurl /]' class=\"username\">[field:uname/]</a> <span class=\"useract\"> <a href=\"[field:spaceurl /]\" class=\"useract-vi\">查看详细资料</a> <a href=\"[field:spaceurl /]&action=guestbook\" class=\"useract-pm\">发送留言</a> <a href=\"[field:spaceurl /]&action=newfriend\" class=\"useract-af\">加为好友</a> </span> <span class=\"userinfo-sp\"><small>用户等级:</small>[field:rankname /]</span> <span class=\"userinfo-sp\"><small>注册时间:</small>[field:jointime function=\"MyDate('Y-m-d H:m',@me)\"/]</span> <span class=\"userinfo-sp\"><small>最后登录:</small>[field:logintime function=\"MyDate('Y-m-d H:m',@me)\"/]</span> </dd>\r\n      </dl>\r\n      ",13655,14550);
$z[35]=Array("arclist","\r\n            <li> <a href=\"[field:arcurl/]\" class=\"preview\"><img src=\"[field:litpic/]\" alt=\"[field:title function='html2text(@me)'/]\"/></a> <a href=\"[field:arcurl/]\" class=\"title\">[field:title/]</a> <span class=\"intro\">更新:[field:pubdate function=\"GetDateMK(@me)\"/]</span> </li>\r\n            ",14708,15069);
$z[35][4]['row']="5";
$z[35][4]['type']="image.";
$z[35][4]['orderby']="click";
$z[36]=Array("myad","",15183,15218);
$z[36][4]['name']="contentRtPicAD2";
$z[37]=Array("myad","",15307,15341);
$z[37][4]['name']="innerfooterAD2";
$z[38]=Array("include","",15349,15386);
$z[38][4]['filename']="footer.htm";

?>