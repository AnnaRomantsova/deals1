<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BO_P.php');

class B_articles_ {

 function redactValues(&$_this,&$values) {
      if(!empty($values['pass_text']))
              $values['pass'] = md5( $values['pass_text'] );
      else unset($values['pass']);

        B_::redactValues($_this,$values);
 }

  function deleteRecord(&$_this,$id) {
       //чистим предложения
       $rs = new Select($_this->db,"delete from offer where id_vendor=$id");
       B_::deleteRecord($_this,$id);
 }

 function addIfcAddRecord(&$_this,&$main) {
         $_FILENAME = B_::addIfcAddRecord($_this,$main);


         $rs = new Select($_this->db,'select * from city order by name');
         while ($rs->next_row()) {
                unset($str_sub);
                $str_sub = new outTree();
                $rs->addFields($str_sub,$ar=array('id','name'));
               // if ($rs->result('id') ==  $main->id_city) $str_sub->addField('selected','selected');
                $main->addField('city',&$str_sub);
         };
         $rs = new Select($_this->db,'select * from act_category order by name');
         while ($rs->next_row()) {
                unset($str_sub);
                $str_sub = new outTree();
                $rs->addFields($str_sub,$ar=array('id','name'));
                //if ($rs->result('id') ==  $main->act_category) $str_sub->addField('selected','selected');
                $main->addField('act_category',&$str_sub);
         };
        $rs->unset_();
         //$main->addField('date',date('d.m.Y'));
        //addCalend($main,1);
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = B_::addIfcEditRecord($_this,$main,$id);
    $rs = new Select($_this->db,'select * from city order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('id') ==  $main->id_city) $str_sub->addField('selected','selected');
           $main->addField('city',&$str_sub);
    };
    unset($rs);
   // echo $main->act_category;
    $rs = new Select($_this->db,'select * from act_category order by name');
    while ($rs->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $rs->addFields($str_sub,$ar=array('id','name'));
           if ($rs->result('id') ==  $main->act_category) {
                   $str_sub->addField('selected','selected');
             //      echo "!";
           };
           //echo $rs->result('id')."  ".$main->act_category;
           $main->addField('act_category_sub',&$str_sub);
    };
    $rs->unset_();
    //$main->addField('is_parent','1');
         //$main->addField('date',date('d.m.Y', $main->datetime));
        //addCalend($main,1);
     //   echotree($main);
    return $_FILENAME;
 }


 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);

        // $r->addFields($str_sub,$ar=array('id','name'));
         $sub->addField('fio', $r->result('surname')." ".$r->result('name')." ".$r->result('secname'));
         $sub->addField('email', $r->result('email'));

         if ($r->result('id_city')>0) {
           $rs = new Select($_this->db,'select * from city where id='.$r->result('id_city'));
           if ($rs->next_row())
             $sub->addField('city',$rs->result('name'));
         };

         if ($r->result('act_category')>0) {
           $rs = new Select($_this->db,'select * from act_category where id='.$r->result('act_category'));
           if ($rs->next_row())
             $sub->addField('act_category',$rs->result('name'));
         };
 }



  function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
         $param['where'] = ' is_chairman<>1';
         //задана улица
         if ($_GET['id_city'] > 0)
              $param['where'] .= ' and id_city = '.$_GET['id_city'];

         //задан вид деятельности
         if ($_GET['act_category']>0)
             $param['where'] .= ' and act_category = '.$_GET['act_category'];


         $param['order'] = 'surname,name desc';

         return $param;
 }

 // формирует списки для фильтра
 function addManager(&$_this,&$main) {

         //города
         $r1 = new Select($_this->db,'select * from city order by name');
         while ($r1->next_row()) {
           unset($str_sub);
           $str_sub = new outTree();
           $r1->addFields($str_sub,$ar=array('id','name'));
           if ($_GET['id_city'] == $r1->result('id'))
              $str_sub->addField('selected','selected');
           $main->addField('city_sub',&$str_sub);
         };
         $r1->unset_();

         //улицы
        $r1 = new Select($_this->db,'select * from act_category order by name' );
         while ($r1->next_row()) {
           unset($street_sub);
           $street_sub = new outTree();
           $r1->addFields($street_sub,$ar=array('id','name'));
           if ($_GET['act_category'] == $r1->result('id'))
              $street_sub->addField('selected','selected');
           $main->addField('act_category_sub',&$street_sub);
         };
         $r1->unset_();

        return B_::addManager($_this,$main);
 }

}

class B_articles extends BO_P {
 function addIfcAddRecord(&$main) {
         return B_articles_::addIfcAddRecord($this,$main);
 }

 function addIfcEditRecord(&$main,$id) {
         return B_articles_::addIfcEditRecord($this,$main,$id);
 }

 function redactValues(&$values) {
         B_articles_::redactValues($this,$values);
 }

 function addSub(&$sub,&$r,$param) {
           B_articles_::addSub($this,$sub,$r,$param);
 }

  function &getParamMngr() {
          return B_articles_::getParamMngr($this);
 }

 function addManager(&$main) {
           return B_articles_::addManager($this,$main);
 }

  function deleteRecord($id) {
          return B_articles_::deleteRecord($this,$id);
 }
}

?>
