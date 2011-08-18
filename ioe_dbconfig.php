<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpwd	= 'root'; // Ioe103
$dbname = 'ioefinance';
$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
if(!$con)
{
    die('Could not connect: ' . mysqli_error());
}
    
    $temptable = "tempcsv";
    $indextable = "indextable";
    
    $snp2000 = "Sheet1"; // SP500_20000103.xls
    $snp2009add = "p2502_dat"; // SNP500_additions_2009sep-present.xls
    $snp2009del = "p2501_dat"; // SNP500_deletion_2009sep-present.xls
    
    $tempsheet = "snpchange00"; // SNP500_IndexChange_2000-2009.csv
    $namesnp = "snptable";
    
?>

