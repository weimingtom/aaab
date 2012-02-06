<?php
$z[0]=Array("global","",253,279);
$z[0][4]['name']="cfg_webname";
$z[1]=Array("global","",323,353);
$z[1][4]['name']="cfg_description";
$z[2]=Array("global","",390,417);
$z[2][4]['name']="cfg_keywords";
$z[3]=Array("include","",503,547);
$z[3][4]['filename']="aabbccdd_head.htm";
$z[4]=Array("sql","\r\n<DL><SPAN class=flr>[field:addtime function=\"MyDate('m-d',@me)\"/]</SPAN><a href=\"/book/show-photo.php?id=[field:id/]\" >[field:title/]</A></DL>\r\n",665,898);
$z[4][4]['sql']="Select * From #@__story_content order by id desc limit 0,24";
$z[5]=Array("sql","\r\n\r\n<DIV class=innr8>\r\n<UL>\r\n  <LI><a href=\"/book/book.php?bid=[field:bid/]\" ><IMG   src=\"[field:litpic/]\"></A></LI></UL>\r\n<DL>\r\n  <DT class=red_lj><STRONG><a href=\"/book/book.php?bid=[field:bid/]\" >[field:bookname/]</A></STRONG></DT>\r\n  <DT>状态：[field:comic/]</DT>\r\n  <DT>作者：[field:author/]</DT>\r\n  <DT>人气：[field:click /]</DT></DL></DIV>\r\n<DIV class=ff></DIV>\r\n",977,1464);
$z[5][4]['sql']="Select * From #@__story_books where iscommend ='1' order by bid desc limit 0,10";
$z[6]=Array("sql","\r\n\r\n  <LI> <a href=\"/book/book.php?bid=[field:bid/]\" ><IMG border=0   src=\"[field:litpic/]\"></A>\r\n  <DIV class=d121><A href=\"[field:bookurl/]\"  target=_blank>[field:bookname/] </A></DIV>\r\n  <DIV class=d121><FONT color=green>\r\n  [field:array runphp='yes']@me = (@me['status']==0 ? \"连载中\" : \"已完结\"); [/field:array] \r\n  \r\n  </FONT>-人气:[field:click /]</DIV></LI>\r\n ",3060,3520);
$z[6][4]['sql']="Select * From #@__story_books order by bid desc limit 0,5";
$z[7]=Array("sql","\r\n <LI><a href=\"/book/book.php?bid=[field:bid/]\" ><IMG border=0 \r\n  src=\"[field:litpic/]\"></A>  <DIV class=d121><a href=\"/book/book.php?bid=[field:bid/]\" >[field:bookname/] </A></DIV>\r\n  <DIV class=d121><FONT color=green>连载中</FONT>-人气:[field:click /]</DIV></LI>\r\n  \r\n",13798,14163);
$z[7][4]['sql']="Select * From #@__story_books order by click desc limit 0,25";
$z[8]=Array("sql","\r\n<DIV class=innr72><A href=\"/book/book.php?bid=[field:bid/]\" >[field:bookname/]</A></DIV>\r\n",16164,16358);
$z[8][4]['sql']="Select * From #@__story_books WHERE catid='3' order by bid desc limit 0,24";
$z[9]=Array("sql","\r\n<DIV class=innr72><A href=\"/book/book.php?bid=[field:bid/]\" >[field:bookname/]</A></DIV>\r\n",16446,16640);
$z[9][4]['sql']="Select * From #@__story_books WHERE catid='4' order by bid desc limit 0,24";
$z[10]=Array("include","",64986,65030);
$z[10][4]['filename']="aabbccdd_foot.htm";

?>