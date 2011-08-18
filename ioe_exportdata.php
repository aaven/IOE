<?php
	if (isset($_POST['q'])) {  
        $tickers = $_POST['q'];
        $response = "Selected tickers from $tickers: ";
        foreach ($tickers as $tt) {
            $response = $response . $tt . " ";
        }
        echo $response;
    }
    
?>