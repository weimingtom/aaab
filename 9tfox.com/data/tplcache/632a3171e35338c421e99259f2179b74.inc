<LI> <a href="/book/book.php?bid=[field:bid/]" ><IMG border=0   src="[field:litpic/]"></A>
  <DIV class=d121><A href="[field:arcurl/]"  target=_blank>[field:bookname/] </A></DIV>
  <DIV class=d121><FONT color=green>
  
 {dede:field name='array' runphp='yes'}
if (@me['status']=='0')
{@me=@me['title'];}
else{@me=@me['author'];}
{/dede:field}
  
  </FONT>-人气:[field:click /]</DIV></LI>