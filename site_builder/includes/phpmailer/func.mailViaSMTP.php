<?php

/**
 * @package PHPMailer
 * функции отправки почты с запросом к базе.
 * 
 * @author Milena Eremeeva (fenyx@yandex.ru)
 * @version 2.01 - 09.08.2007 16:45
 * 
 * .01 изменение с recipient
 */
 
require_once($GLOBALS['inc_path']."/phpmailer/class.phpmailer.php");

/**
 * Создает новый PHPMailer с настройками, прописанными в базе
 *
 * @param string $table поле с заголовками для письма в таблице setup
 * @return PHPMailer
 */
function &newViaSMTP($table) {
   global $db;
   ini_set('max_execution_time',250);
   require_once($GLOBALS['inc_path']."/phpmailer/class.phpmailer.php");

   $mail = new PHPMailer();
   $mail->PluginDir = $GLOBALS['inc_path']."/phpmailer/";

   $mail->CharSet="Windows-1251";

 //настройка SMTP
   $SMTP_res=$db->query('select value from '.$GLOBALS['table_setup'].' where var="SMTP_settings"');
   $SMTP_set=explode(";\r\n",stripslashes($db->result(0,0)));
   for ($i=0; $i<count($SMTP_set); $i++) {
      $ex=explode('=',$SMTP_set[$i]);
      if ($ex[0]>'')
      switch ($ex[0]) {
         case 'mail':
             if ($ex[1]=='true')
                $mail->IsMail();
             else
                $mail->IsSMTP();  // send via SMTP
             break;
         case 'auth':
             $mail->SMTPAuth = ($ex[1]=='true') ? true : false ;
             break;
         case 'Host':
             $mail->Host=$ex[1];
             break;
         case 'Port':
             $mail->Port=$ex[1];
             break;
         case 'Username':
             $mail->Username=$ex[1];
             break;
         case 'Password':
             $mail->Password=$ex[1];
             break;
      }
   }
   $db->free_result($SMTP_res);
   
   
 //заголовки письма из базы
   $msg_res=$db->query('select value from '.$GLOBALS['table_setup'].' where var="'.$table.'"');
   $msg_set=explode(";\r\n",stripslashes($db->result(0,0)));
   for ($i=0; $i<count($msg_set); $i++) {
      $ex=explode('=',$msg_set[$i]);
      if ($ex[0]>'')
      switch ($ex[0]) {
         case 'From':
             $mail->From=$ex[1];
             $mail->AddReplyTo($ex[1]);
             break;
         case 'FromName':
             $mail->FromName=$ex[1];
             break;
         case 'recipient':
             if (''!=$ex[1])
                $mail->AddAddress($ex[1]);
             break;
         case 'Subject':
                $mail->Subject  = $ex[1].$subj;
             break;
       }
   }
   
   return $mail;
}

/**
 * Цепляет к письму массив файлов из пришедших $_FILES
 *
 * @param PHPMailer $mail
 * @param array $attach массив файлов из пришедших $_FILES
 */
function addAtach(&$mail,$attach = null)  {
   if ( isset($attach) ) {
       foreach ( $attach as $a ) {
           if ( $a['size'] > 0 ) {
               $mail->AddAttachment($a['tmp_name'], $a['name']);
           }
       }
   } 	
}

/**
 * осуществляет отправку письма
 *
 * @param PHPMailer $mail
 * @param string $content
 * @param bool $IsHTML
 * @return int
 */
function sendViaSMTP(&$mail,$content,$IsHTML=false)  {  
   $mail->IsHTML($IsHTML);
   $mail->Body = $content;
   return (double)$mail->Send();
}

/**
 * Создает PHPMailer и осуществляет отправку письма
 *
 * @param string $table поле с заголовками для письма в таблице setup
 * @param string $content
 * @param bool $IsHTML
 * @param array $attach массив файлов из пришедших $_FILES
 * @return int
 */
function mailViaSMTP($table,$content,$IsHTML=false,$attach = null) {
   $mail =  &newViaSMTP($table);
   if (isset($attach))
   		addAtach(&$mail,&$attach);
   return sendViaSMTP(&$mail,$content,$IsHTML);
}


?>