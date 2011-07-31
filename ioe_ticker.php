<form name='upcoming_form' method='GET'>
<select name="ticker">
<option value="russel">Russel 2000</option>
<option value="snp">SNP</option>
<option value="nasdaq">Nasdaq</option>
</select>
<select name="year">
<option value="2000">2000</option>
<option value="2001">2001</option>
<option value="2002">2002</option>
<option value="2003">2003</option>
<option value="2004">2004</option>
<option value="2005">2005</option>
<option value="2006">2006</option>
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
</select>
<p><button id='list'>Get tickerlist</button></p>
</form>

<script>
document.getElementById("list").addEventListener("click",extract,false);
// Get user input and submit form
function extract(){
    document.upcoming_form.submit();
} 
</script>

<?php
    include ('ioe_dbconfig.php');
    
    if(isset($_GET['ticker']) && isset($_GET['year'])){
        $year = $_GET['year'];
        $ticker = $_GET['ticker'];
        
        $start = $year.'-01-01';
        $end = $year.'-12-31';
        
        echo("Extracting data...");
        
        $sql = "SELECT date, cname, ticker, BIDLO, ASKHI, PRC, VOL, OPENPRC FROM tempCSV WHERE $ticker='Y' and date>='$start' AND date<='$end'";
        $res = mysqli_query($con,$sql);
        if (!$res)
        {
            echo"<p/>Could not select: " . mysqli_error($con);
        } 
        
        if (mysqli_num_rows($res) == 0) {
            echo("<p>No result found!</p>");
        } else {
        
            while ($row = mysqli_fetch_row($res)) {
                echo("<p>$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7]</p>");
            }
        }
        
        // Display results and unset the global array $_GET
        unset($_GET);
    }
?>
