<?php

/**
 * "плоская" структура <br>
 * работа со стандартной записью в системе администрирования <br>
 * <br>
 * class B extends Module
 *
 * @package BACK
 * @version 1.03 - 15.12.2006 10:00
 *
 * .02 исправлена ошибка при очищении таблицы <br>
 * .03 заслешивание в redactvalue <br>
 *
 */


include_once('class.module.php');

/**
 * реализация
 * @uses Select
 * @uses outTree
 */
class B_ {

 /**
  * формирует дерево редактирования записи<br>
  * интерфейс добавления
  * @param B $_this
  * @param outTree $main дерево редактирования
  * @return string файл шаблона добавления
  */
 function addIfcAddRecord(&$_this,&$main) {
     $main->addField('add','');

         $_this->initPath($main,false);
         $ot_last = new outTree();
         $ot_last->addField('name', 'Добавление');
         $main->path->addField('last',$ot_last);

         return 'redact.html';
 }

 /**
  * формирует дерево редактирования записи<br>
  * интерфейс изменения
  * @param B $_this
  * @param outTree $main дерево редактирования
  * @param int $id id изменяемой записи
  * @return string файл шаблона изменения
  */
 function addIfcEditRecord(&$_this,&$main,$id) {
          $main->addField('edit','');

     $r = new Select($_this->db,'select * from '.$_this->table.' where id="'.$id.'"');
     if ($r->next_row()) {
                 $_this->initPath($main,false);
                 $ot_last = new outTree();
                 $ot_last->addField('name', 'Редактирование' );
                 $main->path->addField('last',$ot_last);

         $r->addAll($main);
         $GLOBALS['r'] = &$r;
              return 'redact.html';
     }

     return null;
 }


 /**
  * действие: сохраняет новую запись
  * @param B $_this
  * @param array $values ассоц.массив значений полей
  */
 function saveNewRecord(&$_this,&$values) {
          $_this->redactValues($values);
     $values['id'] = $id = $_this->db->next_id($_this->table);
     $_this->db->insert($_this->table, $values);
     return $id;
 }

 /**
  * действие: сохраняет существующую запись
  * @param B $_this
  * @param array $values ассоц.массив значений изменяемых полей
  * @param int $id id изменяемой записи
  */
 function saveRecord(&$_this,&$values,$id) {
          $_this->redactValues($values);
        //  var_dump($values);
       // echo $id;
         $_this->db->update($_this->table, $values, 'id="'.$id.'"');
 }

 /**
  * предварительная обработка <br>
  * ассоц.массива значений полей<br>
  * при сохранении записей
  * @param B $_this
  * @param array $values ассоц.массив значений полей
  */
 function redactValues(&$_this,&$values) {
          foreach ( $values as $key => $value) {
                  //echo $key.' = '.$value.'<br />';
            $values[$key] = $value;
          }
         $values['pabl'] = (double)$values['pabl'];
 }


 /**
  * действие: очищает таблицу
  * @param B $_this
  * @return int количество удаленных записей
  */
 function clearTable(&$_this) {

    // удаление записей
    $ri = new Select($_this->db,'select * from '.$_this->table);
    while ($ri->next_row())
       $_this->deleteRecord($ri->result('id'),$ri,false);
    $ri->unset_();
        $_this->db->query('delete from '.$_this->table);
        return $_this->db->affected_rows();

 }


 /**
  * действие: удаляет запись
  * @param B $_this
  * @param int $id id удаляемой записи
  * @param Select $r запрос с удаляемой записью
  * @param bool $qd удалять саму запись или ограничиться "подготовительными действиями"
  * @return int количество удаленных записей
  */
 function deleteRecord(&$_this,$id,$r = null,$qd = true) {
        if ($qd) {
                $_this->db->query('delete from '.$_this->table.' where id="'.$id.'"');
                 return $_this->db->affected_rows();
        }
        else
                return 0;

 }


 /**
  * действие: публикует/не публикует запись
  * @param B $_this
  * @param int $id id изменяемой записи
  */
 function pablRecord(&$_this,$id) {
    $r = new Select($_this->db,'select pabl from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row())
                $_this->db->query('update '.$_this->table.' set pabl="'.((1+intval($r->result(0)))%2).'" where id="'.$id.'"');
    $r->unset_();
 }


 /**
  * добавляет путь в дереве менеджера или дерево редактирования
  * @param B $_this
  * @param outTree $main дерево
  * @param Select $r запрос с записью-"концом пути"
  * @param bool $with_last включать конец пути или нет
  */
 function initPath(&$_this,&$main,$with_last = true) {

         $nameFirst = $_this->caption;
         $path = new outTree();
        if (!$with_last) {
                 $ot_first = new outTree();
                $ot_first->addField('name', $nameFirst);
                $ot_first->addField('href', '?' );
                $path->addField('first',$ot_first);
        }
        else {
                $ot_last = new outTree();
                $ot_last->addField('name', $nameFirst );
                $path->addField('last',$ot_last);
        }

        $main->addField('path',$path);
 }

 /**
  * добавляет кнопки действий в дереве менеджера<br>
  * кнопки могут быть добавлены как в само дерево менеджера для текущей записи<br>
  * так и в дерево ветки записи
  * @param B $_this
  * @param outTree $main дерево менеджера или ветки
  * @param array $param параметры менеджера и записи
  */
 function addButtons(&$_this,&$main,&$param) {
         $main->addField('butRedact','');
         $main->addField('butDelete','');
         $main->addField('butPabl','');
         //echotree($main);
 }


 /**
  * добавляет пункты действий в дерево менеджера
  * @param B $_this
  * @param outTree $main дерево менеджера
  * @param array $param параметры менеджера
  */
 function addActions(&$_this,&$main,&$param) {
        $main->addField('actAdd','');
        $main->addField('actions','');

 }

 /**
  * добавляет одну ветку записей в дерево менеджера
  * @param B $_this
  * @param outTree $sub дерево ветки
  * @param Select $r запрос с записями
  * @param array $param параметры менеджера и записи
  */
 function addSub(&$_this,&$sub,&$r,$param) {
        $_this->addButtons($sub,$param);
           $r->addFields($sub,$ar = array('name','id','pabl'));
 }

 /**
  * добавляет ветки записей в дерево менеджера
  * @param B $_this
  * @param outTree $main дерево менеджера
  * @param Select $r запрос с записями
  * @param array $param параметры менеджера
  * @param string $field имя поля, с которым добавлять ветки
  */
 function addSubs(&$_this,&$main,&$r,&$param,$field = 'records') {
            $ot = new outTree();
     while ($r->next_row()) {
             $sub =  new outTree();
             $param = &array_merge($param,$ar=$r->fetch_assoc());
             //var_dump($param);
             $_this->addSub($sub,$r,$param);
             $ot->addField('sub',$sub);
             unset($sub);
             //echo "k";
     }
            $main->addField($field,$ot);
 }

 /**
  * добавляет записи в дерево менеджера
  * @param B $_this
  * @param outTree $main дерево менеджера
  * @param array $param параметры менеджера
  */
 function addRecords(&$_this,&$main,&$param) {

        //echo 'select * from '.$_this->table.(isset($param['where']) ? ' where '.$param['where'] : '').(isset($param['order']) ? ' order by '.$param['order'] : '');
        $r = new Select($_this->db,'select * from '.$_this->table.(isset($param['where']) ? ' where '.$param['where'] : '').(isset($param['order']) ? ' order by '.$param['order'] : ''));
         //echo $r->num_rows;

    if ($r->num_rows) {
           $_this->addSubs($main,$r,$param);
           $main->addField('actClear','');
    }
    $r->unset_();
 }

 /**
  * получает параметры менеджера записей в массиве
  * @param B $_this
  * @return array
  */
 function &getParamMngr(&$_this) {
         return array();
 }

 /**
  * формирует дерево менеджера записей
  * @param B $_this
  * @param outTree $main дерево менеджера
  * @return string файл шаблона менеджера
  */
 function addManager(&$_this,&$main) {
         $param = &$_this->getParamMngr();
         $_this->initPath($main);
         $_this->addRecords($main,$param);
         $_this->addActions($main,$param);
//        echotree($main);
        return 'manager.html';
 }

 /**
  * получает "событие" по пришедшим переменным $_GET
  * @param B $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  */
 function getEvent(&$_this,$location = '') {
         $_this->createEvent($location);

  // отображение интерфейса менеджера
    if (!isset($GLOBALS['main'])) {
             $GLOBALS['main'] = new outTree();
             $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addManager($GLOBALS['main']);
    }

    out::_echo($GLOBALS['main'],$GLOBALS['main_FILENAME']);
 }

 /**
  * генерирует "событие" по пришедшим переменным $_GET
  * @param B $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  * @return bool сработало событие или нет
  */
 function createEvent(&$_this,$location = '') {

 // очищение таблицы
     if ( isset($_GET['clear']) ) {
        $_this->clearTable();
        header('Location: ?event=1&'.$location);
     }

 // удаление
     elseif ( isset($_GET['delete']) ) {
        $_this->deleteRecord($_GET['delete']);
        header('Location: ?event=1&'.$location);
     }

 // публиковать/не публиковать
     elseif ( isset($_GET['pabl']) ) {
        $_this->pablRecord($_GET['pabl']);
        header('Location: ?event=1&'.$location);
     }

 // интерфейс добавления записи
        elseif     (isset($_GET['add'])) {
            $GLOBALS['main'] = new outTree();
                $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addIfcAddRecord($GLOBALS['main']);
        }

// интерфейс изменения записи
        elseif (isset($_GET['edit'])) {
            $GLOBALS['main'] = new outTree();
                $GLOBALS['main_FILENAME'] = $GLOBALS['back_html_path'].$_this->addIfcEditRecord($GLOBALS['main'],$_GET['edit']);
        }

 // добавление записи
         elseif  (isset($_GET['save_new'])) {
             $id = $_this->saveNewRecord($_POST);
         header('Location: ?event=1&'.$location);
         }

 // изменение записи
     elseif (isset($_GET['save'])) {
             $_this->saveRecord($_POST,$_GET['save']);
         header('Location: ?event=1&'.$location);
         }

         else
                 return false;
         return true;
 }

}

/**
 * работа со стандартной записью
 */
class B extends Module {
 var $table;

 function B(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->initB($db,$_name,$_caption,$_table);
 }

 function initB(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->table = $_table;
        $this->initModule($db,$_name,$_caption);
 }


 function redactValues(&$values) {
         B_::redactValues($this,$values);
 }

 function saveNewRecord(&$values) {
         return B_::saveNewRecord($this,$values);
 }

 function saveRecord(&$values,$id) {
         B_::saveRecord($this,$values,$id);
 }

 function clearTable() {
         return B_::clearTable($this);
 }

 function deleteRecord($id,$r = null,$qd = true) {
         B_::deleteRecord($this,$id,$r,$qd);
 }

 function pablRecord($id) {
         B_::pablRecord($this,$id);
 }

 function addIfcAddRecord(&$main) {
         return B_::addIfcAddRecord($this,$main);
 }

 function addIfcEditRecord(&$main,$id) {
         return B_::addIfcEditRecord($this,$main,$id);
 }

 function initPath(&$main,$with_last = true ) {
         B_::initPath($this,$main,$with_last);
 }

 function addButtons(&$main,&$param) {
         B_::addButtons($this,$main,$param);
 }

 function addActions(&$main,&$param) {
         B_::addActions($this,$main,$param);
 }

 function addRecords(&$main,&$param) {
         B_::addRecords($this,$main,$param);
 }

 function addManager(&$main) {
         return B_::addManager($this,$main);
 }

 function addSub(&$sub,&$r,&$param) {
           B_::addSub($this,$sub,$r,$param);
 }

 function addSubs(&$main,&$r,&$param,$field = 'records') {
           B_::addSubs($this,$main,$r,$param,$field);
 }

 function &getParamMngr() {
           return B_::getParamMngr($this);
 }

 function createEvent($location = '') {
         return B_::createEvent($this,$location);
 }

 function getEvent($location = '') {
         B_::getEvent($this,$location);
 }

}



?>
