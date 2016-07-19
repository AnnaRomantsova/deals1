<?php

/**
 * @package ALL
 *
 * Классы шаблонизатора
 *
 * @author Max Kudryavcev (icq 349-870-107)
 * @author изменяла Milena Eremeeva (fenyx@ya.ru)
 * @version 1.10 - 21.09.2007 14:30
 *
 * .05 дописан служебный класс outTree_ <br>
 * .05 появилась возможность инициализировать "сквозные" переменные (видны на каждом уровне дерева). <br>
 * .06 дописан метод outTree_::mergeFields - присоединяет одно поле к другому <br>
 * .07 исправлен варнинг <br>
 * .08 дописан метод outTree_::getFirst -  получить первую ветку под таким именем <br>
 * .09 дописан метод outTree_::getLast -  получить последнюю ветку под таким именем <br>
 * .10 добавлена экспериментальная возможность "отрицать поле" - нельзя использовать ! в начале названия поля <br>
 *
 */

 define('OPEN_TAG','[%');   //открывающий тэг
 define('CLOSE_TAG','%]');  //закрывающий тэг (не должен включать в себя подстроку равную открывающему тэгу)
 define('SEP','/');         //разделитель узлов дерева вывода в шаблоне
 define('UP','..');         //указатель на уровень выше

 $templates_path = $document_root.'/shablons/';


 $GLOBALS['mainOutTree'] = new outTree();

 /* дерево параметров для вывода
  * каждый узел может быть: либо объектом класса outTree,
  * либо массивом обектов, либо простым значением
  */
  class outTree
  { var $PAR;  // ссылка на родителя

    /**
     * @param string $template шаблон по умолчанию
     * @return outTree
     */
    function outTree($template=null) {
            if (isset($template))
                    $this->TEMPLATE = $template;
    }

   /** добавление узла дерева <br>
     * если добавляемый узел в данной ветке уже существует <br>
     * то он преобразовывается к индекированному массиву <br>
     * нельзя использовать зарезервированные имена: <br>
     * PAR <br>
     * TEMPLATE <br>
     * TEMPLATE_NOT_FILE <br>
     * ITEMTYPE <br>
     * имена, начинающиеся на !
     *
     * @param string $name имя поля шаблона
     * @param mixed $val значение поля, может быть outTree
     */
    function addField($name,$val)
     { if (is_object($val)) $val->PAR=&$this;
       if (isset($this->$name))
        {  if (!is_array($this->$name))
            {  $tmp1 = array();
                if  ( is_object($this->$name) )
                   $tmp1[] = &$this->$name;
                else
                   $tmp1[] = $this->$name;
               $this->$name = &$tmp1;
            }

           $tmp = &$this->$name;
           if  ( is_object($val) )
                $tmp[] = &$val;
           else
                $tmp[] = $val;
        }
       else
        {  if  ( is_object($val) )
             $this->$name=&$val;
           else
             $this->$name=$val;
        }
     }
  }


 class out
  {
    /* обработка и вывод шаблона из файла или строки (если $is_str=1) $template_file
     * &$root - указатель на узел дерева вывода
     */
    static public function _echo(&$root,$template_file,$parent=null,$is_str=0,$I=0,$COUNT=1)
     {
       $ext_out=$int_out='';

       if (!$is_str)
        { $fp=@file($GLOBALS['templates_path'].$template_file);
          if (!$fp)
           { echo ' <p><b> Файл '.$GLOBALS['templates_path'].$template_file.' не доступен! </b></p>';
             return false;
             //exit;
           }
          $str=implode('',$fp);
        }
       else
          $str=$template_file;
       $flag=true;
       $_exit=false;
       $evl_str='';
       while (!$_exit)
        {
          if ($flag)
           { $tag=OPEN_TAG;
             $out='ext_out';
           }
          else
           { $tag=CLOSE_TAG;
             $out='int_out';
           }

          $_pos=strpos($str,$tag);
          if (!$flag)                                          // на случай вложенности тегов
           { $xpos=strpos($str,OPEN_TAG);
             while ( (!($xpos===false)) && ($xpos<$_pos) )
              { $_pos=strpos($str,$tag,$_pos+strlen($tag));
                $xpos=strpos($str,OPEN_TAG,$xpos+strlen(OPEN_TAG));
              }
           }                                                   //============================

          if ($_pos===false)
           { $$out=$str;
             $_exit=true;
           }
          else
           { $$out=substr($str,0,$_pos);
             $flag=!$flag;
             $str=substr($str,$_pos+strlen($tag));
           }

          if ($out=='int_out')
           { //echo '!'.$$out.'!';
             if ($is_str)
                out::evl($root,$$out,$parent,$I,$COUNT);
             else
                $evl_str.=$$out;
           }
          else
           {// echo '!!!';
             if ($evl_str>'')
              { out::evl($root,$evl_str,$parent,$I,$COUNT);
                $evl_str='';
              }
             echo $$out;
           }
        }
     }

    public static function evl(&$root,$cmd,$parent=null,$I=0,$COUNT=1)
     {
       $cmd=trim($cmd);
       switch ($cmd[0]) //здесь берется первый символ команды
        { case '(' : //)
                     eval(substr($cmd,1,-1));
                     break;

          case '{' : //}
                     out::_echo($root,substr($cmd,1,-1),$parent,1,$I,$COUNT);
                     break;

          case '[' : //]
                     $template = isset($root->TEMPLATE) ? $root->TEMPLATE : substr($cmd,1,-1);
                     out::_echo($root,$template,$parent,intval(isset($root->TEMPLATE_NOT_FILE)),$I,$COUNT);
                     break;

          case '?' : // switch
                     $coms=out::parsSwitch(substr($cmd,1));
                     for ($j=0; $j<count($coms[0]); $j++)
                      { if ($root==$coms[1][$j])
                         { out::_echo($root,$coms[0][$j],$parent,1,$I,$COUNT);
                         }
                      }
                     break;
          case '.': //уровень выше
                     $cmd=explode(SEP,$cmd,2);
                     if (isset($parent->PAR))
                        out::evl($parent,$cmd[1],$parent->PAR,$I,$COUNT);
                     else
                        out::evl($parent,$cmd[1],null,$I,$COUNT);
                     break;

          case '*': //сквозной доступ
                     $cmd=explode(SEP,$cmd,2);
                     out::evl($GLOBALS['mainOutTree'],$cmd[1],$root,$I,$COUNT);
                     break;

          default  : $cmd=explode(SEP,$cmd,2);
                                   $fieldName = $cmd[0];
                                   if (('!' == $fieldName[0]) && !isset($root->{substr($fieldName,1)})) {
                                           if (count($cmd)>1)
                                                   out::evl($root->$cmd[0],$cmd[1],$root);
                                           break;
                                   }
                     if (isset($root->$cmd[0]))
                      { if (count($cmd)>1)
                         { if (is_array($root->$cmd[0]))
                            { //var_dump($root->$cmd[0]);
                              $cnt=count($root->$cmd[0]);
                              $pr=&$root->$cmd[0];
                              for ($j=0; $j<$cnt; $j++)
                               { out::evl($pr[$j],$cmd[1],$root,$j,$cnt);
                               }

//                              $c = foreach_($pr,$keys = array());
//                              for ($j=0; $j<$c; $j++)
//                                out::evl($pr[$keys[$j]],$cmd[1],&$root,$j,$c);


                            }
                           else
                              out::evl($root->$cmd[0],$cmd[1],$root);
                         }
                        else
                         { echo $root->$cmd[0];
                         }
                      }
        }
     }

    public static  function parsSwitch($str) //разбирает строку на то что в { } и за ними
     {
       $ext_out=array();
       $int_out=array();
       $open_tag='{';
       $close_tag='}';
       $flag=true;
       $_exit=false;
       $evl_str='';
       while (!$_exit)
        {
          if ($flag)
           { $tag=$open_tag;
             $out=@$ext_out;
           }
          else
           { $tag=$close_tag;
             $out=@$int_out;
           }

          $_pos=strpos($str,$tag);
          if (!$flag)                                          // на случай вложенности тегов
           { $xpos=strpos($str,$open_tag);
             while ( (!($xpos===false)) && ($xpos<$_pos) )
              { $_pos=strpos($str,$tag,$_pos+strlen($tag));
                $xpos=strpos($str,$open_tag,$xpos+strlen($open_tag));
              }
           }                                                   //============================

          if ($_pos===false)
           { if ($flag)
                $ext_out[]=$str;
             else
                $int_out[]=$str;
             $_exit=true;
           }
          else
           { if ($flag)
                $ext_out[]=trim(substr($str,0,$_pos));
             else
                $int_out[]=substr($str,0,$_pos);
             $flag=!$flag;
             $str=substr($str,$_pos+strlen($tag));
           }

        }
       return array($int_out,$ext_out);
     }
  }

/**
 * Служебный класс для работы с объектами outTree
 */
class outTree_ {

 /**
  * для отладки - выводит все дерево
  *
  * @param outTree $ot
  * @param string $name имя узла
  * @param int $color цвет рамки вокруг объекта
  */
 function echoOutTree($ot,$name='',$color=128) {
         if ('PAR' !== $name) {

                 if ($color < (255-20)) $color+=8;
                 $col = (dechex($color));
                echo '<div style="padding: 10px 0 10px 20px; margin: 0 1px 2px 0; clear:both;border: 1px solid #'.$col.$col.$col.'">';
                if (is_object($ot)) {
                        echo '<span style="color:#00c">'.$name.' (Object '.get_class($ot).')</span>';
                         $fields = get_object_vars($ot);
                         foreach ($fields as $key => $value) {
                                outTree_::echoOutTree($value,$key,$color);
                         }
                }
                elseif (is_array($ot)) {
                        echo '<span style="color:#0b0"><b>'.$name.'</b> (Array '.count($ot).')</span>';
                        foreach ($ot as $key => $value) {
                                outTree_::echoOutTree($value,$key,$color);
                        }
                }
                else
                        echo '<span><b>'.$name.'</b> ('.gettype($value).')</span><br clear="all" />'.$ot;
                echo '</div>';
         }
 }

 /**
  * возвращает указатель на узел в массиве по заданому ITEMTYPE
  * если не нашел - NULL
  *
  * @param outTree $_this
  * @param string $field    имя массива
  * @param string $ITEMTYPE метка узла
  * @param bool   $flag     если ложь  - возвращает номер узла
  * @return outTree
  */
 function &find(&$_this,$field,$ITEMTYPE,$flag = true) {

         if (is_array($_this->$field)) {
                 $c = count($_this->$field);
                 for ($i = 0; $i < $c; $i++)
                         if (is_object($_this->{$field}[$i]) && ($ITEMTYPE == $_this->{$field}[$i]->ITEMTYPE))
                                return  $flag ? $_this->{$field}[$i] : $i;
         }

         elseif (is_object($_this->$field) && ($ITEMTYPE == $_this->$field->ITEMTYPE))
                 return $flag ? $_this->$field : 0;
    return NULL;

 }

 /**
  * Перецепляет поле
  *
  * @param outTree $out   откуда отцеплять
  * @param outTree $in    куда цеплять
  * @param string $field имя поля
  */
 function changeParent(&$out,&$in,$field) {
         if (isset($out->$field)) {
             $in->$field = &$out->$field;
             $in->$field->PAR = &$in;
             unset ($out->$field);
         }
 }

 /**
  * присоединяет к полю $tree->$field1 поле $tree->$field2
  * @param outTree $tree дерево
  * @param string $field1 к чему присоединять
  * @param string $field2 что присоединять
  */
 function mergeFields(&$tree,$field1,$field2) {
         if (isset($tree->$field2)) {
                 if (is_array($tree->$field2)) {
                         $c = count($tree->$field2);
                         for ($i = 0; $i < $c; $i++)
                                 $tree->addField($field1,$tree->{$field2}[$i]);
                 }
                 else
                         $tree->addField($field1,$tree->$field2);
         }
 }

 /**
  * получить первый элемент в поле дерева
  *
  * @param outTree $tree дерево
  * @param string $field имя поля
  * @return outTree
  */
 function &getFirst(&$tree,$field) {
        if (is_array($tree->$field))
                return $tree->{$field}[0];

        elseif (isset($tree->$field))
                return $tree->$field;

        return null;
 }

 /**
  * получить полседний элемент в поле дерева
  *
  * @param outTree $tree дерево
  * @param string $field имя поля
  * @return outTree
  */
 function &getLast(&$tree,$field) {
        if (is_array($tree->$field))
                return $tree->{$field}[count($tree->{$field})-1];

        elseif (isset($tree->$field))
                return $tree->$field;

        return null;
 }

}

?>
