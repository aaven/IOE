<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Login</title>
</head>
<body>
<h1>Login</h1> 
	<form method="post" action="login_handle.php"> 
		<table> 
		<tr><td>E-mail address:</td> 
			<td><input type='email' name='email'/></td></tr>  
		<tr><td>Password:</td> 
			<td><input type='password' name='password'/></td></tr> 
			<tr> 
			<td colspan='2'> 
			<input type='submit' name='login' value='Login'/> 
			</td> 
		</tr> 
		</table> 
	</form> 
<a href="/register.php">register</a>
</body>
</html>
