<?php

/**
 * запись c возможностью <br>
 *  T - фильтровать записи по родителю <br>
 *  S - запомнить id в сессию <br>
 *  <br>
 * class BT_S extends BT <br>
 * доступны методы BT_S,BS,BT,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BT.php');
include_once('class.BS.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BT_S_ {

 /**
  * вставляет вырезанные записи
  * @param BT_S $_this
  * @param array $param параметры менеджера
  */
 function pastAction(&$_this,&$param) {
	$table = $_this->table;
	$where = '2=3';

  // таблица - недерево
	if (isset($param['nottree'])) {
		foreach ($_SESSION['idCuts'][$table] as $key => $v)
				$where.= (' || id="'.$key.'"');
	}
  // таблица - дерево
	else {
		$br = new Brunch($param['parent'],$table,'',$_this->db);
		$GLOBALS['br'] = &$br;
		foreach ($_SESSION['idCuts'][$table] as $key => $v)
		  // не вставлять родительский раздел в дочерний
			if (!in_array($key,$br->ids))
				$where.= (' || id="'.$key.'"');
	}
	$_this->db->query('update '.$table.' set parent="'.$param['parent'].'" where '.$where);
 }

 /**
  * генерирует "событие" по пришедшим переменным $_GET
  * @param BT_S $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  * @return bool сработало событие или нет
  */
  function createEvent(&$_this,$location = '') {
  /// !!! порядок важен	!!!
   if    (BT_::createEvent($_this,$location));
   elseif(BS_::createEvent($_this,$location));
   else
		return false;
   return true;
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  T - фильтровать записи по родителю <br>
 *  S - запомнить id в сессию
 */
class BT_S extends BT {

 function initBT_S(&$db,$_name = null,$_caption =null,$_table = null) {
	$this->initBT_S($db,$_name,$_caption,$_table);
 }

 function pastAction(&$param) {
 	BT_S_::pastAction($this,$param);
 }

 function createEvent($location = '') {
 	return BT_S_::createEvent($this,$location);
 }



///--------------- BS

 function addButtonsCut(&$main,$id) {
 	BS_::addButtonsCut($this,$main,$id);
 }

 function addButtons(&$main,&$param) {
 	BS_::addButtons($this,$main,$param);
 }

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

}

?>