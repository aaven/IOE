<?php
    
    function importCSV(){
        include ('ioe_dbconfig.php');
        
        // import csv into tempCSV table
        // empty tempCSV first
        $sql="TRUNCATE TABLE tempCSV";
        if(!mysqli_query($con,$sql))
		{
            mysqli_close($con);
			die('<p/>FAIL to empty tempCSV: ' . mysqli_error($con));
		}
        
        $filename = $_GET["filename"];
        //$filename = "/Users/aavenjin/Documents/AAF/wharton_data/AAPL.csv";
        $tempname = substr($filename, strrpos($filename, '/'));
        echo "\n<p/>importing data from $tempname....\t";
        $sql = "LOAD DATA LOCAL INFILE '$filename' INTO TABLE tempCSV FIELDS TERMINATED BY  ',' LINES TERMINATED BY '\n'";
        if(!mysqli_query($con,$sql))
		{
            mysqli_close($con);
			die('<p/>FAIL to load data into tempCSV: ' . mysqli_error($con));
		}
        if(!mysqli_query($con,"DELETE FROM tempCSV WHERE Date = '0000-00-00'") 
           || !mysqli_query($con,"DELETE FROM tempCSV WHERE BIDLO = ''")
           || !mysqli_query($con,"DELETE FROM tempCSV WHERE ASKHI = ''")
           || !mysqli_query($con,"DELETE FROM tempCSV WHERE PRC = ''")
           || !mysqli_query($con,"DELETE FROM tempCSV WHERE VOL = ''")
           || !mysqli_query($con,"DELETE FROM tempCSV WHERE OPENPRC = ''"))
		{
            mysqli_close($con);
			die('<p/>FAIL to filter data in tempCSV: ' . mysqli_error($con));
		}
        
        // store company id and name into CompanyTable
        $sql = "SELECT DISTINCT cname from tempCSV";
        $sql1 = "SELECT DISTINCT cid from tempCSV";
        $result = mysqli_query($con,$sql);
        $result1 = mysqli_query($con,$sql1);
        if(!$result || !$result1)
		{
            mysqli_close($con);
            die('<p/>FAIL to select cname or cid from tempCSV: ' . mysqli_error($con));
		}
        
        $c = mysqli_num_rows($result);
        $c1 = mysqli_num_rows($result1);
        if($c1 > 1)
        {
            mysqli_free_result($result);
            mysqli_free_result($result1);
            mysqli_close($con);
            die('<p/>ERROR: more than one id in a file');
        }
        else
        {
            $row = mysqli_fetch_row($result1);
            $cid = $row[0];
            $names = "";
            while ($row = mysqli_fetch_row($result)) {
                $names = $names . $row[0] . ",";
            }
        }
        mysqli_free_result($result);
        mysqli_free_result($result1);
        
        $out = ""; // output
        if(!$cid || !$names)
        {
            $out = "<p/>No data in $tempname";
        }
        else
        {
        $sql = "INSERT INTO CompanyTable (cid, cname) VALUES ('$cid', '$names')";
        $tablename = "CompanyData" . $cid;
        if(!mysqli_query($con,$sql))
		{
            $e = mysqli_error($con);
            if(strncmp("Duplicate",$e,9) !== 0)
            {
                $out = $out . "<p/>FAIL to insert company info ($cid, $names) into CompanyTable: " . $e;
            }
            elseif(strpos($e,"cname") !== false)
            {
                $out = $out . "<p/>FAIL to insert company info ($cid, $names) into CompanyTable: " . $e;
            }
		}
        else
        {
            // create unique table for the company: "CompanyData"+cid
            $sql="CREATE TABLE $tablename (date DATE, open DOUBLE, high DOUBLE, low DOUBLE, close DOUBLE, volume BIGINT, adj_close DOUBLE, PRIMARY KEY(date))";
            if(!mysqli_query($con,$sql))
            {
                $e = mysqli_error($con);
                if(strncmp("Duplicate",$e,9) !== 0)
                {
                    $out = $out . "<p/>FAIL to create $tablename: " . $e;
                }
            }
        }
        
        $sql="INSERT INTO $tablename (date, open, high, low, close, volume) SELECT date, OPENPRC, ASKHI, BIDLO, PRC, VOL FROM tempCSV";
        if(!mysqli_query($con,$sql))
		{
            $e = mysqli_error($con);
            if(strncmp("Duplicate",$e,9) !== 0)
            {
                $out = $out . "<p/>FAIL to insert data into $tablename: " . $e;
            }
            else
            {
                $out = "DUPLICATE data! " . $out;
            }
		}
        else
        {
            $out = "SUCCESS! ($cid, $names)" . $out;
        }
        }
        
        // close connection and output
        mysqli_close($con);
        echo"$out";
    }

    importCSV();
?>