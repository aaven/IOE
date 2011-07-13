<?php
$dbhost = 'localhost';
$dbuser = '';
$dbpwd	= '';
$dbname = 'ioefinance';
$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
if(!$con)
{
    die('Could not connect: ' . mysqli_error());
}
?>

