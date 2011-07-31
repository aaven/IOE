<?php
    
    function importCSV(){
        include ('ioe_dbconfig.php');
        
        $sql="INSERT INTO testTable (no, cname, symbol) SELECT cid, cname, ticker FROM tempCSV GROUP BY cid, cname, ticker";
        if(!mysqli_query($con,$sql))
		{
            $e = mysqli_error($con);
            echo"FAIL:" . $e;
		}else{
            echo"SUCCESS";
        }
        

        // close connection and output
        mysqli_close($con);
    }

    importCSV();
?>