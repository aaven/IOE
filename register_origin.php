<html>
<head><title>Register</title></head>
<body>
	<h1>Registration</h1>
	<form method="post" action="re_handle.php">
		<table>
		<tr><td>E-mail address:</td>
			<td><input type='email' name='email' onchange='check_email()'/></td></tr>
		<tr><td>First name:</td>
			<td><input type='text' name='first_name'/></td></tr>
		<tr><td>Last name:</td>
			<td><input type='text' name='last_name'/></td></tr>
		<tr><td>Password:</td>
			<td><input type='password' name='password'/></td></tr>
			<tr>
			<td colspan='2'>
			<input type='submit' name='register' value='Register'/>
			</td>
		</tr>
		</table>
	</form>
</body>
</html>
