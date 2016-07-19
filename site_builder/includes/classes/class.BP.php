<?php

/**
 * запись c возможностью<br>
 *  P - постраничного просмотра<br>
 * <br>
 * class BP extends B<br>
 * доступны методы BP,B
 *
 * @package BACK
 * @version 1.01 - 15.12.2006 10:00
 *
 */

include_once('class.B.php');

/**
 * @uses Select
 * @uses outTree
 * @uses Pager
 */
class BP_ {

 /**
  * получает параметры менеджера записей в массиве
  * @param BP $_this
  * @return array
  */
 function &getParamMngr(&$_this) {
         $param = &B_::getParamMngr($_this);
          $param['jumpValue'] = $_GET['id_back'];
          $param['asGET'] = true;
         return $param;
 }

 /**
  * добавляет записи в дерево менеджера с постраничным просмотром
  * @param BP $_this
  * @param outTree $main дерево менеджера
  * @param array $param параметры менеджера
  */
 function addRecords(&$_this,&$main,&$param) {
           include($GLOBALS['inc_path'].'/service/class.pager.php');
    $main->addField('cp',!empty($_GET['cp']) ? $_GET['cp'] : 0);
           $param = &$_this->getParamMngr();
         //  var_dump($param);
        if ($pg = &Pager::newPager($_this->db,$_this->table,$GLOBALS[$_this->name.'_acount'],$_GET['cp'],$param)) {
                 $pg->addPAGER($main);
             $_this->addSubs($main,$pg->r,$param);
             $main->addField('actClear','');
    }
 }

 /**
  * получает "событие" по пришедшим переменным $_GET
  * @param BP $_this
  * @param string $location при необходимости произойдёт редирект на ?event=1&'.$location
  */
 function getEvent(&$_this,$location = '') {

//         if (isset($_GET['order']))
 //              $location.='&order='.$_GET['order'].'&';
         $location.='cp='.$_GET['cp'];
         if (isset($_GET['id_back']))
           $location.='&id_back='.$_GET['id_back'];
         //echo $location;
         B_::getEvent($_this,$location);
 }



}

/**
 * работа со стандартной записью c возможностью <br>
 *  P - постраничного просмотра
 */
class BP extends B {

 function initBP(&$db,$_name = null,$_caption =null,$_table = null) {
        $this->table = $_table;
        $this->initModule($db,$_name,$_caption);
 }

 function addRecords(&$main,&$param) {
         BP_::addRecords($this,$main,$param);
 }

 function &getParamMngr() {
           return BP_::getParamMngr($this);
 }

 function getEvent($location = '') {
         BP_::getEvent($this,$location);
 }

}



?>
