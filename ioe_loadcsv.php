<?php
    
    function importCSV(){
        include ('ioe_dbconfig.php');
        
        /*
         * Attention: under windows, only lower case table name is used. 
         * Can add to 'path-to-mysql\bin\my.ini':
         *          lower_case_table_names=0
         * Here we simply use lower case table names.
         */
        $temptable = "tempcsv";
        $indextable = "indextable";
        
        $names = $_GET["filename"];
        while ($names != "") {
            /*
             * get filename from names list
             */
            $pos = strpos($names, ',');
            $tickername = substr($names, 0, $pos);
            $names = substr($names, $pos+1);
            $filename = "wharton_data/$tickername.csv";
            
            /*
             * empty tempCSV, load data into tempCSV
             */
            if (!mysqli_query($con,"TRUNCATE TABLE $temptable"))
            {
                $e = mysqli_error($con);
                echo ("FAIL to empty $temptable: $e\n");
            }
            if (!mysqli_query($con,"LOAD DATA LOCAL INFILE '$filename' INTO TABLE $temptable FIELDS TERMINATED BY  ',' LINES TERMINATED BY '\n' IGNORE 1 LINES"))
            {
                $e = mysqli_error($con);
                echo ("FAIL to load data into $temptable: $e\n");
            }
            if (!mysqli_query($con,"DELETE FROM $temptable WHERE open=''"))
            {
                $e = mysqli_error($con);
                echo ("FAIL to filter data in $temptable: $e\n");
            }
            
            /*
             * create CompanyData tables, and insert symbol info according to cid and cname into IndexTable
             */
            $sql = "SELECT DISTINCT cid FROM $temptable";
            $result = mysqli_query($con,$sql);
            if (!$result)
            {
                $e = mysqli_error($con);
                echo ("FAIL to select cid from $temptable: $e\n");
            }
            
            $c = mysqli_num_rows($result);
            if ($c > 0)
            {
                if ($c > 1) {
                    echo (">>>> $tickername has MULTIPLE permno <<<<\n");
                } else {
                    echo (">>>> $tickername has ONE permno <<<<\n");
                }
                
                while ($row = mysqli_fetch_row($result)) {
                    // for each cid, create table CompanyData+cid
                    $table = "CompanyData" . $row[0];
                    $s = "CREATE TABLE $table (date DATE NOT NULL, low DOUBLE NOT NULL, high DOUBLE NOT NULL, close DOUBLE NOT NULL, vol BIGINT NOT NULL, open DOUBLE NOT NULL, PRIMARY KEY (date))";
                    //echo ("1. creating $table\n");
                    $res = mysqli_query($con,$s);
                    if (!$res) {
                        $e = mysqli_error($con);
                        echo ("FAIL to create $table: $e\n");
                    }
                    mysqli_free_result($res);
                    
                    // for each cid, insert data into CompanyData+cid
                    $s = "INSERT INTO $table (date, low, high, close, vol, open) SELECT date, low, high, close, vol, open FROM $temptable WHERE cid='$row[0]'";
                    //echo ("2. insert data into $table\n");
                    $res = mysqli_query($con,$s);
                    if (!res) {
                        $e = mysqli_error($con);
                        echo ("FAIL to insert data into $table: $e\n");
                    }
                    mysqli_free_result($res);
                    
                    // for each cid, find all cnames
                    $s = "SELECT DISTINCT cname FROM $temptable WHERE cid='$row[0]'";
                    //echo ("3. for each cid ($row[0]), find all cnames.\n");
                    $res = mysqli_query($con,$s);
                    if (!$res)
                    {
                        $e = mysqli_error($con);
                        echo ("FAIL to select cname from $temptable: $e\n");
                    }
                    
                    while ($r = mysqli_fetch_row($res)) {
                        // for each cname, find start and end dates
                        $ss = "SELECT MIN(date), MAX(date) FROM $temptable WHERE cname='$r[0]' AND cid='$row[0]'";
                        //echo ("(a). for each cname ($r[0]), find start and end dates.\n");
                        $ress = mysqli_query($con,$ss);
                        if (!$ress)
                        {
                            $e = mysqli_error($con);
                            echo ("FAIL to select min(date),max(date) from $temptable: $e\n");
                        }
                        $rr = mysqli_fetch_row($ress);
                        mysqli_free_result($ress);
                        
                        // insert into IndexTable
                        $ss = "INSERT INTO $indextable (ticker, cid, cname, start, end) VALUES ('$tickername', '$row[0]', '$r[0]', '$rr[0]', '$rr[1]')";
                        echo ("(b). insert into $indextable: '$tickername', '$row[0]', '$r[0]', '$rr[0]', '$rr[1]'\n");
                        $ress = mysqli_query($con,$ss);
                        if (!$ress)
                        {
                            $e = mysqli_error($con);
                            echo ("FAIL to insert into $indextable: $e\n");
                        }
                        mysqli_free_result($ress);
                    }
                    mysqli_free_result($res);
                }
            } else {
                echo (">>>> $tickername has no data <<<<\n");
            }
            mysqli_free_result($result);
            //echo ("finish importing $tickername\n");
        }
        mysqli_close($con);
        
    }
    
    importCSV();
    ?>