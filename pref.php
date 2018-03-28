<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 08/11/2017
 * Time: 19:43
 */
session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);
$username=$_SESSION['username'];
$email=$_SESSION['email'];
$postVisAll=null;
$allowAllFollow=null;
if(isset($_POST['postVisAll'])){
    $postVisAll = $_POST['postVisAll'];
}
if(isset($_POST['allowAllFollow'])){
    $allowAllFollow = $_POST['allowAllFollow'];
}
if(isset($_POST['post'])){
    //check first to see if preference is there
    $check = "SELECT * FROM preferences WHERE username='$username'";
    $res = mysqli_query($connect,$check);
    if(mysqli_num_rows($res)>0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $update = "UPDATE preferences SET postVisAll='$postVisAll', allowAllFollow='$allowAllFollow' WHERE username='$username'";
            if (mysqli_query($connect, $update)) {
                // send email
                $to = $email;
                $subject = "Preferences Updated";
                $message = "<html><body>";
                $message .= "<p>Your preference settings have been updated.</p>";
                $message .= "<a href='localhost/myClimb/profile.php'>Click here</a> to go back</body></html>";
                $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
                $headers .= 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                if (mail($to, $subject, $message, $headers)) {
                    header('Location: profile.php');
                }
            } else {
                echo "There is an error somewhere";
            }
        }
    }
}elseif(isset($_POST['changePass'])){
    $passwordKey = generateRandomString();
    $to = $email;
    $subject = "Password Updated";
    $message = "<html><body>";
    $message .= "<p>Your password has been updated. Please verify</p>";
    $message .= "<a href='localhost/myClimb/profile.php?key=".$passwordKey."'>Click here</a> to verify</body></html>";
    $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    if(mail($to,$subject,$message,$headers)){
        header('Location: profile.php');
        $_SESSION['newPass'] = $_POST['password'];
        $_SESSION['passwordKey'] = $passwordKey;
    }
}else {
    $mySQL = "INSERT INTO preferences(username,postVisAll,allowAllFollow) VALUES('$username','$postVisAll','$allowAllFollow')";
    if (mysqli_query($connect, $mySQL)) {
        echo "inserted";
    } else {
        echo "There is an error somewhere";
        echo mysqli_error($connect);
    }
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}