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
            echo $row['username']."<br>";
        }
    }
}
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
?>