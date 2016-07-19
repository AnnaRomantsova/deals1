<?

/**
 * интерфейс. меню слева
 * @package BACK
 */

session_start();
if ($_SESSION['valid_user']=='admin')
{
?>
<html>
<head>
  <title>Администрирование</title>
<LINK rel="stylesheet" type="text/css" href="/_css/back.css">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<BASE target="content">
  <script language="JavaScript">
   function collapse()
    {
      fr=parent.document.getElementById('_fr');
      mopn=document.getElementById('_mopn');
      menu=parent.document.getElementById('_menu');
      mclosed=document.getElementById('_mclosed');

      fr.cols='14,*';
      mopn.style.display='none';
      menu.style.borderTop='1px solid #767A80';
      menu.style.borderRight='1px solid #767A80';
      document.body.style.backgroundColor='#D9DFE9';
      document.body.scroll='no';
      mclosed.style.display='block';
    }
   function show()
    {
      mclosed.style.display='none';
      document.body.scroll='auto';
      document.body.style.backgroundColor='#FFFFFF';
      menu.style.border='none';
      fr.cols='160,*';
      mopn.style.display='block';
    }

  </script>
</head>
<body bgcolor="#ffffff" leftmargin=0 topmargin=0>
<div id="_mopn" style="padding:0px 0px 0px 5px; margin:0px;">
<table width="100%" height="21" class="title_menu">
   <tr><td width="100%">&nbsp;Меню</td>
       <td><img src="/_images/back/menu_close.gif" width="14" height="14" alt="Свернуть панель" border="0" hspace="2" onclick="collapse();" style="cursor:pointer"></td></tr>
</table>
<br>
<table width="100%" border="0" class="menu">


<tr>
<td align=center><a href="/site_builder/moduls/structure/back.php"><img src="/_images/back/menu_structure.gif" border=0 align=absMiddle></a></td>
<td><a href="/site_builder/moduls/structure/back.php">Структура сайта</a></td>
</tr>

    <tr>
        <td align=center><a href="/site_builder/moduls/deals/back.php"><img src="/_images/back/menu_news.gif" border=0 align=absMiddle></a></td>
        <td><a href="/site_builder/moduls/deals/back.php" class="menu">Сделки</a></td>
    </tr>

 <tr>
<td align=center><a href="/site_builder/moduls/auth/back.php"><img src="/_images/back/menu_news.gif" border=0 align=absMiddle></a></td>
<td><a href="/site_builder/moduls/auth/back.php" class="menu">Пользователи</a></td>
</tr>




<tr>
<td align=center><a href="/site_builder/moduls/page_editor/page_editor.php"><img src="/_images/back/menu_edit_pages.gif" border=0 align=absMiddle></a></td>
<td><a href="/site_builder/moduls/page_editor/page_editor.php" class="menu">Блоки</a></td>
</tr>

<tr>
<td align=center><a href="/site_builder/moduls/setup/back.php"><img src="/_images/back/menu_setup.gif" border=0 align=absMiddle></a></td>
<td><a href="/site_builder/moduls/setup/back.php" class="menu">Конфигурация</a></td>
</tr>

<tr>
<td align=center><a href="/site_builder/moduls/feedback/back.php"><img src="/_images/back/menu_mail_set.gif" border=0 align=absMiddle></a></td>
<td><a href="/site_builder/moduls/feedback/back.php">Настройки почты</a></td>
</tr>

<tr height=50>
<td align=center><a href="exit.php"><img src="/_images/back/menu_exit.gif" border=0 align=absMiddle></a></td>
<td><a href="exit.php" class="menu" target="_parent">Выход</a></td>
</tr>
</table>
</div>
<img id="_mclosed" src="/_images/back/menu_show.gif" width="14" height="14" alt="Развернуть панель" border=0 onclick="show();"
 style="cursor:pointer; position:absolute; top:50%; left:0px; display:none;">
</body>
</html>
<?
}
else
{
?>
<script language="JavaScript">
<!--
parent.location.href="index.php";
-->
</script>
<?
}
?>
