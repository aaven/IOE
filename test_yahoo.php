<h2>Using YQL to Access the Upcoming API</h2>
<form name='upcoming_form'>
Location: <input name='l' id='location' type='text' size='20'/><br/>
Event: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='e' id='event' type='text' size='20'/><br/>
<p><button id='find_event'>Find Event</button></p>
</form>

<script>
	// Attach event handler to button
  document.getElementById("find_event").addEventListener("click",createEquityTable,false);
  // Get user input and submit form
  function find_event(){
    document.upcoming_form.event.value = document.getElementById('event').value || "music";
    document.upcoming_form.location.value = document.getElementById('location').value || "San Francisco";
    document.upcoming_form.submit();
  } 
</script>

<?php
  $BASE_URL = "https://query.yahooapis.com/v1/public/yql";

  if(isset($_GET['e']) && isset($_GET['l'])){
    $location = $_GET['l'];
    $query = $_GET['e'];
    $events="";
     
    // Form YQL query and build URI to YQL Web service
   $yql_query1 = "select * from upcoming.events where location='$location' and search_text='$query'";
    $yql_query = "select * from yahoo.finance.historicaldata where symbol = 'YHOO' and startDate = '2009-09-11' and endDate = '2010-03-10'";
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
    $yql_query_url1 = $BASE_URL . "?q=" . urlencode($yql_query1) . "&format=json";
	//echo "$yql_query_url";
    // Make call with cURL
    $session = curl_init($yql_query_url);
    $session1 = curl_init($yql_query_url1);
    curl_setopt($session1, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($session1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
    $json = curl_exec($session);
    $json1 = curl_exec($session1);
    //echo"$json";
    //echo "$json1";
    if(!$json) 
    { 
    	echo 'Curl error: ' . curl_error($session);
    } 
    curl_close($session);
    // Convert JSON to PHP object 
    $phpObj =  json_decode($json);
//echo"$phpObj";
    // Confirm that results were returned before parsing
    if(!is_null($phpObj->query->results)){
      // Parse results and extract data to display
      echo "not null\n";
      foreach($phpObj->query->results->quote as $event){
        $events .= "<div><h2>" . $event->date . "</h2><p>";
        //$events .= html_entity_decode(wordwrap($event->description, 80, "<br/>"));
        //$events .="</p><br/>$event->Open<br/>$event->Highe<br/>";
        //$events .="$event->venue_city, $event->venue_state_name";
        //$events .="<p><a href=$event->ticket_url>Buy Tickets</a></p></div>";
      }
    }
    // No results were returned
    if(empty($events)){
      $events = "Sorry, no events matching $query in $location";
    }
    // Display results and unset the global array $_GET
    echo $events;
    unset($_GET);
  }
?>
