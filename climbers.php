<?php
include 'connect.php';
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
$q = "SELECT * FROM users";
$r = mysqli_query($connect, $q);
if(!isset($_SESSION['username'])){
    while ($row = mysqli_fetch_assoc($r)){
            echo "<p><a href='userProfile.php?id=".$row['userID']."'>".$row['username']."</a></p>
            <p>Last Active: ".getTime($row['lastActive'])."</p>";
    }
}elseif(mysqli_num_rows($r)>0) { //table is non-empty
    while ($row = mysqli_fetch_assoc($r)){
        //dont show current user signed in
        if ($row['username'] != $_SESSION['username']) {
            $checkFollow = "SELECT * FROM follow WHERE follower_uName='" . $_SESSION['username'] . "' AND following_uName='" . $row['username'] . "'";
            $r2 = mysqli_query($connect, $checkFollow);
            if(mysqli_num_rows($r2)>0) { //table is non-empty
                while ($row2 = mysqli_fetch_assoc($r2)) {
                    if ($row2['accepted'] == '0') {
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button name='unfollow' type='submit'>Pending</button> </form>";
                    }else {
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button name='unfollow' type='submit'>Following</button> </form>";
                    }
                }
            }else {
                echo "<form id='follow' action='follow.php' method='post'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'> <button name='follow' type='submit'>Follow</button> </form>  ";
            }
        }
    }
}
function getTime($lastActiveTime){
    date_default_timezone_set('Europe/London');
    $dateTime = date("Y-m-d h:i:s");
    $userTime = new DateTime($lastActiveTime);
    $now = new DateTime($dateTime);
    $dateDiff = $userTime->diff($now);
    //if over a year
    if($dateDiff->format("%Y")>=1){
        return $dateDiff->format("%Y")." years ago.";
    }else{
        //if over 28 days
        if($dateDiff->format("%d")>=28){
            return $dateDiff->format("%m")." months ago.";
        }else{
            //if a day or over
            if($dateDiff->format("%d")>=1){
                return $dateDiff->format("%d")." days ago.";
            }else{
                //if over an hour
                if($dateDiff->format("%h")>1){
                    //if under 24 hours
                    if($dateDiff->format("%h")<=24){
                        return $dateDiff->format("%h")." hours ago";
                    }
                }else{
                    if($dateDiff->format("%m")<=1){
                        return "about a minute ago";
                    }else{
                        return $dateDiff->format("%m")." minutes ago";
                    }
                }
            }
        }
    }
}
?>
<html>
<head>
    <title>All Climbers</title>
</head>
<body>
