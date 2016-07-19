<?php

/**
 * структура типа дерево <br>
 *  <br>
 * class BTr extends Module <br>
 *  <br>
 * class BSc extends BFTTO_FTTSFS_TOTS <br>
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BFTTO_FTTSFS_TOTS.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Brunch
 */
class BSc_ {

 /**
  * добавляет кнопки действий в дереве менеджера<br>
  * кнопки могут быть добавлены как в само дерево менеджера для текущей записи<br>
  * так и в дерево ветки записи
  * @param BSc $_this
  * @param outTree $main дерево менеджера или ветки
  * @param array $param параметры менеджера и записи
  */
 function addButtons(&$_this,&$main,&$param) {
	 if ( (1 != $param['id']) )	{ // если не корень
	    $main->addField('butRedact','');
	    $_this->addButtonsCut($main,$param['id']);
	 }

	 if (empty($param['root']))  { // если не текущий каталог
	 	$main->addField('butDelete','');
	    $main->addField('butPabl','');
	    $_this->addButtonsSort($main,$param);
	 }
 }

 /**
  * формирует дерево менеджера записей
  * @param BSc $_this
  * @param outTree $main дерево менеджера
  * @return string файл шаблона менеджера
  */
 function addManager(&$_this,&$main) {
 	$param = &$_this->getParamMngr();
 	$_this->addRecords($main,$param);
	return 'manager.html';
 }

}

/**
 * работа со стандартной записью "раздел" c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  S - запомнить id в сессию <br>
 *  O - сортировать по полю sort <br>
 *  T - фильтровать записи по родителю
 */
class BSc extends BFTTO_FTTSFS_TOTS {

  function addButtons(&$main,&$param) {
 	BSc_::addButtons($this,$main,$param);
  }

  function addManager(&$main) {
 	return BSc_::addManager($this,$main);
  }

}

/**
 * @uses Select
 * @uses outTree
 */
class BTr_ {

 /**
  * действие: очищает "раздел"
  * @param BTr $_this
  * @param int   $id     какой "раздел" очищать
  * @param int   $type   что удалять:<br>
  *                      0 - всё;<br>
  *                      2 - только "подразделы"<br>
  *                      любые комбинации можно удалять, подставляя их произведение:<br>
  *                      например разделы и товары = 3*2 = 6
  */
 function clearSection(&$_this,$id,$type = 0) {
    // удаление подразделов
    if (0 == ($type%2))
    	$_this->deleteSections($id);


 }

 /**
  * действие: удаляет "раздел"
  * @param BTr $_this
  * @param int $id id удаляемого "раздела"
  * @param Select $r запрос с удаляемой записью
  * @param bool $qd удалять саму запись или ограничиться "подготовительными действиями"
  */
 function deleteSection(&$_this,$id,$r = null,$qd = true) {
 	$_this->Section->deleteRecord($id,$r,$qd);
	$_this->clearSection($id);
 }

 /**
  * удаляет все "подразделы" в "разделе"
  * @param BTr $_this
  * @param int $id id удаляемого "раздела"
  * @param Select $r запрос с удаляемой записью
  * @param bool $qd удалять саму запись или ограничиться "подготовительными действиями"
  */
 function deleteSections(&$_this,$id) {
    $r = new Select($_this->db,'select * from '.$_this->Section->table.' where parent="'.$id.'"');
    while ($r->next_row()) {
       $_this->deleteSection($r->result('id'),$r,false);
    }
    $r->unset_();
	$_this->db->query('delete from '.$_this->Section->table.' where parent="'.$id.'"');
 }


 /**
  * действие: вставляет вырезанные записи<br>
  * производит некоторое действие, затем очищает сессию
  * @param BTr $_this
  * @param array $param параметры менеджера
  */
 function pastRecords(&$_this,&$param) {
 	$_this->Section->pastRecords($param);
 }


 /**
  * добавляет пункты действий в дерево менеджера
  * @param BTr $_this
  * @param outTree $main дерево менеджера
  * @param array $param параметры менеджера
  */
 function addActions(&$_this,&$main,&$param) {
  //если утерян путь к корню - выходим на страницу по умолчанию.
	if ( 0 > $GLOBALS['br']->level)
       header('Location: ?sct=1');

	$main->addField('actAddSection','');

	if (   isset($_SESSION['idCuts']) &&
	      ( $co = count($_SESSION['idCuts'][$_this->Section->table]))
	     )
		 $main->addField('actPast',$co);

  // если проинициализировано хотя бы одно из действий
	if (     isset($main->actAddSection)
	      ||  isset($main->actClear)
	      ||  isset($main->actPast)
	    )
	 	$main->addField('actions','');
 }

 /**
  * формирует дерево менеджера записей
  * @param BTr $_this
  * @param outTree $main дерево менеджера
  * @return string файл шаблона менеджера
  */
 function addManager(&$_this,&$main) {
 	$_GET['sct'] =  ( !empty($_GET['sct']) ? $_GET['sct'] : 1 );

 	$r = new Select($_this->db,'select * from '.$_this->Section->table.' where id="'.$_GET['sct'].'"');
    $GLOBALS['r']  = &$r;
    if ($r->next_row()) {
	    // интерфейс текущего раздела
   	    $r->addFields($main,$ar = array('name','id'));

		$br = new Brunch($_GET['sct'], $_this->Section->table, '', $_this->db);
	    $GLOBALS['br']  = &$br;

		$_this->Section->initPath($br,$main,$r);
	 	$_this->Section->addButtons($main,$ar = array('id'=>$_GET['sct'],'root'=>1));
		$_this->Section->addManager($main);

	 	$_this->addActions($main,$param);

		return 'manager.html';
    }
 }

 /**
  * генерирует "событие" по пришедшим переменным $_GET
  * @param BTr $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  * @return bool сработало событие или нет
  */
 function createEvent(&$_this) {

 	 $location = '&sct='.$_GET['sct_back'];
 	 if ('s' == $_GET['type'])
 	 	$GLOBALS['b'] = &$_this->Section;

  // очищение секции
     if     ( isset($_GET['clear_s']) ) {
         $_this->clearSection($_GET['clear_s'],$_GET['clear_type']);
         header('Location: ?event=1&sct='.$_GET['clear_s']);
     }

 // удаление секции
     elseif ( isset($_GET['delete'])&& ('s' == $_GET['type'])) {
        $_this->deleteSection($_GET['delete']);
        header('Location: ?event=1'.$location);
     }

 // вставить вырезанные записи
     elseif ( isset($_GET['past']) ) {
		$_this->pastRecords($ar=array('parent'=>$_GET['past']));
        header('Location: ?event=1'.$location);
     }

 // отменить вставку
     elseif ( isset($_GET['undoPast']) ) {
		unset($_SESSION['idCuts'][$_this->Section->table]);
        header('Location: ?event=1'.$location);
     }

 // стандартные действия для записи
     elseif ( isset($GLOBALS['b']) ) {
     	return $GLOBALS['b']->createEvent($location);
     }
     else return false;
     return true;
 }


 /**
  * получает "событие" по пришедшим переменным $_GET
  * @param BTr $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  */
 function getEvent(&$_this,$location = '') {
 	$_this->createEvent($location = '');

  // отображение интерфейса менеджера
    if (!isset($GLOBALS['main'])) {
	     $GLOBALS['main'] = new outTree();
	     $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addManager($GLOBALS['main']);
 	}

    out::_echo($GLOBALS['main'],$GLOBALS['main_FILENAME']);
 }

}


/**
 * дерево из одной таблицы
 */
class BTr extends Module {

 /**
  * @var BSc $Section объект-"раздел" для работы с "плоской" структурой
  */
 var $Section;

 function BTr(&$_db,$_name = null,$_caption = null,$table_sections,&$arFilesS) {
 	$this->initBTr($_db,$_name,$_caption,$table_sections,$arFilesS);
 }

 function initBTr(&$_db,$_name,$_caption,$table_sections,&$arFilesS) {
	$this->Section = new BSc($_db,$_name,$_caption,$table_sections,$arFilesS);
	$this->initModule($_db,$_name,$_caption);
 }

 function clearSection($id,$type = 0) {
 	BTr_::clearSection($this,$id,$type);
 }

 function deleteSection($id,$r = null,$qd = true) {
 	BTr_::deleteSection($this,$id,$r,$qd);
 }

 function deleteSections($id) {
 	BTr_::deleteSections($this,$id);
 }

 function pastRecords(&$param) {
 	BTr_::pastRecords($this,$param);
 }

 function addManager(&$main) {
 	return BTr_::addManager($this,$main);
 }

 function addActions(&$main,&$param) {
 	BTr_::addActions($this,$main,$param);
 }

 function createEvent() {
 	return BTr_::createEvent($this);
 }

 function getEvent($location = '') {
 	BTr_::getEvent($this,$location);
 }


}


?>