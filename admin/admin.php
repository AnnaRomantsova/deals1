<?php
/**
 * интерфейс. главный фрейм
 * @package BACK
 */
?>
<HTML>
<HEAD>
<TITLE>Администрирование сайта</TITLE>
<link rel="SHORTCUT ICON" href="/favicon.ico">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<BASE target="content">
</HEAD>

<FRAMESET rows=60,* FRAMEBORDER ="NO" >
        <FRAME src="admin_hat.php" SCROLLING="no">
        <FRAMESET cols=170,* bgcolor="#aaaaaa" id="_fr">
                <FRAME src="admin_menu.php" id="_menu">
                <FRAME name="content" src="" style="padding-left:5px">
        </FRAMESET>
</FRAMESET>

</HTML>