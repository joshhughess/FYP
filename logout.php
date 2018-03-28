<?php
    session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);
date_default_timezone_set('Europe/London');
$dateTime = date("Y-m-d h:i:s");
$updateActivity = "UPDATE users SET lastActive='".$dateTime."' WHERE username='".$_SESSION['username']."'";
$res = mysqli_query($connect,$updateActivity);
if(!$res){echo mysqli_error($connect);}
unset($_SESSION["username"]);
unset($_SESSION["userID"]);
unset($_SESSION['email']);
unset($_SESSION['passwordKey']);
echo 'You have logged out';
header('Refresh: 0.5; URL = index.php');
?>