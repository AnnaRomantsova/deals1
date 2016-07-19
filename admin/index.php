<?php
/**
 * интерфейс. форма авторизации
 * @package BACK
 */
?>
<html>
<head>
<LINK rel="stylesheet" type="text/css" href="../_css/back.css">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<title>Система управления сайтом</title>
</head>
<body bgcolor=#ffffff style="margin:5px 10px 0px 10px" onload="document.adm.password.focus()">


<b>
<font size=5>Система управления сайтом</font>
</b>
<p>
Введите пароль:
<form method="post" action="auth.php" target=_parent name="adm">
<input name="password" type="password" width=20><p>
<input name="submit" type="submit" value="Вход">
</form>
</body>
</html>