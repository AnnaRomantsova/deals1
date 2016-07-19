<?php

 include('config.php');
 unset($main);
 $main_FILENAME = $front_html_path.'panel.html';

 //var_dump($_POST );
 $main = &addInCurrentSection($main_FILENAME,false);



 $parent=0;
 $r2 = new Select($db,'select parent from site_tree where page="'.$site->pageid.'"');
 $r2->next_row();
 $parent = $r2->result('parent');
        if ( $_SESSION ['user']>0) {


           $r1 = new Select($db,'select name from users u where u.id="'.$_SESSION ['user'].'"');
           //echo 'select name from users u where u.id="'.$user.'"';
           
          // echotree($site->pageid);
           $r1->next_row();
          // echo $r1->result('name');
           $main->addField('logo',$r1->result('name'));
        }  

 
 $month=array("€нвар€","феврел€","марта","апрел€","ма€","июн€","июл€","августа","сент€бр€","окт€бр€","но€бр€","декабр€");
 $main->addField('date',date("d",time())." ".$month[((int) date("m",time())-1)]." ".date("Y",time()));
//echoTree($main);


?>
