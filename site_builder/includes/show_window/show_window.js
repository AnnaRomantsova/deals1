/* BACK
   показ картинки
*/
 _href="";
  _title="";
  flag=false;
  hiddenImg= new Image();
  function loaded()
   { flag=true;
     show_window("/site_builder/includes/show_window/show_img.php",hiddenImg.width+16,hiddenImg.height);
   }

  function show_image(href,title)
   {
     _href=href;
     _title=title;
     if (navigator.userAgent.indexOf("Opera")==-1)
      {
        hiddenImg.onload=loaded;
        hiddenImg.src= href;
      }
     else
      {
        hiddenImg.src= href;
        loaded();
      }
   }
  function show_window(href,wd,ht)
   {
     var left=screen.availWidth/2-wd/2;
     var top=screen.availHeight/2-ht/2;
     window.open(href,"","width="+wd+", height="+ht+", left="+left+", top="+top+", menubar=0, toolbar=0, location=0, directories=0, status=0, resizable=1, scrollbars=1");
   }

 function img_resize(elem,wd,ht)
 {
   if (wd>"")
    { if (elem.width>wd)
         elem.height=wd; }

   if (ht>"")
    { if (elem.height>ht)
         elem.height=ht; }
 }