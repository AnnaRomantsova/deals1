<?
/** 
 * @package BACK
 */


 session_start();

 if ($_SESSION['valid_user']=='admin')  {

	include($_SERVER['DOCUMENT_ROOT'].'/setup.php');
    include($inc_path.'/db_conect.php');
	include('config.php');
	
	include($inc_path.'/service/class.output.php');
    include($inc_path."/service/func.service.php");
 	
    $main_FILENAME= 'back/feedback/back.html';
    $main = new outTree();

    if ( isset($_POST['save']) ) {

     	 foreach ( $_POST as $key => $value)
     	 	$$key=$value;
     	 
     	//	сохранение smtp настроек
   	 	 $db->query('update '.$table_setup.' set value="'.addslashes("mail=$isMail;\r\nauth=".((isset($auth) && (1==$auth)) ? 'true' : 'false' ).";\r\nHost=$Host;\r\nPort=$Port;\r\nUsername=$Username;\r\nPassword=$Password").'" where var="SMTP_settings"');
		 	
     	//	сохранение настроек почты для разделов
	     foreach ($CONTACTS as $key => $value) 
	   	 	 $db->query('update '.$table_setup.' set value="'.addslashes("From=".$_POST[$key.'_From'].";\r\nFromName=".$_POST[$key.'_FromName'].";\r\nrecipient=".$_POST[$key.'_recipient'].";\r\nSubject=".$_POST[$key.'_Subject']).'" where var="'.$key.'"');
         	
         reload();
           
    }
     
    
	//	вывод smtp настроек
	$r_SMTP = new Select($db,'select * from '.$table_setup.' where var="SMTP_settings"');
	if ($r_SMTP->next_row()) {
			$ex=explode(";\r\n",$r_SMTP->result('value'));
		    $c_ex= count($ex);
		    for ($i=0;$i<$c_ex;$i++) {
		    	$ex2=explode('=',$ex[$i]);
		    	//echo $ex2[0].' = '.$ex2[1].'<br>';
		    	$main->addField($ex2[0],textFormat($ex2[1]));
		    }
		    
	}
	else
   		$db->insert($table_setup,$ar=array('var'=>'SMTP_settings','value'=>"mail=true;\r\nauth=false;\r\nHost=;\r\nPort=;\r\nUsername=;\r\nPassword="));

   	if (!isset($main->mail) || ('true' == $main->mail) ) {
   		$main->addField('isMail','');
   		$main->addField('disp_smtp',''); 
   	}
   	else 
   		$main->addField('isSMTP','');
   		
   	if (isset($main->auth) && ('false' == $main->auth) ) 
   		unset($main->auth);

   	//	вывод настроек почты для разделов
    foreach ($CONTACTS as $key => $c) {
     	$sub =  new outTree();
     	$paramValues=array('From'=>'','FromName'=>'','recipient'=>'','Subject'=>'');
     	
     	$r = new Select($db,'select * from '.$table_setup.' where var="'.$key.'"');
     	if ($r->next_row()) {
		    $ex=explode(";\r\n",$r->result('value'));
		    $c_ex= count($ex);
		    for ($i=0;$i<$c_ex;$i++) {
		    	$ex2=explode('=',$ex[$i]);
		    	//echo $ex2[0].' = '.$ex2[1].'<br>';
		    	$paramValues[$ex2[0]]=$ex2[1];
		    }
     	}
     	else {
     		$db->insert($table_setup,$ar=array('var'=>$key,'value'=>"From=;\r\nFromName=;\r\nrecipient=;\r\nSubject="));
     	}
     	
     	foreach ($c['param'] as $p) 
     		$sub->addField($p,textFormat($paramValues[$p]));
     	
     	$sub->addField('var',$key);
     	$sub->addField('caption',$c['caption']);
     	$main->addField('sub',&$sub);
     	
     	$r->unset_();
     	unset($sub);
    }
     
	out::_echo($main,$main_FILENAME);

 }
 else header('Location: '.$auth_path);

?>