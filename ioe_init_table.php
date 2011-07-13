<?php

function createEquityTable(){
	include ('ioe_dbconfig.php');
	$sql="CREATE TABLE EquityTable (tablename VARCHAR(20), current_name VARCHAR(7) NOT NULL UNIQUE, update_start_date DATE DEFAULT '00-00-00', update_end_date DATE DEFAULT '00-00-00', PRIMARY KEY(tablename))";
	if(!mysqli_query($con,$sql))
	{
		die('Could not create EquityTable: ' . mysqli_error());
	}
	
	$sql="CREATE TABLE AliasTable (name VARCHAR(7) NOT NULL, start_date DATE DEFAULT '00-00-00', end_date DATE DEFAULT '00-00-00', current_name VARCHAR(7) NOT NULL, PRIMARY KEY(name, start_date))";
	if(!mysqli_query($con,$sql))
	{
		die('Could not create AliasTable: ' . mysqli_error());
	}
	echo "successfully init $dbname";
}

createEquityTable();
?>