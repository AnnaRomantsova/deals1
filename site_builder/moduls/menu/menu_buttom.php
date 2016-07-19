<?php
/**
 * @package FRONT
 */
 // echo $parent;
 /*echotree($GLOBALS['current_outTree']);
 $m = new Menu('front/menu/menu_sub.html',128,0);
 $m->addMenu(&$GLOBALS['current_outTree'],'include');
 echotree($m);
 unset($m);
 */
        include_once($inc_path."/service/class.menu.php");
 //���� ����������� � ������������ ����� ���



              $menuName = 'menu_buttom';
              unset($menu);
              $menu = new Menu('front/menu/'.$menuName.'.html',145,0);
              $menu->addMenu($site,$menuName);

              unset($menu);
              $menu = new Menu('front/menu/'.$menuName.'.html',145,1);
              //$menu->pagesModul = array (   14 => 'feedb/menu_sub')
              $menu->addMenu($site,$menuName);

       // if (isset($site->menu_sub->menu->sub[3]))
       //         $site->menu_sub->menu->sub[3]->addfield('sep','');
      // echotree($site);
?>
