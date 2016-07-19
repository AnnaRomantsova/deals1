<?php

/**
 * @package BACK
 */


include ($inc_path.'/func.back.php');
include ($inc_path.'/classes/class.BTr.php');

function getDefaultConfigPage(&$values) {
        $values['shablon'] =  1;
        $values['section1'] = 'page=4;';
//        $values['section2'] = 'modul=151;';
        $values['section3'] = 'page=3;';
}

function getIdPage($main_section) {
          $pg_id=0;
    $ex=explode(";",$main_section); $c_ex = count($ex);
    for ($i=0; $i < $c_ex; $i++) {
        $ex1=explode("=",$ex[$i]);
        if (trim($ex1[0])=='page') {
          $pg_id=doubleval(trim($ex1[1]));
          break;
        }
    }
        return $pg_id;
  }

function getNamePage(&$_this,&$values,$id = null) {
        $values['page'] = trim($values['page']);
        if ($values['page']=='')
                 $values['page'] = 'p_'.mktime();

          $r =  new Select($_this->db,'select * from '.$_this->table.' where page="'.addslashes($values['page']).'" and section="0"'.($id ? ' and id!="'.addslashes($id).'"' : ''));
          if ($r->next_row())
                  $values['page'] = ($values['page'].'_'.mktime());
          Select_::unset_($r);
}



// раздел
class BS_structure_ {


  /**
  * @param BS_structure $_this
  */
  function addSections(&$_this,&$main,&$param) {
    $r = new Select($_this->db,'select * from '.$_this->table.(isset($param['where']) ? ' where section="1" and '.$param['where'] : '').(isset($param['order']) ? ' order by '.$param['order'] : ''));
    if ($r->num_rows) {
      $_this->addSubs($main,$r,$param);
      $main->addField('actClearS','');
    }
    $r->unset_();
  }

  /**
  * @param BS_structure $_this
  */
  function addPages(&$_this,&$main,&$param) {
    $r = new Select($_this->db,'select * from '.$_this->table.(isset($param['where']) ? ' where section="0" and '.$param['where'] : '').(isset($param['order']) ? ' order by '.$param['order'] : ''));
    if ($r->num_rows) {
      $_this->addSubs($main,$r,$param);
      $main->addField('actClearP','');
    }
    $r->unset_();
  }

  /**
  * @param BS_structure $_this
  */

  function addManager(&$_this,&$main) {
    $param = &$_this->getParamMngr();

    $_this->addSections($main,$param);
    if (isset($main->records)) {
      $main->addField('sections',&$main->records);
      unset($main->records);
    }

    $_this->addPages($main,$param);
    if (isset($main->records)) {
      $main->addField('pages',&$main->records);
      unset($main->records);
    }
    return 'manager.html';
  }


/**
  * @param BS_structure $_this
  */
 function upRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort,section,parent from '.$_this->table.' where id="'.addslashes($id).'"');
    if ($r->next_row())
        record_up($_this->table,$r->result('sort'),'( parent="'.$r->result('parent').'") and section="'.$r->result('section').'"');
    $r->unset_();
 }

  /**
  * @param BS_structure $_this
  */
 function downRecord(&$_this,$id) {
    $r = new Select($_this->db,'select sort,section,parent from '.$_this->table.' where id="'.addslashes($id).'"');
    if ($r->next_row())
        record_down($_this->table,$r->result('sort'),' ( parent="'.$r->result('parent').'")  and section="'.$r->result('section').'"');
    $r->unset_();
 }


  /**
  * @param BS_structure $_this
  */
  function addIfcAddRecord(&$_this,&$main,$parent) {
    if ($_FILENAME = BT_::addIfcAddRecord($_this,$main,$parent)) {
      $main->addField('content',"loadFCKeditor('content','');");
      $main->addField('fix',0);
      if (1 == $_GET['sction']) {
              $_FILENAME = 'redact_s.html';
              $main->path->last->name = 'Добавление раздела';

      }

      if (0 == $_GET['sction']) {
              $_FILENAME = 'redact_p.html';
              $main->path->last->name = 'Добавление страницы';
      }
    }
    return $_FILENAME;
  }

  /**
  * @param BS_structure $_this
  */
  function addIfcEditRecord(&$_this,&$main,$id) {
    if ($_FILENAME = BF_T_::addIfcEditRecord($_this,$main,$id)) {

      if (0 == $main->section) {
              $_FILENAME = 'redact_p.html';
               array_pop($main->path->sub);
               $main->path->last->name = 'Редактрирование страницы';

                // добавление контента первой page= ; в main_section
             if ($pg_id=getIdPage($main->main_section)) {
                     $GLOBALS['r_p'] = new Select($_this->db,'select * from '.$GLOBALS['pages_table'].' where id="'.$pg_id.'"');
                     if ($GLOBALS['r_p']->next_row()) {
                                        $main->addField('content','addFCKeditor($GLOBALS["r_p"],"content");');
                     }
             }
      }

      if (1 == $main->section)
              $_FILENAME = 'redact_s.html';
    }
    return $_FILENAME;
  }

 function addSub(&$_this,&$sub,&$r,$param) {
         B_::addSub($_this,$sub,$r,$param);
         $r->addFields($sub,$ar =array('menu','fix','section','page'));
 }


 function addButtons(&$_this,&$main,&$param) {
         if ( (1 != $param['id']) )        { // если не корень
            $main->addField('butRedact','');
            $_this->addButtonsCut($main,$param['id']);
         }

         if (empty($param['root']))  { // если не текущий каталог
            if (!$param['fix'])
                         $main->addField('butDelete','');
            $main->addField('butPabl','');
            $_this->addButtonsSort(&$main,&$param);
         }
 }

 function createEvent(&$_this,$location = '') {
 // в меню запись
   if  (isset($_GET['menu'])) {
             $_this->menuRecord($_GET['menu']);
         go('?event=1&'.$location);
   }
  /// !!! порядок важен        !!!
   elseif(BT_O_::createEvent($_this,$location));
   elseif(BT_S_::createEvent($_this,$location));
   else
                return false;
   return true;
 }


 function menuRecord(&$_this,$id) {
    $r = new Select($_this->db,'select menu from '.$_this->table.' where id="'.$id.'"');
    if ($r->next_row())
                $_this->db->query('update '.$_this->table.' set menu="'.((1+intval($r->result(0)))%2).'" where id="'.$id.'"');
    $r->unset_();
 }

 function deleteRecord(&$_this,$id,$r = null,$qd = true) {
         BF_S_::deleteRecord($_this,$id,$r,$qd);
         if (!$r)
                 $r = &$GLOBALS['r'];

        if (!$r->result('section') && !$r->result('fix')) {
                $pg_id = getIdPage($r->result('main_section'));
                $_this->db->query('delete from '.$GLOBALS['pages_table'].' where id="'.$pg_id.'"');
        }

 }

 function saveNewRecord(&$_this,&$values,$parent,$last = null) {
          if (!$values['section']) {
                  getNamePage($_this,$values);
                   $valuesP = array('id' => ($pg_id = $_this->db->next_id($GLOBALS['pages_table'])),
                                                     'content' => $values['content'],
                                                     'name' => $values['page']
                   );
         $_this->db->insert($GLOBALS['pages_table'], $valuesP);
         $values['main_section'] = 'page='.$pg_id.';';
         unset($values['content']);
          }
          getDefaultConfigPage($values);
          $values['fix'] = 0;
         $id = BFT_TO_::saveNewRecord($_this,$values,$parent,$last);
     return $id;
 }


 function saveRecord(&$_this,&$values,$id) {
           $r =  new Select($_this->db,'select * from '.$_this->table.' where id="'.addslashes($id).'"');
           if ($r->next_row()) {
                    getNamePage($_this,$values,$id);
                 if ($pg_id = getIdPage($r->result('main_section')))
                           $_this->db->query('update '.$GLOBALS['pages_table'].' set content="'.addslashes($values['content']).'", name="'.addslashes($values['page']).'" where id="'.$pg_id.'"');
             BF_::saveRecord(&$_this,&$values,$id);


          }
           Select_::unset_($r);
 }


 function redactValues(&$_this,&$values) {
    if (empty($values['title']))
            $values['title'] = $values['name'];
        B_::redactValues($_this,$values);
 }

}

class BS_structure extends BSc {

  function redactValues(&$values) {
         BS_structure_::redactValues($this,$values);
  }

  function addSections(&$main,&$param) {
    BS_structure_::addSections($this,$main,$param);
  }

  function addPages(&$main,&$param) {
    BS_structure_::addPages($this,$main,$param);
  }

  function addManager(&$main) {
    return BS_structure_::addManager($this,$main);
  }

  function upRecord($id) {
         BS_structure_::upRecord($this,$id);
  }

  function downRecord($id) {
         BS_structure_::downRecord($this,$id);
  }

  function addIfcAddRecord(&$main,$parent) {
    return BS_structure_::addIfcAddRecord($this,$main,$parent);
  }

  function addIfcEditRecord(&$main,$id) {
    return BS_structure_::addIfcEditRecord($this,$main,$id);
  }

  function addSub(&$sub,&$r,&$param) {
           BS_structure_::addSub($this,$sub,$r,$param);
  }

  function addButtons(&$main,&$param) {
         BS_structure_::addButtons($this,$main,$param);
  }

  function createEvent($location = '') {
        return BS_structure_::createEvent($this,$location);
  }

  function menuRecord($id) {
         BS_structure_::menuRecord($this,$id);
  }

  function deleteRecord($id,$r = null,$qd = true) {
                 BS_structure_::deleteRecord($this,$id,$r,$qd);
  }

  function saveNewRecord(&$values,$parent,$last = null) {
         return BS_structure_::saveNewRecord($this,$values,$parent,$last);
  }

  function saveRecord(&$values,$id) {
         BS_structure_::saveRecord($this,$values,$id);
  }


}




class B_structure_ {

 function addActions(&$_this,&$main,&$param) {
  //если утерян путь к корню - выходим на страницу по умолчанию.
  if ( 0 > $GLOBALS['br']->level)
       header('Location: ?sct=1');

  if (  (1 == $GLOBALS['br']->level) && isset($_SESSION['idCuts']) &&
        ( $co = count($_SESSION['idCuts'][$_this->Section->table]))
       )
     $main->addField('actPast',$co);

  if ( 0 == $GLOBALS['br']->level )
     $main->addField('actAddSection','');

  if ( 1 == $GLOBALS['br']->level )
     $main->addField('actAddPage','');

  // если проинициализировано хотя бы одно из действий
  if (     isset($main->actAddSection)
        ||  isset($main->actAddPage)
        ||  isset($main->actClearS)
        ||  isset($main->actClearP)
        ||  isset($main->actPast)
      )
    $main->addField('actions','');
 }




  function clearSection(&$_this,$id,$type = 0) {
    // удаление подразделов
    if (0 == ($type%5))
    $_this->deleteSections($id);

    // удаление товаров
    if (0 == ($type%7))
    $_this->deletePages($id);


  }

  function deleteSections(&$_this,$id) {
    $r = new Select($_this->db,'select * from '.$_this->Section->table.' where parent="'.$id.'" and section="1"');
    while ($r->next_row()) {
      $_this->deleteSection($r->result('id'),$r,false);
    }
    $r->unset_();
    $_this->db->query('delete from '.$_this->Section->table.' where parent="'.$id.'" and section="1"');
  }

  /**
   * @param B_structure $_this
   */
  function deletePages(&$_this,$id) {
    $r = new Select($_this->db,'select * from '.$_this->Section->table.' where parent="'.$id.'" and section="0"');
    while ($r->next_row()) {
      $_this->deleteSection($r->result('id'),$r,false);
    }
    $r->unset_();
    $_this->db->query('delete from '.$_this->Section->table.' where parent="'.$id.'" and section="0"');
  }


}

class B_structure extends BTr  {

 /**
  * @var BS_structure
  */
 var $Section;


 function B_structure(&$_db,$_name = null,$_caption = null,$table_sections,&$arFilesS,$param) {
  $this->initB_structure(&$_db,$_name,$_caption,$table_sections,&$arFilesS,$param);
 }

 function initB_structure(&$_db,$_name,$_caption,$table_sections,&$arFilesS,$param) {
  $this->Section = new BS_structure(&$_db,$_name,$_caption,$table_sections,$arFilesS);
  $this->initModule(&$_db,$_name,$_caption);
 }

 function addActions(&$main,&$param) {
  B_structure_::addActions($this,$main,$param);
 }



  function clearSection($id,$type = 0) {
    B_structure_::clearSection($this,$id,$type);
  }

  function deleteSections($id) {
    B_structure_::deleteSections($this,$id);
  }

  function deletePages($id) {
    B_structure_::deletePages($this,$id);
  }


}

?>
