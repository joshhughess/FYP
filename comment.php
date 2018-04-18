<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 17/04/2018
 * Time: 23:03
 */
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_POST['postID'])){
    $postID = $_POST['postID'];
}
if(isset($_POST['comment'])){
    $comments = $_POST['comment'];
}
if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
}

echo $postID." - ".$comments." -> ".$userID;

$addReport = "INSERT INTO comments(postID,comment,userID) VALUES('".$postID."','".$comments."','".$userID."')";
$res = mysqli_query($connect,$addReport);
if($res){
    echo "success";
    header("Location:climbers.php?replied");
}else{
    echo mysqli_error($connect);
}