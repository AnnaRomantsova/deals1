<?
/**
 * @package BACK
 */


 @include('config.php');
 @include_once($inc_path."/template_read.php");
 @include_once($inc_path."/db_conect.php");
 @include_once($inc_path."/admin_functions.php");

 session_start();

 //echo $back_html_path;
 $html_FILENAME=$back_html_path.'back_page_editor.html';
 $sub_FILENAME=$back_html_path.'back_page_editor_opt.html';
 $html1_FILENAME=$back_html_path.'back_page_editor_1.html';

 while (list($key,$val)=each($_GET))
  { if ($key=='next') $next=$val;
    if ($key=='ed_page_select') $ed_page_select=$val;
  }

 while (list($key,$val)=each($_POST))
  { $$key=$val;
    //echo $key."=".$val."<br>";
  }

if ($_SESSION['valid_user']=='admin')
{

 if (isset($savepage) and ($ed_page_select>""))
  { $content=addslashes($content);
    $result=$db->query('update '.$table.' set content="'.addslashes($_POST['content']).'" where id="'.$ed_page_select.'"');
    echo "
<html>
<head>
<title></title>
<LINK rel='stylesheet' type='text/css' href='/_css/back.css'>
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=windows-1251'>
</head>
<body>
<br><center><b style='font-size:12px;'>—траница сохранена</b><br><br>
≈сли через несколько секунду не произайдет автоматичекого возврата в редактор нажмите <a href='?'>сюда</a></center>
<SCRIPT language='JavaScript'>setTimeout('location.href=\"?ed_page_select=$ed_page_select&next=1\"',1000);</SCRIPT>
</body>
</html>
";
        exit;
  }

 if (isset($next)and($ed_page_select>""))
  { $html1_FILENAME=$html_FILENAME;
    $result=$db->query("select * from $table where id='$ed_page_select'");
  }

 $allresult=$db->query("select * from $table where id<'10' order by sort");
 $num=$db->num_rows($allresult);
 $str=template_read($html1_FILENAME,$counter1);
 //echo $str;
 while ($str)
  { if ($str=='content')
     {
             //echo stripslashes(@$db->result($str,0,$result));
             loadFCKeditor( $str, stripslashes(@$db->result($str,0,$result)) );
     }
    else
    if ($str=='sub')
     { for ($i=0; $i<$num; $i++)
        { $counter2=0;
          $strc=template_read($sub_FILENAME,$counter2);
          while ($strc)
           { if ($strc=='selected')
              { if ((isset($ed_page_select))&&(@$db->result('id',$i,$allresult)==$ed_page_select)) echo "selected";
              }
             echo stripslashes(@$db->result($strc,$i,$allresult));
             $strc=template_read($sub_FILENAME,$counter2);
           }
        }
     }
    else echo stripslashes(@$db->result($str,0,$result));
    //$html1_FILENAME = str_replace('//',''
    //$html1_FILENAME = '/www/botmika/users/botmika-issjru/www/htdocs/shablons/back/page_editor/oldShablons/back_page_editor.html';
    $str=template_read($html1_FILENAME,$counter1);
   // echo $html1_FILENAME;
  }
}
else header("Location: $auth_path");
?>
