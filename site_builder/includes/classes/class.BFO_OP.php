<?php

/**
 * запись c возможностью <br> 
 *  F - хранить сопровождающие файлы <br> 
 *  O - сортировать по полю sort <br>
 *  P - постраничного просмотра <br> 
 *  <br>
 * class BFO_OP extends BF_O <br>
 * доступны методы BFO_OP,BO_P,BP,BF_O,BF,BO,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 * 
 */

include_once('class.BF_O.php');
include_once('class.BO_P.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Pager
 */
class BFO_OP_ {
}

/**
 * работа со стандартной записью c возможностью <br> 
 *  F - хранить сопровождающие файлы <br>
 *  O - сортировать по полю sort <br>
 *  P - постраничного просмотра 
 */
class BFO_OP extends BF_O {
 
 function initBFO_OP(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->initBF_O($db,$_name,$_caption,$_table);
 }

///--------------- BO_P 

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
///--------------- /BO 

 
}
?>