<?php
    
    include ('ioe_dbconfig.php');
    
    if (!mysqli_query($con,"TRUNCATE TABLE $namesnp")) {
        $e = mysqli_error($con);
        echo ("<p/>FAIL to empty $namesnp: $e\n");
    }
    
    if (!mysqli_query($con,"INSERT INTO $namesnp(start,ticker) SELECT B,E FROM $snp2000")) {
        $e = mysqli_error($con);
        echo ("<p/>FAIL to copy data from $snp2000 to $namesnp: $e\n");
    }
    
    $sql = "SELECT * FROM $tempsheet ORDER BY date";
    if (!$res = mysqli_query($con,$sql)) {
        $e = mysqli_error($con);
        echo ("<p/>FAIL to select data from $tempsheet : $e\n");
    }
    
    // UPDATE events SET date_starts = DATE_ADD(date_starts,INTERVAL 14 DAY)
    while ($row = mysqli_fetch_row($res)) {
        if ($row[3] != "" && $row[3] != "---" && $row[3] != "------") {
            if (!mysqli_query($con,"UPDATE $namesnp SET end='$row[4]' WHERE ticker='$row[3]' AND $row[4]<end")) {
                $e = mysqli_error($con);
                echo ("<p/>FAIL to delete $row[3] on $row[4]: $e\n");   
            } else {
                echo ("<p/>Delete $row[3] on $row[4].\n");
            }
        }
        if ($row[1] != "" && $row[1] != "---" && $row[1] != "------") {
            if (!mysqli_query($con,"INSERT INTO $namesnp (start,ticker) VALUES ('$row[4]','$row[1]')")) {
                $e = mysqli_error($con);
                echo ("<p/>FAIL to add $row[1] on $row[4]: $e\n");   
            } else {
                echo ("<p/>Add $row[1] on $row[4] to $namesnp.\n");
            }
        }
     }
     mysqli_free_result($res);
    
    /*
    $sql = "SELECT A,C FROM $snp2009add ORDER BY A";
    if (!$res = mysqli_query($con,$sql)) {
        $e = mysqli_error($con);
        echo ("<p/>FAIL to select data from $snp2009add : $e\n");
    }
    
    while ($row = mysqli_fetch_row($res)) {
        if ($row[1] != "------") {
            if (!mysqli_query($con,"INSERT INTO $namesnp (start,ticker) VALUES ('$row[0]','$row[1]')")) {
                $e = mysqli_error($con);
                echo ("<p/>FAIL to add $row[1] on $row[0]: $e\n");   
            } else {
                echo ("<p/>Add $row[1] on $row[0] to $namesnp.\n");
            }
        }
    }
    mysqli_free_result($res);
    */
    mysqli_close($con);

?>















