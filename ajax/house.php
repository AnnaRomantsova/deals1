<?php

// Запрет на кэширование
header("Expires: Mon, 23 May 1995 02:00:00 GTM");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GTM");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//
//В этом файле хранятся логин и пароль к БД
require_once("../setup.php");
include_once($inc_path.'/db_conect.php');
include_once($inc_path.'/func.front.php');
$shablon = 'front/house/house.html';

$main = new outTree();


if ( $_POST['id_city'] >0 )
   {
       unset($sub);
       $sub = new outTree();
       $sub->addField('id','0');
       $sub->addField('name','Выберите улицу');
       $main->addField('sub_street',$sub );
       $r = new Select($db,'select * from street where id_city= '.$_POST['id_city'].' order by name');
       while ( $r->next_row() ) {
           unset($sub);
           $sub = new outTree();
           $r->addFields($sub, $ar=array('id','name') );
           $main->addField('sub_street',$sub );
      };
     //echo "65645";
      out::_echo($main,$shablon);
  }
if ( $_POST['id_street'] >0 )
   {
       unset($sub);
       $sub = new outTree();
       $sub->addField('id','0');
       $sub->addField('name','Выберите дом');
       $main->addField('sub_house',$sub );
       $r = new Select($db,'select * from house where id_street= '.$_POST['id_street'].' order by number,fract');
       while ( $r->next_row() ) {
           unset($sub);
           $sub = new outTree();
           if ($r->result('fract')!=='') {
              if ($r->result('fract')>0) $name = $r->result('number').'/'.$r->result('fract');
              else $name = $r->result('number').' '.$r->result('fract');
           } else  $name = $r->result('number');
           $sub->addField('name',$name);
           $r->addFields($sub, $ar=array('id'));
           $main->addField('sub_house',$sub );
      };
     //echo "65645";
      out::_echo($main,$shablon);
  }




?>