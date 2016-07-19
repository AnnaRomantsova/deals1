<?php


  include('config.php');
  //include_once('func.front.php');
  $p_FILENAME = $front_html_path.'panel.html';


   $main = new outTree($p_FILENAME);



   $sql['sql'].="select * from news where pabl=1  order by datetime desc,ntype limit 0,".$GLOBALS[$modulName.'_fcount'];

  // echo $sql['sql'];
   $ri = new Select($db,$sql['sql']);

   $d = '';
   $d1=1;

    $month=array("€нвар€","феврел€","марта","апрел€","ма€","июн€","июл€","августа","сент€бр€","окт€бр€","но€бр€","декабр€");

   if ($ri->next_row()) {
                  $sub = new outTree();

                  $d1 = date('d.m.Y', $ri->result('datetime'));
                  $d=$d1;
                  $sub1 = new outTree();
                  $ri->addFields($sub1,$ar=array('id','watch'));

                  if ($ri->result('ntype')==1 ) $sub1->addField('ntype','news');
                    else if ($ri->result('ntype')==2 ) $sub1->addField('ntype','law');

                  $ri->addFieldIMG($sub1,'image1');
                  $str=trim($ri->result('name'));
                  $str1=substr_replace($str, strtoupper($str[0]),0,1);
                  $sub1->addField('name',$str);
                  $ri->addFieldHTML($sub1,'preview');

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

                  if ($ri->result('ntype')==1 ) $sub1->addField('ntype','news');
                    else if ($ri->result('ntype')==2 ) $sub1->addField('ntype','law');

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
 ?>
