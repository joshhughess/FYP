<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 17/04/2018
 * Time: 23:03
 */
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);

if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
    if(isset($_POST['postID'])){
        $postID = mysqli_real_escape_string($connect,$_POST['postID']);
        if(isset($_POST['comment'])){
            $comments = mysqli_real_escape_string($connect,$_POST['comment']);
            $addReport = "INSERT INTO comments(postID,comment,userID) VALUES('".$postID."','".$comments."','".$userID."')";
            $res = mysqli_query($connect,$addReport);
            if($res){
                echo "success";
                header("Location:index.php?repliedComment");
            }else{
                header("Location:index.php?error");
            }
        }else{
            header("Location:index.php?error");
        }
    }else{
        header("Location:index.php?error");
    }
}else{
    header("Location:index.php?notLoggedin");
}