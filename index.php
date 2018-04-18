<?php
include('connect.php');
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
?>
<html>
		<head>
		<title>myClimb</title>

		</head>
