<?php

 /**
  * служебные функции 
  * @package BACK
  */
 
include($inc_path.'/service/class.output.php');
include($inc_path."/service/func.service.php");
include($inc_path.'/admin_functions.php');


/**
 * добавляет запись в RSS
 * @param array $values ассоциативный массив полей для занесения в базу <br>
 * 						title,link,description
 */
 function addRecordRss(&$values) {
    global $db;
  	$table = &$GLOBALS['rss_table'];

    $valuesR = array( 'id' => ( $id = $db->next_id($table) ),
    				  'datetime' => time(),
    				  'pabl' => 1);
     				   
    $db->insert($table, array_merge($values,$valuesR));
 }
 
 
/**
 * добавляет запись в RSS о новой записи
 * @param string $link URI страницы, где произошло добавление
 * @param string $name имя страницы, где произошло добавление
 * @param array $values ассоциативный массив полей для занесения в базу 
 */ 
 function addRecordRssAdd($link,$name,&$values) {
  	if (!empty($values['add_RSS'])) 
  	 	addRecordRss($ar = array(
  	 		'name' => $name,
	 		'link' => $link,
  	 		'description' => (!empty($values['RSS_description']) ? $values['RSS_description'] : 'Добавлена новая информация')
  	 	));
 }
 
/**
 * добавляет запись в RSS об измененной записи
 * @param string $link URI страницы, где произошло изменение
 * @param string $name имя страницы, где произошло изменение
 * @param array $values ассоциативный массив полей для занесения в базу 
 */ 
  function addRecordRssEdit($link,$name,&$values) {
  	if (!empty($values['add_RSS'])) 
  	 	addRecordRss($ar = array(
  	 		'name' => $name,
	 		'link' => $link,
  	 		'description' => (!empty($values['RSS_description']) ? $values['RSS_description'] : 'Изменение на странице')
  	 	));
 }


 
/**
 * добавляет поле о наличии файла в дерево
 * @param outTree $main дерево 
 * @param Select  $r    запрос 
 * @param string  $field  имя поля, где лежит файл
 */
 function addFile(&$main,&$r,$field ) { 
 	 $file = $r->result($field);
     if ($file && is_readable( $GLOBALS['document_root'].rawurldecode($file) ))
          $main->addField($field,textFormat($file));
 }
 
 
/**
 * добавляет интерфейс отправки данных в RSS
 * @param outTree $main дерево
 */
 function add_addRss(&$main) { 
 	 $ot = new outTree('back/rss/addRss.html');
 	 $main->addField('addRss',&$ot);
 } 

/**
 * добавляет календарик
 * @param outTree $main дерево
 * @param int $n номер календарика
 */
 function addCalend(&$main,$n) {
 	$path = 'back/calend/';
 	
 	$ot = new outTree();
 	$ot->addField('n',$n);
 	
 	$ot_inc = new outTree($path.'include.html');
 	$ot_inc->addField('yc',date('Y'));
 	$ot_inc->addField('mc',intval(date('n'))-1);
 	$ot_inc->addField('dc',date('j'));
 	$ot->addField('include',&$ot_inc);
 	
 	$ot_but = new outTree($path.'button.html');
 	$ot_but->addField('n',$n);
 	$ot->addField('button',&$ot_but);
 	
 	$ot_calend = new outTree($path.'calend.html');
 	$ot_calend->addField('n',$n);
 	$ot_calend->addField('imgPath','/_images/calend/');
 	$ot->addField('calend',&$ot_calend);
 	
 	$main->addField('calend'.$n,&$ot);
 }

?>