<?php

 include('config.php');
 include($inc_path.'/object.php');
 include($inc_path.'/myfunc.php');

 unset($main);
 //$sh_s = $front_html_path.'s.html';
 //$sh_i = $front_html_path.'orgs.html';

 include($inc_path.'/classes/class.BF.php');
 include($inc_path.'/admin_functions.php');


if ( !$_SESSION ['user']>0) header('Location: /');
//var_dump($_POST);
//echotree($site);  

switch ($site->pageid) {
	case "podbor": {$sh = $front_html_path.'podbor.html'; break;};
	case "prep": {$sh = $front_html_path.'prep.html';  break;};
	case "contrib": {$sh = $front_html_path.'contrib.html';  break;};
	case "contract": {$sh = $front_html_path.'contract.html';  break;};
}
$main = &addInCurrentSection($sh); 
$main->addField('page',$site->pageid);
switch ($site->pageid) {
	case "podbor": {$main->addField('pagename','Подборка');
                    $main->addField('object_name','Подборкb');  
                     break;};
	case "prep": {  $main->addField('pagename','Подготовка заявок');
					$main->addField('object_name','Подготовка заявок');  
					  break;};
	case "contrib": {$main->addField('pagename','Участие');
					 $main->addField('object_name','Участие');  
					  break;};
	case "contract": {$main->addField('pagename','Контракты');
					 $main->addField('object_name','Контракты');  
					 $sh = $front_html_path.'contract.html';  break;};
}



$table = "deals";
   

if ($_POST['id']>0) {        
                  //нажали кнопку удалить одну запись
                  if ($_POST['del']>0){
                  	       delete($main,$db,$table);	                                              
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
				  //инфо
				  else if ($_POST['info']>0){
					  info($main,$db,$table,$id);
					  $main->addField('mode','mode_show_one');
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


  $main->addfield('user_id',$_SESSION['user']);
 ///////////Основные функции   
     
   
  function delete($main,$db,$table){
  	   
	  	$profile = new object($db,$main, $table);
	  	$profile->delete($_POST['id']);
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

  function info($main,$db,$table){
      global $site;
  	  $profile = new object($db,$main, $table);
  	  //echo "d";
	  switch ($site->pageid) {
		  case "podbor": {
			  $fields = array(array("fname" => 'number', "rname" => '№ извещения', "type" => 'varchar', "length" => '255'),
				  array("fname" => 'sposob', "rname" => 'Способ закупки', "type" => 'varchar', 'list_table' => 'relate'),
				  array("fname" => 'sposob', "rname" => 'Тип', "type" => 'text'),
				  array("fname" => 'name_etp', "rname" => 'Наименование ЭТП', "type" => 'varchar'),
				  array("fname" => 'srok_end_podach', "rname" => 'Срок окончания подачи заявок', "type" => 'varchar', 'list_table' => 'relate'),
				  array("fname" => 'customer', "rname" => 'Наименование заказчика', "type" => 'varchar'),
				  array("fname" => 'inn_customer', "rname" => 'ИНН заказчика', "type" => 'varchar'),
				  array("fname" => 'geographi', "rname" => 'География', "type" => 'varchar'),
				  array("fname" => 'predmet', "rname" => 'Предмет закупки', "type" => 'varchar', 'list_table' => 'relate'),
				  array("fname" => 'price_lot', "rname" => 'НМЦК', "type" => 'varchar'),
			  );
			  break;    }
		  case "prep": { $fields = array(array("fname" => 'number', "rname" => '№ извещения', "type" => 'varchar', "length" => '255'),
			  array("fname" => 'sposob', "rname" => 'Способ закупки', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'sposob', "rname" => 'Тип', "type" => 'text'),
			  array("fname" => 'name_etp', "rname" => 'Наименование ЭТП', "type" => 'varchar'),
			  array("fname" => 'srok_end_podach', "rname" => 'Срок окончания подачи заявок', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'customer', "rname" => 'Наименование заказчика', "type" => 'varchar'),
			  array("fname" => 'inn_customer', "rname" => 'ИНН заказчика', "type" => 'varchar'),
			  array("fname" => 'geographi', "rname" => 'География', "type" => 'varchar'),
			  array("fname" => 'id_relate', "rname" => 'Предмет закупки', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'price_lot', "rname" => 'НМЦК', "type" => 'varchar'),
		  );  break;};
		  case "contrib": { $fields = array(array("fname" => 'contract_number', "rname" => '№ контракта', "type" => 'varchar', "length" => '255'),
			  array("fname" => 'sposob', "rname" => 'Способ закупки', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'type', "rname" => 'Тип', "type" => 'varchar'),
			  array("fname" => 'name_etp', "rname" => 'Наименование ЭТП', "type" => 'varchar'),
			  array("fname" => 'srok_end_podach', "rname" => 'Срок окончания подачи заявок', "type" => 'varchar', ),
			  array("fname" => 'customer', "rname" => 'Наименование заказчика', "type" => 'varchar'),
			  array("fname" => 'inn_customer', "rname" => 'ИНН заказчика', "type" => 'varchar'),
			  array("fname" => 'geographi', "rname" => 'География', "type" => 'varchar'),
			  array("fname" => 'id_relate', "rname" => 'Предмет закупки', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'price_lot', "rname" => 'НМЦК', "type" => 'varchar'),
			  array("fname" => 'price_winner', "rname" => 'Цена победителя', "type" => 'varchar'),
			  array("fname" => 'inn_winner', "rname" => 'ИНН победителя', "type" => 'varchar'),
			  array("fname" => 'name_winner', "rname" => 'Наименование победителя', "type" => 'varchar'),
		  ); break;};
		  case "contract": { $fields = array(array("fname" => 'number', "rname" => '№ извещения', "type" => 'varchar', "length" => '255'),
			  array("fname" => 'responsible', "rname" => 'Ответственное лицо' , "type" => 'text' ),
			  array("fname" => 'number', "rname" => '№ извещения', "type" => 'varchar', "length" => '255'),
			  array("fname" => 'sposob', "rname" => 'Способ закупки', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'price_contract', "rname" => 'Цена контракта', "type" => 'varchar'),
			  array("fname" => 'sposob', "rname" => 'Тип', "type" => 'text'),
			  array("fname" => 'name_etp', "rname" => 'Наименование ЭТП', "type" => 'varchar'),
			  array("fname" => 'srok_end_podach', "rname" => 'Срок окончания подачи заявок', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'customer', "rname" => 'Наименование заказчика', "type" => 'varchar'),
			  array("fname" => 'inn_customer', "rname" => 'ИНН заказчика', "type" => 'varchar'),
			  array("fname" => 'geographi', "rname" => 'География', "type" => 'varchar'),
			  array("fname" => 'id_relate', "rname" => 'Предмет закупки', "type" => 'varchar', 'list_table' => 'relate'),
			  array("fname" => 'price_lot', "rname" => 'НМЦК', "type" => 'varchar'),
		  ); break;};
	  };
 //echo $_POST['id'];
	  $profile->add_fields($fields,$_POST['id']);
	  $main->addfield('number',$main->tab_fields[0]->value);
  // echotree($main);
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
  	$profile->add($_POST);
  };
  
  function edit_all($main,$db,$table){  	
  	global $site;
  	$profile = new object($db,$main,$table );
  	
  	
  	switch ($site->pageid) {
  		case "podbor": {$wh=" status<3"; break;};
  		case "prep": { $wh=" status in (3,5)"; break;};
  		case "contrib": {$wh=" status in (6,7)"; break;};
  		case "contract": {$wh=" status in (8)";   break;};
  	}
  	
  	$profile->put_data('sub','',$wh);

  	$i=1;
  	if (is_array($main->sub))
  	   $arr=$main->sub;
  	else $arr = array($main->sub);
  	foreach ($arr as  $sub) {
	   foreach ($sub as  $key => $value) {
		  //if ($key=='status') $sub->status= get_sprav_val('status','name', $sub->status);

		  //  echo get_sprav_val('status','name',$sub->id);
		  if ($key=='status') $sub->status1=get_sprav_val('status','name',$sub->status);
		  if ($key=='status_prep') $sub->status_prep=get_sprav_val('status_prep','name',$sub->status_prep);
		  if ($key=='status_uch') $sub->status_uch=get_sprav_val('status_uch','name',$sub->status_uch);
		  if ($key=='status_contract') $sub->status_contract=get_sprav_val('status_contract','name',$sub->status_contract);
		  if ($key=='date_create') $sub->date_create=make_date($sub->date_create,false,0);
		  if ($key=='date_income') $sub->date_income=make_date($sub->date_income,false,0);
		  if ($key=='srok_end_podach') $sub->srok_end_podach=make_date($sub->srok_end_podach,false,0);
		  if ($key=='date_realiz') $sub->date_realiz=make_date($sub->date_realiz,false,0);
		 }
	}





  };
  //echotree($main);
 ?>