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
        $postID = mysqli_real_escape_string($connect,$_POST['postID']);
        if (isset($_POST['commentID'])) {
            $commentID = mysqli_real_escape_string($connect,$_POST['commentID']);
            $removeComment = "DELETE FROM comments WHERE commentID='" . $commentID . "' AND postID='" . $postID . "'";
            $res = mysqli_query($connect, $removeComment);
            if ($res) {
                header("Location:posts.php?postID=" . $postID . "&removedComment");
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