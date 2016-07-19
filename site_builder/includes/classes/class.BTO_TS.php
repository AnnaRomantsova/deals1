<?php

/**
 * запись c возможностью <br>
 *  T - фильтровать записи по родителю <br>
 *  O - сортировать по полю sort <br>
 *  S - запомнить id в сессию <br>
 *  <br>
 * class BTO_TS extends BT_O <br>
 * доступны методы BTO_TS,BT_S,BS,BT_O,BO,BT,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BT_O.php');
include_once('class.BT_S.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BTO_TS_ {

 /**
  * генерирует "событие" по пришедшим переменным $_GET
  * @param BTO_TS $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  * @return bool сработало событие или нет
  */
 function createEvent(&$_this,$location = '') {
  /// !!! порядок важен	!!!
   if    (BT_O_::createEvent($_this,$location));
   elseif(BT_S_::createEvent($_this,$location));
   else
		return false;
   return true;
 }

 /**
  * добавляет кнопки действий в дереве менеджера<br>
  * кнопки могут быть добавлены как в само дерево менеджера для текущей записи<br>
  * так и в дерево ветки записи
  * @param BTO_TS $_this
  * @param outTree $main дерево менеджера или ветки
  * @param array $param параметры менеджера и записи
  */
 function addButtons(&$_this,&$main,&$param) {
 	BO_::addButtons($_this,$main,$param);
 	$_this->addButtonsCut($main,$param['id']);
 }

 /**
  * вставляет вырезанные записи
  * @param BTO_TS $_this
  * @param array $param параметры менеджера
  */
 function pastAction(&$_this,&$param) {
	$table = $_this->table;
	$where = '2=3';
	$count = 0;

  // таблица - недерево
	if (isset($param['nottree'])) {
		foreach ($_SESSION['idCuts'][$table] as $key => $v) {
			$where.= (' || id="'.$key.'"');
			$_this->db->query('update '.$table.' set sort="'.($count++).'" where id="'.$key.'"');
		}
	}
  // таблица - дерево
	else {
		$br = new Brunch($param['parent'],$table,'',$_this->db);
		$GLOBALS['br'] = &$br;
		foreach ($_SESSION['idCuts'][$table] as $key => $v)
		  // не вставлять родительский раздел в дочерний
			if (!in_array($key,$br->ids)) {
				$where.= (' || id="'.$key.'"');
				$_this->db->query('update '.$table.' set sort="'.($count++).'" where id="'.$key.'"');
			}
	}
	$_this->db->query('update '.$table.' set sort=sort+"'.$count.'" where parent="'.$param['parent'].'"');
	$_this->db->query('update '.$table.' set parent="'.$param['parent'].'" where '.$where);
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  T - фильтровать записи по родителю <br>
 *  O - сортировать по полю sort <br>
 *  S - запомнить id в сессию
 */
class BTO_TS extends BT_O {

 function initBTO_TS(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
	$this->initBTSo($db,$_name,$_caption,$_table);
 }

 function addButtons(&$main,&$param) {
 	BTO_TS_::addButtons($this,$main,$param);
 }

 function createEvent($location = '') {
	return BTO_TS_::createEvent($this,$location);
 }

 function pastAction(&$param) {
 	BTO_TS_::pastAction($this,$param);
 }

///--------------- BT_S

 // pastAction - переопределен

	///--------------- BS

	 function addButtonsCut(&$main,$id) {
	 	BS_::addButtonsCut($this,$main,$id);
	 }

	 // addButtons - переопределен

	 function deleteRecord($id,$r = null,$qd = true) {
	 	BS_::deleteRecord($this,$id,$r,$qd);
	 }

	 function cutRecord($id,$justDel = false) {
	 	BS_::cutRecord($this,$id,$justDel);
	 }

	 // pastAction - переопределен

	 function pastRecords(&$param) {
	 	BS_::pastRecords($this,$param);
	 }

	 // createEvent - переопределен

	///--------------- /BS
///--------------- /BT_S

}
?>