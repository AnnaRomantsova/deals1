<?php

 $FILENAME = 'front/menu/menu_vendor.html';

 $main = &addInCurrentSection($FILENAME);
  unset($main->content);

  //echotree($site);


  if ( $_SESSION ['vendor']>0) {
     $sub = new outTree();
     $sub->addfield('href','lk');
     $sub->addfield('name','Личный профиль');

     $T = new outTree();
     if ($site->pageid =='lk') $T->addfield('S','');  else  $T->addfield('A','');
     $sub->addField('T',$T);


     $main->addField('sub',$sub);
     unset($sub); unset($T);
     $sub = new outTree();
     $sub->addfield('href','vend_zakupki');
     $sub->addfield('name','Закупки');

     $T = new outTree();
     if ($site->pageid =='vend_zakupki')  $T->addfield('S','');  else  $T->addfield('A','');
     $sub->addField('T',$T);


     $main->addField('sub',$sub); unset($sub); unset($T);
  };

    $site->addField($GLOBALS['currentSection'],&$main);
    echotree($site);
?>
