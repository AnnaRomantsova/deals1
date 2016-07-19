<?php

include ('config.php');

$FILENAME = $front_html_path . 'auth.html';
$reg_FILENAME = $front_html_path . 'reg.html';
$forgot_FILENAME = $front_html_path.'forgot.html';

require_once($inc_path."/phpmailer/func.mailViaSMTP.php");

//require_once($inc_path."/phpmailer/func.mailViaSMTP.php");


$patch = $HTTP_SERVER_VARS [HTTP_REFERER];
$main->addfield ( 'site_path', $patch );

 //���� ������ �����
  if  (isset($_GET['exit'])) {

      //$_SESSION['vendor']=null;
      //$_SESSION['user']=null;
      $_SESSION = array ();
      setcookie("e_mail",'');
      setcookie("password",'');
      unset($_COOKIE['e_mail']);
      unset($_COOKIE['password']);
      //var_dump($_SESSION);
      header ( "location: http://" . $_SERVER['HTTP_HOST'] );
  };

// echotree($main);
  if ($_GET['forgot']>0) {
       unset($main);
       $main = new outTree($forgot_FILENAME);
  }
//���� ������ "�����' � ����� �����, ������
  if (isset ( $_POST ['log_in'] )) {
        //����������
        $r = new Select ( $db, 'select * from users where email="' . htmlspecialchars ( addslashes ( $_POST ['email'] ) ) . '" and pass="' . md5 ( $_POST ['password'] ) . '"' );
        if ($r->next_row ()) {
                $_SESSION = array ();
                $_SESSION ['user'] = $r->result ( 'id' );
                

                //���� ���� ����� "��������� ����"
                if (isset ( $_POST ['save-me'] )) {
                        $username = "" . addslashes ( $_POST ["email"] ) . "";
                        $passw = "" . addslashes ( $_POST ["password"] ) . "";
                        $pasw = md5 ( $passw );
                        setcookie ( 'e_mail', $username, time () + 864000 );
                        setcookie ( 'password', $pasw, time () + 864000 );
                } ;
                header ( "location: http://" . $_SERVER['HTTP_HOST']);
               
        } else {
                //��������� ��������� ���������
                foreach ( $_POST as $pkey => $val ) {
                        if ($_POST [$pkey] != '' ) {
                                $par .= '/' . $pkey . '/' . htmlspecialchars ( strip_tags ( stripslashes ( urldecode ( $val ) ) ) ,null, "windows-1251");
                        };
                };
               unset($main);
               $main = new outTree ( $FILENAME );
               foreach ( $_POST as $pkey => $val ) {
                        if (($pkey =='email') or ($pkey =='password') )
                                $main->addField ( $pkey, urldecode ( $val ) );
               };
               $message = '����� ��� ������ ������ �� ���������!';
               $main->addField ( 'message', '������! ' . $message );

        };

//���� ������ "�����������"
} else if (isset ( $_POST ['register'] ))  {

        //����������
        $err = '';
        if (strip_tags ( addslashes ( $_POST ['email'] ) ) == '' or strip_tags ( addslashes ( $_POST ['pass1'] ) ) =='' or strip_tags ( addslashes ( $_POST ['pass2'] ) ) == '')
                $err = 1;
        if (! preg_match ( "([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)", $_POST ['email'] ) and $_POST ['email'] != "") {
                $err = 2;
        };
        if ($_POST['pass1'] !== $_POST['pass2']) $err=4;

        $r = new Select ( $db, 'select * from users where email="' . strip_tags ( addslashes ( $_POST ['email'] ) ) . '"' );
        if ($r->num_rows > 0)
                $err = 3;
        //������
        if ($err == '') {

              foreach ( $_POST as $key => $value)
                $$key= htmlspecialchars ( addslashes ($value),null, "windows-1251");


              $r1 = new Select ( $db, "insert into users (pass,pass_text,name,sort,fio,act_category,inn,link,image1,about,tel,adress,email,is_chairman,surname,secname,date,id_company,id_city)
                        values('".md5($pass1)."','$pass1','$name','1','$fio','$act_category','$inn','$link','$image1','$about','$tel','$adress','$email','0','','',".time().",'',$city)");
              //echo  "insert into users (pass,pass_text,name,sort,fio,act_category,inn,link,image1,about,tel,adress,email,is_chairman,surname,secname,date,id_company,id_city)
               //         values('".md5($pass1)."','$pass1','$name','1','$fio','$act_category','$inn','$link','$image1','$about','$tel','$adress','$email','0','','',".time().",'',$city)";

              //$main->addField ( 'log', '' );
              $_SESSION ['vendor'] = mysql_insert_id ();
              //������ �� ����
              $letter = "������� �� ����������� �� ����� " . $GLOBALS ['mainOutTree']->SERVER_NAME."
��� �����: ".$_POST ['email']. "\r\n������: ".$_POST ['password'];
              $mail = &newViaSMTP('mail_register');
              $mail->Subject = "����������� �� ����� " . $GLOBALS ['mainOutTree']->SERVER_NAME;
              $subm = sendViaSMTP($mail,$letter,false);

               header ( "location: /lk" );
        } else {

                unset($main);
                $main = new outTree ( $reg_FILENAME );

                switch ($err) {
                  case '1' :
                          $message = "�� �� ����� E-mail ��� ������";
                          break;
                  case '2' :
                          $message = "������� ���������� E-mail �����!";
                          break;
                  case '3' :
                          $message = '����� E-mail ��� ����������!';
                          break;
                  case '4' :
                          $message = '������ �� ���������!';
                          break;
                };
                if ($err>0) $main->addField ( 'message', '������! ' . $message );

               
                foreach ( $_POST as $pkey => $val )
                    $main->addField ( $pkey, urldecode ( $val ) );

                $r = new Select ( $db, 'select * from site_pages where id =5' );
                if ($r->next_row() > 0) {
                       $main->addField('license',strip_tags($r->result('content')));
                };

        };
        //�������������� ������
} else if  ($_GET ['register'] >0) {
    unset($main);
    $main = new outTree ( $reg_FILENAME );
    
     
     $r = new Select ( $db, 'select * from site_pages where id =5' );
     if ($r->next_row() > 0) {
         $main->addField('license',strip_tags($r->result('content')));
     };

} else if (isset ( $_POST ['repair'] )) {
        unset($main);
        $main = new outTree ( $forgot_FILENAME );
        //����������
        if (! preg_match ( "/^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$/", htmlspecialchars ( stripslashes ( $_POST ['email'] ) ) ) and htmlspecialchars ( stripslashes ( $_POST ['email'] ) ) != "")
             $main->addField ( 'message', '������� ���������� E-mail �����!' );
        else {
             if ( $_POST ['email'] !=='') {
              $r = new Select ( $db, 'select * from users where email="' . strip_tags ( addslashes ( $_POST ['email'] ) ) . '"' );
              $r->next_row ();
              if ($r->num_rows == 1) {

                 $letter = "��� ������ : " . $r->result ( 'pass_text' );
                 $mail = &newViaSMTP('mail_register');
                 $mail->Subject = '�������������� ������ �� ����� ' . $GLOBALS ['mainOutTree']->SERVER_NAME;
                 $subm = sendViaSMTP($mail,$letter,false);

                 if ($subm>0) {
                         unset($main);
                         $main = new outTree ($FILENAME );
                         $main->addField ( 'message', '������ c ������� ���������� �� ��� E-mail.' );
                   }
                   else $main->addField ( 'message', '������ �������� ������.' );

              } else $main->addField ( 'message','��������� E-mail �� ��������������� �� ����� �����.');
             };
       }

       foreach ( $_POST as $pkey => $val ) {
                if (($pkey =='email') or ($pkey =='password') )
                        $main->addField ( $pkey, urldecode ( $val ) );
       };
};

if (isset ( $main )) {
        $site->addField ( $GLOBALS ['currentSection'], $main );
        unset ( $main );
}

?>