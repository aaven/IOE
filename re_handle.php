<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
function insertUserT(){
	include ('dbconfig.php');
	if(!isset($_POST['register'])||($_POST['register']!='Register'))
	{
		echo"Don't reload this page";
		exit();
	}
	
	$email=$_POST["email"];
	$fname=$_POST["first_name"];
	$lname=$_POST["last_name"];
	$pwd=md5($_POST["password"]);
	
	$email = stripslashes($email);
	$pwd = stripslashes($pwd);
	$fname = stripslashes($fname);
	$lname = stripslashes($lname);

	$email = mysqli_real_escape_string($con,$email);
	$pwd = mysqli_real_escape_string($con,$pwd);
	$fname = mysqli_real_escape_string($con,$fname);
	$lname = mysqli_real_escape_string($con,$lname);
	
	$vkey=md5(uniqid(rand()));
	$sql="INSERT INTO UserTableTemp (email, firstname, lastname, password, vkey) VALUES ('$email', '$fname', '$lname', '$pwd', '$vkey')";
	
	if(!mysqli_query($con,$sql))
	{
		die('Could not insert: ' . mysqli_error());
	}
	
	mysqli_close($con);
	//$to=$email;
	include('Mail.php');
    include('Mail/mime.php'); 
    // Constructing the email
    $sender = "mobiperf@gmail.com";                                             // Your email address
    $recipient = $email;                               // The Recipients name and email address
    $subject = "Test Email";                                                // Subject for the email
    $headers['From'] = $sender;
    $headers['To']   = $recipient;
    $headers['Subject'] = $subject;
    $body = "Your Comfirmation link \r\n";
	$body.= "Click on this link to activate your account \r\n";
	$body.= "http://localhost/comfirmation.php?passkey=$vkey";         
    // SMTP authentication params
    $smtp_params["host"]     = "smtp.googlemail.com";
    $smtp_params["port"]     = "587";
    $smtp_params["auth"]     = true;
	$smtp_params["debug"]	=true; 
    $smtp_params["username"] = "mobiperf@gmail.com";
    $smtp_params["password"] = "robusTneT";
    // Sending the email using smtp
    $mail = &Mail::factory("smtp", $smtp_params);
	echo"$recipient, $headers, $body";
    $result = $mail-> send($recipient, $headers, $body);

	if($result == 1)
    {
      echo("Your message has been sent!\r\n");
    }
    else
    {
      echo("Your message was not sent: " . $result);
    }
    
	echo"Please verify your email address.";
//	echo "<meta http-equiv='refresh' content='3; url=/index.php ' />\n";
}

insertUserT();
?>
