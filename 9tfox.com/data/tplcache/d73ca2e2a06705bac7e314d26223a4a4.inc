
                [field:pagestyle runphp='yes'] @me= (@me==3 ? '<li>' : '');[/field:pagestyle]
                <a href='[field:linkurl/]' target='_blank'  [field:pagestyle runphp='yes'] @me= (@me==3 ? 'class="pic"' : ''); [/field:pagestyle]>
                  <img src='[field:imgsrc/]' id='bigimg' [field:imgwidth /] alt='[field:alttext /]' border='0' />
                </a>
                <!-- 如果使用的是多页单图模式(幻灯)，把href里的链接改为 javascript:dPlayNext(); 表示点击看下一页 -->
                <a href="[field:linkurl/]" [field:pagestyle runphp='yes']@me = (@me==3 ? 'class="title"' : '');[/field:pagestyle]>
                	[field:alttext /]
                </a>
                [field:pagestyle runphp='yes'] @me = (@me==3 ? '</li>' : ''); [/field:pagestyle]
             