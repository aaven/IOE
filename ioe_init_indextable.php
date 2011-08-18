<?php
    
    echo ("NOTICE: use this script only ONCE.");
    include ('ioe_dbconfig.php');
    echo "<p/>Starting init ticker list in $dbname<p/>";
    
    $sql = "CREATE TABLE $namesnp (start DATE NOT NULL DEFAULT '2000-01-03', end DATE NOT NULL DEFAULT '2010-12-31', ticker VARCHAR(10) NOT NULL, cid VARCHAR(20), PRIMARY KEY(start,ticker), UNIQUE(ticker, end))";
    if (!mysqli_query($con,$sql))
    {
        echo"<p/>Could not create $namesnp: " . mysqli_error($con);
    } else {
        echo ("<p/>$namesnp created.");
    }
    
    $sql = "CREATE TABLE $tempsheet (cnameadd VARCHAR(50) NOT NULL, tickeradd VARCHAR(10) NOT NULL, cnamedel VARCHAR(50) NOT NULL, tickerdel VARCHAR(10) NOT NULL, date DATE NOT NULL)";
    if (!mysqli_query($con,$sql))
    {
        echo"<p/>Could not create $tempsheet: " . mysqli_error($con);
    } else {
        echo ("<p/>$tempsheet created.");
    }
    
    // $s = "UPDATE $namesnp SET $namesnp.cid = CONCAT($namesnp.cid, ',$indextable.cid') FROM $namesnp INNER JOIN $indextable ON $indextable.ticker = $namesnp.ticker";
    $filename = "/Applications/MAMP/htdocs/ioedata/SNP500_IndexChange_2000-2009.csv";
    if (!mysqli_query($con,"LOAD DATA LOCAL INFILE '$filename' INTO TABLE $tempsheet FIELDS TERMINATED BY  ',' LINES TERMINATED BY '\n' IGNORE 3 LINES")) {
        $e = mysqli_error($con);
        echo ("<p/>FAIL to load data : $e\n");
    } else {
        echo ("<p/>$tempsheet data imported.");
    }
    
    mysqli_close($con);

?>