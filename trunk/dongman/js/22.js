<!--    
        var stopscroll = false;    
        var scrollElem = document.getElementById("andyscroll");    
        var marqueesHeight = scrollElem.style.height;    
        scrollElem.onmouseover = new Function('stopscroll = true');    
        scrollElem.onmouseout  = new Function('stopscroll = false');    
        var preTop = 0;    
        var currentTop = 0;    
        var stoptime = 0;    
        var leftElem = document.getElementById("scrollmessage");    
        scrollElem.appendChild(leftElem.cloneNode(true));    
        init_srolltext();    
        function init_srolltext(){    
         scrollElem.scrollTop = 0;    
         setInterval('scrollUp()', 1);//的面的这个参数25, 是确定滚动速度的, 数值越小, 速度越快    
        }    
        function scrollUp(){    
         if(stopscroll) return;    
         currentTop += 1; //设为1, 可以实现间歇式的滚动; 设为2, 则是连续滚动    
         if(currentTop == 100) {    
          stoptime += 1;    
          currentTop -= 1;    
          if(stoptime == 400) {    
           currentTop = 0;    
           stoptime = 0;    
          }    
         }else{    
          preTop = scrollElem.scrollTop;    
          scrollElem.scrollTop += 1;    
          if(preTop == scrollElem.scrollTop){    
           scrollElem.scrollTop = 0;    
           scrollElem.scrollTop += 1;    
          }    
         }    
        }    
        //-->   