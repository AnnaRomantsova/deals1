<?
/**
 * @package PHPMailer
 * @deprecated функции отправки почты с запросом к базе.
 */
require_once($GLOBALS['inc_path']."/phpmailer/class.phpmailer.php");
require_once($GLOBALS['inc_path']."/phpmailer/func.addimages.php");

function mailViaSMTPwithAttach($content,$table,$to_adr='',$subj='',$IsHTML=false, &$attach)
 {
   global $db;
   ini_set('max_execution_time',250);
   require_once($GLOBALS['inc_path']."/phpmailer/class.phpmailer.php");

   $mail = new PHPMailer();
   $mail->PluginDir = $GLOBALS['inc_path']."/phpmailer/";

   $mail->CharSet="Windows-1251";

   $SMTP_res=$db->query('select value from setup where var="SMTP_settings"');
   $SMTP_set=explode(";\r\n",stripslashes($db->result(0,0)));
   for ($i=0; $i<count($SMTP_set); $i++)
    { $ex=explode('=',$SMTP_set[$i]);
      if ($ex[0]>'')
      switch ($ex[0])
       { case 'mail':
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

   $msg_res=$db->query('select value from setup where var="'.$table.'"');
   $msg_set=explode(";\r\n",stripslashes($db->result(0,0)));
   for ($i=0; $i<count($msg_set); $i++)
    { $ex=explode('=',$msg_set[$i]);
      if ($ex[0]>'')
      switch ($ex[0])
       { case 'From':
             $mail->From=$ex[1];
             $mail->AddReplyTo($ex[1]);
             break;
         case 'FromName':
             $mail->FromName=$ex[1];
             break;
         case 'recipient':
             if ($to_adr=='')
              { $mail->AddAddress($ex[1]);
                $to_adr=$ex[1];
              }
             else
                $mail->AddAddress($to_adr);
             break;
         case 'Subject':
                $mail->Subject  = $ex[1].$subj;
             break;
       }
    }

   if ($subj>'')
      $mail->Subject  = $subj;

   $mail->IsHTML($IsHTML);
   $mail->Body = $content;
   if ( isset($attach) ) {
       foreach ( $attach as $a ) {
           if ( $a['size'] > 0 ) {
               $mail->AddAttachment($a['tmp_name'], $a['name']);
           }
       }
   }
   return $mail->Send();

 }


function mailViaSMTP($content,$table,$to_adr='',$subj='',$IsHTML=false,$from_adr='')
 { global $db;
   ini_set('max_execution_time',250);
   require_once($GLOBALS['inc_path']."/phpmailer/class.phpmailer.php");
   $mail = new PHPMailer();
   $mail->PluginDir = $GLOBALS['inc_path']."/phpmailer/";
   $mail->CharSet="Windows-1251";

   $SMTP_res=$db->query('select value from setup where var="SMTP_settings"');
   $SMTP_set=explode(";\r\n",stripslashes($db->result(0,0)));
   for ($i=0; $i<count($SMTP_set); $i++)
    { $ex=explode('=',$SMTP_set[$i]);
      if ($ex[0]>'')
      switch ($ex[0])
       { case 'mail':
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

   $msg_res=$db->query('select value from setup where var="'.$table.'"');
   $msg_set=explode(";\r\n",stripslashes($db->result(0,0)));
   for ($i=0; $i<count($msg_set); $i++)
    { $ex=explode('=',$msg_set[$i]);
      if ($ex[0]>'')
      switch ($ex[0])
       { case 'From':

            if ($from_adr =='') {
                $mail->From=$ex[1];
                $mail->AddReplyTo($ex[1]);
            } else {
                $mail->From=$from_adr;
                $mail->AddReplyTo($from_adr);
            };
             break;
         case 'FromName':
             $mail->FromName=$ex[1];
             break;
         case 'recipient':
             if ($to_adr=='')
              { $mail->AddAddress($ex[1]);
                $to_adr=$ex[1];
              }
             else
                $mail->AddAddress($to_adr);
             break;
         case 'Subject':
                $mail->Subject  = $ex[1].$subj;
             break;
       }
    }

   if ($subj>'')
      $mail->Subject  = $subj;

   $mail->IsHTML($IsHTML);
   $mail->Body = $content;
   return $mail->Send();
 }

function mail_prepare($table,$IsHTML=false,$settings='')
 { $mail=true; $auth=false; $Port=25;
   $Host=$Username=$Password='';
   $From=$FromName=$recipient=$Subject=$content='';

   ini_set('max_execution_time',250);

   $SMTP_res=mysql_query('select value from setup where var="SMTP_settings"');
   $SMTP_set=explode(";\r\n",stripslashes(mysql_result($SMTP_res,0,0)));
   for ($i=0; $i<count($SMTP_set); $i++)
    { $ex=explode('=',$SMTP_set[$i]);
      if ($ex[0]>'')
         $$ex[0]=$ex[1];
    }
   mysql_free_result($SMTP_res);

   $msg_res=mysql_query('select value from setup where var="'.$table.'"');
   $msg_set=explode(";\r\n",stripslashes(mysql_result($msg_res,0,0)));
   for ($i=0; $i<count($msg_set); $i++)
    { $ex=explode('=',$msg_set[$i]);
      if ($ex[0]>'')
         $$ex[0]=$ex[1];
    }
   mysql_free_result($msg_res);

   if (is_array($settings))
      while (list($key,$val)=each($settings))
       { $$key=$val;
       }

   $objPHPMailer = new PHPMailer();
   $objPHPMailer->PluginDir = $GLOBALS['inc_path']."/phpmailer/";
   $objPHPMailer->CharSet="Windows-1251";
   $objPHPMailer->IsHTML($IsHTML);

   if ($mail=='true')
      $objPHPMailer->IsMail();  // send via standart mail fanction
   else
      $objPHPMailer->IsSMTP();  // send via SMTP

   $objPHPMailer->SMTPAuth = ($auth=='true') ? true : false ;
   $objPHPMailer->Host=$Host;
   $objPHPMailer->Port=$Port;
   $objPHPMailer->Username=$Username;
   $objPHPMailer->Password=$Password;

   $objPHPMailer->From=$From;
   $objPHPMailer->AddReplyTo($From);
   $objPHPMailer->FromName=$FromName;
   $objPHPMailer->AddAddress($recipient);
   $objPHPMailer->Subject=$Subject;
   $objPHPMailer->Body = $content;
   return $objPHPMailer;
 }

function mail_send(&$objPHPMailer,$settings='')
 {
   if (is_array($settings))
    { while (list($key,$val)=each($settings))
       {
         switch ($key)
          { case 'From':
                $objPHPMailer->From=$val;
                $objPHPMailer->ReplyTo[0][0] = trim($val);
                $objPHPMailer->ReplyTo[0][1] = '';
                break;
            case 'FromName':
                $objPHPMailer->FromName=$val;
                break;
            case 'recipient':
                $objPHPMailer->to[0][0] = trim($val);
                $objPHPMailer->to[0][1] = '';
                break;
            case 'Subject':
                $objPHPMailer->Subject=$val;
                break;
            case 'content':
                $objPHPMailer->Body=$val;
                break;
          }
       }
    }

   return $objPHPMailer->Send();
 }
?>
