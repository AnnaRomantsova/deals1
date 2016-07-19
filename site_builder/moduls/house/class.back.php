<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BF_P.php');
include ($inc_path.'/delete.php');

class B_news_ {

 function redactValues(&$_this,&$values) {

    if (empty($values['title']))
            $values['title'] = $values['name'];
        B_::redactValues($_this,$values);
 }

 function deleteRecord(&$_this,$id) {
       //чистим фотки, новости ТСЖ, опросы
       //echo $id;die;
       house_delete($id);
       B_::deleteRecord($_this,$id);
 }

 function addIfcAddRecord(&$_this,&$main) {
    $_FILENAME = B_::addIfcAddRecord($_this,$main);
    $rs = new Select($_this->db,'select * from city order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('id') ==  $main->id_city) $str_sub->addField('selected','selected');
           $main->addField('city_sub',&$str_sub);
    };

    $rs = new Select($_this->db,'select s.id,s.name,c.name as city from street s , city c where c.id=s.id_city order by  c.name,s.name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name','city'));
           if ($rs->result('id') ==  $main->id_city) $str_sub->addField('selected','selected');
           $main->addField('street_sub',&$str_sub);
    };

    $rs = new Select($_this->db,'select * from company order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('id') ==  $main->id_company) $str_sub->addField('selected','selected');
           $main->addField('company_sub',&$str_sub);
    };

    $rs->unset_();
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = BF_::addIfcEditRecord($_this,$main,$id);
    //echotree($main);
    $rs = new Select($_this->db,'select c.id from city c, street s where s.id_city=c.id and s.id = '. $main->id_street);
    if ($rs->next_row()) $city=$rs->result('id');
    $rs->unset_();

    $rs = new Select($_this->db,'select * from city order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('id') ==  $city) $str_sub->addField('selected','selected');
           $main->addField('city_sub',&$str_sub);
    };

    $rs = new Select($_this->db,'select s.id,s.name,c.name as city from street s , city c where c.id=s.id_city order by c.name,s.name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name','city'));
           if ($rs->result('id') ==  $main->id_street) $str_sub->addField('selected','selected');
           $main->addField('street_sub',&$str_sub);
    };

    $rs = new Select($_this->db,'select * from company order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('id') ==  $main->id_company) $str_sub->addField('selected','selected');
           $main->addField('company_sub',&$str_sub);
    };

    $rs->unset_();
    return $_FILENAME;
 }

 // список домов
 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);


         $r1 = new Select($_this->db,'select c.name as city_name,s.name as street_name from house h,city c, street s where h.id_street=s.id and s.id_city = c.id and h.id='. $r->result('id'));
         if ($r1->next_row()) {
             $sub->addField('city_name',$r1->result('city_name'));
             $sub->addField('street_name',$r1->result('street_name'));
         };

         if( $r->result('id_company')>0)
               $r1 = new Select($_this->db,'select * from company where id='. $r->result('id_company'));
               if ($r1->next_row()) $sub->addField('company',$r1->result('name'));

         $r->addFields($sub,$ar=array('number','fract'));
         $r1->unset_();
 }

 // реализует выбор улиц по городу
 function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);

         //задана улица
         if (isset($_GET['id_street']))
           if ($_GET['id_street'] > 0)
              $param['where'] = ' id_street = '.$_GET['id_street'];
         //задан только город
         if (isset($_GET['id_city']) and !($_GET['id_street'] > 0) )
            if ($_GET['id_city'] > 0)
              $param['query'] = 'select * from house h, city c,street s where c.id=s.id_city and h.id_street=s.id and c.id= '.$_GET['id_city'];

         $param['order'] = 'id_street,number,fract';


         return $param;
 }

 // формирует списки для фильтра
 function addManager(&$_this,&$main) {

         //города
         $r1 = new Select($_this->db,'select * from city');
         while ($r1->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $r1->addFields($str_sub,$ar=array('id','name'));
           if ($_GET['id_city'] == $r1->result('id'))
              $str_sub->addField('selected','selected');
           $main->addField('str_sub',&$str_sub);
         };
         $r1->unset_();

         //улицы
         if ($_GET['id_city']>0) $where = ' where id_city='.$_GET['id_city'];
         $r1 = new Select($_this->db,'select * from street '.$where);
         while ($r1->next_row()) {
           unset($street_sub);
           $street_sub = new outTree();
           $r1->addFields($street_sub,$ar=array('id','name'));
           if ($_GET['id_street'] == $r1->result('id'))
              $street_sub->addField('selected','selected');
           $main->addField('street_sub',&$street_sub);
         };
         $r1->unset_();

        return B_::addManager($_this,$main);
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
           return B_news_::getParamMngr($this,$query);
 }
 function deleteRecord($id) {
          return B_news_::deleteRecord($this,$id);
 }
///*
function addManager(&$main) {
           return B_news_::addManager($this,$main);
 }
 //*/
}

?>
