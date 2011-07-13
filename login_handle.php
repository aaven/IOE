<?php
ob_start();

include('dbconfig.php');
// username and password sent from form 
$email=$_POST['email']; 
$password=md5($_POST['password']);

// To protect MySQL injection (more detail about MySQL injection)
$email = stripslashes($email);
$password = stripslashes($password);
$email = mysqli_real_escape_string($con,$email);
$password = mysqli_real_escape_string($con,$password);

$sql="SELECT * FROM UserTable WHERE email='$email' and password='$password'";
$result=mysqli_query($con, $sql);
if(!$result)
	{
		die('Could not insert: ' . mysqli_error());
	}
// Mysql_num_row is counting table row
$count=mysqli_num_rows($result);
// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1)
{
	// Register $myusername, $mypassword and redirect to file "login_success.php"
	session_register("email");
	session_register("password"); 
	header("location: index.php");
}
else 
{	
	echo "Wrong Username or Password";
	echo "<a href= 'login.php'>retry login</a>";
}
ob_end_flush();
?>