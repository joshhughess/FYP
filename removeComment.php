<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 18/04/2018
 * Time: 12:48
 */
include 'connect.php';

$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_SESSION['userID'])) {
    if (isset($_POST['postID'])) {
        if (isset($_POST['commentID'])) {
            $removeComment = "DELETE FROM comments WHERE commentID='" . $_POST['commentID'] . "' AND postID='" . $_POST['postID'] . "'";
            $res = mysqli_query($connect, $removeComment);
            if ($res) {
                header("Location:posts.php?postID=" . $_POST['postID'] . "&removedComment");
            } else {
                echo mysqli_error($connect);
            }
        } else {
            header("Location:index.php");
        }
    } else {
        header("Location:index.php");
    }
}else{
    header("Location:index.php?notLoggedin");
}