<?php
include('connect.php');
include_once('simple_html_dom.php');
$connect = mysqli_connect($host,$userName,$password, $db);
$username = $_SESSION['username'];
if(isset($_POST['saveReview'])){
    $sql = "INSERT INTO review(climbID,userID,title,comments,starRating) VALUES('".$_POST['climbID']."','".$_SESSION['userID']."','".$_POST['reviewTitle']."','".$_POST['reviewComments']."','".$_POST['rating']."')";
    $res = mysqli_query($connect,$sql);
    if($res){
        header("Location:climb.php?id=".$_POST['climbID']."&reviewSent=1");
    }else{
        echo "something went wrong, please try again";
    }
}else{
    echo "Something went wrong";
}

?>