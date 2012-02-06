<DL><SPAN class=flr>[field:pubdate function="MyDate('m-d',@me)"/]</SPAN><A href="[field:bookurl/]" target=_blank>[field:title/]</A></DL>
{/dede:arclist}
</DIV></DIV>
<DIV class="inkk mato10">
<DIV class=inbt>推荐漫画</DIV>
{dede:arclist  row='10'  att='c' } 
<DIV class=innr8>
<UL>
  <LI><A href="[field:arcurl/]"><IMG 
  src="[field:litpic/]"></A></LI></UL>
<DL>
  <DT class=red_lj><STRONG><A href="[field:bookurl/]" >[field:title/]</A></STRONG></DT>
  <DT>状态：[field:comic/]</DT>
  <DT>作者：[field:author/]</DT>
  <DT>人气：[field:click /]</DT></DL></DIV>
<DIV class=ff></DIV>
{/dede:arclist}
</DIV></DIV>
<DIV class=index_r>
<DIV class="ssk red_lj matoa">
<FORM name=formsearch action=/plus/search.php target=_blank><INPUT value=0 
type=hidden name=kwtype> 
<TABLE border=0 cellSpacing=0 cellPadding=0 width="100%">
  <TBODY>
  <TR>
    <TD height=30 width="11%"><FONT class=redzic>漫画搜索：</FONT></TD>
    <TD width="35%"><INPUT id=search-keyword class=sos2 size=55 type=text 
      name=keyword></TD>
    <TD width="10%" align=middle><INPUT src="/images/dmss.gif" type=image 
      name=""></TD>
    <TD width="44%">

    <A href="#" target=_blank>火影忍者</A><A href="#" >海贼王</A><A href="#" >死神</A><A href="#" >银魂</A><A href="#" 
      target=_blank>不迷羊/不迷途的羔羊</A></TD></TR></TBODY></TABLE></FORM></DIV>
<DIV style="PADDING-BOTTOM: 10px; WIDTH: 750px; HEIGHT: 80px; OVERFLOW: hidden" 
class=ad750>
<SCRIPT type=text/javascript src="/images/linkImage.htm"></SCRIPT>
</DIV>
<DIV class=inkk>
<DIV class=inbt2>
<SCRIPT type=text/javascript src="/images/yc.js"></SCRIPT>

<UL class=qh>
  <LI class=dj><A onClick="javascript:only(5,1,'a',event);" 
  href="/###">今日最新更新</A></LI>
  <LI><A onClick="javascript:only(5,2,'a',event);" 
  href="/###">热门连载漫画</A></LI>
  <LI><A onClick="javascript:only(5,3,'a',event);" 
  href="/###">经典完结漫画</A></LI>
  <LI><A onClick="javascript:only(5,4,'a',event);" 
  href="/###">特别推荐漫画</A></LI>
  <LI><A onClick="javascript:only(5,5,'a',event);" 
  href="/###">头条推荐漫画</A></LI></UL></DIV>
<DIV id=a1 class=innr2>
<UL>

{dede:sql sql="Select * From #@__story_books order by bid desc limit 0,5"}

  <LI> <a href="/book/book.php?bid=[field:bid/]" ><IMG border=0   src="[field:litpic/]"></A>
  <DIV class=d121><A href="[field:bookurl/]"  target=_blank>[field:bookname/] </A></DIV>
  <DIV class=d121><FONT color=green>
  
  [field:status /]
  
  </FONT>-人气:[field:click /]</DIV></LI>