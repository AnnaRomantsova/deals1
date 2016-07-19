<?php
/**
 *  Классы и функции по обработке записей и выводу сущностей в шаблоны.
 */

include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BF_P.php');
include($inc_path.'/object.php');
include($inc_path.'/myfunc.php');

//сжтие картинки
 function image_resize($filename,$new_width,$new_height){

  //$filename=substr($filename,1);
  list($width, $height) = getimagesize($filename);

  $ratio_orig = $width/$height;

  if ($new_width/$new_height > $ratio_orig) {
     $new_width = $new_height*$ratio_orig;
  } else {
     $new_height = $new_width/$ratio_orig;
  }

  $image_p = imagecreatetruecolor($new_width, $new_height);
  $image = imagecreatefromjpeg($filename);
  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
  imagejpeg($image_p, $filename, 100); //50% это качество 0-100%
 };


class B_news_ {

  function saveRecord(&$_this,&$values,$id) {


       $time = &$values['time'];
       $date = &$values['srok_end_podach'];
       $values['srok_end_podach'] = @mktime(substr($time,0,2),substr($time,3,2),0,substr($date,3,2),substr($date,0,2),substr($date,6));
      BF_::saveRecord($_this,$values,$id);
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
         $main->addField('date',date('d.m.Y'));
    $main->addField('time',date('H:i'));
            $profile = new object($this->db,$main, $this->table);

                    $fields = array(array("fname" => 'number', "rname" => '№ извещения', "type" => 'varchar', "length" => '255'),
                        array("fname" => 'sposob', "rname" => 'Способ закупки', "type" => 'varchar', 'list_table' => 'relate'),
                        array("fname" => 'type', "rname" => 'Тип', "type" => 'text'),
                        array("fname" => 'name_etp', "rname" => 'Наименование ЭТП', "type" => 'varchar'),
                        array("fname" => 'srok_end_podach', "rname" => 'Срок окончания подачи заявок', "type" => 'varchar', 'list_table' => 'relate'),
                        array("fname" => 'customer', "rname" => 'Наименование заказчика', "type" => 'varchar'),
                        array("fname" => 'inn_customer', "rname" => 'ИНН заказчика', "type" => 'varchar'),
                        array("fname" => 'geographi', "rname" => 'География', "type" => 'varchar'),
                        array("fname" => 'predmet', "rname" => 'Предмет закупки', "type" => 'varchar', 'list_table' => 'relate'),
                        array("fname" => 'price_lot', "rname" => 'НМЦК', "type" => 'varchar'),

                       array("fname" => 'price_winner', "rname" => 'Цена победителя', "type" => 'varchar'),
                    array("fname" => 'inn_winner', "rname" => 'ИНН победителя', "type" => 'varchar'),
                    array("fname" => 'name_winner', "rname" => 'Наименование победителя', "type" => 'varchar'),
                );


            $profile->add_fields($fields,$id);
    //    $main->addField('preview',"loadFCKeditor('preview','');");
    addCalend($main,1);
    return $_FILENAME;
 }

 function addIfcEditRecord(&$_this,&$main,$id) {
    $_FILENAME = BF_::addIfcEditRecord($_this,$main,$id);


     $profile = new object($this->db,$main, $this->table);

             $fields = array(
                 array("fname" => 'date_create', "rname" => 'Дата добавления', "type" => 'date', "length" => '255','id_cal'=>1),
                 array("fname" => 'date_income', "rname" => 'Дата поступления', "type" => 'date', "length" => '255','id_cal'=>2),
                 array("fname" => 'number', "rname" => '№ извещения', "type" => 'varchar', "length" => '255'),
                 array("fname" => 'sposob', "rname" => 'Способ закупки', "type" => 'varchar', 'list_table' => 'relate'),
                 array("fname" => 'type', "rname" => 'Тип', "type" => 'varchar'),
                 array("fname" => 'name_etp', "rname" => 'Наименование ЭТП', "type" => 'varchar'),
                 array("fname" => 'srok_end_podach', "rname" => 'Срок окончания подачи заявок', "type" => 'date','id_cal'=>3 ),
                 array("fname" => 'customer', "rname" => 'Наименование заказчика', "type" => 'varchar', "length" => '255'),
                 array("fname" => 'inn_customer', "rname" => 'ИНН заказчика', "type" => 'varchar'),
                 array("fname" => 'geographi', "rname" => 'География', "type" => 'varchar', "length" => '255'),
                 array("fname" => 'predmet', "rname" => 'Предмет закупки', "type" => 'varchar', 'list_table' => 'relate', "length" => '255'),
                 array("fname" => 'price_lot', "rname" => 'НМЦК', "type" => 'varchar'),
                 array("fname" => 'price_winner', "rname" => 'Цена победителя', "type" => 'varchar'),
                 array("fname" => 'inn_winner', "rname" => 'ИНН победителя', "type" => 'varchar'),
                 array("fname" => 'name_winner', "rname" => 'Наименование победителя', "type" => 'varchar', "length" => '255'),
                 array("fname" => 'comment', "rname" => 'Коментарий', "type" => 'text'),
         );


     $profile->add_fields($fields,$id);



    $main->addField('date',date('d.m.Y', $main->datetime));
    $main->addField('time',date('H:i', $main->datetime));

    $main->addField('about','addFCKeditor($GLOBALS["r"],"about");');
//    $main->addField('preview','addFCKeditor($GLOBALS["r"],"preview");');
    addCalend($main,1);
    addCalend($main,2);
    addCalend($main,3);
    return $_FILENAME;
 }

 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);
         //$sub->addField('date',date('d.m.y, H i', $r->result('datetime')));

      //   $rs = new Select($_this->db,'select c.name as company,h.*,s.name as street from house h,company c, street s where c.id=h.id_company and s.id=h.id_street and h.id='.$r->result('id_house'));
       //  if ($rs->next_row())
        $r->addFields($sub,$ar=array('number','sposob','srok_end_podach','customer','inn_customer','price_lot','status','date_income','responsible','date_create'));
        foreach ($sub as  $key => $value) {
             //if ($key=='status') $sub->status= get_sprav_val('status','name', $sub->status);

          //  echo get_sprav_val('status','name',$sub->id);
             if ($key=='status') $sub->status1=get_sprav_val('status','name',$sub->status);
             if ($key=='date_create') $sub->date_create=make_date($sub->date_begin,false,0);
             if ($key=='srok_end_podach') $sub->srok_end_podach=make_date($sub->srok_end_podach,false,0);
             if ($key=='date_realiz') $sub->date_realiz=make_date($sub->date_realiz,false,0);
         }




  }

 function &getParamMngr(&$_this) {
         $param = &BP_::getParamMngr($_this);
         //$param['order'] = 'datetime desc,id_house desc';
        // $param['where'] = ' ntype=1';
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

  function saveRecord(&$values,$id) {
         B_news_::saveRecord($this,$values,$id);
 }

}

?>
