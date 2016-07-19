<?php

 include('config.php');

 unset($main);
 $FILENAME = $front_html_path.'front.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];

 //если авторизован и председатель этого тсж
 if ( $_SESSION ['user']>0) {
    $r1 = new Select($db,'select * from users where id="'.$user.'"');
    if ($r1->next_row())
    $user_company = $r1->result('id_company');
 };

// var_dump($_POST);

// выбран дом
 if ($cookie>0 || $user_company>0) {

            if ($cookie>0) {
              $r = new Select($db,'select * from house where id="'.$cookie.'"');
              if ($r->next_row())
                 $id_company = $r->result('id_company');
              //echo $id_company;
            } else
              $id_company = $user_company;

           // echo $id_company;
            $main = &addInCurrentSection($FILENAME);
            unset($main->content);
            $r->addFields($site,$ar=array('title','description','keywords'));
            $r->addFields($main,$ar=array('id','name','alt3'));

            if ($id_company >0) {
                        if ( $_SESSION ['user']>0) {
                                    //если выбран дом
                                    if ($cookie>0) $main->addField('mode','mode_show');
                                   //если юзер - председатель этого ТСЖ
                                    else if ($user_company == $id_company)
                                    {
                                          //нажали кнопку редактировать
                                          if ($_POST['edit_submit']>0)
                                                $main->addField('mode','mode_redact');
                                          //нажали кнопку сохранить
                                          else if ($_POST['save_submit']>0){
                                                //echo 'update company set about = "'.$_POST['about'].'" where id='.$id_company;
                                                $r1 = new Select($db,'update company set pre_about = "'.$_POST['pre_about'].'",pabl=0 where id='.$id_company);
                                                $main->addField('mode','mode_edit');
                                          }
                                          //ничего не нажимали
                                          else $main->addField('mode','mode_redact');
                                    }

                                    //юзер - не председатель этого ТСЖ
                                    else  $main->addField('mode','mode_show');

                        }
                           else $main->addField('mode','mode_show');
                        $r1 = new Select($db,'select * from company where id="'.$id_company.'"');
                        if ($r1->next_row()) {
                                     $r1->addFieldHTML($main,'pre_about');
                                     $r1->addFieldHTML($main,'about');
                        };
            }
            else
                        header('Location: /error404');
            $r->unset_();
    }
  else header('Location: /');
// echotree($main);

 ?>

