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


echo "dd";
var_dump($_POST);
if ($_POST['checked']>0) {
   $ri = new Select($db,"update opros set cnt=cnt+1 where id=".$_POST['checked']);
};

if (!($_POST['formid']>0)) die;


$main = new outTree();


 $ri = new Select($db,"select * from opros where id=".$_POST['formid']);

 while ($ri->next_row()) {
        unset($sub);
        $sub = new outTree();
        $ri->addFields($sub,$ar=array('id','cnt','name'));

        $r = new Select($db,"select sum(cnt) as cnt from opros where parent=".$ri->result('id'));
        if ($r->next_row()) $max = $r->result('cnt');

        $r = new Select($db,"select * from opros where parent=".$ri->result('id'));
        while ($r->next_row()) {
             unset($sub1);
             $sub1 = new outTree();
             $r->addFields($sub1,$ar=array('id','cnt','name'));
             $perc = round(($r->result('cnt'))/$max*100);
             $sub1->addField('perc',$perc);
             $sub1->addField('width',$perc*2);
             $sub->addField('sub1',&$sub1);
        };

        $main->addField('sub',&$sub);


};
//echotree($main);

$ri->unset_();




 // var_dump($main);
   $site_FILENAME = 'front/opros/panel_ajax.html';
   out::_echo($main,$site_FILENAME);
?>