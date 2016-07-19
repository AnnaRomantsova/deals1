<?php

/**
 * интерфейс. выход
 * @package BACK
 */

@session_start();
unset($_SESSION['valid_user']);
@session_destroy();
//header('Location: index.php');
echo "<SCRIPT language='JavaScript'>parent.parent.location.href='index.php'</SCRIPT>";
?>