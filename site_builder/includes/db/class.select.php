<?php

 /**
  * @package ALL
  */

/**

 * Класс для работы с результатом запроса SELECT к бд
 *
 * @uses Db
 * @uses outTree
 * @author Milena Eremeeva
 * @version 2.07 - 27.08.2007 9:50
 *
 * .01 добавлена функция isEOF <br>
 * .01 добавлена возможность выставлять границу запроса (до какой записи) <br>
 * .02 добавлена функция fetch_assoc <br>
 * .03 изменены функции addFieldIMG,addFieldsIMG - интерфейс совместим со старыми версиями <br>
 * .04 очистка кеша clearstatcache в методе addFieldFILE <br>
 * .05 добавлен служебный класс Select_ - с деструктором Select. <br>
 * .06 добавлена функция addField (ну наконец-то!!!) <br>
 * .07 добавлена функция addFieldDATE
 *
 */
 class Select
 {

  /**
   *  объект работы с базой
   *  @var Db
   */
  var $db;

  /**
   *  строка запроса
   *  @var string
   */
  var $query_string = '';

  /**
   *  дескриптор запроса
   *  @var string
   */
  var $query_id = 0;

  /**
   *  количество строк
   *  @var int
   */
  var $num_rows = 0;

  /**
   *  курсор по строкам (см. метод next_row)
   *  @var int
   */
  var $result_row = -1;

  /**
   *
   * @param Db $_db объект работы с базой
   * @param string $_query запрос SELECT
   * @param boolean $try в значению true не останавливает работу при неудаче
   * @return Select
   */
  function Select( &$_db, $_query, $try = false )
   {
      $this->db = &$_db;
      $this->query_string = $_query;
      $this->query_id = $_db->query($_query, $try);
      $this->num_rows = $_db->num_rows($this->query_id);
      $this->result_row = -1;
   }

   /**
    * проверяет, достигнут курсором конец
    * @return bool
    */
   function isEOF() {
                   return $this->result_row == $this->num_rows;
   }

  /**
   * возвращает ассоциированный массив текущей строки
   * @return array
   */
  function &fetch_assoc() {
      return $this->db->fetch_assoc($this->query_id,$this->result_row);
  }


  /**
   * выполняет повторный к базе
   */
  function reSelect()
   {
      $this->query_id = $this->db->query($this->query_string);
      $this->num_rows = $this->db->num_rows($this->query_id);
      $this->result_row = -1;
   }


  /**
   * "освобождает" дескриптор запроса
   */
  function unset_()
   {
      $this->db->free_result( $this->query_id );
   }

  /**
   * сдвигает курсор на следующую запись<br>
   * возвращает FALSE - если дошел до конца фактического или специально указанного в $this->end
   * @return bool
   */
  function next_row()
   {
     if ( ($this->result_row < $this->num_rows - 1) && (isset($this->end) ? $this->result_row < $this->end : true) )
      {
         $this->result_row++;
         return true;
      }
     else return false;
   }


  /**
   * возвращает значение поля из строки запроса
   * @param string $field_name имя поля
   * @param int $row номер строки, по умолчанию той, на которую указывает курсор - не обязателен
   * @return mixed
   */
  function result($field_name, $row = null)
   {
     if ( $this->query_id )
           $r = $this->db->result( $field_name,
                                   ( isset($row) ? $row : $this->result_row ),
                                   $this->query_id
                                 );
     return stripslashes($r);
  }



  /**
   * освобождает запрос и сбрасывает курсор
   * @return void
   */
  function free_result()
   {    $this->db->free_result( $this->query_id );
        $this->result_row = -1;
        $this->num_rows = 0;
        $this->query_id = null;
   }

  /**
   * возвращает количество строк запроса
   * @deprecated не нужен, так как инициализируется свойство $this->num_rows в конструкторе
   * @return int
   */
  function num_rows()
   {
        return $this->db->num_rows( $this->query_id );
   }

  /**
   * инициализирует все поля типом Text из запроса в $ot, используя результат запроса к базе
   * @param outTree $ot объект, в который добавляем
   */
  function addAll(&$ot)
   {
      $numfields = $this->db->num_fields($this->query_id);
      for( $i =0 ; $i < $numfields; $i++ )  {
          $ot->addField( $field_name = $this->db->field_name($i,$this->query_id),
                         htmlspecialchars($this->result($field_name),null, "windows-1251"));
      }
   }


  /**
   * инициализирует поля типа Text в $ot, используя результат запроса к базе
   * @param outTree $ot объект, в который добавляем
   * @param array $fields поля, которые добавляем
   */
  function addFields(&$ot,&$fields)
   {   $cnt = count($fields);
       for ( $i = 0; $i < $cnt; $i++ )
          $ot->addField( $fields[$i], htmlspecialchars($this->result($fields[$i]),null, "windows-1251") );
   }


  /**
   * инициализирует полe типа Text в $ot, используя результат запроса к базе
   * @param outTree $ot объект, в который добавляем
   * @param string $fields полt, которое добавляем
   */
  function addField(&$ot,$field) {
      $ot->addField( $field, htmlspecialchars($this->result($field),null, "windows-1251") );
  }

  /**
   * инициализирует поле типа HTML в $ot
   * @param outTree $ot объект, в который добавляем
   * @param string  $fieldHtml поле таблицы
   */
  function addFieldHTML(&$ot, $fieldHtml)
   {   $ot->addField( $fieldHtml, $this->result($fieldHtml) );
   }

  /**
   * Аналог addFieldHTML, делает тоже самое для массива полей
   *
   * @param outTree $ot объект, в который добавляем
   * @param string  $fields массив полей
   */
  function addFieldsHTML(&$ot,&$fields)
   {   $cnt = count($fields);
       for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldHtml( $ot, $fields[$i] );
   }


  /**
   * инициализирует ветку $fieldImg типа IMG в $ot, если картинка существует,
   * @deprecated  иначе 'not_'.$fieldImg - добиться того же результата можно, использовав в шаблоне вызов отрицания поля [%!$fieldImg%]
   *
   * @param outTree $ot объект, в который добавляем
   * @param string  $fieldImg поле таблицы, где хранится путь к картинке
   * @param string  $path - добавочный путь к картинке
   */
  function addFieldIMG(&$ot, $fieldImg, $path = '') {
     global $document_root;
     $isize = @getImageSize($document_root.($file = $path.rawurldecode($this->result($fieldImg)))  );
     if ($isize) {
        $tmp = new outTree();
        $tmp->addField('w',$isize[0]);
        $tmp->addField('h',$isize[1]);
        $tmp->addField('src', $file );
        $ot->addField( $fieldImg, $tmp);
     }
     else
             $ot->addField('not_'.$fieldImg,'');
   }

  /**
   * Аналог addFieldIMG, делает тоже самое для массива полей
   *
   * @param outTree $ot объект, в который добавляем
   * @param string  $fields массив полей
   * @param string  $path добавочный путь к картинке
   */
  function addFieldsIMG(&$ot,&$fields,$path = '') {
     $cnt = count($fields);
     for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldIMG( $ot, $fields[$i], $path );
  }

  /**
  * инициализирует ветку fieldF типа FILE в $ot, если файл существует
  * @param outTree $ot объект, в который добавляем
  * @param string  $fieldF поле таблицы, где хранится путь к файлу
  * @return void
  */
  function addFieldFILE(&$ot, $fieldF)
   { global $document_root;
     $file_name = $this->result($fieldF);
     if ($file_name && is_readable( $document_root.( $file=rawurldecode($file_name) ) ))
      { $tmp = new outTree();
        $tmp->addField('size',round(filesize($document_root.$file)/1024,2));
        $tmp->addField('type',strtolower(substr(strrchr($file,'.'),1)));
        $tmp->addField('href', $file );
        $ot->addField( $fieldF, $tmp);
      }
     clearstatcache();
   }

  /**
   * Аналог addFieldFILE, делает тоже самое для массива полей
   *
   * @param outTree $ot объект, в который добавляем
   * @param string  $fields массив полей
   */
  function addFieldsFILE(&$ot,&$fields)
   { $cnt = count($fields);
     for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldFILE( $ot, $fields[$i] );
   }

  /**
  * инициализирует ветку fieldE типа EMAILa в $ot
  * @param outTree $ot объект, в который добавляем
  * @param string  $fieldE поле таблицы
  * @return void
  */
  function addFieldEMAILa(&$ot, $fieldE)
   { global $document_root;
     if ($this->result($fieldE))
      { $tmp = new outTree();
        $tmp->addField('open',get_script_a_mail(textFormat($this->result($fieldE))));
        $tmp->addField('close','</a>');
        $ot->addField( $fieldE, $tmp);
      }
   }

  /**
   * Аналог addFieldEMAILa, делает тоже самое для массива полей
   *
   * @param outTree $ot объект, в который добавляем
   * @param string  $fields массив полей
   */
  function addFieldsEMAILa(&$ot,&$fields)
   { $cnt = count($fields);
     for ( $i = 0; $i < $cnt; $i++ )
          $this->addFieldEMAILa( $ot, $fields[$i] );
   }


  /**
  * инициализирует ветку field с именем $fieldName типа DATE в $ot
  * @param outTree $ot объект, в который добавляем
  * @param string  $field поле таблицы
  * @param string  $fieldName поле шаблона
  */
  function addFieldDATE(&$ot,$field,$fieldName = 'date') {
        $datetime = intval($this->result($field));
        $d =  new outTree();
        $d->addField('day',date('j',$datetime));
        $d->addField('year',date('Y',$datetime));
        $d->addField('month',date('n',$datetime));
        $ot->addField($fieldName,$d);
  }

}

/**
 * @todo не помню, зачем написала этот класс, возможно, пригодится
 *
 */
class Select_ {

        /**
         * деструктор класса Select
         *
         * @param Select $r
         */
        function unset_(&$r) {
                $r->unset_();
                unset($r);
        }
}


?>
