 <?php


   //В этом файле хранятся логин и пароль к БД
     require_once("../setup.php");
     include_once($inc_path.'/db_conect.php');
     include_once($inc_path.'/func.front.php');

   echo $_POST['id_company'];
  //редактирование отчетов
  if ($_POST['rep_edit']>0) {

     foreach($_POST as $gname => $val) {
        $val = iconv("UTF-8", "WINDOWS-1251",  $val);
        if (strpos($gname,g_edit)==0) {
            $gid = substr($gname,6);
            if ($gid > 0)
                    $q = new Select($db,"update reports set name='$val' where id=$gid");

        };
     };
   }


  //удаление
  if ($_POST['rep_del']>0) {


     foreach($_POST as $gname => $val) {
        $val = iconv("UTF-8", "WINDOWS-1251",  $val);
        if (strpos($gname,g_edit)==0) {
            $gid = substr($gname,6);
            if ($gid > 0)
                    $q = new Select($db,"delete from reports where id=$gid");


        };
     };
   }

     if ($_POST['id_company']>0) {
        $main = new outTree();
        $r = new Select($db,'select * from reports where id_company="'.$_POST['id_company'].'" order by name');
        while ($r->next_row()) {
                               unset($sub);
                               $sub = new outTree();
                               $r->addFields($sub,$ar=array('id','name','file'));
                               $main->addField('sub',&$sub);
        };

      $site_FILENAME = 'front/reports/front_ajax.html';
       out::_echo($main,$site_FILENAME);
    };

?>