<?php
include('connect.php');
include_once('simple_html_dom.php');
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    if (isset($_POST['saveReview'])) {
        $climbID = mysqli_real_escape_string($connect,$_POST['climbID']);
        $userID = mysqli_real_escape_string($connect,$_SESSION['userID']);
        $reviewTitle = mysqli_real_escape_string($connect,$_POST['reviewTitle']);
        $reviewComments = mysqli_real_escape_string($connect,$_POST['reviewComments']);
        $starRating = mysqli_real_escape_string($connect,$_POST['starRating']);
        $sql = "INSERT INTO review(climbID,userID,title,comments,starRating) VALUES('" . $climbID . "','" . $userID . "','" . $reviewTitle . "','" . $reviewComments . "','" . $starRating . "')";
        $res = mysqli_query($connect, $sql);
        if ($res) {
            header("Location:climb.php?id=" . $_POST['climbID'] . "&reviewSent=1");
        } else {
            echo "something went wrong, please try again";
        }
    } else {
        echo "Something went wrong";
    }
}else{
    header("Location:index.php?notLoggedin");
}
?>