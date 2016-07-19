<?php

// ?????? ?? ???????????
header('Content-type: text/html; charset=windows-1251');
header("Expires: Mon, 23 May 1995 02:00:00 GTM");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GTM");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

//


//? ???? ????? ???????? ????? ? ?????? ? ??
require_once("../setup.php");
include_once($inc_path.'/db_conect.php');
include_once($inc_path.'/func.front.php');
include_once($inc_path.'/object.php');
include($inc_path.'/myfunc.php');

$front_html_path='front/deals/';
$sh = $front_html_path.'ajax/edit_one.html';

if (!($_POST['id']>0)) die;


$main = new outTree();
$table="deals";
$profile = new object($db,$main, $table);

switch ($_POST['page']){
	case "podbor": {  $fields = array(
                        array("fname" => 'price_zaiav', "rname" => 'Цена заявки', "type" => 'varchar'),
                        array("fname" => 'status', "rname" => 'Статус', "type" => 'list','list_table' => 'status', 'sort' =>'id', 'where' => ' where id in (1,2,3)' ),
                        array("fname" => 'comment', "rname" => 'Коментарий', "type" => 'text'),
                        );
                     break;};
	case "prep": {  $fields = array(
                        array("fname" => 'status_prep', "rname" => 'Статус подготовки', "type" => 'list','list_table' => 'status_prep', 'sort' =>'id',  ),
                        array("fname" => 'status', "rname" => 'Статус', "type" => 'list','list_table' => 'status', 'sort' =>'id', 'where' => ' where id in (1,3,5)' ),
                        array("fname" => 'comment', "rname" => 'Коментарий', "type" => 'text'),
                        );
					  break;};
	case "contrib": {$fields = array(
                         array("fname" => 'status_uch', "rname" => 'Статус участия', "type" => 'list','list_table' => 'status_uch', 'sort' =>'id',  ),
                         array("fname" => 'status', "rname" => 'Статус', "type" => 'list','list_table' => 'status', 'sort' =>'id', 'where' => ' where id in (6,3,7)' ),
                         array("fname" => 'comment', "rname" => 'Коментарий', "type" => 'text'),
                     );
                     break;};
	case "contract": {$fields = array(
                        array("fname" => 'status_contract', "rname" => 'Статус контракта', "type" => 'list','list_table' => 'status_contract', 'sort' =>'id',  ),
                        array("fname" => 'status', "rname" => 'Статус', "type" => 'list','list_table' => 'status', 'sort' =>'id', 'where' => ' where id in (6,8)' ),
                        array("fname" => 'comment', "rname" => 'Коментарий', "type" => 'text'),
                      );
                      break;};
}

$main->addfield('id',$_POST['id']);
$main->addfield('user_id',$_POST['user_id']);
$main->addfield('page',$_POST['page']);

$profile->add_fields($fields,$_POST['id']);
if ($_POST['save']>0) {$profile->save_edit($_POST['id']);};


$main->addfield('number',$main->tab_fields[0]->value);

//echotree($main->id);

 // var_dump($main);

   out::_echo($main,$sh);
?>