<?php

 include('config.php');
 unset($main);
 $main_FILENAME = $front_html_path.'panel.html';

 $r = new Select($db,'select * from '.$table_name.' where pabl="1" order by datetime desc,id desc limit '.($pbcount+$pscount));
 if ($r->num_rows) {
         $main = &addInCurrentSection($main_FILENAME,false);
         while ( $r->next_row() ) {
                 unset($sub);
            $sub = new outTree();
                $sub->addField('date',date('d.m.Y',$r->result('datetime')));
            $r->addFields($sub, $ar=array('id','name','alt1','alt2') );
                $r->addFieldsHTML($sub,$ar=array('about','preview'));
                if (trim($sub->about))
                $sub->addField('details','');

            if ($r->result_row < $pbcount) {
                    $r->addFieldIMG($sub,'image2');
                    $main->addField( 'sub_big', &$sub );
            }
            else {
                    $r->addFieldIMG($sub,'image1');
                    if ($pbcount == $r->result_row)
                            $sub->addField('first','');
                    $main->addField( 'sub_small', &$sub );
            }
         }
 }
 $r->unset_();
 //echoTree($main);


?>
