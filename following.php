<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
?>

<div class = "container">

    <div class = "row">

        <div class = "col-lg-9">


            <div class = "panel panel-default">

                <div class = "panel-body">

                    <div class ="page-header">
                        <?php
                            $userName=$_SESSION['username'];
                            $followArray = array();
                            $mySQL = "SELECT * FROM follow WHERE follower_uName='$userName'";
                            $r = mysqli_query($connect, $mySQL);
                            if(mysqli_num_rows($r)>0): //table is non-empty
                                while($row = mysqli_fetch_assoc($r)):
                                    $followingName = $row['following_uName'];
                                    $findUser="SELECT * FROM users WHERE username='$followingName'";
                                    $res = mysqli_query($connect,$findUser);
                                    if(mysqli_num_rows($res)>0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            $username = $row['username'];
                                            $checkPref = "SELECT * FROM preferences WHERE username='$followingName'";
                                            $res = mysqli_query($connect,$checkPref);
                                            if (mysqli_num_rows($res) > 0) {
                                                while ($row = mysqli_fetch_assoc($res)) {
                                                    if($row['postVisAll']=="on"){
                                                        echo "anyone can view posts from ".$username;
                                                    }else{
                                                        $checkFollow = "SELECT * FROM follow WHERE follower_uName='$userName' AND following_uName='$followingName'";
                                                        $res = mysqli_query($connect,$checkFollow);
                                                        if (mysqli_num_rows($res) > 0) {
                                                            while ($row = mysqli_fetch_assoc($res)) {
                                                                $followID=$row['id'];
                                                                $findPosts = "SELECT * FROM post WHERE username='$followingName' ORDER BY postID DESC";
                                                                $res = mysqli_query($connect,$findPosts);
                                                                if (mysqli_num_rows($res) > 0) {
                                                                    while ($row = mysqli_fetch_assoc($res)) {
                                                                        echo "<p><a href='userProfile.php?id=".$followID."'>".$row['username']. "</a> - ".$row['post']."</p>";
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
                                                $checkFollow = "SELECT * FROM follow WHERE follower_uName='$userName' AND following_uName='$followingName'";
                                                $res = mysqli_query($connect,$checkFollow);
                                                if (mysqli_num_rows($res) > 0) {
                                                    while ($row = mysqli_fetch_assoc($res)) {
                                                        $followUname=$row['following_uName'];
                                                        $findFollowID="SELECT * FROM users WHERE username='$followUname'";
                                                        $res = mysqli_query($connect,$findFollowID);
                                                        if (mysqli_num_rows($res) > 0) {
                                                            while ($row = mysqli_fetch_assoc($res)) {
                                                                $followID=$row['userID'];
                                                            }
                                                        }
                                                        $findPosts = "SELECT * FROM post WHERE username='$followingName' ORDER BY postID DESC";
                                                        $res = mysqli_query($connect,$findPosts);
                                                        if (mysqli_num_rows($res) > 0) {
                                                            while ($row = mysqli_fetch_assoc($res)) {
                                                                //push values into array so that i can order by postID rather than by postID and userPosted
                                                                $values= array();
                                                                array_push($values,$row['postID']);
                                                                array_push($values,$followID);
                                                                array_push($values, $row['username']);
                                                                array_push($values,$row['post']);
                                                                array_push($followArray,$values);
                                                            }
                                                        } else {
                                                            echo "No posts available";
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }else{
                                        echo "User not found please try again. <a href='climbers.php'>Go back</a>";
                                    }
                                endwhile;
                            endif;
                            //sort array by postID in ascending order
                            usort($followArray, function($a, $b) {
                                return $a[0] < $b[0];
                            });
                            for($i=0;$i<(sizeof($followArray));$i++){
                                echo "<p>";
                                echo "<a href='userProfile.php?id=".$followArray[$i][1]."'>".$followArray[$i][2]."</a> - ". $followArray[$i][3];
                                echo "</p>";
                            }

                        ?>