<?php
include 'connect.php';
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
echo "<style>
    span.link a {
        font-size:150%;
        color: #000000;
        text-decoration:none;
    }	
</style>";
$userName=$_SESSION['username'];
echo "<title>All Climbers</title>";
echo '<div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab"><a class="active" href="#allClimbers">All Climbers</a></li>
        <li class="tab"><a href="#following">Following</a></li>
        <li class="tab"><a href="#followingRequests">Following Requests</a></li>
            </ul>
      </div>';
echo "<style>
.tabs .tab a{
            color:#000;
        } /*Black color to the text*/

        .tabs .tab a:hover {
            background-color:#eee;
            color:#000;
        } /*Text color on hover*/

        .tabs .tab a.active {
            color:#000;
        } /*Background and text color when a tab is active*/

        .tabs .indicator {
            background-color:#000;
        } 
</style>";
echo "<div class='col s12' id='allClimbers'>";
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
echo "</div>";
echo "<div class='col s12' id='following'>";
$findSelfFollow = "SELECT * FROM follow WHERE follower_uName='".$_SESSION['username']."' AND following_uName='".$_SESSION['username']."'";
$res = mysqli_query($connect,$findSelfFollow);
if($res){
    while($row = mysqli_fetch_assoc($res)){
        $selfFollowID = $row['followID'];
    }
}else{
    echo mysqli_error($connect);
}
$findFollowing = "SELECT * FROM follow WHERE follower_uName='".$_SESSION['username']."' AND followID!='".$selfFollowID."'";
$res = mysqli_query($connect,$findFollowing);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        if($row['following_uName']!=$_SESSION['username']){
            $otherUser = $row['following_uName'];
        }
        $findUser = "SELECT * FROM users WHERE username='".$otherUser."'";
        $res2 = mysqli_query($connect,$findUser);
        if(mysqli_num_rows($res2)>0){
            while($row2 = mysqli_fetch_assoc($res2)){
                if ($row['accepted'] == '0') {
                    echo "<form id='unfollow' action='follow.php?action=unfollow' method='post'><a href='userProfile.php?id=".$row2['userID']."'>" . $row2['firstName'] . " " . $row2['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row2['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button name='unfollow' type='submit'>Pending</button> </form>";
                }else {
                    echo "<form id='unfollow' action='follow.php?action=unfollow' method='post'><a href='userProfile.php?id=".$row2['userID']."'>" . $row2['firstName'] . " " . $row2['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row2['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button name='unfollow' type='submit'>Following</button> </form>";
                }
            }
        }else{
            echo "unable to find user";
        }
    }
}else{
    echo "You don't seem to be following anyone at the moment. Don't be shy!";
}
echo "</div>";
echo "<div class='col s12' id='followingRequests'>";
$mySQL = "SELECT * FROM follow WHERE following_uName='".$_SESSION['username']."' AND accepted='0'";
$r = mysqli_query($connect, $mySQL);
if(mysqli_num_rows($r)>0) {
    while ($row = mysqli_fetch_assoc($r)) {
        echo $row['follower_uName']." has requested to follow you. Do you wish to accept? <form method='post' action='followResponse.php?action=yes' id='acceptYes'><input type='hidden' value='".$row['follower_uName']."' name='follower_uName'><input type='submit' value='Yes'></form><form id='acceptNo' method='post' action='followResponse.php?action=no'><input type='hidden' value='".$row['follower_uName']."' name='follower_uName'><input type='submit' value='No'></form>";
    }
}else{
    echo mysqli_error($connect);
}
echo "</div>";
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