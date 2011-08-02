<?php
    
    function importCSV(){
        include ('ioe_dbconfig.php');
        
        /*
         * import csv into empty tempCSV
         */
        if (!mysqli_query($con,"TRUNCATE TABLE tempCSV"))
		{
			die ('<p/>FAIL to empty tempCSV: ' . mysqli_error($con));
		}
        
        $filename = $_GET["filename"];
        //$filename = "/Users/aavenjin/Documents/AAF/wharton_data/AAPL.csv";
        $tempname = substr($filename, strrpos($filename, '/'));
        $tickername = substr($tempname, 1, -4);
        //echo ("<p/>importing data from $tempname....");
        if (!mysqli_query($con,"LOAD DATA LOCAL INFILE '$filename' INTO TABLE tempCSV FIELDS TERMINATED BY  ',' LINES TERMINATED BY '\n' IGNORE 1 LINES"))
		{
			die ('<p/>FAIL to load data into tempCSV: ' . mysqli_error($con));
		}

        if (!mysqli_query($con,"DELETE FROM tempCSV WHERE open=''"))
		{
			die('<p/>FAIL to filter data in tempCSV: ' . mysqli_error($con));
		}
        
        /*
         * create tables and insert index data according to cid and cname
         */
        $sql = "SELECT DISTINCT cid FROM tempCSV";
        $result = mysqli_query($con,$sql);
        if (!$result)
		{
            die ('<p/>FAIL to select cid from tempCSV: ' . mysqli_error($con));
		}
        
        $c = mysqli_num_rows($result);
        if ($c > 0)
        {
            if ($c > 1) {
                echo ("<p/>>>>> $tempname has multiple PERMNO <<<<");
            } else {
                echo ("<p/>>>>> $tempname has one PERMNO <<<<");
            }
            
            while ($row = mysqli_fetch_row($result)) {
                // for each cid, create table CompanyData+cid
                $table = "CompanyData" . $row[0];
                $s = "CREATE TABLE $table (date DATE NOT NULL, low DOUBLE NOT NULL, high DOUBLE NOT NULL, close DOUBLE NOT NULL, vol BIGINT NOT NULL, open DOUBLE NOT NULL, PRIMARY KEY (date))";
                //echo ("<p/>1. creating $table");
                $res = mysqli_query($con,$s);
                if (!$res) {
                    $e = mysqli_error($con);
                    echo ("<p/>FAIL to create $table: $e");
                }
                mysqli_free_result($res);
                
                // for each cid, insert data into CompanyData+cid
                $s = "INSERT INTO $table (date, low, high, close, vol, open) SELECT date, low, high, close, vol, open FROM tempCSV WHERE cid='$row[0]'";
                //echo ("<p/>2. insert data into $table");
                $res = mysqli_query($con,$s);
                if (!res) {
                    $e = mysqli_error($con);
                    echo ("<p/>FAIL to insert data into $table: $e");
                }
                mysqli_free_result($res);
                
                // for each cid, find all cnames
                $s = "SELECT DISTINCT cname FROM tempCSV WHERE cid='$row[0]'";
                //echo ("<p/>3. for each cid ($row[0]), find all cnames.");
                $res = mysqli_query($con,$s);
                if (!$res)
                {
                    die ('<p/>FAIL to select cname from tempCSV: ' . mysqli_error($con));
                }
                
                while ($r = mysqli_fetch_row($res)) {
                    // for each cname, find start and end dates
                    $ss = "SELECT MIN(date), MAX(date) FROM tempCSV WHERE cname='$r[0]' AND cid='$row[0]'";
                    //echo ("<p/>(a). for each cname ($r[0]), find start and end dates.");
                    $ress = mysqli_query($con,$ss);
                    if (!$ress)
                    {
                        die ('<p/>FAIL to select min(date),max(date) from tempCSV: ' . mysqli_error($con));
                    }
                    $rr = mysqli_fetch_row($ress);
                    mysqli_free_result($ress);
                    
                    // insert into IndexTable
                    $ss = "INSERT INTO IndexTable (ticker, cid, cname, start, end) VALUES ('$tickername', '$row[0]', '$r[0]', '$rr[0]', '$rr[1]')";
                    echo ("<p/>(b). insert into IndexTable: '$tickername', '$row[0]', '$r[0]', '$rr[0]', '$rr[1]'");
                    $ress = mysqli_query($con,$ss);
                    if (!$ress)
                    {
                        die ('<p/>FAIL to insert into IndexTable: ' . mysqli_error($con));
                    }
                    mysqli_free_result($ress);
                }
                mysqli_free_result($res);
            }
        } else {
            echo ("<p/>no data in $tempname");
        }
        mysqli_free_result($result);
        mysqli_close($con);
        //echo ("<p/>finish importing $tempname");
    }

    importCSV();
?>