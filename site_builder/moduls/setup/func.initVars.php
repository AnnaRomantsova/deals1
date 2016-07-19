<?php
/**
 * @package ALL
 */ 

	include($moduls_root.'/setup/config.php');
   
  /**
  * иницализирует глобальные переменные по массиву VARS
  * @return void
  * 
  * @author Milena Eremeeva (fenyx@yandex.ru)
  */ 
  function initVars() {
  	 global $db, $VARS, $table_setup;
  	 foreach ($VARS as $key => $value) {
     	$r = new Select($db,'select value from '.$table_setup.' where var="'.$key.'"');
     	$GLOBALS[$key] = ( $r->next_row() ? $r->result(0) : '' );
     	$r->unset_();
     }
  }
 
?>