<?php
 /**
  * @package FRONT
  */


 /**
 * Строит объект outTree для одноуровневого меню по таблице $site_tree
 * @todo можно хорошо оптимизировать этот класс, извлекая все необходимое одним запросом с JOIN
 *
 * @uses Db
 * @uses outTree
 * @uses func.service.php
 *

 * @version 2.11 - 11.09.2007 17:50
 *
 * .04 убрана проверка на флаг menu страниц при выводе меню по разделам <br>
 * .05 изменен способ внедрения модулей <br>
 * .06 расширен интерфейс метода addMenu <br>
 * .06 метод addMenu присоединяет копию ветки <br>
 * .07 исправлен глюк с формированием групп <br>
 * .08 формирование групп -  c использованием функции separateGroup из func.service.php <br>
 * .09 проверка на $_GET['i'] и $_GET['s'] <br>
 * .10 добавлена флаг count - если в разделе одна страница - one, иначе many <br>
 * .11 под новую структуру
 *
 */
 class Menu {

    /**
    *  @var outTree
    */
    var $ot;

    /**
    *  шаблон меню
    *  @var string
    */
    var $template = '';

    /**
    *  id родительской секции в базе
    *  @var int
    */
    var $parent;

    /**
    *  выводить секции или страницы
    *  @var int
    */
    var $section;

    /**
    *  количество пунктов меню в группе
    *  @var int
    */
    var $count_in_group;

    /**
    *  количество групп - если задано - $count_in_group пересчитывается
    *  @var int
    */
    var $count_group;

    /**
    *  для особых запросов
    *  @var string
    */
    var $where = '';

    /**
    *  меню раскрыто только в разделе
    *  @var bool
    */
    var $open_in_section = false;

    /**
    *  ассоциативный массив кода (id пункта => 'папка модуля/имя файла' без .php), например
    * <code>
    * $this->pagesModul = array (
    *                                   14 => 'catalog/menu_sub',
    *                                   17 => 'portfolio/menu_sub'
    *                                 )
    * </code>
    * позволяет формировать любую конфигурацию пункта меню, отличную от стандартной<br>
    * в скриптах доступна текущая ветка в переменной $GLOBALS['current_outTree']
    *
    *  @var array
    */
    var $pagesModul = array();

    function Menu(
        $template,
        $parent = 1,
        $section = 1,
        $where = '')
     {
       $this->template = $template;
       $this->parent = $parent;
       $this->section = $section;
       $this->where = $where;
     }

    /**
     * формирует секцию
     *
     * @param outTree $menu дерево меню
     * @param outTree $sub дерево пункта меню
     * @param int $parent id пункта меню
     * @return bool проинициализировал секцию, или нет
     */
    function init_section(&$menu, &$sub, $parent)
     { global $db, $site_table, $page;

       $flag_in_section = false;
       //echo 'select page,id from '.$site_table.' where parent="'.$parent.'" and section="0" and pabl="1" order by sort limit 2';
       $rp = new Select($db,'select page,id from '.$site_table.' where parent="'.$parent.'" and section="0" and pabl="1" order by sort limit 2');
       if ($rp->next_row())
        {
           $rsp = new Select($db,'select parent from '.$site_table.' where page="'.$page.'" and section="0" and pabl="1"');
           $page_sub = $rp->result('page');
           if (isset($this->pagesModul[$parent])) {
                           unset($GLOBALS['current_outTree']);
                           $GLOBALS['current_outTree'] = &$sub;
                           include($GLOBALS['moduls_root'].'/'.$this->pagesModul[$parent].'.php');
           }
               else {

                   // если текущая страница доступна и имеет родителем раздел $parent
                   if ( $rsp->next_row() && ($parent == $rsp->result('parent'))  )
                    { // если первая страница раздела - не текущая
                      if ( $page_sub != $page )
                         $T = 'SA';

                      // если выбран раздел, объект
                      elseif ( isset($_GET['s']) || isset($_GET['i']) )
                         $T = 'SA';

                      else
                         $T = 'S';

                      $flag_in_section = true;

                    }
                   else  $T = 'A';

                   $rsp->unset_();

                   $sub->addField(
                       'href',
                       'index' != $page_sub ? textFormat($page_sub) : ''
                   );

                   $sub->addField('page', textFormat($page_sub) );
                   $sub->addField('T', $T );
                   $sub->addField('count',$rp->num_rows > 1 ? 'many' : 'one');
               }

           $menu->addField('sub',$sub);
        }
       $rp->unset_();

       return $flag_in_section;
     }


    /**
     * формирует дерево меню в $this->ot
     *
     * @return bool успешно или нет
     */
    function init_()
     { global $db, $site_table, $page;
       //echo 'select * from '.$site_table.' where id="'.$this->parent.'" and section="1" and pabl="1"';
       $r_main = new Select($db,'select * from '.$site_table.' where id="'.$this->parent.'" and section="1" and pabl="1"');
       if (!$r_main->next_row()) return false;
       $main = new outTree();
       $flag_in_section = false; // проверка на нахождение в секции

// инициализация первой страницы в разделе
       $first = new outTree();
       $sub = new outTree();
       $flag_in_section = $this->init_section($first , $sub, $this->parent) || $flag_in_section;
       $f = array('name','id');
       $r_main->addFields($sub, $f);
       $main->addField('first',$first);
       $r_main->unset_(); unset($sub);

       $r = new Select($db,'select * from '.$site_table.' where parent="'.$this->parent.'" and section="'.$this->section.'" and menu="1" and pabl="1" '.$this->where.' order by sort');
       // echo 'select * from '.$site_table.' where parent="'.$this->parent.'" and section="'.$this->section.'" and menu="1" and pabl="1" '.$this->where.' order by sort';
       if ($r->num_rows)
        {
           $menu = new outTree();
           $num_rows=1;
           while ($r->next_row())
            {
               $sub = new outTree();
               $f = array('name','id');
               $r->addFields($sub, $f);
               $sub->addField('num',$num_rows);
               $num_rows+=1;
// если меню по разделам
               if (1 == $this->section)
                   $flag_in_section
                     = ($this->init_section($menu, $sub, $r->result('id')) || $flag_in_section);

// если меню по страницам
               else
                {  $page_sub = $r->result('page');
                   $id_sub = $r->result('id');

                //добавление кода.
                   if (isset($this->pagesModul[$id_sub])) {
                                   $GLOBALS['current_outTree'] = &$sub;
                                           include($GLOBALS['moduls_root'].'/'.$this->pagesModul[$id_sub].'.php');
                   }
                       else {
                                   $T =  (   $page_sub != $page
                                   ? 'A'
                                   : (isset($_GET['s']) || isset($_GET['i']) ? 'SA' : 'S')
                           );
                           $sub->addField(
                                   'href',
                                   'index' != $page_sub ? textFormat($page_sub) : ''
                           );
                           $sub->addField('T', $T );
                           $sub->addField('page', textFormat($page_sub) );
                   }
                   $menu->addField('sub',$sub);

                }

               unset($sub);

            }

           $this->ot = &$main;

           // если не находимся в разделе - выходим
           if ( $this->open_in_section && !$flag_in_section )
               return true;

// инициализация меню и расставление разделителей
           $cm = ( isset($menu->sub) ? ( is_array($menu->sub) ? count($menu->sub) : 1 ) : 0);
           if ($cm)
            { for($i = 0; $i < ($cm-1); $i++)
                  $menu->sub[$i]->addField('separator','');

              if (isset ($this->count_group))
                        separateGroup($menu->sub,$this->count_group,'separator_group');

  // если не задано количество групп, но задано количество в группе
              elseif (isset ($this->count_in_group))
                  for($i = $this->count_in_group-1; $i < ($cm-1); $i+=$this->count_in_group)
                      $menu->sub[$i]->addField('separator_group','');

              $main->addField('menu',$menu);
            }

        }

        return true;
     }


     /**
      * рапечатывает $this->ot по шаблону this->template
      *
      */
     function print_() {
        if (isset($this->ot) )
           out::_echo( $this->ot, $this->template );
     }

         /** добавляет ветку типа Menu в дерево
          * @param  outTree $ot  объект, в который добавляем
          * @param  string  $name имя ветки
          * @param  boolean $flagInit инициализировать меню или нет
          * @return void
          */
     function addMenu(&$ot,$name,$flagInit = true) {
             if ($flagInit)
                     $this->init_();
             if (isset($this->ot) ) {
                $this->ot->TEMPLATE = $this->template;
           $ot->addField($name,$this->ot);
             }
     }

 }

?>
