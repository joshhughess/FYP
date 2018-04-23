<?php
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_SESSION['userID'])) {
    if ($connect) {
        if (isset($_POST['follow'])) {
            $followerName = mysqli_real_escape_string($connect, $_POST['followerName']);
            $followingName = mysqli_real_escape_string($connect, $_POST['followingName']);
            $checkUserPref = "SELECT * FROM preferences WHERE username='$followingName'";
            $r = mysqli_query($connect, $checkUserPref);
            if (mysqli_num_rows($r) > 0) {
                while ($row = mysqli_fetch_assoc($r)) {
                    if ($row['allowAllFollow'] == "N") {
                        $follow = "INSERT INTO follow(follower_uName,following_uName,accepted) 
                        VALUES('$followerName','$followingName','0')";
                        if (mysqli_query($connect, $follow)) {
                            //send email
                            $to = findUserEmail(findUserID($followingName));
                            $subject = findUsername($_SESSION['userID']) . " has started following you!";
                            $message = "<html><body>";
                            $message .= "<p>" . $followerName . " has started following you, feel free to go to their profile to check them out</p>";
                            $message .= "<a href='localhost/myClimb/userProfile.php?id=".findUserID($followerName)."'>Go to profile</a></body></html>";
                            $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
                            $headers .= 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            //*********************redirect is dodgy!!!!!
                            if (mail($to, $subject, $message, $headers)) {
                                $true = true;
                                //                    header("Location: userProfile.php");
                            }
                            if ($true) {
                                header('Refresh: 0.5; URL = index.php');
                                exit();
                            }
                        } else {
                            echo "There is an error somewhere";
                        }
                    } else {
                        $follow = "INSERT INTO follow(follower_uName,following_uName,accepted) 
                        VALUES('$followerName','$followingName','1')";
                        if (mysqli_query($connect, $follow)) {
                            $to = findUserEmail(findUserID($followingName));
                            $subject = findUsername($_SESSION['userID']) . " has started following you!";
                            $message = "<html><body>";
                            $message .= "<p>" . $followerName . " has started following you, feel free to go to their profile to check them out</p>";
                            $message .= "<a href='localhost/myClimb/userProfile.php?id=".findUserID($followerName)."'>Go to profile</a></body></html>";
                            $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
                            $headers .= 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            //*********************redirect is dodgy!!!!!
                            if (mail($to, $subject, $message, $headers)) {
                                $true = true;
                                //                    header("Location: userProfile.php");
                            }
                            if ($true) {
                                header('Refresh: 0.5; URL = index.php');
                                exit();
                            }
                        } else {
                            echo "There is an error somewhere";
                        }
                    }
                }
            }
        } elseif (isset($_POST['unfollow']) && $_GET['action'] == "unfollow") {
            $followerName = mysqli_real_escape_string($connect, $_POST['followerName']);
            $followingName = mysqli_real_escape_string($connect, $_POST['followingName']);
            $mySQL = "DELETE FROM follow WHERE follower_uName='$followerName' AND following_uName='$followingName'";
            if (mysqli_query($connect, $mySQL)) {
                header("Location:index.php");
            } else {
                echo "There is an error somewhere";
            }
        }
    }
}else{
    header("Location:index.php?notLoggedin");
}

?>