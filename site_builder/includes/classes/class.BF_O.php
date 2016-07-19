<?php

/**
 * запись c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  O - сортировать по полю sort <br>
 * <br>
 * class BF_O extends BF <br>
 * доступны методы BF_O,BF,BO,B
 *
 * @package BACK
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.BF.php');
include_once('class.BO.php');

/**
 * @uses Select
 * @uses outTree
 */
class BF_O_ {

 /**
  * действие: сохраняет новую запись
  * @param BF_O $_this
  * @param array $values ассоц.массив значений полей
  * @param bool $last сохранять запись последней или нет
  */
 function saveNewRecord(&$_this,&$values,$last = null) {
         $id = BO_::saveNewRecord($_this,$values,$last);
         $_this->uploadFiles($values,$id);
     return $id;
 }

}

/**
 * работа со стандартной записью c возможностью <br>
 *  F - хранить сопровождающие файлы <br>
 *  O - сортировать по полю sort <br>
 */
class BF_O extends BF {

 function initBF_O(&$db,$_name = null,$_caption =null,$_table = null,&$arFiles) {
        $this->initBF($db,$_name,$_caption,$_table);
 }

 function saveNewRecord(&$values,$last = null) {
         return BF_O_::saveNewRecord($this,$values);
 }

///--------------- BO

 // saveNewRecord - переопределен

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
///--------------- /BO


}
?>
