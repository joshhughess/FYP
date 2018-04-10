<?php
include('connect.php');
include_once('simple_html_dom.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
$username = $_SESSION['username'];

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
            echo "<li><div class='collapsible-header' style='display: block'><h5>".$row['name']." - ".$row['grade']."<a href='climb.php?id=".$row['climbID']."' class='right'><i class='material-icons' style='color:rgba(0,0,0,0.87)'>info_outline</i></a></h5></div>";
            echo "<div class='collapsible-body'><h6>Climbing Types</h6><ul class='collection'>";
            if($row['isSport']==1){
                echo "<li class='collection-item'>Sport</li>";
            }
            if($row['isTrad']==1){
                echo "<li class='collection-item'>Trad</li>";
            }
            if($row['isTopRope']==1){
                echo "<li class='collection-item'>Top Rope</li>";
            }
            if($row['isBouldering']==1){
                echo "<li class='collection-item'>Bouldering</li>";
            }
            if($row['isMountaineering']==1){
                echo "<li class='collection-item'>Mountaneering</li>";
            }
            if($row['isFreeSolo']==1){
                echo "<li class='collection-item'>Free Solo</li>";
            }
            echo "</ul>";
            echo $row['information']."</div>";
            echo "</li>";
        }
        echo "</ul>";
    }
}
?>