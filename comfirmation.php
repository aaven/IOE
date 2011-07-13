<?php
ob_start();

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
function insertUser()
{
	include ('dbconfig.php');
        $vkey=$_GET["passkey"];
        $sql="SELECT * FROM UserTableTemp WHERE vkey = '$vkey'";
		$result=mysqli_query($con, $sql);
		$count = mysqli_num_rows($result);
        if($count!=1)
        {
                die('Invaild Confirmation: ' . mysqli_error());
        }
	else
	{
		$row = mysqli_fetch_assoc($result);
		$email = $row['email'];echo"$email";
		$fname = $row['firstname'];
		$lname = $row['lastname'];
		$pwd = $row['password'];
		$sql="DELETE FROM UserTableTemp WHERE email = '$email'";
		if(!mysqli_query($con,$sql))
		{
		die('Could not delete: ' . mysqli_error());	
		}
		$sql="INSERT INTO UserTable (email, firstname, lastname, password) VALUES ('$email', '$fname', '$lname', '$pwd')";
		if(!mysqli_query($con,$sql))
	        {
                die('Could not insert: ' . mysqli_error());
       		}
      		mysqli_close($con);
      	session_register("email");
      	session_register("pwd");
		echo"Congratulation! Your membership has been comfirmed.\r \n";
		echo "<meta http-equiv='refresh' content='3; url=/index.php ' />\n";
	}

}

insertUser();	

ob_end_flush();
?>
