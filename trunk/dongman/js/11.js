<!--//--><![CDATA[//><!--
			var Tu_Speed = 10; //速度(毫秒)
			var Tu_Space = 15; //每次移动(px)
			var Tu_PageWidth = 814; //翻页宽度
			var Tu_fill = 0;
			var Tu_MoveLock = false;
			var Tu_MoveTimeObj;
			var Tu_Comp = 0;
			var Tu_AutoPlayObj=null;
			var TU_Pages = Math.floor(GetObj("Tu_List1").scrollWidth / Tu_PageWidth);
			var TU_Page = 1;
			var Tu_GotoLock = false;
			GetObj("Tu_List2").innerHTML = GetObj("Tu_List1").innerHTML;
			GetObj('TU_Cont').scrollLeft = Tu_fill>=0?Tu_fill:GetObj('Tu_List1').scrollWidth - Math.abs(Tu_fill);
			GetObj("TU_Cont").onmouseover = function(){clearInterval(Tu_AutoPlayObj);}
			GetObj("TU_Cont").onmouseout = function(){Tu_AutoPlay();}
			Tu_AutoPlay();
			TU_PageList();
			function GetObj(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")')}else{return eval('document.all.'+objName)}}
			function Tu_AutoPlay(){
				clearInterval(Tu_AutoPlayObj);

				Tu_AutoPlayObj = setInterval('TU_GoDown();TU_StopDown();',5000);
			}

			function TU_PageList(){
				var i,temp="";
				for(i = 1;i<=TU_Pages;i++){
					temp += "<img src='images/a_35.gif' class='" + (TU_Page==i?"dotON":"dotOFF") + "' onclick='TU_GotoPage(" + i + ")' alt='" + i + "页' />";
				};
				GetObj('TuList').innerHTML = temp;
			}
			function TU_GotoPage(num){
				if(Tu_MoveLock)return;
				Tu_MoveLock = true;
				Tu_GotoLock = true;
				Tu_Comp = (num - 1) * Tu_PageWidth - GetObj('TU_Cont').scrollLeft;
				TU_Page = num;
				TU_PageList();
				clearInterval(Tu_AutoPlayObj);
				TU_Comp();
				Tu_AutoPlay();
			}
			function TU_GoUp(){
				if(Tu_MoveLock) return;
				clearInterval(Tu_AutoPlayObj);
				Tu_MoveLock = true;
				Tu_MoveTimeObj = setInterval('TU_ScrUp();',Tu_Speed);
			}
			function TU_StopUp(){
				if(Tu_GotoLock){return};
				clearInterval(Tu_MoveTimeObj);
				if((GetObj('TU_Cont').scrollLeft - Tu_fill) % Tu_PageWidth != 0){
					Tu_Comp = Tu_fill - (GetObj('TU_Cont').scrollLeft % Tu_PageWidth);
					TU_Comp();
				}else{
					Tu_MoveLock = false;
				}
				Tu_AutoPlay();
			}
			function TU_ScrUp(){
				if(GetObj('TU_Cont').scrollLeft <= 0){GetObj('TU_Cont').scrollLeft = GetObj('TU_Cont').scrollLeft + GetObj('Tu_List1').offsetWidth}
				GetObj('TU_Cont').scrollLeft -= Tu_Space ;
			}

			function TU_GoDown(){
				clearInterval(Tu_MoveTimeObj);
				if(Tu_MoveLock) return;
				clearInterval(Tu_AutoPlayObj);
				Tu_MoveLock = true;
				TU_ScrDown();
				Tu_MoveTimeObj = setInterval('TU_ScrDown()',Tu_Speed);
			}
			function TU_StopDown(){
				if(Tu_GotoLock){return};
				clearInterval(Tu_MoveTimeObj);
				if(GetObj('TU_Cont').scrollLeft % Tu_PageWidth - (Tu_fill>=0?Tu_fill:Tu_fill+1) != 0 ){
					Tu_Comp = Tu_PageWidth - GetObj('TU_Cont').scrollLeft % Tu_PageWidth + Tu_fill;
					TU_Comp();
				}else{
					Tu_MoveLock = false;
				}
				Tu_AutoPlay();
			}
			function TU_ScrDown(){
				if(GetObj('TU_Cont').scrollLeft >= GetObj('Tu_List1').scrollWidth){GetObj('TU_Cont').scrollLeft = GetObj('TU_Cont').scrollLeft - GetObj('Tu_List1').scrollWidth;}
				GetObj('TU_Cont').scrollLeft += Tu_Space ;
			}

			function TU_Comp(){
				if(Tu_Comp == 0){
					TU_Page = Math.round((GetObj('TU_Cont').scrollLeft - Tu_fill) / Tu_PageWidth) + 1;
					if(TU_Page>TU_Pages){TU_Page = 1};
					TU_PageList();
					Tu_MoveLock = false;
					Tu_GotoLock = false;
					return;
				}

				var num;
				var TempTu_Speed = Tu_Speed,TempTu_Space = Tu_Space;
				if(Math.abs(Tu_Comp)<Tu_PageWidth/5){
					TempTu_Space =  Math.round(Math.abs(Tu_Comp/5));
					if(TempTu_Space<1){TempTu_Space=1};
				}

				if(Tu_Comp < 0){
					if(Tu_Comp < -TempTu_Space){
						Tu_Comp += TempTu_Space;
						num = TempTu_Space;
					}else{
						num = -Tu_Comp;
						Tu_Comp = 0;
					}
					GetObj('TU_Cont').scrollLeft -= num;
					setTimeout('TU_Comp()',TempTu_Speed);
				}else{
					if(Tu_Comp > TempTu_Space){
						Tu_Comp -= TempTu_Space;
						num = TempTu_Space;
					}else{
						num = Tu_Comp;
						Tu_Comp = 0;
					}
					GetObj('TU_Cont').scrollLeft += num;
					setTimeout('TU_Comp()',TempTu_Speed);
				}
			}
			//--><!]]>