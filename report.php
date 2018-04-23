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
    $postID = mysqli_real_escape_string($connect,$_POST['postID']);
}
if(isset($_POST['group1'])){
$type=mysqli_real_escape_string($connect,$_POST['group1']);
}
if(isset($_POST['comments'])){
 $comments = mysqli_real_escape_string($connect,$_POST['comments']);
}
if(isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    if (isset($_POST['commentID'])) {
        $commentID = $_POST['commentID'];
    }
    if (isset($commentID)) {
        $checkIfReported = "SELECT * FROM report WHERE postID='" . $postID . "' AND commentID='" . $commentID . "' AND userID='" . $userID . "'";
    } else {
        $checkIfReported = "SELECT * FROM report WHERE postID='" . $postID . "' AND userID='" . $userID . "'";
    }
    $res = mysqli_query($connect, $checkIfReported);
    if (mysqli_num_rows($res) > 0) {
        header("Location:index.php?reportAlreadySent");
    } else {
        if (isset($commentID)) {
            $addReport = "INSERT INTO report(postID,commentID,isType,comments,userID) VALUES('" . $postID . "','" . $commentID . "','" . $type . "','" . $comments . "','" . $userID . "')";
        } else {
            $addReport = "INSERT INTO report(postID,isType,comments,userID) VALUES('" . $postID . "','" . $type . "','" . $comments . "','" . $userID . "')";
        }
        $res = mysqli_query($connect, $addReport);
        if ($res) {
            echo "success";
            header("Location:index.php?reportSent");
        } else {
            echo mysqli_error($connect);
        }
    }
}else{
   header("Location:index.php?notLoggedin");
}