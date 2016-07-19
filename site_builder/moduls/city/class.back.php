<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BF_P.php');
include ($inc_path.'/delete.php');

class B_news_ {

 function deleteRecord(&$_this,$id) {

       //чистим улицы
       $rs = new Select($_this->db,'select * from street where id_city='.$id);
       while ($rs->next_row()) {
             //чистим дома
             $street=$rs->result('id');
             $r1 = new Select($_this->db,'select * from house where id_street='.$street);
             while ($r1->next_row()) {
                 house_delete($r1->result('id'));
             };
             $r1 = new Select($_this->db,'delete from house where id_street='.$street);
             $r1->unset_();
       };
       $r1 = new Select($_this->db,'delete from street where id_city='.$id);
       B_::deleteRecord($_this,$id);
 }

 function redactValues(&$_this,&$values) {
         $time = &$values['time'];
         $date = &$values['date'];
    $values['datetime'] = @mktime(substr($time,0,2),substr($time,3,2),0,substr($date,3,2),substr($date,0,2),substr($date,6));
    if (empty($values['title']))
            $values['title'] = $values['name'];
        B_::redactValues($_this,$values);
 }

 function addIfcAddRecord(&$_this,&$main) {
         $_FILENAME = B_::addIfcAddRecord($_this,$main);
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = BF_::addIfcEditRecord($_this,$main,$id);
    return $_FILENAME;
 }

 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);

 }

 function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
         $param['order'] = 'name';
         return $param;
 }


}

class B_news extends BF_P {

 function redactValues(&$values) {
         B_news_::redactValues($this,$values);
 }

 function addIfcAddRecord(&$main) {
         return B_news_::addIfcAddRecord($this,$main);
 }

 function addIfcEditRecord(&$main,$id) {
         return B_news_::addIfcEditRecord($this,$main,$id);
 }

 function addSub(&$sub,&$r,$param) {
           B_news_::addSub($this,$sub,$r,$param);
 }

 function &getParamMngr() {
           return B_news_::getParamMngr($this);
 }

  function deleteRecord($id) {
          return B_news_::deleteRecord($this,$id);
 }

}

?>
