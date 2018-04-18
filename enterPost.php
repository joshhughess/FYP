<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 08/11/2017
 * Time: 18:31
 */
session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);
if($connect)
{
    if(isset($_POST['post'])){
        date_default_timezone_set('Europe/London');
        $datePost = date('Y-m-d H:i:s');
        $timePost = date("H:i:s");
        $username=$_SESSION['username'];
        $post=mysqli_real_escape_string($connect,$_POST['post']);
        $mySQL = "INSERT INTO post(username,post,timePost,datePost) VALUES('$username','$post','$timePost','$datePost')";
        if (mysqli_query($connect, $mySQL)) {
            header("Location:index.php");
        }else{
            echo "There is an error somewhere";
        }
    }
}