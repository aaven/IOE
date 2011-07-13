<html>
<head>
<script type="text/javascript">
function showHint(str)
{
var xmlhttp;
if (str.length==0)
  { 
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    $var = xmlhttp.responseText;
    //document.getElementById("txtHint").innerHTML=$var;
    alert($var);
    if($var == "email is okay")
    {
    	document.getElementById("txtHint").innerHTML = "email is ok";
    	document.forms[0].register.disabled = false;
    }
    else
    {
    document.getElementById("txtHint").innerHTML=$var;
    document.forms[0].register.disabled = true;
    }
    }
  }
xmlhttp.open("POST","check_email.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("q="+str);
}
</script>
</head>


<html>
<head><title>Register</title></head>
<body>
	<h1>Registration</h1>
	<form method="post" action="re_handle.php">
		<table>
		<tr><td>E-mail address:</td>
			<td><input type='email' name='email' onblur="showHint(this.value)"/></td></tr> 
		<tr><td>First name:</td>
			<td><input type='text' name='first_name'/></td></tr>
		<tr><td>Last name:</td>
			<td><input type='text' name='last_name'/></td></tr>
		<tr><td>Password:</td>
			<td><input type='password' name='password'/></td></tr>
			<tr>
			<td colspan='2'>
			<input type='submit' disabled='true' name='register' value='Register'/>
			</td>
		</tr>
		</table>
	</form>
	<p>Suggestions: <span id="txtHint"></span></p> 
</body>
</html>
