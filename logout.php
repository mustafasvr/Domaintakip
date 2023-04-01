<?php 
session_start();
unset($_SESSION['userlogin']);
unset($_SESSION['adminonline']);
header("Location:login.php");
exit;
 ?>