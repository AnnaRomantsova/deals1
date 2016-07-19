<?php

/**
 * запись c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  T - фильтровать записи по родителю <br>
 *  S - запомнить id в сессию <br>
 *  <br>
 * class BFT_TS_FS extends BF_T <br>
 * доступны методы BFT_TS_FS,BT_S,BF_S,BS,BF,BF_T,BT,BF,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */


include_once('class.BF_T.php');
include_once('class.BF_S.php');
include_once('class.BT_S.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BFT_TS_FS_ {

}

/**
 * работа со стандартной записью c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  S - запомнить id в сессию <br>
 *  T - фильтровать записи по родителю
 */
class BFT_TS_FS extends BF_T {

 function initBFT_TS_FS(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->arFiles = &$arFiles;
	$this->initBF_T($db,$_name,$_caption,$_table);
 }

///--------------- BF_S
 function deleteRecord($id,$r = null,$qd = true) {
 	BF_S_::deleteRecord($this,$id,$r,$qd);
 }
///--------------- /BF_S

///--------------- BT_S
 function pastAction(&$param) {
 	BT_S_::pastAction($this,$param);
 }
///--------------- /BT_S


///--------------- BF_S,BT_S
	///--------------- BS

	 function addButtonsCut(&$main,$id) {
	 	BS_::addButtonsCut($this,$main,$id);
	 }

	 function addButtons(&$main,&$param) {
	 	BS_::addButtons($this,$main,$param);
	 }

	 // deleteRecord - переопределен

	 function cutRecord($id,$justDel = false) {
	 	BS_::cutRecord($this,$id,$justDel);
	 }

	 // pastAction - переопределен

	 function pastRecords(&$param) {
	 	BS_::pastRecords($this,$param);
	 }

	 function createEvent($location = '') {
 		return BS_::createEvent($this,$location);
 	 }

	///--------------- /BS
///--------------- /BF_S,BT_S

}

?>