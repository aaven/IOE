<?php
    
echo ("NOTICE: use this script only ONCE.");


function createTables(){
    include ('ioe_dbconfig.php');
    echo "<p/>Starting init $dbname<p/>";
    
	/*
	for ($i = 2000; $i <= 2011; $i++) 
	{
		$tname = "TickerListTable" . $i;
    	$sql="CREATE TABLE $tname (sname VARCHAR(7), ticker VARCHAR(10), PRIMARY KEY(sname))";
		if(!mysqli_query($con,$sql))
		{
			echo"<p/>Could not create $tname: " . mysqli_error($con);
		}
	}
	*/
	$sql = "CREATE TABLE $indextable (ticker VARCHAR(10) NOT NULL, cid INT NOT NULL, cname VARCHAR(50) NOT NULL, start DATE DEFAULT '2000-01-01' NOT NULL, end DATE DEFAULT '2010-12-31' NOT NULL, PRIMARY KEY (ticker,start), UNIQUE(ticker,end), UNIQUE(cid,start), UNIQUE(cid,end))";
	if (!mysqli_query($con,$sql))
	{
		echo ("<p/>Could not create $indextable: " . mysqli_error($con));
	} else {
        echo ("<p/>$indextable created.");
    }
    
    $sql = "CREATE TABLE $temptable (date DATE, cname VARCHAR(50), SHRCLS VARCHAR(1), ticker VARCHAR(10), cid INT NOT NULL, low DOUBLE NOT NULL, high DOUBLE NOT NULL, close DOUBLE NOT NULL, vol BIGINT NOT NULL, open DOUBLE NOT NULL, PRIMARY KEY(date))";
	if (!mysqli_query($con,$sql))
	{
		echo"<p/>Could not create $temptable: " . mysqli_error($con);
	} else {
        echo ("<p/>$temptable created.");
    }
    
    
	echo "<p/>Finish init $dbname";
    mysqli_close($con);
}

createTables();

?>