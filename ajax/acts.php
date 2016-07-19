<?php

try
{

		//Open database connection
	require_once("../setup.php");
    include_once($inc_path.'/db_conect.php');
    $table=$_GET["table"];

    
	//Getting records (listAction)
	if($_GET["action"] == "list")
	{
		 
		//Get records from database
		$result = mysql_query("SELECT * FROM $table where parent=$_GET[parent]");
		//echo "j";
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	//Creating a new record (createAction)
	else if($_GET["action"] == "create")
	{
		//Insert record into database
		$result = mysql_query("INSERT INTO $table(name, cnt,parent) VALUES('" . $_POST["name"] . "', '" . $_POST["cnt"] ."', '$_GET[parent]') ;");
		
		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM $table WHERE id = LAST_INSERT_ID();");
		$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
	}
	//Updating a record (updateAction)
	else if($_GET["action"] == "update")
	{
		//Update record in database
		$result = mysql_query("UPDATE $table SET name = '" . $_POST["name"] . "', cnt = " . $_POST["cnt"] . " WHERE id = " . $_POST["id"] . " and parent=$_GET[parent] ");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{
		//Delete from database
		$result = mysql_query("DELETE FROM $table WHERE id = " . $_POST["id"] . " ");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}

	//Close database connection

}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}
	
?>