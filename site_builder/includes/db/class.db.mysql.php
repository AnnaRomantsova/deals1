<?php

 /**
  * @package ALL
  */

 include_once('class.select.php');

 /**
 * Класс для работы с базой данных MYSQL. <br>
 * Имеет универсальный интерфейс, может быть переделан под любую базу <br>
 *
 * @author Milena Eremeeva
 * @version 2.03 - 24.01.2007 14:00
 *
 * .01 добавлена функция affected_rows <br>
 * .02 изменена функция fetch_assoc <br>
 * .03 оптимизированы и "обезопасены" функции insert и update, добавлены функции getFieldsNames,getInsertQuery,getUpdateQuery
 */
class Db {

  /**
   *  хост базы
   *  @var string
   */
  var $host = '';

  /**
   *  порт
   *  @var int
   */
  var $port = 0;

  /**
   *  логин доступа к хосту базы
   *  @var string
   */
  var $user = '';

  /**
   *  пароль доступа к хосту базы
   *  @var string
   */
  var $password = '';

  /**
   *  имя базы
   *  @var string
   */
  var $database = '';

  /**
   *  дескриптор коннекта к базе
   *  @var string
   */
  var $link_id = 0;

  /**
   *  дескриптор последнего запроса
   *  @var string
   */
  var $query_id = 0;

  /**
   *  курсор по строкам (см. метод next_row)
   *  @var int
   */
  var $result_row = -1;

  function Db(
           $_host,
           $_user,
           $_password,
           $_database,
           $_port = null
  ) {

    $this->host = $_host;
    if (isset($_port))
        $this->port = $_port;
    $this->user = $_user;
    $this->password = $_password;
    $this->database = $_database;
  }

  /**
   * Установливает соединение с базой (следит за тем, было установлено соединение или нет)
   * остановливает работу скрипта при неудаче<br>
   */
  function connect() {

    if ( $this->link_id == 0 ) {
      $this->link_id = @mysql_connect($this->host.($this->port ? ':'.$this->port : ''),
                                      $this->user,
                                      $this->password
      );
      ///****/echo("<br>Отладка connect: (".$this->link_id.")");
      if ( !$this->link_id ) {
        $this->halt("Подключение не установлено...");
      };
      if ( !@mysql_select_db($this->database) ) {
        $this->halt("Подключение с базой не установлено...");
      };
      @mysql_query('set names cp1251',$this->link_id);
    };
  }

  /**
   * выполняет запрос к базе, возвращает его дескриптор<br>
   * останавливает работу скрипта при неудаче<br>
   * сбрасывает курсор<br>
   * @param string $query_string строка запроса
   * @param bool $try в значению true не останавливает работу при неудаче
   * @return int дескриптор запроса
   */
  function query($query_string, $try = false) {
    $this->connect();
    //echo $query_string.'<br />';
    //exit;
    $this->query_id = @mysql_query($query_string, $this->link_id);
    if ( !$this->query_id && !$try)
          $this->halt("Ошибочный SQL запрос: ".$query_string);
    $this->result_num_rows = $this->num_rows();
    $this->result_row = -1;
    return $this->query_id;
  }



  /**
   * выполняет запрос к базе
   * @param string $query_string строка запроса
   * @return string номер и описание ошибки в случае позниковения
   */
  function queryCron($query_string) {
    $this->connect();
    $this->query_id = @mysql_query($query_string, $this->link_id);
    $query_error = '';
    if ($errno = mysql_errno())
            $query_error = $errno.': '.mysql_error();
    return $query_error;
  }

  /**
   * сдвигает курсор на следующую запись, возвращает FALSE - если дошел до конца
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   * @return bool
   */
  function next_row($_query_id = null)
   {
     if ( $this->result_row < $this->num_rows( ( $_query_id ? $_query_id : $this->query_id ) )-1 )
      {
               $this->result_row++;
         return true;
      }
     else return false;
   }

  /**
   * возвращает значение поля из строки запроса
   * @param string $field имя поля
   * @param int $row номер строки, по умолчанию той, на которую указывает курсор - не обязателен
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   * @return mixed
   */
  function result($field_name, $row = null, $_query_id = null)
   { if ( !isset($_query_id) ) $_query_id = $this->query_id;
     if ( $_query_id )
           $r = @mysql_result(
                   $_query_id,
                   ( isset($row) ? $row : $this->result_row ),
                   $field_name );
     return $r;
  }

  /**
   * освобождает запрос и сбрасывает курсор
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   */
  function free_result($_query_id = null)
   {
             @mysql_free_result( ($_query_id ? $_query_id : $this->query_id) );
        if (!$_query_id)
         {      $this->result_row = -1;
                $this->query_id = null;
         }
   }

  /**
   * меняет текущий запрос, сбрасывает курсор
   * @param string $_query_id
   */
  function set_current_result($_query_id)
   {    $this->query_id = $_query_id;
        $this->result_row = -1;
   }


  /**
   * возвращает количество строк запроса
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   * @return int
   */
  function num_rows( $_query_id = null )
   {
            return @mysql_num_rows( ($_query_id ? $_query_id : $this->query_id) );
   }

  /**
   * возвращает массив строк с именами таблиц<br>
   * сбрасывает курсор
   * @return array
   */
  function tables_names()
   {    $_tables_names = array();
        $this->query('SHOW tables');
        while ($this->next_row())
               array_push( $_tables_names, $this->result(0));
        return $_tables_names;
   }

  /**
   * добавление поля после поля в таблицу
   * @param string $_table_name имя таблицы
   * @param string $_field_name имя поля
   * @param string $_field_name_after после какого поля вставлять
   * @param string $_field_type тип вставляемого поля
   */
  function add_field_after($_table_name, $_field_name, $_field_name_after = null, $_field_type)
   {    $this->query('ALTER TABLE '.$_table_name.'
                      ADD '.$_field_name.' '.$_field_type.
                      ($_field_name_after ? ' AFTER '.$_field_name_after  : '') );
   }

  /**
   * удаление поля из таблицы
   * @param string $_table_name имя таблицы
   * @param string $_field_name имя поля
   */
  function drop_field($_table_name, $_field_name)
   {    $this->query('ALTER TABLE '.$_table_name.' DROP '.$_field_name );
   }

  /**
   * возвращает имя поля по номеру
   * @param int $col номер поля
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   * @return string
   */
  function field_name( $col, $_query_id = null )
   {
            return @mysql_field_name(($_query_id ? $_query_id : $this->query_id), $col);
   }


  /**
   * возвращает тип поля по номеру
   * @param int $col номер поля запроса
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   * @return string
   */
  function field_type( $col, $_query_id = null )
   {
            return @mysql_field_type(($_query_id ? $_query_id : $this->query_id), $col);
   }

  /**
   * возвращает количество полей запроса
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   * @return int
   */
  function num_fields( $_query_id = null )
   {
            return @mysql_num_fields( ($_query_id ? $_query_id : $this->query_id) );
   }

  /**
   * генерирует новый ключ id для таблицы
   * @param string $table_name имя таблицы
   * @return int
   */
  function next_id($table_name)
  {
    $this->query('select max(id) from '.$table_name);
    return $this->result(0,0) + 1;
  }

  /**
   * в случае ошибки ругается и завершает программу
   * @param string $msg сообщение об ошибке
   */
  function halt($msg)
  {
    echo('<html><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /><body>
      <div class="center"><b class="message_error">
        Внимание, ошибка:  '.$msg.'<br />Приносим извинения за временные технические неполадки
      </b></div></body></html>
    ');
    die('');
  }

  /**
   * возвращает ассоциированный массив строки
   * @param int $result дескриптор запроса
   * @param int $row номер строки
   * @return array
   */
  function &fetch_assoc($_query_id,$row = null) {
            if (isset($row))
                            mysql_data_seek($_query_id,$row);
      return mysql_fetch_assoc($_query_id);
  }

  /**
   * возвращает id последней вставленной записи
   * @return int
   */
  function insert_id()
  { return mysql_insert_id();
  }

 /**
  * возвращает колличество затронутых рядов в последнем запросе
  * @return int
  */
 function affected_rows() {
          return mysql_affected_rows();
 }

 /**
  * возвращает массив с именами полей в таблице
  *
  * @param string $table_name имя таблицы
  * @return array
  */
 function &getFieldsNames($table_name) {
   $this->query('select * from '.$table_name.' limit 1');
   $numfields = $this->num_fields();
   $fieldsNames = array();
   for($i =0; $i < $numfields; $i++)
                   $fieldsNames[] = $this->field_name($i);
   return $fieldsNames;
 }


 /**
  * вставляет строку в таблицу
  * @param string $table_name имя таблицы
  * @param array $insert_array ассоциативный массив (имя поля => значение)
  * @return void
  */
 function insert($table_name, &$insert_array) {
          if ( count($insert_array) > 0 )
                  $fieldsNames = &$this->getFieldsNames($table_name);

     if ($query = $this->getInsertQuery($fieldsNames, $table_name, &$insert_array))
             $this->query($query);
 }

 /**
  * формирует SQL-строку запрос для вставки (INSERT) строки в таблицe<br>
  * "закавычивает" входные данные
  * @param array $fieldsNames массив с именами полей в таблице
  * @param string $table_name имя таблицы
  * @param array $insert_array ассоциативный массив (имя поля => значение)
  * @return string
  */
 function getInsertQuery(&$fieldsNames, $table_name, &$insert_array) {
     if ( count($insert_array) > 0 ) {
             $insert_query = 'insert into '.$table_name.' values(';
                  foreach ($fieldsNames as $fn) {
                $value = (   isset($insert_array[$fn])
                             ? '"'.addslashes($insert_array[$fn]).'"'
                             : 'null' );
                $insert_query .= $value;
                    $insert_query .= ", ";
                  }
                  return substr($insert_query,0,-2).')';
     }
     return '';
 }


 /**
  * изменяет строки в таблицe
  * @param string $table_name имя таблицы
  * @param array $update_array ассоциативный массив (имя поля => значение)
  * @param string $where условие изменения - не обязателен
  * @return void
  */
 function update($table_name, &$update_array, $where = null) {
          if ( count($update_array) > 0 )
                  $fieldsNames = &$this->getFieldsNames($table_name);
     if ($query = $this->getUpdateQuery($fieldsNames,$table_name,&$update_array,$where))
             $this->query($query);
           //  echo $query;
 }

 /**
  * формирует SQL-строку запрос для изменения (UPDATE) строки в таблицe
  * "закавычивает" входные данные
  * @param array $fieldsNames массив с именами полей в таблице
  * @param string $table_name имя таблицы
  * @param array $update_array ассоциативный массив (имя поля => значение)
  * @param string $where условие изменения - не обязателен
  * @return string
  */
 function getUpdateQuery(&$fieldsNames, $table_name, &$update_array, $where = null) {
   $update_query = 'update '.$table_name.' set  ';
   $field_query = '';

   foreach ($fieldsNames as $fn) {
      if ( !isset($update_array[$fn]) ) continue;
      $value = '"'.addslashes($update_array[$fn]).'"';
      $field_query .= $fn.' = '.$value.' ,';
   }
   if ($field_query) {
      $update_query.= substr($field_query, 0, -2) ;
      if ( $where )
        $update_query .= ' where '.$where;
      return $update_query;
   }
   return '';
 }


}



?>
