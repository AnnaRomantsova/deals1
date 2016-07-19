<?php

  include('config.php');

  $FILENAME = $front_html_path.'panel.html';

    $main = new outTree($FILENAME);

  if (isset($_SESSION['user'])) {

      $main->addField('log','');
      $r = new Select($db,'select * from '.$GLOBALS['table_name'].' where id="'.$_SESSION['user'].'"');
      if ($r->next_row())
            $r->addFields($main,$ar=array('id','name','secname'));
  //   var_dump($_COOKIE);
  }else {

          $main->addField('not_log','');
  };



//echoTree($main);
  if (isset($main)) {
                $site->addField($GLOBALS['currentSection'],&$main);
//echoTree($site);
                unset($main);
  };
 ?>