<?php

 /**
  * @package ALL
  */

 include_once('class.select.php');


/** 
 * Класс для работы с результатом запроса SELECT к бд <br>
 * расширен двумя функциями для различия полей с постфиксам EN
 * 
 * @uses Db
 * @uses outTree
 * @author Milena Eremeeva
 * @version 2.07 - 27.08.2007 9:50
 */ 
 class SelectL extends Select {
 	
  /**
   * инициализирует все поля без постфикса EN из запроса типа Text в $ot 
   * @param outTree $ot объект, в который добавляем
   */
  function addAllLangD(&$ot)
   {  global $language,$languages;
        
      $numfields = $this->db->num_fields($this->query_id);
      for( $i =0 ; $i < $numfields; $i++ )  {
      	  $field_name = $this->db->field_name($i,$this->query_id);
      	  $flag = true;
      	  foreach ($languages as $value) {
      	  	 $flag = $flag && ( substr($field_name,-2) == $value );
      	  }
      	  if ($flag)
          	$ot->addField( $field_name, htmlspecialchars($this->result($field_name),null, "windows-1251")); 
      }
   }

  /**
   * инициализирует все поля c постфиксом EN из запроса типа Text в $ot 
   * @param outTree $ot объект, в который добавляем
   */
  function addAllLang(&$ot,$LANG)
   {  $numfields = $this->db->num_fields($this->query_id);
      for( $i =0 ; $i < $numfields; $i++ )  {
      	  $field_name = $this->db->field_name($i,$this->query_id);
      	  if ($LANG == substr($field_name,-2))
          	$ot->addField( $field_name, htmlspecialchars($this->result($field_name),null, "windows-1251")); 
      }
   } 
  
 }


?>