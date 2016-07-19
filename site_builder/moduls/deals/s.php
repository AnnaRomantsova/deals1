<?php

 include('config.php');
 include($inc_path.'/object.php');
 include($inc_path.'/myfunc.php');

 unset($main);
 $sh_s = $front_html_path.'s.html';
 $sh_i = $front_html_path.'orgs.html';

 include($inc_path.'/classes/class.BF.php');
 include($inc_path.'/admin_functions.php');


if ( !$_SESSION ['user']>0) header('Location: /');
 
   
$main = &addInCurrentSection($sh_s); 
$main->addField('pagename','Сделки');
$main->addField('object_name','сделку');  
$main->addField('page','deals');
$table = "deals_sections";
$table_items = "deals_items";
   

if ($_POST['id']>0) {        
                  //нажали кнопку удалить одну запись
                  if ($_POST['del']>0){
                  	       delete($main,$db,$table,$table_items);	                                              
	                       $main->addField('mode','mode_edit');
                  }
                  //нажали кнопку редактировать одну запись
                  else if ($_POST['edit']>0 && ($_POST['id']>0) ){
	                  	  edit_one($main,$db,$table);
	                  	  $main->addField('mode','mode_edit_one');
                  }
                  //нажали кнопку сохранить одну запись
                  else if ($_POST['save_submit']>0){
	                       save_edit($main,$db,$table);                    
	                       $main->addField('mode','mode_edit');
                  }
  }
  //создать новую запись
  else if ($_POST['new']>0){  	
			  		new_record($main,$db,$table);                    
			  		$main->addField('mode','mode_new');                      
  }
  //сохранить новую запись
  else if ($_POST['new_submit']>0){          
                   save_new_record($main,$db,$table);
                   $main->addField('mode','mode_edit');
  }
  else if ($_GET['i'] >0) $main->addField('mode','mode_show_one');
     else  $main->addField('mode','mode_edit');
  
  if ($main->mode=='mode_edit')                    
        edit_all($main,$db,$table);            

 ///////////Основные функции   
     
   
  function delete($main,$db,$table,$table_items){
  	   // echo $_POST['id'];
	  	$profile = new object($db,$main, $table);
	  	$profile->delete($_POST['id']);
	   	$profile->delete_by_parent($table_items,$_POST['id']);
  };   
  
  function edit_one($main,$db,$table){
  	    
	  	$fields = array( array("fname" => 'name', "rname" => 'Наименование', "type" => 'varchar' ,"length" => '255' ),
	  			array("fname" => 'id_relate', "rname" => 'Тип клиента', "type" => 'list','list_table' => 'relate' ),
	  			array("fname" => 'responsible', "rname" => 'Ответственное лицо' , "type" => 'text' ),
	  			array("fname" => 'members', "rname" => 'Участники процесса' , "type" => 'text'),
	  			 
	  	);
	  	$profile = new object($db,$main, $table);	  	
	  	$profile->add_fields($fields,$_POST['id']);
	  	$profile->edit($_POST['id'],'sub');
  	
  };
  
  function save_edit($main,$db,$table){  	
	  	$profile = new object($db,$main, $table);
	  	$profile->save_edit($_POST['id']);
  };
    
  function new_record($main,$db,$table) {  	  
	  	$fields = array( array("fname" => 'name', "rname" => 'Наименование', "type" => 'varchar' ,"length" => '255' ),
	  			array("fname" => 'id_relate', "rname" => 'Тип клиента', "type" => 'list','list_table' => 'relate' ),
	  			array("fname" => 'responsible', "rname" => 'Ответственное лицо' , "type" => 'text' ),
	  			array("fname" => 'members', "rname" => 'Участники процесса' , "type" => 'text'),
	  	
	  	);
	  	$profile = new object($db,$main, $table);
	  	$profile->add_fields($fields);
  };
  
  function save_new_record($main,$db,$table){  	
  	$profile = new object($db,$main, $table);
  	$step = get_first_step($_POST['id_relate']);
  	//echo $step;die;
  //	$_POST['id_step'] = $step;
  	$add_id=$profile->add($_POST);  	
  	
  	$data = array("parent" =>$add_id , "step_id" =>$step, "status"=>"1");
  	$profile1 = new object($db,$main, "deals_items");
  	$profile1->add($data);
  	
  };
  
  function edit_all($main,$db,$table){  	
  	$profile = new object($db,$main,$table );
  	$profile->put_data('sub');
  	$i=1;
  	$arr = array();
  	if (is_array($main->sub)) {  
  		foreach ($main->sub as $sub) { 
  			$arr[] = $sub;
  		};
  	} else { $arr[] = $main->sub; };
  	foreach ($arr as $sub) {
  		
  		$step = get_step_by_parent('deals_items',$sub->id);
  		$sub->step= $step['name'];
  		$sub->num_pp = $i;
  		$i++;
  		foreach ($sub as $key => $value) {
  			if ($key=='id_relate') $sub->relate= get_sprav_val('relate','name', $sub->id_relate);
  			if ($key=='date_begin') $sub->date_begin=make_date($sub->date_begin);
  		};
  	 };
  	
  };
  
 ?>