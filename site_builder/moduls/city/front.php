<?php

 include('config.php');

 unset($main);
 $s_FILENAME = $front_html_path.'front.html';
 $i_FILENAME = $front_html_path.'item.html';

// запись
    if (isset($_GET['i'])) {
            $r = new Select($db,'select * from '.$GLOBALS['table_name'].' where id="'.addslashes($_GET['i']).'" and pabl="1" and about!=""');
            if ($r->next_row()) {
                        $main = &addInCurrentSection($i_FILENAME);
                    unset($main->content);
                    $r->addFields($site,$ar=array('title','description','keywords'));
                    $r->addFields($main,$ar=array('id','name','alt3'));
                    $r->addFieldHTML($main,'about');
                        $main->addField('date',date('d.m.Y',$r->result('datetime')));
                        $r->addFieldIMG($main,'image3');

                        $r_next = new Select($db,'select id from '.$GLOBALS['table_name'].'  where ((datetime > '.$r->result('datetime').') OR (datetime = '.$r->result('datetime').') AND  (id > '.$r->result('id').' )) and about!="" order by datetime,id limit 1');
                        if ($r_next->next_row())
                                $main->addField('next',$r_next->result('id'));
                        $r_next->unset_();

                        $r_prev = new Select($db,'select id from '.$GLOBALS['table_name'].' where ((datetime < '.$r->result('datetime').') OR (datetime = '.$r->result('datetime').') AND  (id < '.$r->result('id').' ))  and about!="" order by datetime desc,id desc limit 1');
                        if ($r_prev->next_row())
                                $main->addField('prev',$r_prev->result('id'));
                        $r_prev->unset_();

                    addLast($GLOBALS['site']->path,$main->name);
            }
            else
                        header('Location: /error404');
            $r->unset_();
    }

// все записи
    else {
                   include($inc_path.'/service/class.pager.php');
                $main = &addInCurrentSection($s_FILENAME);
                $ri = new Select($GLOBALS['db'],'SELECT * FROM '.$GLOBALS['table_name'].' WHERE pabl="1" ORDER BY datetime desc,id desc');
                $pg = &PagerQuery::new_($ri,$GLOBALS[$modulName.'_fcount'],$_GET['cp'],$ar=array('href'=>'/'.$GLOBALS['page'],'jumpValue'=>$_GET['ib']));

                if ($pg) {
                        $pg->addPAGER($main);
                        if (isset($main->pager)) {
                                if (isset($_GET['show']) && 'all' == $_GET['show']) {
                                        $ri->result_row = -1;
                                        unset($main->pager,$ri->end);
                                        $main->addField('show','all');
                                }

                                else
                                        $main->addField('show','pager');

                        }

                          while ($ri->next_row()) {
                                unset($sub);
                                $sub = new outTree();
                                $ri->addFields($sub,$ar=array('id','name','alt2'));
                                $sub->addField('date',date('d.m.Y',$ri->result('datetime')));
                            $ri->addFieldsHTML($sub,$ar=array('preview','about'));
                            if (trim($sub->about))
                                    $sub->addField('details','');
                            $ri->addFieldIMG($sub,'image2');
                                $main->addField('sub',&$sub);
                        }
                        $ri->unset_();
            }
    }

         unset($main);

 ?>

