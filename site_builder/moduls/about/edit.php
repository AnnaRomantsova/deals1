<?php
 //показывает документ на чтение или редактирование
 include('config.php');

 unset($main);
 $FILENAME = $front_html_path.'front.html';

 if ($_COOKIE['id_house']>0) $cookie=$_COOKIE['id_house'];
// запись
 if ($cookie>0) {
            $r = new Select($db,'select * from house where id="'.$cookie.'"');
            if ($r->next_row()) {

                    $main = &addInCurrentSection($FILENAME);
                    unset($main->content);
                    $r->addFields($site,$ar=array('title','description','keywords'));
                    $r->addFields($main,$ar=array('id','name','alt3'));

                    $r1 = new Select($db,'select * from company where id="'.$r->result('id_company').'"');
                    if ($r1->next_row()) $r1->addFieldHTML($main,'about');

                    //если авторизован и председатель этого тсж
                    if ( $_SESSION ['user']>0) {

                        $r1 = new Select($db,'select * from users where id="'.$_SESSION ['user'].'"');
                        if ($r1->next_row()) $id_company = $r1->result('id_company');

                        if ($id_company == $r->result('id_company')) $main->addField('edit','1');
                    };
            }
            else
                        header('Location: /error404');
            $r->unset_();
    }
// echotree($main);

 ?>