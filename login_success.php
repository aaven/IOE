<? 
session_start();
if(!session_is_registered(email)){
header("location:login.php");
}
?>

<html>
<body>
Login Successful
</body>
</html>

n