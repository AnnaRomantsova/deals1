/* FRONT 
   открывает новое окно w на h с адресом href по середине экрана
*/
 function showImg(href,w,h) { 
     w+=40;
     h+=40;
     var left=screen.availWidth/2-w/2;
     var top=screen.availHeight/2-h/2;
     window.open(href,'_blank', "width="+w+", height="+h+", left="+left+", top="+top+", menubar=0, toolbar=0, location=0, directories=0, status=0, resizable=1, scrollbars=1");
 }
