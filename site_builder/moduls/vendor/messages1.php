<?php

include ('config.php');

$FILENAME = $front_html_path . 'auth.html';
$reg_FILENAME = $front_html_path . 'reg.html';
$forgot_FILENAME = $front_html_path.'forgot.html';

require_once($inc_path."/phpmailer/func.mailViaSMTP.php");
//$main = new outTree ( $FILENAME );


 unset($main);
$patch = $HTTP_SERVER_VARS [HTTP_REFERER];
//$main->addfield ( 'site_path', $patch );

//var_dump($_POST);
//если нажали "Войти' и ввели логин, пароль
if (isset ( $_POST ['log_in'] )) {
        //проверочки
        $r = new Select ( $db, 'select * from vendor where email="' . htmlspecialchars ( addslashes ( $_POST ['e_mail'] ) ) . '" and pass="' . md5 ( $_POST ['password'] ) . '"' );
        if ($r->next_row ()) {
                $_SESSION = array ();
                unset($_SESSION ['user']);
                $_SESSION ['vendor'] = $r->result ( 'id' );
               // var_dump($_SESSION);
                header ( "location: /lk");
        } else {
                //сохраняем введенные параметры
                foreach ( $_POST as $pkey => $val ) {
                        if ($_POST [$pkey] != '' ) {
                                $par .= '/' . $pkey . '/' . htmlspecialchars ( strip_tags ( stripslashes ( urldecode ( $val ) ) ) );
                        };
                };
        header ( "location: /vendor_auth/formid/1/register/5$par$k" );
        };

//если нажали "Регистрация"
} else if (isset ( $_POST ['register'] )) {
        //проверочки
        $err = '';
        if (strip_tags ( addslashes ( $_POST ['e_mail'] ) ) == '' or strip_tags ( addslashes ( $_POST ['password'] ) ) == '')
                $err = 1;
        if (! preg_match ( "([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)", $_POST ['e_mail'] ) and $_POST ['e_mail'] != "") {
                $err = 2;
        };
        $r = new Select ( $db, 'select * from vendor where email="' . strip_tags ( addslashes ( $_POST ['e_mail'] ) ) . '"' );
        if ($r->num_rows > 0)
                $err = 3;

        //удачно
        if ($err == '') {

              foreach ( $_POST as $key => $value)
                $$key= htmlspecialchars ( addslashes ($value));

              $r1 = new Select ( $db, "insert into $GLOBALS[table_name] (pass,pass_text,name,sort,fio,act_category,inn,link,image1,about,tel,adress,email)
                          values('".md5($password)."','$password','$name','1','$fio','$act_category','$inn','$link','$image1','$about','$tel','$adress','$e_mail')");

              //$main->addField ( 'log', '' );
              $_SESSION ['vendor'] = mysql_insert_id ();
              //письмо на мыло
              $letter = "Спасибо за регистрацию на сайте " . $GLOBALS ['mainOutTree']->SERVER_NAME."
Ваш Логин: ".$_POST ['e_mail']. "\r\nПароль: ".$_POST ['password'];
              $mail = &newViaSMTP('mail_register');
              $mail->Subject = "Регистрация на сайте " . $GLOBALS ['mainOutTree']->SERVER_NAME;
              $subm = sendViaSMTP($mail,$letter,false);

              if (strpos ( $patch, 'vendor_auth' ) == false)
                        header ( "location: $patch" );
              else
                        header ( "location: /" );
        } else {

                $main = new outTree ( $reg_FILENAME );
                switch ($err) {
                  case '1' :
                          $message = "Вы не ввели E-mail или пароль";
                          break;
                  case '2' :
                          $message = "Введите корректный E-mail адрес!";
                          break;
                  case '3' :
                          $message = 'Такой E-mail уже существует!';
                          break;
                  };

                if ($err>0) $main->addField ( 'message', 'Ошибка! ' . $message );
                foreach ( $_POST as $pkey => $val )
                    $main->addField ( $pkey, urldecode ( $val ) );

        };

//восстановление пароля
} else if (isset ( $_POST ['repair'] )) {
        $main = new outTree ( $forgot_FILENAME );
        //проверочки
        if (! preg_match ( "/^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$/", htmlspecialchars ( stripslashes ( $_POST ['e_mail'] ) ) ) and htmlspecialchars ( stripslashes ( $_POST ['e_mail'] ) ) != "")
             $main->addField ( 'message', 'Введите корректный E-mail адрес!' );
        else {
              $r = new Select ( $db, 'select * from vendor where email="' . strip_tags ( addslashes ( $_POST ['e_mail'] ) ) . '"' );
              $r->next_row ();
              if ($r->num_rows == 1) {

                 $letter = "Ваш пароль : " . $r->result ( 'pass_text' );
                 $mail = &newViaSMTP('mail_register');
                 $mail->Subject = 'Восстановление пароля на сайте ' . $GLOBALS ['mainOutTree']->SERVER_NAME;
                 $subm = sendViaSMTP($mail,$letter,false);

                 if ($subm>0) $main->addField ( 'message', 'Письмо c паролем отправлено на Ваш E-mail.' );
                   else $main->addField ( 'message', 'Ошибка отправки письма.' );

              } else $main->addField ( 'message','Указанный E-mail не зарегестрирован на нашем сайте.');
       }

       foreach ( $_GET as $pkey => $val ) {
                if (($pkey =='e_mail') or ($pkey =='password') )
                        $main->addField ( $pkey, urldecode ( $val ) );
       };

//регистрация  в случае косяков
} else if (isset ( $_GET ['register'] )) {

        switch ($_GET ['register']) {
                case '1' :
                        $message = "Вы не ввели логин или пароль";
                        break;
                case '2' :
                        $message = "Введите корректный E-mail адрес!";
                        break;
                case '3' :
                        $message = 'Такой email уже существует!';
                        break;
                case '4' :
                        $message = 'Указанный e-mail не зарегестрирован на нашем сайте.';
                        break;
                case '5' :
                        $message = 'Логин или пароль введен не правильно!';
        };

        unset($main);
        $main = new outTree ( $FILENAME );

         foreach ( $_GET as $pkey => $val ) {
                if (($pkey =='e_mail') or ($pkey =='password') )
                        $main->addField ( $pkey, urldecode ( $val ) );
         };
       // echotree($main);
        $main->addField ( 'message', 'Ошибка! ' . $message );
       // $main->addField ( 'formid', '1' );

} else {
    unset ( $main );
    $main = new outTree ( $reg_FILENAME );
    $main->addField ( 'formid', '1' );
};

if (isset ( $main )) {
        $site->addField ( $GLOBALS ['currentSection'], &$main );
        unset ( $main );
}
;

?>