<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpwd	= 'Ioe103';
$dbname = 'ioefinance';
$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
if(!$con)
{
    die('Could not connect: ' . mysqli_error());
}
?>

