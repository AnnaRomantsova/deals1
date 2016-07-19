<?php

 include('config.php');
 include($inc_path.'/object.php');

 unset($main);
 $sh_profile = $front_html_path.'profile.html';
 $sh_orgs = $front_html_path.'orgs.html';

 include($inc_path.'/classes/class.BF.php');
 include($inc_path.'/admin_functions.php');


if ( !$_SESSION ['user']>0) header('Location: /');
 
   //  unset($main->content);
   
$main = &addInCurrentSection($sh_orgs); 
     
	 
    
if ($_POST['id']>0) {
        
                  //нажали кнопку удалить одну запись
                  if ($_POST['del']>0){
                       $profile = new object($db,$main, 'organization');
                       $profile->delete($_POST['id']);
                        
                       $main->addField('mode','mode_edit');
                  }
                  //нажали кнопку редактировать одну запись
                  else if ($_POST['edit']>0){
                        if ($_POST['id']>0)
                            $main->addField('mode','mode_edit_one');
                  }
                  //нажали кнопку сохранить одну запись
                  else if ($_POST['save_submit']>0){
                       $profile = new object($db,$main, 'organization');
                       $profile->save_edit($_POST['id']);
                    
                       $main->addField('mode','mode_edit');
                  }
  }
  //создать новую запись
  else if ($_POST['new']>0){
                        $main->addField('mode','mode_new');                      
  }
  //сохранить новую запись
  else if ($_POST['new_submit']>0){
          
                   $profile = new object($db,$main, 'organization');
                   $id = $profile->add($_POST);                                    
                   $main->addField('mode','mode_edit');
}
else if ($_GET['i'] >0) $main->addField('mode','mode_show_one');
           //  else $main->addField('mode','mode_show');
                //ничего не нажимали
                else  $main->addField('mode','mode_edit');

     //режим редактирорвания одной записи
     if (($_POST['id']>0)&&($main->mode=='mode_edit_one')) {
                   $profile = new object($db,$main, 'organization');
                   $profile->edit($_POST['id'],'sub');
     //режим редактиорования всех записей
     } else if ($main->mode=='mode_edit'){
     	
          if ($_GET['id']>0) {
            
              $profile = new object($db,$main, 'organization');
              $profile->put_data('sub',$_GET['id']);
          }
          else {
              
              $profile = new object($db,$main, 'organization' );
              $profile->put_data('sub');
              foreach ($main->sub as $sub) {
                foreach ($sub as $key => $value) {        
                  if ($key=='is_active') {
                      if ($value==1) $sub->is_active="да"; else $sub->is_active="нет";
                      
                  }
                }
              }    
            
          }
       //   $main->addField('mode','mode_edit');
          $main->addField('pagename','Организации');
          $main->addField('object_name','организацию');
     	
     }
     //реждим добавления
     else if ($main->mode=='mode_new') {
       
     }
     //режим просмотра всех новостей
     else if (($main->mode=='mode_show')&&(!($_GET['i']>0)))
     {
          


    }
    //режим просмотра одной новости
     else if (($main->mode=='mode_show_one')&&($_GET['i']>0)){
            $r = new Select($db,'select * from organization where id='.$_GET['i']);
            if ($r->next_row()) {

                    $profile = new object($db,$main, 'organization');
                   $profile->edit($_GET['i'],'sub');
            }
            else
                        header('Location: /error404');
            $r->unset_();

     }


// echotree($main);
  //echo $main->mode;
 ?>