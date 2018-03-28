<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
$username = $_SESSION['username'];
$mySQL = "SELECT * FROM follow WHERE following_uName='$username' AND accepted='0'";
$r = mysqli_query($connect, $mySQL);
if(mysqli_num_rows($r)>0) {
    while ($row = mysqli_fetch_assoc($r)) {
        echo $row['follower_uName']." has requested to follow you. Do you wish to accept? <form method='post' action='followResponse.php?action=yes' id='acceptYes'><input type='hidden' value='".$row['follower_uName']."' name='follower_uName'><input type='submit' value='Yes'></form><form id='acceptNo' method='post' action='followResponse.php?action=no'><input type='hidden' value='".$row['follower_uName']."' name='follower_uName'><input type='submit' value='No'></form>";
    }
}