<?php

/**
 * запись c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  T - фильтровать записи по родителю <br>
 *  <br>
 * class BF_T extends BF <br>
 * доступны методы BF_T,BT,BF,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BF.php');
include_once('class.BT.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BF_T_ {

 /**
  * действие: сохраняет новую запись
  * @param BF_T $_this
  * @param array $values ассоц.массив значений полей
  * @param int $parent id родительской записи
  */
 function saveNewRecord(&$_this,&$values,$parent) {
     $values['parent'] = $parent;
     return BF_::saveNewRecord($_this,$values);
 }

 /**
  * формирует дерево редактирования записи<br>
  * интерфейс изменения
  * @param BF_T $_this
  * @param outTree $main дерево редактирования
  * @param int $id id изменяемой записи
  * @param string $table имя таблицы, где хранятся родительские записи
  * @return string файл шаблона добавления
  */
 function addIfcEditRecord(&$_this,&$main,$id,$table = null) {
     if ($_FILENAME = BT_::addIfcEditRecord($_this,$main,$id,$table)) {
 	 	$_this->addFiles($main);
 	 }
     return $_FILENAME;
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  T - фильтровать записи по родителю
 */
class BF_T extends BF {

 function initBF_T(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->arFiles = &$arFiles;
	$this->initBF($db,$_name,$_caption,$_table);
 }

 function saveNewRecord(&$values,$parent) {
 	return BF_T_::saveNewRecord($this,$values,$parent);
 }

 function addIfcEditRecord(&$main,$id,$table = null) {
 	return BF_T_::addIfcEditRecord($this,$main,$id,$table);
 }


///--------------- BT

 // saveNewRecord - переопределен

 function initPath(&$br,&$main,&$r,$with_end = false,$with_last = true ) {
 	BT_::initPath($this,$br,$main,$r,$with_end,$with_last);
 }

 function addIfcAddRecord(&$main,$parent,$table = null) {
 	return BT_::addIfcAddRecord($this,$main,$parent,$table);
 }

 // addIfcEditRecord - переопределен

 function &getParamMngr() {
   	return BT_::getParamMngr($this);
 }

 function addManager(&$main) {
 	return BT_::addManager($this,$main);
 }

///--------------- /BT

}

?>