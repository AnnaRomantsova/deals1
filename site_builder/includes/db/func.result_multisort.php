<?php

 /**
  * @package ALL
  */

  /**
   * делает мультисортировку по заданным полям запроса
   * @param Db    $db объект по работе с базой
   * @param array $_ar куда сортировать
   * @param array $_fields_sort как сортировать
   * @param string $_query_id дескриптор запроса, по умолчанию последнего - не обязателен
   * @return void
   */
  function result_multisort(&$db,&$_ar,&$_fields_sort,$_query_id = null)
   {
     if ( $num = count( $_fields_sort ) )
      {
         $numfields = $db->num_fields($_query_id);
         $arg = $arg_end = $arg_begin = '';

         $fields = array();

         for( $k =0 ; $k < $numfields; $k++ )
          {  $fn = $db->field_name($k,$_query_id);
             array_push($fields,$fn);

             if ( !isset($_fields_sort[$fn]) )
                $arg_end .= '$_ar[\''.$fn.'\'] ,';

             else
                $arg_begin_ar[$fn].= '$_ar[\''.$fn.'\'] , '.$_fields_sort[$fn].' ,';
          }

         foreach ( $_fields_sort as $key => $value)
            if ( isset($arg_begin_ar[$key]) ) $arg_begin.= $arg_begin_ar[$key];

         if ( $arg_end )
            $arg = $arg_begin.substr($arg_end, 0, -2);

         else
            $arg = substr($arg_begin, 0, -2);

         //echo '!!'.$arg;
         eval ('array_multisort('.$arg.');');
      }

   }

   /**
   * инициализирует ассоциативный массив массивов по последнему запросy для мультисортировки
   * @param array $_ar что инициализировать
   * @return void
   */
  function init_array_multisort(&$db,&$_ar)
   {
     $numfields = $db->num_fields();
     $i = 0;
     while ( $db->next_row() )
      {
         for( $k =0 ; $k < $numfields; $k++ )
           {  $fn = $db->field_name($k);
              $_ar[$fn][$i] = $db->result($fn);
           }
         $i++;
      }
   }




?>