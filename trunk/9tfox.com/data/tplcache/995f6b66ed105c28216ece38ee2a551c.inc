
<div class="book_news_style_form1">
                        	<div class="book_news_style_img"><a href="[field:bookurl/]">[field:litpic runphp='yes']@me = (empty(@me['litpic']) ? "<img src='/images/defaultpic.gif' width='80' height='100' />" : "<img src='@me' width='80' height='100' />"); [/field:litpic]</a></div>
          <div class="book_news_style_text1">
               	<h1>[[field:cataloglink/]] <a href="[field:bookurl/]">[field:bookname/]</a> </h1><h2>作者：[field:author/]    【是否完成连载：[field:ischeck/]】</h2>
                            	<h3>[field:description runphp='yes'] if(strlen(@me)>=240) @me = substr(@me,0,240)."...";[/field:description]</h3>
                            </div>
                    	</div>
                     