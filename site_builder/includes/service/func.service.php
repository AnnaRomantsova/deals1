<?php
/**
 * служебные функции
 * @package ALL
 * @author Milena Eremeeva (fenyx@ya.ru)
 * @version 3.01 - 22.10.2007 16:40
 *
 * .01 добавлена функция addFieldIMG
 *
 */

include('class.brunch.php');

 /**
 * удаляет поля из дерева
 * @param outTree $outTree дерево
 * @param array $fields массив имен полей, которые удаляем
 */
 function removeFields(&$outTree,&$fields) {
         $cnt = count($fields);
    for ( $i = 0; $i < $cnt; $i++ )
       unset($outTree->$fields[$i]);
 }

 /**
  * добавляет поле типа FILE в дерево
  *
  * @param outTree $ot дерево
  * @param string $fieldF имя поля
  * @param string $file_name путь к файлу от корня сайта
  */
 function addFieldFILE(&$ot, $fieldF, $file_name = '') {
  global $document_root;
  if ($file_name && is_readable( $document_root.( $file=rawurldecode($file_name) ) )) {
            $tmp = new outTree();
            $tmp->addField('size',round(filesize($document_root.$file)/1024,2));
            $tmp->addField('type',strtolower(substr(strrchr($file,'.'),1)));
            $tmp->addField('href', $file );
            $ot->addField( $fieldF, $tmp);
  }
  clearstatcache();
 }

 /**
  * добавляет поле типа IMG в дерево
  *
  * @param outTree $ot дерево
  * @param string $fieldI имя поля
  * @param string $file_name путь к файлу от корня сайта
  */
 function addFieldIMG(&$ot, $fieldI, $file_name = '') {
  global $document_root;
  $isize = @getImageSize($document_root.($file = $path.rawurldecode($file_name))  );
  if ($isize) {
    $tmp = new outTree();
    $tmp->addField('w',$isize[0]);
    $tmp->addField('h',$isize[1]);
    $tmp->addField('src', $file );
    $ot->addField( $fieldI, $tmp);
  }
  else
         $ot->addField('not_'.$fieldI,'');
  clearstatcache();
 }

 /**
  * расслешивает $_str и заменяет в ней все теги на entity<br>
  * т.е. делает безопасной строчку для вывода в html-код
  *
  * @param string $_str
  * @return string
  */
 function textFormat($_str) {
         return htmlspecialchars(stripslashes($_str),null, "windows-1251");
 }

 /**
  * расслешивает $_str
  *
  * @param string $_str
  * @return string
  */
 function htmlFormat($_str) {
         return stripslashes($_str);
 }

 /**
  * получает js-код для записи ссылки с емаилом
  * <code>
  * <a href="mailto:$_email">
  * </code>
  *
  * @param string $_email
  * @return string
  */
 function get_script_a_mail($_email) {
   if (!$_email) return '';
   $ex = explode('@',$_email);
   return '<script type="text/javascript"><!--
     name = "'.$ex[0].'";
     am = "@";
     domain = "'.$ex[1].'";
     document.write(\'<a href="mailto:\'+name+am+domain+\'">\');
    // --></script>';
 }

  /**
  * получает js-код для записи емаила
  *
  * @param string $_email
  * @return string
  */
 function get_script_mail($_email) {
         if (!$_email) return '';
    $ex = explode('@',$_email);
    return '<script type="text/javascript"><!--
     name = "'.$ex[0].'";
     am = "@";
     domain = "'.$ex[1].'";
     document.write(name+am+domain);
    // --></script>';
 }

 /**
  * @deprecated печатает js-скрипт для редиректа на страницу $href
  *
  * @param string $href
  */
 function link_href($href) {
         echo '<script type="text/javascript">
              <!-- //
              location.href = "'.$href.'";
              // -->
          </script>';
    exit;
 }

 /**
  * @deprecated печатает js-скрипт для редиректа на страницу /error404
  */
 function link_error() {
         link_href('/error404');
 }

 /**
  * распечатывает дерево для отладки и останавливает скрипт
  * @param outTree $main дерево
  */
 function echoTree(&$main) {
         outTree_::echoOutTree($main);
         exit;
 }

 /**
  * @deprecated печатает js-скрипт для редиректа на страницу $action с передачей переменных POST
  *
  * @param string $action
  * @param array $postvar ассоц.массив переменных (имя => значение) для передачи в POST
  */
 function reload($action = '?', $postvar = array() )  {
     echo '<html><body><form method="post" action="'.$action.'" id="reload">';
     foreach ( $postvar as $key => $value)
        echo '<input type="hidden" name="'.$key.'" value="'.textFormat($value).'">';
     echo '
<input type="hidden" name="reload_" value="reload">
</form>
<script type="text/javascript">document.getElementById("reload").submit();</script></body></html>';
     exit;
 }


 /**
  * печатает HTML-документ с js-скриптом с телом $str
  *
  * @param string $str
  */
 function echoJS($str)  {
     echo '<html><body>
<script type="text/javascript">
'.$str.'
</script></body></html>';
     exit;
 }

 /**
  * печатает HTML-документ с js-скриптом с вызовом js-функции alert
  *
  * @param string $str что выводить алертом
  */
 function alert($str)  {
          echoJS('alert("'.addslashes($str).'");');
 }


 /**
  * инициализирует поле с типом окончания русского языка
  * например 1 сайт, 2 сайта, 5 сайтов
  *
  * @param outTree $ot
  * @param string $field имя поля
  * @param int $count число
  */
 function getCountEnd(&$ot,$field,$count) {
         $val = 5;
         $c = round($count)%10;
         if (1 == $c)
                 $val = 1;
        elseif ($c && ($c < 5))
                 $val = 2;
         $ot->addField($field,$val);
 }


 /**
  * Делит на заданое количество групп массив outTree
  *
  * @param array $sub массив outTree
  * @param int $cg колличество групп
  * @param string $field поле разделитель
  */
 function separateGroup(&$sub,$cg,$field = 'separator') {
           $c =  count($sub);
           if ($c > 1) {
                    $separators =  array_fill(0,$cg,0);
                    for ($i = 0; $i < $c; $i++)
                            $separators[$i%$cg]++;

                    $separator = -1;
                    for ($i=0;$i<$cg;$i++) {
                            if ($separators[$i]) {
                                $separator+=$separators[$i];
                                if ($separator < ($c-1))
                                        $sub[$separator]->addField($field,'');
                            }
                    }
           }
 }

 /**
  * редирект на $uri с остановкой скрипта
  *
  * @param string $uri
  */
 function go($uri) {
         header('Location: '.$uri);
        exit;
 }


function ru2Lat($string) {

        $rus = array('ё','ж','ц','ч','ш','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я');
        $lat = array('yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA');
        $string = str_replace($rus,$lat,$string);
        $string = strtr($string,
            "АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ",
            "ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");

        return($string);
}


?>
