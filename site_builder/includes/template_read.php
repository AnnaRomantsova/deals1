<?

 /**
  * @deprecated Классы шаблонизатора, <br> используются в старых модулях системы администрирования structure, page_editor
  *
  * @package ALL
  * @author Max Kudryavcev (icq 349-870-107)
  * @author изменяла Milena Eremeeva (fenyx@ya.ru)
  */

$counter1=0;
$counter2=0;
$counter3=0;
function template_read($html_FILENAME,&$counter)
 { $_pos=0; $xpos=0;  $repl=null;
   $_exit=0;$str=null;$sstr=null;
   $_exit1=0;$xcounter=0;
   $fp=@fopen("$html_FILENAME",'r');
   if (!$fp)
    { echo " <p><strong> Файл $html_FILENAME не доступен </strong></p>";
      //exit;
    }
   else
    { //if ($counter>0)
         //echo $html_FILENAME;
         while (!feof($fp) and !$_exit1)
          { if ($_pos)
             { $sstr=substr($repl,strpos($repl,'%%',2)+2);
             }
            else $sstr=fgets($fp,999);
            $_pos=0;
            $xpos=strpos($sstr,'%%');
            $repl=stristr($sstr,'%%');
            if ($repl)
             { $_pos=strpos($repl,'%%',2);
               if ($_pos)
                { $str=substr($repl,2,$_pos-2);
                  if ($xcounter<$counter) {$xcounter++;}
                  else
                   { $_exit1=1;
                     $counter++;
                     echo substr($sstr,0,$xpos);
                     return $str;
                     break;
                   }
                }
               else
                if (!($xcounter<$counter))
                 {echo $sstr; $_pos=0; $_exit=0;}
             }
            else
             if (!($xcounter<$counter))
              { echo $sstr; $_pos=0; $_exit=0;}
          }
      if (feof($fp)) return false;
      fclose($fp);
    }
 }


/**
 *  Работа с шаблоном
 */
class Template {
  var $filename;
  var $counter;

  function Template($_filename)
   {  $this->filename = $_filename;
      $this->counter = 0;
   }

  /**
    * извлекает следующую переменную шаблона
    * @return string
  */
  function next_sub()
   {  return template_read($this->filename,$this->counter);
   }

  /**
    * сбрасывает счетчик
  */
  function reset_()
   {  $this->counter = 0;
   }

}

?>
