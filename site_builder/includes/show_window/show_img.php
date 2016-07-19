<?php
/**
 * интерфейс. показ картинки
 * @package BACK
 */
?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<SCRIPT language="JavaScript"><!--

  document.title=opener._title;
  function check_size(img)
   {
     ow=window.outerWidth;
     iw=window.innerWidth;
     oh=window.outerHeight;
     ih=window.innerHeight;
     if (ow && iw && oh && ih)
      {
        window.resizeTo(ow-iw+img.width,oh-ih+img.height);
        window.scrollbars.hide;
      }
   }
//-->
</SCRIPT>
</HEAD>
<BODY BGCOLOR="#FFFFFF" onclick="window.close();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="border:0px solod; padding: 0px 0px 0px 0px; margin 0px 0px 0px 0px;">
<img src="" id="_img" onload="check_size(this);" style="vertical-align: middle;">
</BODY>
</HTML>
<SCRIPT language="JavaScript"><!--
 _img.src=opener._href;
//-->
</SCRIPT>