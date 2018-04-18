<?php
session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);
if($connect)
{
    if(isset($_POST['follow'])) {
        $followerName = mysqli_real_escape_string($connect, $_POST['followerName']);
        $followingName = mysqli_real_escape_string($connect, $_POST['followingName']);
        $checkUserPref = "SELECT * FROM preferences WHERE username='$followingName'";
        $r = mysqli_query($connect, $checkUserPref);
        if (mysqli_num_rows($r) > 0) {
            while ($row = mysqli_fetch_assoc($r)) {
                if ($row['allowAllFollow'] == "N") {
                    $follow = "INSERT INTO follow(follower_uName,following_uName,accepted) VALUES('$followerName','$followingName','0')";
                    if (mysqli_query($connect, $follow)) {
                        header('Refresh: 0.5; URL = climbers.php');
                    } else {
                        echo "There is an error somewhere";
                    }
                } else {
                    $follow = "INSERT INTO follow(follower_uName,following_uName,accepted) VALUES('$followerName','$followingName','1')";
                    if (mysqli_query($connect, $follow)) {
                        header('Refresh: 0.5; URL = climbers.php');
                    } else {
                        echo "There is an error somewhere";
                    }
                }
            }
        } else {
            $follow = "INSERT INTO follow(follower_uName,following_uName,accepted) VALUES('$followerName','$followingName','1')";
            if (mysqli_query($connect, $follow)) {
                header('Refresh: 0.5; URL = climbers.php');
            } else {
                echo "There is an error somewhere";
            }
        }
    }elseif(isset($_POST['unfollow']) && $_GET['action']=="unfollow"){
        echo "unfollow";
        $followerName = mysqli_real_escape_string($connect, $_POST['followerName']);
        $followingName = mysqli_real_escape_string($connect, $_POST['followingName']);
        $mySQL = "DELETE FROM follow WHERE follower_uName='$followerName' AND following_uName='$followingName'";
        if (mysqli_query($connect, $mySQL)) {
            header("Location:climbers.php");
        }else{
            echo "There is an error somewhere";
        }
    }
}

?>