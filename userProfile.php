<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
if(isset($_SESSION['username'])) {
    $currentUser = $_SESSION['username'];
}else{
    $currentUser="";
}
if(isset($_GET['id'])){
    $userID = $_GET['id'];
    echo "<button class='btn waves-effect waves-light'><a href='messages.php?user=".$userID."' style='color:#fff'>Message user</a></button>";
    echo "<button class='btn waves-effect waves-light'><a href='meetup.php?user=".$userID."' style='color:#fff'>Climb with user</a></button>";
    $findUser="SELECT * FROM users WHERE userID='$userID'";
    $res = mysqli_query($connect,$findUser);
    if(mysqli_num_rows($res)>0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $username = $row['username'];
            $checkPref = "SELECT * FROM preferences WHERE username='$username'";
            $res = mysqli_query($connect,$checkPref);
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    if($row['postVisAll']=="on"){
                        echo "anyone can view posts from ".$username;
                    }else{
                        $checkFollow = "SELECT * FROM follow WHERE follower_uName='$currentUser' AND following_uName='$username'";
                        $res = mysqli_query($connect,$checkFollow);
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $findPosts = "SELECT * FROM post WHERE username='$username'";
                                $res = mysqli_query($connect,$findPosts);
                                if (mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo $row['post'];
                                    }
                                } else {
                                    echo "No posts available";
                                }
                            }
                        }else{
                            echo "You need to be following this user to view their posts.";
                        }
                    }
                }
            }else {
                $findPosts = "SELECT * FROM post WHERE username='$username' ORDER BY 'datePost' DESC";
                $res = mysqli_query($connect,$findPosts);
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<p>".$row['username']. " - ".$row['post']." - ".$row['datePost']."</p>";
                    }
                } else {
                    echo $username;
                    echo "No posts available";
                }
            }
        }
    }else{
        echo "User not found please try again. <a href='climbers.php'>Go back</a>";
    }
}else{
    header("Location:index.html");
}

function date_compare($a, $b)
{
    $t1 = strtotime($a[2]);
    $t2 = strtotime($b[2]);
    return $t1 - $t2;
}

?>