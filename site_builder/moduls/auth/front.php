<?php

  include('config.php');

  $FILENAME = $front_html_path.'panel.html';
  $s_FILENAME = $front_html_path.'front.html';
   //
  //echo "!!!!!!!!";
  $main = new outTree($s_FILENAME);
  $patch=$HTTP_SERVER_VARS[HTTP_REFERER];

  //unset($_SESSION);
  $pr = 0;
  $cnt = 0;
  //выводим содержимое корзины

  foreach ($_COOKIE['cash_item'] as $id_good=>$cnt_good) {

        if (!empty($cnt_good) && !empty($id_good) && is_numeric($cnt_good)) {
           $r = new Select($db,"select * from catalog_items where id='".addslashes($id_good)."'");
           $r->next_row();
           $price=$r->result('price_rozn')*$cnt_good;
           if($price>0){
               $price_rub = get_sum_rubl($price,$r->result('valuta'));
               $pr+=$price_rub;
               $cnt+=$cnt_good;
           };
      };
  };
  if($cnt>0) {
        $main->addField('cnt',$cnt);
        $main->addField('price',$pr);
  } ;
 // echotree($main);
  //если нажали выход
  if  (isset($_GET['exit'])) {
      $_SESSION=array();
      setcookie("e_mail",'');
      setcookie("password",'');
     // unset($_COOKIE['e_mail']);
     // unset($_COOKIE['password']);
      header("location: $patch");
  };

  //если зашел юзер
  if (isset($_SESSION['user']))  {
          $main->addField('log','');
          $r = new Select($db,'select * from '.$GLOBALS['table_name'].' where id="'.$_SESSION['user'].'"');
          if ($r->next_row())
            $r->addFields($main,$ar=array('id','name','secname'));
          if ($r->result('name')!=='' and $r->result('secname')!=='')  $main->addField('zpt',',');
  } else
  //если ничего не произошло

  {
     //если было запомни меня
     if (isset($_COOKIE['e_mail'])) {
         $r = new Select($db,'select id from users where email="'.addslashes($_COOKIE['e_mail']).'" and pass="'.addslashes($_COOKIE['password']).'"');
         //если в куках все правильно то логинимся

         if ($r->next_row()) {
        // echo "d";
            $_SESSION=array();
            $_SESSION['user'] = $r->result('id');
             header("location: $patch");
         } else {
         //чистим неправильные куки
            setcookie("e_mail",'');
            setcookie("password",'');
         };
     };
     $main->addField('not_log','');

  }
     if (isset($main)) {

  };
//  echotree($main);
  //echo $GLOBALS['currentSection'];
  $site->addField($GLOBALS['currentSection'],&$main);
   unset($main);

 ?>