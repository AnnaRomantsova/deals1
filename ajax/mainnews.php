<?php

// Запрет на кэширование
header("Expires: Mon, 23 May 1995 02:00:00 GTM");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GTM");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//


//В этом файле хранятся логин и пароль к БД
require_once("../setup.php");
include_once($inc_path.'/db_conect.php');
include_once($inc_path.'/func.front.php');
/*
//Подключаемся к базе
function con_bd($Host, $User, $Passwd, $dbname){
@MYSQL_CONNECT($Host, $User, $Passwd) or die("Ошибка при соединении с Базой MySQL!!!");
@MYSQL_SELECT_DB($dbname) or die("Не могу выбрать таблицу $dbname");
@mysql_query("SET CHARACTER SET cp1251;") or die("Invalid query: ". mysql_error());
}
//echo "ddd";
con_bd($db_host,$db_user,$db_password,$db_name);

    //Проверка правильность имени
    if(!$from>=1)
    {
      $log.="Неправильно заполнено поле 'Ваше имя' (3-15 символов)!";
      $eierr="yes";
    }

*/
   $sql1="select * from setup where var='news_fcount'";
   $r1 = new Select($db,$sql1);
   $r1->next_row();
   $news_count=$r1->result('value');

   $sql1="select count(id) as cnt from news where pabl=1 ";
   $r1 = new Select($db,$sql1);
   $r1->next_row();


   if ( !($news_count >0) ) die;
  // var_dump($_POST);
   $from=$_POST['from']*$news_count;

   if($r1->result('cnt')<=$from) echo "<input type='hidden' name='theend'>";

   $sql['sql']="select * from news where pabl=1  order by datetime desc,ntype limit 0,$from";



  // echo $sql['sql'];
   $ri = new Select($db,$sql['sql']);

   $d = '';
   $d1=1;

   $month=array("января","февреля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
   $main = new outTree();
   if ($ri->next_row()) {
  // echo "d";
                  $sub = new outTree();

                  $d1 = date('d.m.Y', $ri->result('datetime'));
                  $d=$d1;
                  $sub1 = new outTree();
                  $ri->addFields($sub1,$ar=array('id','watch'));

                  if ($ri->result('ntype')==1 ) $sub1->addField('ntype','news');
                    else if ($ri->result('ntype')==2 ) $sub1->addField('ntype','law');

                  $ri->addFieldIMG($sub1,'image1');
                  $str=trim($ri->result('name'));
                  $str1=substr_replace($str, strtoupper($str[0]),0,1);
                  $sub1->addField('name',$str);
                   $ri->addFieldHTML($sub1,'preview');
                  //var_dump($sub1);
                  // echo "1";
                  $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                  $strdate.=" ".date('Y',$ri->result('datetime'));
                  $sub->addField('datetime',$strdate);
                  $sub->addField('sub1',&$sub1);
                  unset($sub1);
  };
  while ($ri->next_row()) {
                  unset($sub1);
                  // echo "1";
                  $sub1 = new outTree();
                  $ri->addFields($sub1,$ar=array('id','watch'));

                  $str=trim($ri->result('name'));
                  $str1=substr_replace($str, strtoupper($str[0]),0,1);
                  $sub1->addField('name',$str1);
                  $ri->addFieldIMG($sub1,'image1');
                   $ri->addFieldHTML($sub1,'preview');
                  if ($ri->result('ntype')==1 ) $sub1->addField('ntype','news');
                    else if ($ri->result('ntype')==2 ) $sub1->addField('ntype','law');

                  $d1 = date('d.m.Y', $ri->result('datetime'));
                  if($d!==$d1) {
                          $d=$d1;
                          $main->addField('sub',&$sub);
                          unset($sub);
                          $sub = new outTree();
                  };
                  $strdate = date("d",$ri->result('datetime'))." ".$month[((int) date("m",$ri->result('datetime'))-1)];
                   $strdate.=" ".date('Y',$ri->result('datetime'));
                 // $sub->addField('datetime',$strdate);
                  if(!isset($sub->datetime)) $sub->addField('datetime',$strdate);
                  $sub->addField('sub1',&$sub1);


  };

  if(isset($sub))  {
    $main->addField('sub',&$sub);
    unset($sub);
  }
  $ri->unset_();

 // var_dump($main);
   $site_FILENAME = 'front/news/panel_ajax.html';
   out::_echo($main,$site_FILENAME);
?>