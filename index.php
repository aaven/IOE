<?php
session_start();
if(!session_is_registered(email)){
header("location:login.php");
}
?>

<html>
<body>
Welcome!!!
<a href='/logout.php'>Log out</a>
</body>
</html>
