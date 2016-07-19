<?php

 include('config.php');

 unset($main);
 $s_FILENAME = $front_html_path.'front.html';
 $i_FILENAME = $front_html_path.'item.html';
 $new_FILENAME = $front_html_path.'new.html';


 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

// var_dump($_COOKIE);
// выбран дом
 if ($cookie>0) {

    $r = new Select($db,'select * from house where id="'.$cookie.'"');
    if ($r->next_row()) $id_company=$r->result('id_company');

    //если авторизован и председатель этого тсж
    if ( $_SESSION ['user']>0) {
      $r1 = new Select($db,'select * from users where id="'.$user.'"');
      if ($r1->next_row())
            $user_company = $r1->result('id_company');
    };

    //режим добалени€
    if (isset($_GET['new'])) {
         if ($_POST['new_submit']>0){
            $r1 = new Select($db,'insert into tsjnews (name,datetime,about,preview,id_company,watch,pabl) values ("'.$_POST['name'].'",'.time().',"'.$_POST['about'].'","'.$_POST['preview'].'",'.$user_company.',0,1)' );
            header('Location: /tsjnews');
         } else
         {
           $main = &addInCurrentSection($new_FILENAME);
           unset($main->content);
         };
    }
// запись
    else if (isset($_GET['i'])) {

            $r = new Select($db,'select * from '.$GLOBALS['table_name'].' where id="'.addslashes($_GET['i']).'" and pabl="1" ');
            if ($r->next_row()) {
                    $main = &addInCurrentSection($i_FILENAME);
                    unset($main->content);

                      if ( $_SESSION ['user']>0) {
                        //если юзер - председатель этого “—∆
                        if ($user_company == $id_company)
                        {
                              //нажали кнопку редактировать
                              if ($_POST['edit_submit']>0){
                                    $main->addField('mode','mode_redact');
                              }
                              //нажали кнопку удалить
                              if ($_POST['del_submit']>0){
                                     $r1 = new Select($db,'delete from tsjnews where id='.$_GET['i']);
                                      header('Location: /tsjnews');
                              }
                              //нажали кнопку сохранить
                              else if ($_POST['save_submit']>0){
                                    //echo 'update company set about = "'.$_POST['about'].'" where id='.$id_company;
                                    $r1 = new Select($db,'update tsjnews set about = "'.$_POST['about'].'", preview = "'.$_POST['preview'].'", name = "'.$_POST['name'].'" where id='.$_GET['i']);
                                    $main->addField('mode','mode_edit');
                              }

                              //ничего не нажимали
                              else {
                                   $main->addField('mode','mode_edit');
                              };
                        } else
                        //если есть юзер но он не председатель
                            $main->addField('mode','mode_show');
                      //если нет юзера
                     } else
                         $main->addField('mode','mode_show');

                    $r = new Select($db,'select * from '.$GLOBALS['table_name'].' where id="'.addslashes($_GET['i']).'" and pabl="1" ');
                    if ($r->next_row()) {

                          $r->addFields($site,$ar=array('title','description','keywords'));
                          $r->addFields($main,$ar=array('id','name'));
                          $r->addFieldHTML($main,'about');
                          $r->addFieldHTML($main,'preview');
                          $main->addField('date',date('d.m.Y',$r->result('datetime')));
                     };

                    $r = new Select($db,'update '.$GLOBALS['table_name'].' set watch=watch+1 where id = '.$_GET['i']);
                    addLast($GLOBALS['site']->path,$main->name);

            }
            else
                        header('Location: /error404');
            $r->unset_();
    }

// все записи
    else {
           $main = new outTree($s_FILENAME);

           $sql="select * from ".$GLOBALS['table_name']." where pabl=1 and id_company = $id_company order by datetime desc limit 0,".$GLOBALS[$modulName.'_fcount'];

         //  echo $sql;
           $ri = new Select($db,$sql);

           $d = '';
           $d1=1;

           $month=array("€нвар€","феврел€","марта","апрел€","ма€","июн€","июл€","августа","сент€бр€","окт€бр€","но€бр€","декабр€");

           if ($ri->next_row()) {
                          $sub = new outTree();

                          $d1 = date('d.m.Y', $ri->result('datetime'));
                          $d=$d1;
                          $sub1 = new outTree();
                          $ri->addFields($sub1,$ar=array('id','watch'));


                          $ri->addFieldIMG($sub1,'image1');
                          $str=trim($ri->result('name'));
                          $str1=substr_replace($str, strtoupper($str[0]),0,1);
                          $sub1->addField('name',$str);
                          $ri->addFieldHTML($sub1,'preview');
                          $sub1->addField('ntype','tsjnews');

                          $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                          if (date('Y',$ri->result('datetime')) !==  date('Y',time())) $strdate.=" ".date('Y',$ri->result('datetime'));
                          $sub->addField('datetime',$strdate);
                          $sub->addField('sub1',&$sub1);
                          unset($sub1);
          };
          while ($ri->next_row()) {
                          unset($sub1);
                          $sub1 = new outTree();
                          $ri->addFields($sub1,$ar=array('id','watch'));

                          $str=trim($ri->result('name'));
                          $str1=substr_replace($str, strtoupper($str[0]),0,1);
                          $sub1->addField('name',$str1);
                          $ri->addFieldIMG($sub1,'image1');
                          $ri->addFieldHTML($sub1,'preview');
                          $sub1->addField('ntype','tsjnews');

                          $d1 = date('d.m.Y', $ri->result('datetime'));
                          if($d!==$d1) {
                                  $d=$d1;
                                  $main->addField('sub',&$sub);
                                  unset($sub);
                                  $sub = new outTree();
                          };
                          $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                          if (date('Y',$ri->result('datetime')) !==  date('Y',time())) $strdate.=" ".date('Y',$ri->result('datetime'));
                         // $sub->addField('datetime',$strdate);
                          if(!isset($sub->datetime)) $sub->addField('datetime',$strdate);
                          $sub->addField('sub1',&$sub1);


          };
          //echotree($main);

           //если авторизован и председатель этого тсж
          if ( $_SESSION ['user']>0) {

              //если юзер - председатель этого “—∆
              if ($user_company == $id_company)  $main->addField('new','');


          };

          if(isset($sub))  {
            $main->addField('sub',&$sub);
            unset($sub);
          }
          $ri->unset_();


          if (isset($main)) {
                  //  echoTree($main);
                    $site->addField($GLOBALS['currentSection'],&$main);
                    unset($main);
          }

    };

         unset($main);
 };
 ?>

