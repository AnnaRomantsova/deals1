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

//      print_r($values);
//      die();
      // var_dump($values);
      //$date = &$values['date'];
    //$values['datetime'] = @mktime(0,0,0,substr($date,3,2),substr($date,0,2),substr($date,6));
        B_::redactValues($_this,$values);
 }

 function addIfcAddRecord(&$_this,&$main) {
         $_FILENAME = B_::addIfcAddRecord($_this,$main);
        $main->addField('about',"loadFCKeditor('about','');");
        $main->addField('preview',"loadFCKeditor('preview','');");
       /* $rs = new Select($_this->db,'select * from company order by name');
        while ($rs->next_row()) {
                   unset($str_sub);
                   $str_sub = new outTree();
                   $rs->addFields($str_sub,$ar=array('id','name'));
        //           if ($rs->result('id') ==  $main->id_company) $str_sub->addField('selected','selected');
                   $main->addField('str_sub',&$str_sub);
        };
        $rs->unset_();

       */
       //$main->addField('date',date('d.m.Y'));
        //addCalend($main,1);
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = B_::addIfcEditRecord($_this,$main,$id);

   // $rs->unset_();
    //$main->addField('is_parent','1');
         //$main->addField('date',date('d.m.Y', $main->datetime));
        //addCalend($main,1);
    //    echotree($main);
    return $_FILENAME;
 }


 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);
         $sub->addField('fio', $r->result('name'));
         $sub->addField('email', $r->result('email'));
         //$rs = new Select($_this->db,'select * from company where id='.$r->result('id_company'));
         //if ($rs->next_row())
        //   $sub->addField('company',$rs->result('name'));

 }



  function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
         $param['order'] = 'name desc';
         //$param['where'] = ' is_chairman=1';


         return $param;
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
}

?>
