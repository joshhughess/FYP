<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 18/04/2018
 * Time: 15:46
 */
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
$deleteUser = "DELETE FROM users WHERE userID='".$_SESSION['userID']."'";
$hasDeleted = false;
$res = mysqli_query($connect,$deleteUser);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting users";
    echo mysqli_error($connect);
}
$deleteReview = "DELETE FROM review WHERE userID='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$deleteReview);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting review";
    echo mysqli_error($connect);
}
$deleteReport = "DELETE FROM report WHERE userID='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$deleteReport);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting report";
    echo mysqli_error($connect);
}
$deletePref = "DELETE FROM preferences WHERE username='".$_SESSION['username']."'";
$res = mysqli_query($connect,$deletePref);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting pref";
    echo mysqli_error($connect);
}
$deletePost = "DELETE FROM post WHERE username='".$_SESSION['username']."'";
$res = mysqli_query($connect,$deletePost);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting post";
    echo mysqli_error($connect);
}
$deleteMessages = "DELETE FROM messages WHERE sentFromID='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$deleteMessages);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting messages";
    echo mysqli_error($connect);
}
$deleteMeetings = "DELETE FROM meetings WHERE userID='".$_SESSION['userID']."' OR user2id='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$deleteMeetings);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting meetings";
    echo mysqli_error($connect);
}
$deleteHasClimbed = "DELETE FROM hasClimbed WHERE userID='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$deleteHasClimbed);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting has climbed";
    echo mysqli_error($connect);
}
$deleteFollow = "DELETE FROM follow WHERE follower_uName='".$_SESSION['username']."' OR following_uName='".$_SESSION['username']."'";
$res = mysqli_query($connect,$deleteFollow);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting follow";
    echo mysqli_error($connect);
}
$deleteConversations = "DELETE FROM conversations WHERE userID='".$_SESSION['userID']."' OR user2id='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$deleteConversations);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting conversations";
    echo mysqli_error($connect);
}
$deleteComments = "DELETE FROM comments WHERE userID='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$deleteComments);
if($res){
    $hasDeleted = true;
}else{
    $hasDeleted = false;
    echo "error deleting comments";
    echo mysqli_error($connect);
}
if($hasDeleted) {
    unset($_SESSION["username"]);
    unset($_SESSION["userID"]);
    unset($_SESSION['email']);
    unset($_SESSION['passwordKey']);
    echo 'You have logged out';
    header('Refresh: 0.5; URL = index.php');
}