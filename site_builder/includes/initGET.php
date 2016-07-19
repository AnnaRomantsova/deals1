<?php

 /** 
 * Инициализация элементов $_GET по запросу на страницу<br>
 * реализует механизм ЧПУ (человеку понятных урлов)<br>
 * разбирает URI следующим образом /var1/var2/var3/var4/var5...<br>
 * var1 присваивается переменной $page, остальные параметры воспринимаются 
 * как запрос GET ?var2=var3&var4=var5...<br>
 * !важно! странице index переменные GET могут быть переданны только прямым запросом ?var1=var2&...
 * 
 * @package FRONT 
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.03 - 14.06.2007 16:50
 * 
 * .01 $page инициализируется только, если не была проинициализирована раньше <br>
 * .02 убрана избыточная инициализация $_GET['sect'],$_GET['id'] <br>
 * .03 заслешена переменная $page ! внимание - это должно быть сделано везде !
 * 
 */ 
 
     $PATH = $_SERVER['REQUEST_URI'];
     if (strstr($PATH, '?')) 
            $PATH = substr($PATH, 0, strpos($PATH, '?'));

     $PATH = ( $PATH = trim($PATH, '/') ? explode('/', trim($PATH, '/')) : array() );
     $c_PATH = count($PATH);

	 
     $GLOBALS['strPATH'] = '/'.implode('/',$PATH);
     if (!isset($page))
     	$page = addslashes( 0 != $c_PATH ? array_shift($PATH) : 'index' );
     
     for ($i = 0; $i < $c_PATH; $i+=2) {
     	$_GET[$PATH[$i]] = $PATH[$i+1];
     }
     
?>