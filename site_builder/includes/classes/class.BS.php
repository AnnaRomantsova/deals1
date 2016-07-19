<?php

/**
 * запись c возможностью <br>
 *  S - запомнить id в сессию <br>
 * <br>
 * class BS extends B <br>
 * доступны методы BS,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.B.php');

/**
 * @uses Select
 * @uses outTree
 */
class BS_ {

 /**
  * генерирует "событие" по пришедшим переменным $_GET
  * @param BS $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  * @return bool сработало событие или нет
  */
 function createEvent(&$_this,$location = '') {

 // запомнить запись в сессию
     if ( isset($_GET['cut']) ) {
        $_this->cutRecord($_GET['cut']);
        header('Location: ?event=1&'.$location);
     }
     else
     	return B_::createEvent($_this,$location);
     return true;
 }

 /**
  * действие: "вырезает" запись - запоминает/удаляет из сессии
  * @param BS $_this
  * @param int $id id "вырезаемой" записи
  * @param bool $justDel только удалить из сессии
  */
 function cutRecord(&$_this,$id,$justDel = false) {
	if ( isset($_SESSION['idCuts']) && isset($_SESSION['idCuts'][$_this->table]) && isset($_SESSION['idCuts'][$_this->table][$id]) )
	     	unset($_SESSION['idCuts'][$_this->table][$id]);

	else {
		if ($justDel) return;
		if (!isset($_SESSION['idCuts'])) {
			$_SESSION['idCuts'] = array();
			if (!isset($_SESSION['idCuts'][$_this->table]))
				$_SESSION['idCuts'][$_this->table] = array();
		}
		$_SESSION['idCuts'][$_this->table][$id]=true;
	}
 }

 /**
  * добавляет кнопки "вырезания" в дереве менеджера<br>
  * кнопки могут быть добавлены как в само дерево менеджера для текущей записи<br>
  * так и в дерево ветки записи
  * @param BS $_this
  * @param outTree $main дерево менеджера или ветки
  * @param array $param параметры менеджера и записи
  */
 function addButtonsCut(&$_this,&$main,$id) {
	$ot = new outTree();
	if ( isset($_SESSION['idCuts']) && isset($_SESSION['idCuts'][$_this->table]) && isset($_SESSION['idCuts'][$_this->table][$id]) )
		$ot->addField('D','D');
	$main->addField('butCut',$ot);
 }

 /**
  * добавляет кнопки действий в дереве менеджера<br>
  * кнопки могут быть добавлены как в само дерево менеджера для текущей записи<br>
  * так и в дерево ветки записи
  * @param BS $_this
  * @param outTree $main дерево менеджера или ветки
  * @param array $param параметры менеджера и записи
  */
 function addButtons(&$_this,&$main,&$param) {
 	B_::addButtons($_this,$main,$param);
 	$_this->addButtonsCut($main,$param['id']);
 }


 /**
  * действие: удаляет запись
  * @param BS $_this
  * @param int $id id удаляемой записи
  * @param Select $r запрос с удаляемой записью
  * @param bool $qd удалять саму запись или ограничиться "подготовительными действиями"
  * @return int количество удаленных записей
  */
 function deleteRecord(&$_this,$id,$r = null,$qd = true) {
	B_::deleteRecord($_this,$id,$r,$qd);
	$_this->cutRecord($id,true);
 }

 /**
  * "вставляет" "вырезанные" записи<br>
  * фактически может произвести с записями, запомненными в сессию, любые действия
  * @param BS $_this
  * @param array $param параметры менеджера
  */
 function pastAction(&$_this,&$param) {
 }

 /**
  * действие: "вставляет" "вырезанные" записи<br>
  * производит некоторое действие, затем очищает сессию
  * @param BS $_this
  * @param array $param параметры менеджера
  */
 function pastRecords(&$_this,&$param) {
	if (isset($_SESSION['idCuts']) && isset($_SESSION['idCuts'][$_this->table])) {
		$_this->pastAction($param);
		unset($_SESSION['idCuts'][$_this->table]);
	}
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  S - запомнить id в сессию
 */
class BS extends B {

 function initBS(&$db,$_name = null,$_caption =null,$_table = null) {
	$this->initB($db,$_name,$_caption,$_table);
 }

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

 function pastAction(&$param) {
 	BS_::pastAction($this,$param);
 }

 function pastRecords(&$param) {
 	BS_::pastRecords($this,$param);
 }

 function createEvent($location = '') {
 	return BS_::createEvent($this,$location);
 }

}

?>
