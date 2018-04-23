<?php
include('connect.php');
include_once('simple_html_dom.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
    $username = $_SESSION['username'];
}else{
    include('nav.php');
}
echo "<title>Search Results</title>";
ob_start();

$post = mysqli_real_escape_string($connect, $_POST['search']);
echo "<h3>You've searched for '".$post."'</h3>";

//search users exact
echo '<div class="row">
        <div>
            <ul class="tabs" style="height: 58px;">
                <li class="tab"><a class="active" href="#allClimbers">All Climbers</a></li>
                <li class="tab"><a href="#climbs">All Climbs</a></li>
           </ul>
      </div>';
echo "<div class='col s12' id='allClimbers'>";
$searchUsersExact = "SELECT * FROM users WHERE username='".$post."'";
$res = mysqli_query($connect,$searchUsersExact);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        header("Location:userProfile.php?id=".$row['userID']);
    }
}else{
    //search users
    $searchUsers= "SELECT * FROM users WHERE username LIKE '%".$post."%'";
    $res = mysqli_query($connect,$searchUsers);
    if(mysqli_num_rows($res)>0){
        while($row=mysqli_fetch_assoc($res)){
            echo "<p><a href='userProfile.php?id=".$row['userID']."'>".$row['username']."</a></p>
            <p>Last Active: ".getTime($row['lastActive'])."</p>";
        }
    }
}
echo "</div>";
echo "<div class='col s12' id='climbs'>";
//search users exact
$searchUsersExact = "SELECT * FROM climbs WHERE name='".$post."'";
$res = mysqli_query($connect,$searchUsersExact);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        header("Location:climb.php?id=".$row['climbID']);
    }
}else{
    //search users
    $searchUsers= "SELECT * FROM climbs WHERE name LIKE '%".$post."%'";
    $res = mysqli_query($connect,$searchUsers);
    if(mysqli_num_rows($res)>0){
        echo "<ul class='collapsible'>";
        while($row = mysqli_fetch_assoc($res)){
            showClimbs($row);
        }
        echo "</ul>";
    }
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
<style>
    .tabs .tab a{
        color:#000;
        padding:0 10px;
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
</style>