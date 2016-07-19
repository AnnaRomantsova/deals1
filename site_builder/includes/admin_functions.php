<?

 /**
  * @package BACK
  */

 include_once('db_conect.php');
 //include($document_root."/FCKeditor/fckeditor.php");
 //include_once($inc_path.'/func.front.php');

 /**
  * загружает FCKeditor<br>
  * или текстарею в зависимости от значения $GLOBALS['FCKeditor']
  * @uses FCKeditor
  * @uses $GLOBALS['FCKeditor']
  */
 function loadFCKeditor($_var,$_val,$w='100%',$h='400px',$toolbar='Default',$config=array()) {
       // echo "1";
        echo '
          <script type="text/javascript" src="/ckeditor_full/ckeditor.js"></script>
          <textarea name="'.$_var.'" id="about" cols="45" rows="10">'.$_val.'</textarea>
          <script type="text/javascript">
              CKEDITOR.replace( "'.$_var.'");
          </script>';
          /*
                  $ot = new outTree();
                  $ot->addField('var',$_var);
                  $ot->addField('value',$_val);
                  out::_echo($ot, 'back/ckeditor.html');
          */
 }

/**
 * загружает FCKeditor c проинициализированным значением поля
 * @param Select  $r    запрос
 * @param string  $field  имя поля
 */
function addFCKeditor(&$r,$field) {
        loadFCKeditor($field,htmlFormat($r->result($field)));
}

 /**
  * @deprecated поднимает запись
  */
 function record_up($table_name,$_up,$where='',$field='sort')
  { global $db;
    if ($where>'') $where=' and '.$where;
    $result=$db->query('select id,'.$field.' from '.$table_name.' where '.$field.'<'.$_up.''.$where.' order by '.$field.' desc limit 1');
    if ($db->num_rows()>0)
     {
       $db->query('update '.$table_name.' set '.$field.'="'.$db->result($field,0,$result).'" where '.$field.'="'.$_up.'"'.$where);
       $db->query('update '.$table_name.' set '.$field.'="'.$_up.'" where id="'.$db->result('id',0,$result).'"');
     }
  }

 /**
  * @deprecated опускает запись
  */
 function record_down($table_name,$_down,$where='',$field='sort')
  { global $db;
    if ($where>'') $where=' and '.$where;
    $result=$db->query('select id,'.$field.' from '.$table_name.' where '.$field.'>'.$_down.''.$where.' order by '.$field.' limit 1');
    if ($db->num_rows()>0)
     {
       $db->query('update '.$table_name.' set '.$field.'="'.$db->result($field,0,$result).'" where '.$field.'="'.$_down.'"'.$where);
       $db->query('update '.$table_name.' set '.$field.'="'.$_down.'" where id="'.$db->result('id',0,$result).'"');
     }
  }

  /**
  * @deprecated Подсчет уровня - на всякий случай
  */
 function get_level($table_name,$_id)
  { global $db;
    $db->query('select parent from '.$table_name.' where id="'.$_id.'"');
    return ( $db->result(0,0) ? 1+get_level($table_name,$db->result(0,0)) : 0 ) ;
  }


 /**
  * сохраняет файл
  */
 function save_file($_file,$path,$name)
  { if (isset($_file['tmp_name']) && ($_file['tmp_name']>'') && ($path>'') && ($name>''))
     { $document_root=$GLOBALS['document_root'];

       if (file_exists($document_root.$path.$name))
        { $i=0;
          $dot_pos=strrpos($name,".");
          $fl_name=substr($name,0,$dot_pos);
          $fl_ext=substr($name,$dot_pos);
          $name=$fl_name."_0".$fl_ext;
          while (file_exists($document_root.$path.$name))
           { $i++;
             $name=$fl_name."_".$i.$fl_ext;
           }
        }

       if (copy($_file['tmp_name'],$document_root.$path.$name))
          return $path.rawurlencode($name);
       else
          return false;
     }
    else
       return false;
  }

 /**
  * подготавливает для сохранения файл
  */
 function prepare_file($table_name,$id,$field,$_file,$path,$name_pattern='',$keep_name=0)
  { global $db;
    if (($table_name>'') && ($id>0) && ($field>'') && (is_array($_file)) && ($path>''))
     { $document_root=$GLOBALS['document_root'];
       $result=$db->query('select '.$field.' from '.$table_name.' where id="'.$id.'"');
       $old_file=$db->result(0,0,$result);

       if (isset($_file) && ($_file['size']>0))
        {
          if (1 == $keep_name)
           { @unlink($document_root.rawurldecode($old_file));
             return save_file($_file,$path,$_file['name']);
           }
          else
           {
                    $file_ver=0;
             if ((trim($old_file)>'') && file_exists($document_root.rawurldecode($old_file)))
              { $verpos=strrpos($old_file,'_v')+2;
                $file_ver=intval(substr($old_file,$verpos,strrpos($old_file,'.')-$verpos));
                @unlink($document_root.rawurldecode($old_file));
              }
             $file_ver++;
             $new_name=str_replace('%ver%',$file_ver,$name_pattern);
             return save_file($_file,$path,$new_name);
           }
        }
     }
    return '';
  }


 /**
  * @deprecated на всякий случай
  */
 function delete_file($table_name,$id,$field)
  { global $db,$document_root;
    $r =  new Select($db,'select '.$field.' from '.$table_name.' where id="'.$id.'"');
    @unlink( $document_root.rawurldecode($r->result(0,0)) );
    $r->unset_();
  }

?>
