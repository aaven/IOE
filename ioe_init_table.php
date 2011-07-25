<?php
    
echo"use this php for only once";


function createTables(){
    include ('ioe_dbconfig.php');
    echo "<p/>starting init $dbname<p/>";
	
	for ($i = 2000; $i <= 2011; $i++) 
	{
		$tname = "TickerListTable" . $i;
    	$sql="CREATE TABLE $tname (sname VARCHAR(7), ticker VARCHAR(10), PRIMARY KEY(sname))";
		if(!mysqli_query($con,$sql))
		{
			echo"<p/>Could not create $tname: " . mysqli_error();
		}
	}
	
	$sql="CREATE TABLE CompanyTable (cid int, cname VARCHAR(120), PRIMARY KEY(cid), UNIQUE(cname))";
	if(!mysqli_query($con,$sql))
	{
		echo"<p/>Could not create CompanyTable: " . mysqli_error();
	}
	
	$sql="CREATE TABLE CurrentSymbolTable (sname VARCHAR(7), t1 DATE DEFAULT '2000-01-01', cid int, PRIMARY KEY(sname), FOREIGN KEY (cid) REFERENCES CompanyTable(cid) ON DELETE CASCADE ON UPDATE CASCADE)";
	if(!mysqli_query($con,$sql))
	{
		echo"<p/>Could not create CurrentSymbolTable: " . mysqli_error();
	}
	
	$sql="CREATE TABLE HistorySymbolTable (sname VARCHAR(7), t1 DATE DEFAULT '2000-01-01', t2 DATE DEFAULT '2010-12-31', cid int, PRIMARY KEY(sname, t1, t2), FOREIGN KEY (cid) REFERENCES CompanyTable(cid) ON DELETE CASCADE ON UPDATE CASCADE)";
	if(!mysqli_query($con,$sql))
	{
		echo"<p/>Could not create HistorySymbolTable: " . mysqli_error();
	}
    
    $sql="CREATE TABLE tempCSV (date DATE, cname VARCHAR(40), SHRCLS VARCHAR(2), ticker VARCHAR(10), cid int, BIDLO DOUBLE, ASKHI DOUBLE, PRC DOUBLE, VOL BIGINT, OPENPRC DOUBLE, PRIMARY KEY(date))";
	if(!mysqli_query($con,$sql))
	{
		echo"<p/>Could not create tempCSV: " . mysqli_error();
	}
    
    
	echo "<p/>finish init $dbname";
    mysqli_close($con);
}

createTables();

?>