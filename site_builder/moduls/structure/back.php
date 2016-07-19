<?
/**
 * @package BACK
 */


 session_start();

 if ($_SESSION['valid_user']=='admin')  {
   include($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/setup.php');
   include($inc_path.'/db_conect.php');
   include('config.php');
     include('class.back.php');

     $back = new B_structure($db,$modulName,$modulCaption,$GLOBALS['sections_table'],$arImgS,array());
     $back->getEvent();

 }
 else header('Location: '.$auth_path);
?>
