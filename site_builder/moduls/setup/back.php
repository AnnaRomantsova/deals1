<?

/** 
 * @package BACK
 */


 include($_SERVER['DOCUMENT_ROOT'].'/setup.php');
 session_start();

 if ($_SESSION['valid_user']=='admin')  {
  	
  	
     include($inc_path.'/db_conect.php');
     include($inc_path."/func.back.php");
     
     $main_FILENAME= 'back/setup/back.html';
     $main = new outTree();
     
 
     if ( isset($_POST['save']) ) {
     	
     	 foreach ( $_POST as $key => $value)
         	$db->query('update '.$table_setup.' set value="'.addslashes($value).'" where var="'.$key.'"');
         	
         reload();
           
     }
    
     
     foreach ($VARS as $key => $value) {
     	$sub =  new outTree();
     	$r = new Select($db,'select * from '.$table_setup.' where var="'.$key.'"');
    	if ($r->next_row()) 
     		$r->addFields($sub,$ar = array('value'));

     	else {
     		$ar=array('var'=>$key);
     		if ('FCKeditor'==$key) 
     			$sub->addField('value',$ar['value'] = 1);

			$db->insert($table_setup,$ar);
     	}	

   		$sub->addField('var',$key);
   		$sub->addField('caption',$value);

   		$main->addField(in_array($key,array('FCKeditor')) ? $key : 'sub', &$sub);

     	$r->unset_();
     	unset($sub);
     }
     
     out::_echo($main,$main_FILENAME);

 }
 else header('Location: '.$auth_path);
?>