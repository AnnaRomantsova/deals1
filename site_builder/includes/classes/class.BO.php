<?php

/**
 * запись c возможностью<br>
 *  O - сортировать по полю sort<br>
 * <br>
 * class BO extends B<br>
 * доступны методы BO,B
 *
 * class BFTTO_FTTSFS_TOTS extends BFT_TO
 * доступны методы BFTTO_FTTSFS_TOTS,BS,BFT_TO,BF_T,BF,BO,BT_O,BT,B
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
class BO_ {

 /**
  * генерирует "событие" по пришедшим переменным $_GET
  * @param BO $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  * @return bool сработало событие или нет
  */
 function createEvent(&$_this,$location = '') {

 // добавление записи
         if  (isset($_GET['save_new'])) {
             $id = $_this->saveNewRecord($_POST,$_GET['save_last']);
         header('Location: ?event=1&'.$location);
         }

 // поднять выше
     elseif ( isset($_GET['up']) ) {
        $_this->upRecord($_GET['up']);
        header('Location: ?event=1&'.$location);
     }

 // опустить ниже
     elseif ( isset($_GET['down']) ) {
        $_this->downRecord($_GET['down']);
        header('Location: ?event=1&'.$location);
     }

     else
             return B_::createEvent($_this,$location);
         return true;
 }

 /**
  * устанавливает значение поля сортировки в ассоц.массиве значений<br>
  * при сохранении новой записи
  * @param BO $_this
  * @param array $values ассоц.массив значений полей
  * @param bool $last сохранять запись последней или нет
  */
 function setRecordSort(&$_this,&$values,$last = false) {
         if (!empty($last)) {
             $rSort=new Select($_this->db,'select max(sort) from '.$_this->table);
             $values['sort'] = $rSort->num_rows ? 1+$rSort->result(0,0) : 0;
             $rSort->unset_();
         }
         else {
             $_this->db->query('update '.$_this->table.' set sort=sort+1');
             $values['sort'] = 0;
         }
 }

 /**
  * действие: сохраняет новую запись
  * @param BO $_this
  * @param array $values ассоц.массив значений полей
  * @param bool $last сохранять запись последней или нет
  */
 function saveNewRecord(&$_this,&$values,$last = false) {
          $_this->setRecordSort($values,$last);
     return B_::saveNewRecord($_this,$values);
 }

 /**
 * проверяет сортировку и, если есть дублирующие значения, пересортирует
 * @param BO $_this
 * @param Select $r где проверяем сортировку - таблица в запросе должна совпадать с $_this->table
 * @return bool пересортировало или нет
 */
 function setValidSort(&$_this,&$r,$reSelect = false) {
        $sorts = array();
        while($r->next_row())
                $sorts[] = intval($r->result('sort'));

        $r->result_row = -1; // возвращаем курсор на место

        $sorts = array_unique($sorts);

   // проверка и пересортировка
        if ( $notValid = ($r->num_rows != count($sorts)) ) {
                while($r->next_row())
                        $_this->db->query('update '.$_this->table.' set sort='.$r->result_row.' where id="'.$r->result('id').'"');
                go($_SERVER['REQUEST_URI']);
        }

        return $notValid;
 }

 /**
  * действие: поднимает запись выше
  * @param BO $_this
  * @param int $id id поднимаемой записи
  */
 function upRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row())
        record_up($_this->table,$r->result('sort'));
    $r->unset_();
 }

 /**
  * действие: опускает запись ниже  :)
  * @param BO $_this
  * @param int $id id опускаемой записи  :)
  */
 function downRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row())
        record_down($_this->table,$r->result('sort'));
    $r->unset_();
 }

 /**
  * добавляет кнопки сортировки в ветку записи в дереве менеджера
  * @param BO $_this
  * @param outTree $main дерево ветки
  * @param array $param параметры менеджера и записи
  */
 function addButtonsSort(&$_this,&$main,&$param) {
         if ($param['sort'] > $param['minSort'])
                 $main->addField('butUp','');

         if ($param['sort'] < $param['maxSort'])
                 $main->addField('butDown','');
 }

 /**
  * добавляет кнопки действий в дереве менеджера<br>
  * кнопки могут быть добавлены как в само дерево менеджера для текущей записи<br>
  * так и в дерево ветки записи
  * @param BO $_this
  * @param outTree $main дерево менеджера или ветки
  * @param array $param параметры менеджера и записи
  */
 function addButtons(&$_this,&$main,&$param) {
         B_::addButtons($_this,$main,$param);
         $_this->addButtonsSort($main,$param);
 }

 /**
  * получает параметры менеджера записей в массиве
  * @param BO $_this
  * @return array
  */
 function &getParamMngr(&$_this) {
         $param = &B_::getParamMngr($_this);
         $param['order']  = 'sort';
         return $param;
 }

 /**
  * добавляет ветки записей в дерево менеджера
  * @param BO $_this
  * @param outTree $main дерево менеджера
  * @param Select $r запрос с записями
  * @param array $param параметры менеджера
  * @param string $field имя поля, с которым добавлять ветки
  */
 function addSubs(&$_this,&$main,&$r,&$param,$field = 'records') {
  // запоминаем конфигурацию запроса и меняем - нам он нужен весь
         $result_row = $r->result_row; $end = $r->end;

  // проверка валидности
    $r->result_row = -1; unset($r->end);
    $_this->setValidSort($r);

  //меняем обратно
         $r->result_row = $result_row; $r->end = $end;

        $param['minSort'] = $r->result('sort',0);
        $param['maxSort'] = $r->result('sort',($r->num_rows-1));

           B_::addSubs($_this,$main,$r,$param,$field);
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  O - сортировать по полю sort
 */
class BO extends B {

 function initBO(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->initB($db,$_name,$_caption,$_table);
 }


 function saveNewRecord(&$values,$last = null) {
         return BO_::saveNewRecord($this,$values,$last);
 }

 function setRecordSort(&$values,$last = null) {
         BO_::setRecordSort($this,$values,$last);
 }

 function setValidSort(&$r,$reSelect = true) {
         BO_::setValidSort($this,$r,$reSelect);
 }

 function upRecord($id) {
         BO_::upRecord($this,$id);
 }

 function downRecord($id) {
         BO_::downRecord($this,$id);
 }

 function addButtonsSort(&$main,&$param) {
         BO_::addButtonsSort($this,$main,$param);
 }

 function addButtons(&$main,&$param) {
         BO_::addButtons($this,$main,$param);
 }

 function createEvent($location = '') {
         return BO_::createEvent($this,$location);
 }

 function &getParamMngr() {
           return BO_::getParamMngr($this);
 }

 function addSubs(&$main,&$r,&$param,$field = 'records') {
           BO_::addSubs($this,$main,$r,$param,$field);
 }


}

?>
