<?php

/**
 * запись c возможностью
 *  O - сортировать по полю sort
 *  P - постраничного просмотра
 *
 * class BO_P extends BO
 * доступны методы BP,BO,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BO.php');
include_once('class.BP.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Pager
 */
class BO_P_ {

 /**
  * @param BO_P $_this
  */
 function &getParamMngr(&$_this) {
 	$param = &BP_::getParamMngr($_this);
 	$param2 = &BO_::getParamMngr($_this);
  	$param = &array_merge($param,$param2);
 	return $param;
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  O - сортировать по полю sort <br>
 *  P - постраничного просмотра
 */
class BO_P extends BO {

 function initBO_P(&$db,$_name = null,$_caption =null,$_table = null) {
	$this->initBO($db,$_name,$_caption,$_table);
 }

 function &getParamMngr() {
   	return BO_P_::getParamMngr($this);
 }

///--------------- BP

 function addRecords(&$main,&$param) {
 	BP_::addRecords($this,$main,$param);
 }

 // getParamMngr - переопределен

 function getEvent($location = '') {
 	BP_::getEvent($this,$location);
 }

///--------------- /BP

}

?>
