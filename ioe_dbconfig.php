<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpwd	= 'root';
$dbname = 'ioefinance';
$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
if(!$con)
{
    die('Could not connect: ' . mysqli_error());
}
?>

