<?php
/**
 * @package FRONT
 */

  $user_company = 0;
  if ( $_SESSION ['user']>0) {
     $r1 = new Select($db,'select * from users where id="'.$user.'"');
     if ($r1->next_row())
     $user_company = $r1->result('id_company');
 


          //echo "1";
          $menuName = 'menu_sub';
          unset($menu);

          $menu = new Menu('front/menu/'.$menuName.'.html',135,0,' and id  not in (144)');
           
          $menu->addMenu($site,$menuName);//echotree($menu);
        // $site->menu_sub->menu->sub[1]->name='Мои предложения';

 };
  //echotree($site->menu_sub);
?>
