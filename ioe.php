<form name='upcoming_form' method='GET'>
Quote: <input name='quote' id='quote' type='text' size='20'/><br/>
Start Date: <input name='start' id='start_date' type='text' size='20'/><br/>
End Date: <input name='end' id='end_date' type='text' size='20'/><br/>
<p><button id='extract'>Extract data</button></p>
</form>

<script>
	document.getElementById("extract").addEventListener("click",extract,false);
	// Get user input and submit form
	function extract(){
		document.upcoming_form.submit();
	} 
</script>

<?php
include ('ioe_dbconfig.php');
function createHistoryTable($tablename){
	include ('ioe_dbconfig.php');
	$sql="CREATE TABLE $tablename (date DATE, open_price DOUBLE, high DOUBLE, low DOUBLE, close_price DOUBLE, volume BIGINT NOT NULL, adj_close DOUBLE NOT NULL, PRIMARY KEY(date))";
	if(!mysqli_query($con,$sql))
	{
		die('<p/>Could not create ' . $tablename . ': ' . mysqli_error($con));
		//echo "<p/>Could not create $tablename: " . mysqli_error($con);
	}
	echo "<p/>...$tablename is created!";
}

function addEquity($name,$start,$end,$tablename){
	include ('ioe_dbconfig.php');
	// check existence in AliasTable, if yes, quit; if not, add to AliasTable and proceed
	
	// add to EquityTable, and create HistoryTable
	$sql="INSERT INTO EquityTable (tablename, update_start_date, update_end_date, current_name) values ('$tablename', '$start', '$end', '$name')";
	if(!mysqli_query($con,$sql))
	{
		die('<p/>Could not add new equity: ' . mysqli_error($con));
		//echo "<p/>Could not add new equity: " . mysqli_error($con);
	}
	echo "<p/>...$name is added to EquityTable with $tablename!";
}

function updateHistoryTable($quote, $tablename){
	include ('ioe_dbconfig.php');
	$sql="INSERT INTO $tablename (date, open_price, high, low, close_price, volume, adj_close) values ('$quote->Date','$quote->Open','$quote->High','$quote->Low','$quote->Close','$quote->Volume','$quote->Adj_Close')";
	if(!mysqli_query($con,$sql))
	{
		// die('<p/>Could not update data for ' . $tablename . ': ' . mysqli_error($con));
		echo "<p/>Could not update data for $tablename: " . mysqli_error($con);
	}
	else 
	{	
		echo "<p/>...$tablename is updated!";
	}
}

function getTableName($name){
	/*
	$sql="SELECT current_name from AliasTable where name='$name'";
	$result=mysqli_query($con,$sql);
	if(!$result)
	{
		die('Could not get current name: ' . mysqli_error());
	}
	$count=mysqli_num_rows($result);
	if($count == 1)
	{
		$tablename = mysqli_fetch_field($result);
	}
	*/
}

function checkExistence($name,$tablename){
	include ('ioe_dbconfig.php');
	$sql="SELECT * FROM EquityTable WHERE current_name='$name'";

	$result=mysqli_query($con, $sql);
	if(!$result)
	{
		die('<p/>Could not look up ' . $tablename . ' in EquityTable: ' . mysqli_error($con));
	}
	$count=mysqli_num_rows($result);
	if(!$count)
	{
		echo "<p/>$name was never looked up before...";
		addEquity($name,'0','0',$tablename);
		createHistoryTable($tablename); // TODO: on drop a quote, how to drop its historytable??
	}
	echo "<p/>...$tablename for $name is okay to use!";
}

$BASE_URL = "https://query.yahooapis.com/v1/public/yql";

if(isset($_GET['quote'])){
	$name = $_GET['quote'];
	$start = $_GET['start'];
	$end = $_GET['end'];
	$quotes = "";

	// Form YQL query and build URI to YQL Web service
    $yql_query = "select * from yahoo.finance.historicaldata where symbol='$name' and startDate='$start' and endDate='$end'";
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
	//	echo "$yql_query_url";
    // Make call with cURL
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
    $json = curl_exec($session);

    if(!$json) 
    { 
    	echo 'Curl error: ' . curl_error($session);
    } 
    curl_close($session);
    // Convert JSON to PHP object 
    $phpObj =  json_decode($json);

	$tablename = "$name" . "HistoryTable";
    if(!is_null($phpObj->query->results)){
    	checkExistence($name,$tablename);
      	// Parse results and extract data
		foreach($phpObj->query->results->quote as $quote){
 			$quotes .= "<div>" . $quote->date . "<p>";
			updateHistoryTable($quote, $tablename);
 		}
    }
    // No results were returned
	if(empty($quotes)){
		echo "<p/>Please make sure start date and end date are right (within ONE year)!";
	}
    // Display results and unset the global array $_GET
    unset($_GET);
}
?>
