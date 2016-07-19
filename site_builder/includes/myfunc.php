<?php

 /**
  * служебные функции - могут зависеть от сайта
  * @package FRONT
  * @version 3.03.argentum - 23.10.2007 10:30
  */

//include($inc_path.'/service/class.output.php');
//include($inc_path.'/service/func.service.php');

 function get_sprav_val($table,$field,$id){
       global $db;
       if (!$id>0) return '';
       $query = "select *  from $table where id=$id";
       $r_pr = new Select($db,$query);
       $r_pr->next_row();
       return $r_pr->result($field);
 }

 function addSpravYear(&$main,$selected_id,$sub_name) {
         $year=date('Y',time());

         for ($i=$year-1; $i<=$year;$i++ ) {
                   unset($sub);
                   $sub = new outTree();
                   $sub->addfield('year',$i);
                   // echo $year; //die;
                   if ($i==$selected_id) $sub->addfield('selected','selected');
                   $main->addField($sub_name,$sub);
         };
  };
  function addSprav(&$main,$table_name,$selected_id,$sub_name) {
         global $db;
         $r1 = new Select ( $db, "select * from $table_name order by name" );
         while ($r1->next_row() > 0) {
                   unset($sub);
                   $sub = new outTree();
                   $r1->addFields($sub,$ar=array('id','name'));
                   if ($r1->result('id')==$selected_id) $sub->addfield('selected','selected');
                   $main->addField($sub_name,$sub);
         };
  };


function addSprav_wide(&$main,$table_name,$selected_id,$sub_name,$where='',$sort='name') {
    global $db;
    $r1 = new Select ( $db, "select * from $table_name $where order by $sort" );
    while ($r1->next_row() > 0) {
        unset($sub);
        $sub = new outTree();
        $r1->addFields($sub,$ar=array('id','name'));
        if ($r1->result('id')==$selected_id) $sub->addfield('selected','selected');
        $main->addField($sub_name,$sub);
    };
};


  function addSprav_sql(&$main,$table,$field,$selected,$sub_name,$sort='') {
         global $db;
         if (strlen($selected)>0)  $wh = "$field = '$selected'";
         if ($sort=='') $ord= $field; else  $ord=$sort;
         $sql = "select distinct $field from $table order by $ord";
         $r1 = new Select ( $db, $sql );
         while ($r1->next_row() > 0) {
                   unset($sub);
                   $sub = new outTree();
                   $r1->addFields($sub,$ar=array('id'));
                   $sub->addField('name',$r1->result($field));
                   if ($r1->result($field)==$selected) $sub->addfield('selected','selected');
                   $main->addField($sub_name,$sub);
                //   echotree($sub);
         };
  };
  function addSprav_list(&$main,$sql,$field,$selected,$sub_name,$sort='') {
         global $db;
         //if (strlen($selected)>0)  $wh = "$field = '$selected'";
        // if ($sort=='') $ord= $field; else  $ord=$sort;
       //

         // var_dump($sel);
         $r1 = new Select ( $db, $sql );
         while ($r1->next_row() > 0) {
                   unset($sub);
                   $sub = new outTree();
                   $r1->addFields($sub,$ar=array('id'));
                   $sub->addField('name',$r1->result($field));
                   if ($selected !== NULL)
                     if (in_array($r1->result('id'),$selected))
                        $sub->addfield('checked','checked');
                 // echo $r1->result('id');
                   $main->addField($sub_name,$sub);
                //   echotree($sub);
         };
  };
  //много выбраных
  function addSpravM(&$main,$table_name,$selected_id=array(),$sub_name) {
         global $db;
         //var_dump($selected_id);
        // if ( in_array(1,$selected_id)) echo"!!";
         $r1 = new Select ( $db, "select * from $table_name order by name" );
         while ($r1->next_row() > 0) {
                   unset($sub);
                   $sub = new outTree();
                   $r1->addFields($sub,$ar=array('id','name'));
                   if ( in_array($r1->result('id'),$selected_id)) {
                   	    $sub->addfield('selected','selected');
                   	    $sub->addfield('checked','checked');
           //        	    echo "я";
                   };
                   $main->addField($sub_name,$sub);
         };
  };

  //добавляем список характеристик юзера из таблицы table1
  function addSpravWatch(&$main,$sub_name,$table1,$table2,$user_id) {
  	      global $db;
  	   $r1 = new Select ( $db, "select t1.name from $table1 t1,$table2 t2 where t1.id=t2.id_type and t2.id_user=$user_id order by name" );
  	   while ($r1->next_row() > 0) {
           unset($sub);
           $sub = new outTree();
           $r1->addFields($sub,$ar=array('id','name'));
           $main->addField($sub_name,$sub);
       };
  };

  function oberni_array($arr){
  	if (count($arr)>0) {
	    $w='(';
	    foreach ( $arr as $f)
	      $w.=$f.',';
	 	$w=substr($w,0,strlen($w)-1).')';
	    return $w;
	};
   };

 //из глобального массива $_POST или $_GET zakon1,zakon6,zakon7 вернет массив (1,6,7)
function get_id_from_post($name,$post=true){
   if ($post) $arr  = $_POST; else $arr=$_GET;
   $id = array();
   foreach ($arr as $key =>$value) {
      if (!(strpos($key,$name) === false )){
         $id1 = substr($key,strlen($name));
         $id[] =$id1;
      };
   };
   return $id;
};

  //сохраняем разделы юзера
  function SaveRazdel($table_name,$post,$user) {
      global $db;
      $r1 = new Select($db,"delete from $table_name where id_user=$user");
      foreach ( $_POST as $key => $value) {
   //  echo $key;
           if (strpos($key,$post)>0) {
              $id = substr($key,strlen($post)+1);
              $r1 = new Select($db,"insert into $table_name(id_user,id_type) values($user,$id)");
           };
      };
  };

  function GetRazdel($table_name,$user) {
      global $db;
      $r1 = new Select($db,"select id_type from $table_name where id_user=$user");
      $ar=array();
      while ($r1->next_row()) {
           $ar[]=$r1->result('id_type');
      };
      return $ar;
  };
  //список разделов заказов
  function addZakazSprav(&$main,$zakaz_id,$sub_name) {
         global $db;

         $r = new Select ( $db, "select * from types_sections t where parent=1 order by sort" );
         while ($r->next_row()) {
           $i=1; $j=1;$first=true;
           $r1 = new Select ( $db, "select * from types t where parent=".$r->result('id')." order by sort" );
           while ($r1->next_row() > 0) {
                   unset($sub);
                   $sub = new outTree();
                   $r2 = new Select ( $db, "select * from zakaz_types zt where zt.id_zakaz=$zakaz_id and id_type=".$r1->result('id'));
                   $r1->addFields($sub,$ar=array('id','name'));
                   if ($r2->next_row()) { $sub->addfield('selected','selected'); $sub->addfield('checked','checked'); };
                   if ($j==3)  {$sub->addfield('tr','</tr><tr>'); $j=0; };
                   if ($first) $sub->addfield('razdel',$r->result('name'));
                    $j++; $first=false;
                   $main->addField($sub_name,$sub);
           };
         };
  };

    //список разделов работ
  function addPhotoSprav(&$main,$id,$sub_name) {
         global $db;

         $r = new Select ( $db, "select * from types_sections t where parent=1 order by sort" );
         while ($r->next_row()) {
           $i=1; $j=1;$first=true;
           $r1 = new Select ( $db, "select * from types t where parent=".$r->result('id')." order by sort" );
           while ($r1->next_row() > 0) {
                   unset($sub);
                   $sub = new outTree();
                   $r2 = new Select ( $db, "select * from galery  where id=$id and id_type=".$r1->result('id'));
                   $r1->addFields($sub,$ar=array('id','name'));
                   if ($r2->next_row()) { $sub->addfield('selected','selected'); $sub->addfield('checked','checked'); };
                   if ($j==3)  {$sub->addfield('tr','</tr><tr>'); $j=0; };
                   if ($first) $sub->addfield('razdel',$r->result('name'));
                    $j++; $first=false;
                   $main->addField($sub_name,$sub);
           };
         };
  };


 //список разделов мастера
  function addUserSprav(&$main,$user_id,$sub_name) {
         global $db;

         $r = new Select ( $db, "select * from types_sections t where parent=1 " );
         $cnt = $r->num_rows();
         $r = new Select ( $db, "select * from types t " );
         $cnt += $r->num_rows();
         $num = ceil($cnt/3);

        // echo $num;
         $r = new Select ( $db, "select * from types_sections t where parent=1 order by sort" );
         $i=1;
         while ($r->next_row()) {
              $j=0;$first=true;
              $r1 = new Select ( $db, "select * from types t where parent=".$r->result('id')." order by sort" );


              while ($r1->next_row() ) {
                   unset($sub);
                   $sub = new outTree();
                   $r2 = new Select ( $db, "select * from user_types zt where zt.id_user=$user_id and id_type=".$r1->result('id'));

                   $r1->addFields($sub,$ar=array('id','name'));

                   if ($r2->next_row()) $sub->addfield('checked','checked');

                   if ($first) $sub->addfield('razdel',$r->result('name'));

                   if ($i==$num)  {$sub->addfield('ul','</ul><ul>'); $i=0; };
                   //if ($i==0)  {$sub->addfield('ul','</ul><ul>');  };
                   if ($j==3)  {$sub->addfield('tr','</tr><tr>'); $j=0; };
                  // echo $i;
                   $i++; $j++; $first=false;
                   $main->addField($sub_name,$sub);
              };
          };
          //echotree($main);
  };

   //список разделов рекламы
  function addRekSprav(&$main,$rek_id,$sub_name) {
         global $db;
         $r = new Select ( $db, "select * from types_sections t where parent=1 order by sort" );
         $i=0;
         while ($r->next_row()) {
              $j=0;$first=true;
              $r1 = new Select ( $db, "select * from types t where parent=".$r->result('id')." order by sort" );
              while ($r1->next_row() ) {
                   unset($sub);
                   $sub = new outTree();
                   $r2 = new Select ( $db, "select * from reklama_types where id_rek=$rek_id and id_type=".$r1->result('id'));
                   if ($first) $sub->addfield('razdel',$r->result('name'));
                   $r1->addFields($sub,$ar=array('id','name'));

                   if ($r2->next_row()) $sub->addfield('checked','checked');

                   if ($i==8)  {$sub->addfield('ul','</ul><ul>'); $i=0; };
                   if ($j==3)  {$sub->addfield('tr','</tr><tr>'); $j=0; };
                  // echo $i;
                   $i++; $j++;$first=false;
                   $main->addField($sub_name,$sub);
              };
         };
  };


   //список разделов мастера
  function addLentaSprav(&$main,$rek_id,$sub_name) {
         global $db;
         $r = new Select ( $db, "select * from types_sections t where parent=1 order by sort" );
         $i=0;
         while ($r->next_row()) {

              $j=0;$first=true;
              $r1 = new Select ( $db, "select * from types t where parent=".$r->result('id')." order by sort" );
              while ($r1->next_row() ) {
                   unset($sub);
                   $sub = new outTree();
                   $r2 = new Select ( $db, "select * from lenta where id=$rek_id and id_type=".$r1->result('id'));

                   $r1->addFields($sub,$ar=array('id','name'));

                   if ($r2->next_row()) {$sub->addfield('checked','checked');  $sub->addfield('selected','selected');};
                   if ($first) $sub->addfield('razdel',$r->result('name'));
                   if ($i==8)  {$sub->addfield('ul','</ul><ul>'); $i=0; };
                   if ($j==3)  {$sub->addfield('tr','</tr><tr>'); $j=0; };
                  // echo $i;
                   $i++; $j++;$first=false;
                   $main->addField($sub_name,$sub);
               };
         };
  };

  //дата по русски
  function make_date($date,$time=false,$mon_word=true){

        $month=array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
        if ($mon_word==true) $strdate = date("j",$date)." ".$month[((int) date("m",$date)-1)]." ".date('Y',$date);
          else $strdate = date("d",$date).".".date("m",$date).".".date('Y',$date);
        if ($time) $strdate .= date(" H:i",$date);
        return $strdate;
  };


  function make_date_in_days($date,$word=true){
        $days = floor( (time()- $date) /86400);
       // echo $days;
        if ($days <5 && $word) {
           if ($days==0) return 'сегодня';
            else if ($days==1) return 'вчера';
              else if ($days==2) return 'позавчера';
               else return "$days дня назад";
        }
        else return make_date($date);
  };

  //разделы заказа
  function add_date_visit(&$main,$user_id) {
         global $db;
         $r1 = new Select($db,"select date_visit from users where id=$user_id");

         if ($r1->next_row())
            $main->addField('date_visit',make_date($r1->result('date_visit'),true));

  };

  //разделы заказа
  function add_zakaz_types(&$main,$zakaz_id) {
         global $db;
         $r1 = new Select($db,"select t.id,t.name from zakaz_types zt,types t where zt.id_type = t.id and id_zakaz=$zakaz_id");
         $i=1;
         while ($r1->next_row()) {
            unset($sub);
            $sub = new outTree();
            $r1->addFields($sub,$ar=array('id','name'));
            if ($i < $r1->num_rows()) $sub->addField('zpt',',');
            $main->addField('zakaz_types',$sub);
            $i++;
         };
  };

  //разделы юзера
  function add_user_types(&$main,$user_id) {
         global $db;
         $r1 = new Select($db,"select t.id,t.name,t.parent from user_types zt,types t where zt.id_type = t.id and id_user=$user_id");
         $i=1;
         while ($r1->next_row()) {
            unset($sub);
            $sub = new outTree();
            $r1->addFields($sub,$ar=array('id'));
            $r2 = new Select($db,"select * from types_sections where id = ".$r1->result('parent'));
            $r2->next_row();
            $sub->addField('name',$r2->result('name').'/'.$r1->result('name'));
            if ($i < $r1->num_rows()) $sub->addField('zpt',',');
            $main->addField('user_types',$sub);
            $i++;
         };
  };

  //разделы ленты
  function add_lenta_types(&$main,$lenta_id) {
         global $db;
         $r1 = new Select($db,"select t.id,t.name from lenta l,types t where l.id_type = t.id and l.id=$lenta_id");
         $i=1;
         while ($r1->next_row()) {
            unset($sub);
            $sub = new outTree();
            $r1->addFields($sub,$ar=array('id','name'));
            if ($i < $r1->num_rows()) $sub->addField('zpt',',');
            $main->addField('lenta_types',$sub);
            $i++;
         };
  };

  //мне нравится
  function add_star(&$main,$id_like,$id_type) {
       global $db;
       if (!($_SESSION['user'])>0) {
          return null;
       };
       $flag=false;
       //заказы
       if ($id_type==1) {
           $r = new Select($db,"select * from zakaz where id=$id_like");
           $r->next_row();
           if ($r->result('id_user')==$_SESSION['user']) { $main->addField('edit',''); $flag=true; };
       }
       //мастера
       if ($id_type==2) {
           if ($id_like==$_SESSION['user']) { $main->addField('edit',''); $flag=true; };
       }
       if ($flag==false ) {
               $main->addField('star','');
               //echo "select * from likes where id_user=$_SESSION[user] and id_like = $id_like";
               $r = new Select($db,"select * from likes where id_user=$_SESSION[user] and id_like = $id_like and id_type = $id_type");
               if ($r->next_row()) { $main->addField('active','active'); $main->addField('like','like'); }
      };
  };

  function get_city() {
       global $db;
       if (!($_COOKIE['id_city'] >0)) {
           $r = new Select($db,'select *  from city where first = 1');
           $r->next_row();
           return $r->result('id');
       } else return $_COOKIE['id_city'];
  };

  //список отзывов
  function add_review(&$main,$id_what,$type,$id_user=0,$sub_name) {
  global $db;
         $r1 = new Select($db,"select * from review where id_what = $id_what and type=$type and pabl=1");
         while ($r1->next_row()) {
            unset($sub);
            $sub = new outTree();
            $r1->addFields($sub,$ar=array('inn','uch'));
            $sub->addField('about',htmlspecialchars_decode($r1->result('about')));
            $sub->addField('date',make_date($r1->result('date'),true));
            $r2 = new Select($db,"select * from users where id = ".$r1->result('id_user'));

            if ($r1->result('id_user') == $id_user) $sub->addField('my_review','');
            $r2->next_row();
            $r2->addFields($sub,$ar=array('id','fio'));
            if ($r2->result('is_master') == 1) $sub->addField('is_master','');
            $sub->addField('idrev',$r1->result('id'));
            $r2->addFieldIMG($sub,'image1');
            $main->addField($sub_name,$sub);
            $i++;
         };
         if ($r1->num_rows == 0) {
             if ($type==1) $main->addField('no_'.$sub_name,'Жалоб пока нет.');
             if ($type==2) $main->addField('no_'.$sub_name,'Жалоб пока нет.');
             if ($type==3) $main->addField('no_'.$sub_name,'Жалоб пока нет.');
             if ($type==4) $main->addField('no_'.$sub_name,'Благодарностей пока нет.');
         };
  };

  //кол-во отзывов
  function cnt_review($id_what,$type) {
         global $db;
         $r1 = new Select($db,"select count(*) as cnt from review where id_what = $id_what and type=$type and pabl=1");
         $r1->next_row();
         return $r1->result('cnt');
  };

  //список отзывов
  function add_user_info(&$main,$id_user,$sub_name) {
        global $db;

        unset($user_sub);
        $user_sub = new outTree();
        $r1 = new Select($db,"select * from users where id=$id_user");
        $r1->next_row();
        $r1->addFields($user_sub,$ar=array('id','fio'));

        if ($r1->result('is_master') == 1) $user_sub->addField('is_master','3');
        //$r1->addFieldsIMG($user_sub,$ar=array('image1'));
        add_user_avatar($user_sub,$r1);

        $main->addField($sub_name,$user_sub); //echotree($main);
  };

    //автар мастера
  function add_user_avatar(&$main,$r) {
        global $db;
        $r->addFieldsIMG($main,$ar=array('image1'));

        //echo $main->image1;
        if (isset($main->not_image1)){
                $tmp = new outTree();
                $tmp->addField('src', '/i/photo-none1.jpg' );
                $main->addField( 'image1',$tmp);

        } else $main->addField( 'is_avatar','');
  };

     //автар мастера
  function is_master($user) {
        global $db;
        $r1 = new Select($db,"select * from users where id=$user");
        $r1->next_row();
        return $r1->result('is_master');
  };

  //права оставлять коментарии,сообщения на конкретную запись
  function check_review_rights(&$main,$user_id,$type,$id_what=0){
       global $db;
       $rev=true;
       //echo $user_id;
       if (!$user_id>0) {
           $rev = false;
           if ($type==2 || $type==3)
              $err_message='Комментарии могут оставлять только зарегистрированные пользователи.';
           else
              $err_message='Предлагать свои услуги могут только зарегистрированные пользователи.';
       //заказы
       } else if ($type==1) {
            //проверка на свой заказ
            $r=new Select($db,"select * from zakaz where id=$id_what");
            $r->next_row();
            //свой заказ
            if ($r->result('id_user') == $user_id)
                $rev = false;
            //чужой
            else {
            	if (is_master($user_id) ==false) {
            	  $rev = false;
            	  $err_message='Если Вы хотите предложить свои услуги, то необходимо сначала в личном кабинете добавить и заполнить профиль мастера. После того как профиль мастера будет одобрен модератором Вы сможете откликаться на заказы.';
                } else {

                  $err_message='Если Вы хотите предложить свои услуги, то вам необходимо откликнуться на этот заказ. Заказчик выберет исполнителя самостоятельно и свяжется с ним по указанным в профиле контактам.';
                };
            };
            if ($id_what >0){
              $r=new Select($db,"select count(*) as cnt from review where id_user=$user_id and type=$type and id_what=$id_what");
              $r->next_row();
              if ($r->result('cnt') >0 ) {$rev = false; $err_message='Вы уже жаловались на эту заявку.';};
            };

            //старый заказ
            $r1 = new Select($db,"select * from zakaz where id=$id_what");
            $r1->next_row();
            if ($r1->result('date_before') < time()) {$rev = false; $err_message='На этот заказ нельзя откликнуться т.к. он не актуален.';};


       //мастера
       } else if ($type==2)
            if ($user_id == $id_what) {$rev = false; };

       $main->addField( 'err_mess',$err_message);
       if ($rev==true) $main->addField( 'rev_true','');
          else {$main->addField( 'rev_false',''); };

  };
  //права оставлять коментарии,сообщения на конкретную запись
  function check_message_rights(&$main,$user_id,$id_what){
       $rev =true;
       if ($user_id == $id_what) $rev = false;
        if ($rev==true) $main->addField( 'mess_true','');
          else {$main->addField( 'mess_false',''); };
  };

  function addreklama (&$main,$id_type,$modul,$id_city) {
       global $db;
       if ($id_city>0) {
               $tables = ',reklama_city rc';
               $where = ' and rc.id_reklama = r.id and rc.id_city='.$id_city;
       };
       if ($id_type!=='') {
               $tables .= ',reklama_types rt';
               $where .= $id_type;
       };
       //echo "select *  from rek r $tables where pabl=1 and $modul=1 $where ORDER BY RAND() LIMIT 1";
       $r = new Select($db,"select *  from rek r $tables where pabl=1 and $modul=1 $where ORDER BY RAND() LIMIT 1");
       if ($r->next_row())  {
               unset($sub);
               $sub = new outTree();
               $r->addFields($sub,$ar=array('name1','name2','name3','text1','text2','text3','tel1','tel2','tel3','link1','link2','link3'));
               $main->addField( 'reklama',$sub);
       };
  };

  function del_zakaz ($id) {
        global $db;
        $r1 = new Select($db,"delete from likes where id_like = $id and id_type=1 ");
        $r1 = new Select($db,"delete from zakaz_types where id_zakaz = $id");
        $r1 = new Select($db,"delete from zakaz where id = $id");

 };


 function make_date_zak($date){
    $year = substr($date,0,4);
    $mon = substr($date,5,2);
    $day = substr($date,8,2);
    $hour = substr($date,11,2);
    $min  = substr($date,14,2);
    return "$day.$mon.$year $hour:$min";
 }

 //date dd.mm.yyyy выход - формат закупок
 function make_date_to_zak($date,$end_of_day){
    $year = substr($date,6,4);
    $mon = substr($date,3,2);
    $day = substr($date,0,2);
    if ($end_of_day) return "$year-$mon-$day"."T23:59:59Z";
     else return "$year-$mon-$day"."T00:00:00Z";

 }
 
 function get_first_step($relate_id){
 	global $db;
 	$r = new Select($db,"select *  from step  where id_relate=$relate_id order by sort");
 	if ($r->next_row())  {
 		return ($r->result('id'));
 	} else return null;
 }
 
 function get_step_by_parent($table,$parent){
 	global $db;
 	$r = new Select($db,"select *  from $table t,step s where t.step_id=s.id and parent=$parent order by s.sort desc");
 	if ($r->next_row())  {
 		$a['name'] = $r->result('name');
 		$a['id'] = $r->result('step_id');
 		return ($a);
 	} else return null;
 }
?>
