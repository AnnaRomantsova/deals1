<?php
//

 
class object {
 var $place; 
 var $table;
 var $db;

   /** инициализация класса  */
 function object($_db,$place,$table) {
 	 
     $this->db = &$_db;
     $this->table = $table;
     $this->place = $place;
    // echotree($db);

 }

   function get_value($id,$field){
       $r = new Select($this->db,"select $field from ".$this->table." where id=$id");
       if ($r->next_row()) {
           return $r->result($field);
       } return null;
   }

  /*добавляет данные*/
  function put_data($field_name,$parent=0,$sql='') {
     
        $r = new Select($this->db,"SHOW COLUMNS FROM ".$this->table);
        $f=null;
        while($r->next_row()) {
                     $f[] = $r->result('Field');
        }; 
        if ($parent >0) $wh = " and parent =$parent";
        if ($sql !=='') $wh.=" and $sql";
        
        $r = new Select($this->db,"select * from ".$this->table." where 1=1 $wh" );
        while ($r->next_row()) { 
               unset($sub);
               $sub = new outTree();
               foreach ($f as $field)   	                 
                   $sub->addField($field,$r->result($field)); 
               $this->place->addField($field_name,$sub);
        };     
  }
  
  /*удаление записи*/
  function delete($id) {  
        $r = new Select($this->db,"delete FROM ".$this->table." where id=$id");       
  }
  
  function delete_by_parent($table,$id) {
  	$r = new Select($this->db,"delete FROM $table where parent=$id");
  }
  
  function add($post_values) {        
       $r = new Select($this->db,"SHOW COLUMNS FROM ".$this->table);
       $f=null;
       while($r->next_row()) {
                     $f[] = $r->result('Field');
       }; 
        
       $cols='';
       $values='';
       foreach ($post_values as $key => $val) {
          if (in_array($key,$f) ) {
              $cols .= ",$key";
              $values .= ",'$val'";
          }
       };
       $cols = substr($cols,1);
     //  $cols .= ',guid';
       $values = substr($values,1);
       //$values .= ',uuid()';
       $sql = "insert into ".$this->table." ($cols) values ($values)";
       $r = new Select($this->db,$sql);     
       return $this->db->insert_id();      
       
  }
  
  //добавляет в $this->place поля для форм редактирования или добавления записи, 
  //на вход подается массив $fields с настройками полей
  function add_fields($fields,$id=0) {
  	$r = new Select($this->db,"SHOW COLUMNS FROM ".$this->table);
  	$f=null;
  	while($r->next_row()) {
  		$f[] = $r->result('Field');
  	};
  
  	if ($id>0) {
  	   $r = new Select($this->db,"select * from ".$this->table." where id=$id" );
  	   $r->next_row();
  	   foreach ($f as $field)
  	   	 $data[$field]=$r->result($field);  			
  	}
  	
    foreach ($fields as $v){
  			$sub = new outTree();
  			$sub->addField('fname',$v['fname']);
  			$sub->addField('rname',$v['rname']);
  			$sub->addField('type',$v['type']);
  			$sub->addField('length',$v['length']);
  			$sub->addField('id_cal',$v['id_cal']);
  			if (array_key_exists($v['fname'],$data)) $sub->addField('value',$data[$v['fname']]);
  			if ($v['type'] =='list' && array_key_exists('list_table',$v)) {
  				//addSprav(&$sub,$v['list_table'],$data[$v['fname']],'list_table');
                //addSprav_sql(&$sub,$v['list_table'],'name',$data[$v['fname']],'list_table',$sort=$v['sort'],$v['where']);
                addSprav_wide($sub,$v['list_table'],$data[$v['fname']],'list_table',$v['where'],$sort=$v['sort']);

  			}
            if ($v['type'] =='date' ) {
                //echo $v['fname'];
                $sub->value =  make_date($data[$v['fname']],false,0);
                //echo make_date($data[$v['fname']],false,0);
            }
  			$this->place->addField('tab_fields',$sub);
  			unset($sub);
  		}  	  	 
  }
  
  
  function edit($id,$field_name) {

        $r = new Select($this->db,"SHOW COLUMNS FROM ".$this->table);
        $f=null;
        while($r->next_row()) {
                     $f[] = $r->result('Field');
        }; 
        $r = new Select($this->db,"select * from ".$this->table." where id=$id" );
        if ($r->next_row()) { 
               unset($sub);
               $sub = new outTree();
               foreach ($f as $field)   	                 
                   $sub->addField($field,$r->result($field)); 
               
               $this->place->addField($field_name,$sub);
        };  
       //echotree($this->place);
  }
  
 function save_edit($id) {
        
       $r = new Select($this->db,"SHOW COLUMNS FROM ".$this->table);
       $f=null;
       while($r->next_row()) {
                     $f[] = $r->result('Field');
       }; 
        
       $cols='';
       $values='';
       foreach ($_POST as $key => $val) {
          if (in_array($key,$f) ) {
              
              $values .= ", $key = '$val'";
          }
       };
       $values = substr($values,1);
       $sql = "update ".$this->table." set $values where id=$id";
      
       $r = new Select($this->db,$sql);

       
  }
  
  
  
  
}


?>
