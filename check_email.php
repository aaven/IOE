<?php
//get the q parameter from URL
//$q=$_GET["q"];

include ('dbconfig.php');	
$email = $_POST["q"];
$email = stripslashes($email);
$email = mysqli_real_escape_string($con,$email);

$sql="SELECT email from UserTableTemp where email='$email'";
$result=mysqli_query($con,$sql);
if(!$result)
{
	die('Could not look into temp table: ' . mysqli_error());
}
$count=mysqli_num_rows($result);

if($count)
{
	$response="$email already registerd but not confirmed";
}
else
{
	$sql="SELECT email from UserTable where email='$email'";
	$result=mysqli_query($con,$sql);
	if(!$result)
	{
		die('Could not look into table: ' . mysqli_error());
	}
	$count=mysqli_num_rows($result);
	
	if($count)
	{
		$response="$email already registerd, please login";
	}
	else
	{
		$response= "email is okay";
	}
}

//output the response
echo $response;
?>