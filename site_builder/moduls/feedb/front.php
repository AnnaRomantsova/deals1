<?
  include('config.php');

 require_once($inc_path."/phpmailer/func.mailViaSMTP.php");
 //echo $_SESSION['randomnr'];
 $main = &incPage($front_html_path.'front.html');

 foreach ( $_POST as $key => $value)
               $$key=$value;

 // строка для JavaScript и проверка наличия обязательных полей
 $flag = true; $str_fieldsWF = '';
 foreach ( $fieldsWithoutFail as $value) {
         //echo "dd";
       $str_fieldsWF.= ('\''.$value.'\',');
       $flag = $flag && !empty($$value);
 }

  $r = new Select($db,'select * from site_pages where id="9"');
 // if ($r->next_row()) $r->addFieldHTML($main,'content');


 $main->addField('fieldsWithoutFail',substr($str_fieldsWF,0,-1));

 if (isset($_POST['send']))
	 if ($_SESSION['norobot'] !== md5($_POST['norobot']))
	 {
	     //var_dump($_SESSION);
	     $flag=false;
	 	 $mess= "Код подтверждения не совпадает";
	 	 $main->addField('kod',$mess);
	 };

 if (isset($_POST['send']) && $flag)  {

 $text = date('d.m.Y').' в '.date('H:i:s').' на сайте '.$_SERVER['SERVER_NAME'].'в разделе Обратная связь было отправлено сообщение.
Тема сообщения: '.$tema.'
От: '.stripslashes($fio).'
Email: '.stripslashes($email).'
Текст: '.stripslashes($text);

         $mail = &newViaSMTP('mail_feed');
         $mail->Subject = $tema;
         $subm = sendViaSMTP($mail,$text,false);
       //  header('Location: '.$GLOBALS['strPATH'].'?subm='.$subm.'#f');



// if (isset($subm))
    $mess = ($subm>0 ? 'ok' : 'er');
    $main->addField('message',$mess);


 }

 unset($main);

?>

