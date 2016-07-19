<?php

/**
 * запись c возможностью <br>
 *  T - фильтровать записи по родителю <br>
 * <br>
 * class BT extends B <br>
 * доступны методы BT,B
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
 * @uses Brunch
 */
class BT_ {

 /**
  * формирует дерево редактирования записи<br>
  * интерфейс добавления
  * @param BT $_this
  * @param outTree $main дерево редактирования
  * @param int $parent id родительской записи
  * @param string $table имя таблицы, где хранятся родительские записи
  * @return string файл шаблона добавления
  */
 function addIfcAddRecord(&$_this,&$main,$parent,$table = null) {
  	 if (!isset($table)) $table = $_this->table;
	 $r = new Select($_this->db,'select * from '.$table.' where id="'.$parent.'"');
     if ($r->next_row()) {
     	 $main->addField('add','');
  	     $main->addField('id',$parent);

		 $br = new Brunch($r->result('id'), $table, '', $_this->db);
		 $_this->initPath($br,$main,$r,true,false);
		 $ot_last = new outTree();
		 $ot_last->addField('name', 'Добавление' );
		 $main->path->addField('last',$ot_last);
         $GLOBALS['r'] = &$r;
         $GLOBALS['br'] = &$br;
     	 return 'redact.html';
     }
	 return null;
 }

 /**
  * формирует дерево редактирования записи<br>
  * интерфейс изменения
  * @param BT $_this
  * @param outTree $main дерево редактирования
  * @param int $id id изменяемой записи
  * @param string $table имя таблицы, где хранятся родительские записи
  * @return string файл шаблона добавления
  */
 function addIfcEditRecord(&$_this,&$main,$id,$table = null) {
  	 $field = 'parent';
  	 if (!isset($table)) {
  	 	$table = $_this->table;
  	 	$field = 'id';
  	 }
     $r = new Select($_this->db,'select * from '.$_this->table.' where id="'.$id.'"');
     if ($r->next_row()) {
     	 $main->addField('edit','');
 		 $br = new Brunch($r->result($field), $table, '');
		 $_this->initPath($br,$main,$r,true,false);
		 $ot_last = new outTree();
		 $ot_last->addField('name', 'Редактирование');
		 $main->path->addField('last',$ot_last);

		 $r->addAll($main);
  	     $main->addField('sct_back',isset($_GET['root']) ? $id : $r->result('parent'));

  	     $GLOBALS['r'] = &$r;
		 $GLOBALS['br'] = &$br;
		 return 'redact.html';
 	 }
     return null;
 }

 /**
  * действие: сохраняет новую запись
  * @param BT $_this
  * @param array $values ассоц.массив значений полей
  * @param int $parent id родительской записи
  */
 function saveNewRecord(&$_this,&$values,$parent) {
     $values['parent'] = $parent;
     return B_::saveNewRecord($_this,$values);
 }


 /**
  * добавляет путь в дереве менеджера или дерево редактирования
  * @param BT $_this
  * @param Brunch $br ветка, в которой хранятся все записи от "корня" до "листа"
  * @param outTree $main дерево
  * @param Select $r запрос с записью-"концом пути"
  * @param bool $with_end  включать лист или нет
  * @param bool $with_last включать конец пути или нет
  */
 function initPath(&$_this,&$br,&$main,&$r,$with_end = false,$with_last = true ) {

    $nameFirst = $_this->caption;

 	$path = new outTree();
	$br->addFieldPATH($path,'?sct=',$ar = array(),$with_end);
	if ( ( (0 < $br->level) || !$with_last )
	    || ((0 == $br->level) && $with_end)
	   ) {
	 	$ot_first = new outTree();
		$ot_first->addField('name', $nameFirst );
		$ot_first->addField('href', '?sct=1' );
		$path->addField('first',$ot_first);

		if ($with_last) {
			$ot_last = new outTree();
			$ot_last->addField('name', textFormat($r->result('name')));
			$path->addField('last',$ot_last);
		}
	}
	else {
		$ot_last = new outTree();
		$ot_last->addField('name', $nameFirst );
		$path->addField('last',$ot_last);
	}

	$main->addField('path',$path);
 }

 /**
  * получает параметры менеджера записей в массиве
  * @param BT $_this
  * @return array
  */
 function &getParamMngr(&$_this) {
 	$param = &B_::getParamMngr($_this);
 	$param['where']  = 'parent="'.$_GET['sct'].'"';
 	return $param;
 }

 /**
  * формирует дерево менеджера записей
  * @param BT $_this
  * @param outTree $main дерево менеджера
  * @return string файл шаблона менеджера
  */
 function addManager(&$_this,&$main) {
 	$param = &$_this->getParamMngr();
 	$_this->initPath($GLOBALS['br'],$main,$GLOBALS['r']);
 	$_this->addRecords($main,$param);
 	$_this->addActions($main,$param);
	return 'manager.html';
 }


 /**
  * генерирует "событие" по пришедшим переменным $_GET
  * @param BT $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  * @return bool сработало событие или нет
  */
 function createEvent(&$_this,$location = '') {
 // интерфейс добавления записи
	if     (isset($_GET['add'])) {
	    $GLOBALS['main'] = new outTree();
		$GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addIfcAddRecord($GLOBALS['main'],$_GET['add']);
	}
 // добавление записи
	elseif  (isset($_GET['save_new'])) {
	     $id = $_this->saveNewRecord($_POST,$_GET['save_new']);
         header('Location: ?event=1&'.$location);
	}

    else
     	return B_::createEvent($_this,$location);
    return true;
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  T - фильтровать записи по родителю
 */
class BT extends B {

 function initBT(&$db,$_name = null,$_caption =null,$_table = null) {
	$this->initB($db,$_name,$_caption,$_table);
 }

 function saveNewRecord(&$values,$parent) {
 	return BT_::saveNewRecord($this,$values,$parent);
 }

 function initPath(&$br,&$main,&$r,$with_end = false,$with_last = true ) {
 	BT_::initPath($this,$br,$main,$r,$with_end,$with_last);
 }

 function addIfcAddRecord(&$main,$parent,$table = null) {
 	return BT_::addIfcAddRecord($this,$main,$parent,$table);
 }

 function addIfcEditRecord(&$main,$id,$table = null) {
 	return BT_::addIfcEditRecord($this,$main,$id,$table);
 }

 function &getParamMngr() {
   	return BT_::getParamMngr($this);
 }

 function addManager(&$main) {
 	return BT_::addManager($this,$main);
 }

 function createEvent($location = '') {
 	return BT_::createEvent($this,$location);
 }


}

?>