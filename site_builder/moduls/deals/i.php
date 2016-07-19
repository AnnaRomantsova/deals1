<?php

 include('config.php');
 include($inc_path.'/object.php');
 include($inc_path.'/myfunc.php');

 unset($main);
 $sh_i = $front_html_path.'i.html';

 include($inc_path.'/classes/class.BF.php');
 include($inc_path.'/admin_functions.php');


if ( !$_SESSION ['user']>0) header('Location: /');
 
   
$main = &addInCurrentSection($sh_i); 
$main->addField('pagename','Сделки');
$main->addField('object_name','сделку');  
$main->addField('page','deals');
$table = "deals_items";
   

if ($_POST['id']>0) {  
                  //нажали кнопку сохранить одну запись
                  if ($_POST['save_submit']>0){
	                       save_edit($main,$db,$table);  	                       
	                       if ($_POST['next_step_edit']>0) next_step($db);
	                       
	                       edit_one($main,$db,$table,$_GET['i']);
                  }
  }  
  else if ($_GET['i'] >0) {
  	edit_one($main,$db,$table,$_GET['i']);
  	$main->addField('mode','mode_edit_one');
  }
 
 
 ///////////Основные функции        
   
  function delete($main,$db,$table){
  	   
	  	$profile = new object($db,$main, $table);
	  	$profile->delete($_POST['id']);
  };   
  
  function edit_one($main,$db,$table,$id){
  	
  	    //находим последний (активный) этап 
  	    $r2 = new Select($db,"SELECT count(*) as cnt from deals_items di where di.parent = $id " );
  	    $r2->next_row();
  	    if ($r2->result('cnt')>0) $main->addField('active_step',$r2->result('cnt')-1);
  	    //  else $main->addField('active_step',0);
  	    
  	    //идем по этапам
	  	$r = new Select($db,"SELECT s.*,ds.id as parent from deals_sections ds, step s where ds.id_relate = s.id_relate and ds.id=$id " );
	  	
	  	//закрытые вкладки
	  	$str='';
	  	$act = $r2->result('cnt');// >0 ? $r2->result('cnt') :1;
	  	for ($i=$act; $i<$r->num_rows() ;$i++)
	  		$str .= ",$i";	  	  
	  	$main->addField('disable',substr($str,1));
	  	
	  	while ($r->next_row()){
	  		unset($sub);
	  		$sub = new outTree();
	  		$r->addFields($sub,$ar=array('id','name','parent'));
	  		
	  		//получаем заполненное значение
	  		$r2 = new Select($db,"SELECT * from deals_items di where di.step_id=".$r->result('id')." and parent=$id");
	  		$r2->next_row();
	  		$sub->addField('id_record', $r2->result('id'));
	  		
	  		//идем по полям этапа
	  		$r1 = new Select($db,"SELECT * from fields_step fs,fields f where fs.id_field=f.id and id_step=".$r->result('id')." order by sort" );
	  		
	  		while ($r1->next_row()){
	  			unset($sub1);
	  			$sub1 = new outTree();
	  			$r1->addFields($sub1,$ar=array('id_field','rname','fname','type','list_table','sprav_table'));	  			
	  			$sub1->addField('value', $r2->result($r1->result('fname')));
	  			
	  			$sub->addField('fields',$sub1);
	  		}
	  		$main->addField('step',$sub);
	  	}
	  	//$fields[] = array("fname" => $r->result('column_name'),  "rname" => $r->result('column_comment') ,"type" => $r->result('data_type')); 	  	
	  	$profile = new object($db,$main, $table);	  	

  	
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
  
  //пкркход на следующий этап
  function next_step($db){ 	
  	
  	$r = new Select($db,"SELECT * from step where id=$_POST[id_step]");  	
  	if ($r->next_row()) {
  		$r2 = new Select($db,"SELECT * from step where id_relate=".$r->result('id_relate')." and  sort > ".$r->result('sort')." order by sort");
  		if ($r2->next_row() && $_POST['parent']>0){
  			$data = array("parent" =>$_POST['parent'], "step_id" =>$r2->result('id'), "status"=>"1");
  			$profile1 = new object($db,$main, "deals_items");
  			$profile1->add($data);
  		}		
  		
  	}
  };
  
  function edit_all($main,$db,$table){  	
  	$profile = new object($db,$main,$table );
  	$profile->put_data('sub');
  	$i=1;
  	foreach ($main->sub as $sub) {
  		foreach ($sub as $key => $value) {
  			if ($key=='id_step') $sub->step= get_sprav_val('step','name', $sub->id_step);
  			if ($key=='id_relate') $sub->relate= get_sprav_val('relate','name', $sub->id_relate);
  			if ($key=='date_begin') $sub->date_begin=make_date($sub->date_begin);
  		}
  		$sub->num_pp = $i;
  		$i++;
  	}
  };
  
 ?>