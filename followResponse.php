<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 09/11/2017
 * Time: 22:29
 */
session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);
$username=$_SESSION['username'];

if($_GET['action']){
    $follower_uName = $_POST['follower_uName'];
    if($_GET['action']=="yes"){
        $mySQL="UPDATE follow SET accepted='1' WHERE follower_uName='$follower_uName'";
        if (mysqli_query($connect, $mySQL)) {
            echo "accepted";
            header("Location:climbers.php");
        }else{
            echo "There is an error somewhere";
        }
    }elseif($_GET['action']=="no"){
        $mySQL="UPDATE follow SET accepted='0' WHERE follower_uName='$follower_uName'";
        if (mysqli_query($connect, $mySQL)) {
            echo "declined";
            header("Location:climbers.php");
        }else{
            echo "There is an error somewhere";
        }
    }else{
        echo "what?";
    }
}