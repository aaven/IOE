<!--
references 
http://www.siteground.com/tutorials/php-mysql/display_table_data.htm
http://stackoverflow.com/questions/1484816/how-can-i-set-the-value-of-a-textbox-through-php
http://www.daniweb.com/web-development/php/threads/106556
http://www.somacon.com/p117.php
-->

<html>
<body>
<form name='upcoming_form' method='GET'>
<select name="ticker">
<option value="snp" selected="selected">SNP</option>
<option value="russell">Russell</option>
<option value="nasdaq">Nasdaq</option>
</select>

<select name="smonth">
<option value='01' selected="selected">January</option>
<option value='02'>February</option>
<option value='03'>March</option>
<option value='04'>April</option>
<option value='05'>May</option>
<option value='06'>June</option>
<option value='07'>July</option>
<option value='08'>August</option>
<option value='09'>September</option>
<option value='10'>October</option>
<option value='11'>November</option>
<option value='12'>December</option>
</select>

<select name="sday" >
<option value='01' selected="selected">01</option>
<option value='02'>02</option>
<option value='03'>03</option>
<option value='04'>04</option>
<option value='05'>05</option>
<option value='06'>06</option>
<option value='07'>07</option>
<option value='08'>08</option>
<option value='09'>09</option>
<option value='10'>10</option>
<option value='11'>11</option>
<option value='12'>12</option>
<option value='13'>13</option>
<option value='14'>14</option>
<option value='15'>15</option>
<option value='16'>16</option>
<option value='17'>17</option>
<option value='18'>18</option>
<option value='19'>19</option>
<option value='20'>20</option>
<option value='21'>21</option>
<option value='22'>22</option>
<option value='23'>23</option>
<option value='24'>24</option>
<option value='25'>25</option>
<option value='26'>26</option>
<option value='27'>27</option>
<option value='28'>28</option>
<option value='29'>29</option>
<option value='30'>30</option>
<option value='31'>31</option>
</select>

<select name="syear">
<option value='2000' selected="selected">2000</option>
<option value='2001'>2001</option>
<option value='2002'>2002</option>
<option value='2003'>2003</option>
<option value='2004'>2004</option>
<option value='2005'>2005</option>
<option value='2006'>2006</option>
<option value='2007'>2007</option>
<option value='2008'>2008</option>
<option value='2009'>2009</option>
<option value='2010'>2010</option>
</select>

<select name="emonth">
<option value='01' selected="selected">January</option>
<option value='02'>February</option>
<option value='03'>March</option>
<option value='04'>April</option>
<option value='05'>May</option>
<option value='06'>June</option>
<option value='07'>July</option>
<option value='08'>August</option>
<option value='09'>September</option>
<option value='10'>October</option>
<option value='11'>November</option>
<option value='12'>December</option>
</select>

<select name="eday" >
<option value='01'>01</option>
<option value='02'>02</option>
<option value='03'>03</option>
<option value='04'>04</option>
<option value='05'>05</option>
<option value='06'>06</option>
<option value='07'>07</option>
<option value='08'>08</option>
<option value='09'>09</option>
<option value='10'>10</option>
<option value='11'>11</option>
<option value='12'>12</option>
<option value='13'>13</option>
<option value='14'>14</option>
<option value='15'>15</option>
<option value='16'>16</option>
<option value='17'>17</option>
<option value='18'>18</option>
<option value='19'>19</option>
<option value='20'>20</option>
<option value='21'>21</option>
<option value='22'>22</option>
<option value='23'>23</option>
<option value='24'>24</option>
<option value='25'>25</option>
<option value='26'>26</option>
<option value='27'>27</option>
<option value='28'>28</option>
<option value='29'>29</option>
<option value='30'>30</option>
<option value='31'selected="selected">31</option>
</select>

<select name="eyear">
<option value='2000' selected="selected">2000</option>
<option value='2001'>2001</option>
<option value='2002'>2002</option>
<option value='2003'>2003</option>
<option value='2004'>2004</option>
<option value='2005'>2005</option>
<option value='2006'>2006</option>
<option value='2007'>2007</option>
<option value='2008'>2008</option>
<option value='2009'>2009</option>
<option value='2010'>2010</option>
</select><br /><br />
<input type="submit" value="Get tickerlist"><br />

</form>

<script type="text/javascript">
function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}


function ExportData(FormName, FieldName) 
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
    
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            $var = xmlhttp.responseText;
            document.getElementById("selectedtickers").innerHTML = $var;
        }
    }
    xmlhttp.open("POST","ioe_exportdata.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("q="+FieldName);
}
</script>

<?PHP
if (isset($_GET['ticker']) && isset($_GET['smonth']) && isset($_GET['sday']) && isset($_GET['syear']) && isset($_GET['emonth']) && isset($_GET['eday']) && isset($_GET['eyear'])) {
    $indextype = $_GET['ticker'];
    $start = $_GET['syear'] . '-' . $_GET['smonth'] . '-' . $_GET['sday'];
    $end = $_GET['eyear'] . '-' . $_GET['emonth'] . '-' . $_GET['eday'];
    
    include('ioe_dbconfig.php');
    if ($indextype == "russell") {
        echo ("<p/>Sorry! Russell under construction.");
    } elseif ($indextype == "nasdaq") {
        echo ("<p/>Sorry! Nasdaq under construction.");
    } elseif ($indextype == "snp") {
        $wantedindex = $namesnp;
    }
        
    $sql = "SELECT DISTINCT ticker FROM $wantedindex WHERE end >= '$start' AND start <= '$end'";
    if (!$res = mysqli_query($con,$sql)) {
        $e = mysqli_error($con);
        echo ("<p/>FAIL to select from $wantedindex: $e\n");
    }
    
    $count = mysqli_num_rows($res);
    echo ("<p/>$indextype from $start to $end has $count tickers.\n");
    if ($count > 0) {
?>

<p><span id="selectedtickers"></span></p>
<form method='POST' action='' name='myform'>
<input type="button" onclick="SetAllCheckBoxes('myform', 'tickers[]', true);" value="select all">
<input type="button" onclick="SetAllCheckBoxes('myform', 'tickers[]', false);" value="unselect all">
<input type="submit" value="Export data"><br />

<?PHP
        $i = 0;
        while ($row = mysqli_fetch_row($res)) {
            //echo ("<p/>$row[0]\n");
            $ticker = $row[0];
?>

<input type="checkbox" id="checkedtickers" name="tickers[]" value="<?php echo htmlentities($ticker); ?>" /><?php echo $ticker; ?><br />

<?PHP
            $i++;
        }
    
        if (isset($_POST['tickers'])) {  
            $tickers = $_POST['tickers'];
            $tickers_in_index = "Selected tickers: ";
            $tickers_in_db = "Stored tickers: ";
            foreach ($tickers as $tt) {
                $tickers_in_index = $tickers_in_index . $tt . " ";
                
                $ss = "SELECT DISTINCT cid FROM $indextable WHERE ticker = '$tt' AND end >= '$start' AND start <= '$end'";
                if (!$ress = mysqli_query($con,$ss)) {
                    $e = mysqli_error($con);
                    echo ("<p/>FAIL to select from $indextable: $e");
                } else {
                    if (mysqli_num_rows($ress) > 0) {
                        $tickers_in_db = $tickers_in_db . $tt . " ";   
                   
                        // export data to csv
                        while ($rr = mysqli_fetch_row($ress)) {
                            $ctable = "CompanyData" . $rr[0];
                            $sss = "SELECT * FROM $ctable WHERE date >= '$start' AND date <= '$end'";
                            if (!$resss = mysqli_query($con,$sss)) {
                                $e = mysqli_error($con);
                                echo ("<p/>FAIL to select data for $tt from $ctable: $e\n");
                            } else {
                                $csvname = "db$tt.csv";
                                echo ("<p/>Created $csvname with id $rr[0]\n");
                                $fp = fopen($csvname, 'w');
                                fputcsv($fp, array('date','low','high','close','volume','open'));
                                while ($rrr = mysqli_fetch_row($resss)) {
                                    $list = array($rrr[0], $rrr[1], $rrr[2], $rrr[3], $rrr[4], $rrr[5]);
                                    // echo ("<p/>$rrr[0] $rrr[1] $rrr[2] $rrr[3] $rrr[4] $rrr[5]\n");
                                    fputcsv($fp, $list);
                                }
                                fclose($fp);
                            }
                        }
                 
                    }
                }
            }
            echo ("<p/>$tickers_in_index\n");
            echo ("<p/>$tickers_in_db\n");
        }
    }
}
?>
</form>
</body>
</html>
