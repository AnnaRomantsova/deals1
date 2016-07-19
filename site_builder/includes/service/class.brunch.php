<?php

 /**
  * @package ALL
  */

 /**
 * Класс для работы c ветками дерева, хранящегося в одной таблице
 *
 * @uses Db
 * @uses outTree
 *
 * @author Milena Eremeeva (fenyx@yandex.ru)
 * @version 2.03 - 11.01.2007 10:30
 *
 * .03 добавлена возможность указывать объект Db (по умолчанию остался глобальный $db)<br>
 *     изменился интерфейс конструктора
 */
 class Brunch {

          /**
          *  имя таблицы
          *  @var string
          */
          var $table;

          /**
          *  имя поля - где брать названия веток
          *  @var string
          */
          var $name;

          /**
          *  id текущей вершины
          *  @var int
          */
          var $id;

          /**
          *  уровень текущей вершины
          *  @var int
          */
          var $level;

          /**
          *  массив id всех вершин от текущей до корня, индексированный уровнями
          *  @var array
          */
          var $ids = array();

          /**
          *  массив названий всех вершин от текущей до корня, индексированный уровнями
          *  @var array
          */
          var $names = array();

          var $db;

          function Brunch($_id, $_table, $_where = ' AND pabl="1"', $_db = null, $_name = 'name') {
                    $this->table = $_table;
              $this->where = $_where;
              $this->name = $_name;
              $this->id = $_id;
              $this->db = !isset($_db) ? $GLOBALS['db'] : $_db;
              $this->initLevel();
          }

          function initLevel() {
                    $this->level = $this->getLevel($this->id);
          }

          /**
            * рекурсивно вычисляет уровень конечной вершины,
            * id и названия всех вершин от корня до текущей
            * @param int $_id с какого id стартовать
            * @return int
          */
          function getLevel($_id) {
                    global $db;
               //     echo 'select parent,'.$this->name.' from '.$this->table.' where id="'.$_id.'"'.$this->where;
              $r = $db->query('select parent,'.$this->name.' from '.$this->table.' where id="'.$_id.'"'.$this->where);
              if ($db->next_row()) {
                       $parent = $db->result(0,0,$r);
                 $level = $parent ? 1+$this->getLevel($parent) : 0;
                 $this->ids[$level] = $_id;
                 $this->names[$level] = textFormat($db->result(1,0,$r));

                       return $level;
              }
              else
                 return -1000;
          }

         /**
         * добавляет sub в полe типа Path в $ot
         * @param outTree $path объект, в который добавляем
         * @param string $href адрес, к которому приписывается id
         * @param array $levels_ignore уровни, которые не должны быть ссылками
         * @param bool $with_end включать хвост, или нет
         * @return void
         */
         function addFieldPATH(&$path, $href, &$levels_ignore, $with_end = false) {
                   // var_dump($this->ids);
                   // echo $this->id;
                    foreach($this->ids as $key => $value) {
                                $flag_with_end = ($with_end ?  true : ($this->id != $value) );
                              //  echo $this->id." ".$value."<br>";
                               // $flag_with_end = true;
                           if ($key && $flag_with_end)  { // без корня и хвоста
                           //if ($flag_with_end)  { // если корня нет - то так.
                               $sub =  new outTree();
                               $sub->addField('separator','');
                               $sub->addField('href',$href.$value);
                               $sub->addField('name',$this->names[$key]);
                               $sub->addField('T', in_array($key,$levels_ignore) ? 'S' : 'A');
                               $path->addField('sub',&$sub);
                               unset($sub);
                           }
               }
             // echotree($path);
         }


        function addFieldPATHmy(&$path, $href, &$levels_ignore, $with_end = false) {
                   // var_dump($this->ids);
                   // echo $this->id;
                    foreach($this->ids as $key => $value) {
                                $flag_with_end = ($with_end ?  true : ($this->id != $value) );
                              //  echo $this->id." ".$value."<br>";
                               $flag_with_end = true;
                           if ($key && $flag_with_end)  { // без корня и хвоста
                           //if ($flag_with_end)  { // если корня нет - то так.
                               $sub =  new outTree();
                               if ($this->id != $value) $sub->addField('separator','');
                               $sub->addField('href',$href.$value);
                               $sub->addField('name',$this->names[$key]);
                               $sub->addField('T', in_array($key,$levels_ignore) ? 'S' : 'A');
                               $path->addField('sub',&$sub);
                               unset($sub);
                           }
               }
             // echotree($path);
         }
 }




?>
