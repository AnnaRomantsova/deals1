<?php

/**
 * запись c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  S - запомнить id в сессию <br>
 * <br>
 * class BF_S extends BF <br>
 * доступны методы BF_S,BS,BF,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BF.php');
include_once('class.BS.php');

/**
 * @uses Select
 * @uses outTree
 */
class BF_S_ {

 /**
  * действие: удаляет запись
  * @param BF_S $_this
  * @param int $id id удаляемой записи
  * @param Select $r запрос с удаляемой записью
  * @param bool $qd удалять саму запись или ограничиться "подготовительными действиями"
  * @return int количество удаленных записей
  */
 function deleteRecord(&$_this,$id,$r = null,$qd = true) {
	BF_::deleteRecord($_this,$id,$r,$qd);
	$_this->cutRecord($id,true);
 }
}

/**
 * работа со стандартной записью c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  S - запомнить id в сессию <br>
 */
class BF_S extends BF {

 function BF_S(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->initBF_S($db,$_name,$_caption,$_table,$arFiles);
 }

 function initBF_S(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->arFiles = &$arFiles;
	$this->initBF($db,$_name,$_caption,$_table);
 }

 function deleteRecord($id,$r = null,$qd = true) {
 	BF_S_::deleteRecord($this,$id,$r,$qd);
 }

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

 function pastAction(&$param) {
 	BS_::pastAction($this,$param);
 }

 function pastRecords(&$param) {
 	BS_::pastRecords($this,$param);
 }

 function createEvent($location = '') {
 	return BS_::createEvent($this,$location);
 }


///--------------- /BS


}


?>