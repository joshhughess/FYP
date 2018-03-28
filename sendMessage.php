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
echo "current user - ".$currentUser."<br>conversationID -".$_POST['conversationID']."<br>";
date_default_timezone_set('Europe/London');
$dateTime = date("Y-m-d H:i:s");
$now = new DateTime($dateTime);
$checkExists = false;
$checkConversation = "SELECT * FROM conversations WHERE userID='".findUserID($currentUser)."' AND user2id='".$_POST['messageTo']."'";
$res = mysqli_query($connect,$checkConversation);
if(mysqli_num_rows($res)>0) {
    $checkExists=true;
}else{
    $checkConversation2 = "SELECT * FROM conversations WHERE userID='".$_POST['messageTo']."' AND user2id='".$currentUser."'";
    $res = mysqli_query($connect,$checkConversation2);
    if(mysqli_num_rows($res)>0) {
        $checkExists=true;
    }
}
if($checkExists==true){
    $sql = "INSERT INTO messages(conversationID,message,messageTime,sentFromID) VALUES('".$_POST['conversationID']."','".addslashes($_POST['sendMessage'])."',NOW(),'".findUserID($currentUser)."')";
    $res = mysqli_query($connect,$sql);
    if($res) {
        echo "sent message1";
    }else{
        echo mysqli_error($connect);
    }
}else{
    $sql = "INSERT INTO conversations(userID,user2id) VALUES('".findUserID($currentUser)."','".$_POST['messageTo']."')";
    $res = mysqli_query($connect,$sql);
    if($res) {
        $sql2 = "SELECT * FROM conversations WHERE userID='" . findUserID($currentUser) . "' AND user2id='" . $_POST['messageTo'] . "'";
        $res = mysqli_query($connect, $sql2);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $conversationID = $row['conversationID'];
            }
        }
        $sql3 = "INSERT INTO messages(conversationID,message,messageTime,sentFromID) VALUES('" . $_POST['conversationID'] . "','" . $_POST['sendMessage'] . "',NOW(),'" . findUserID($currentUser) . "')";
        $res = mysqli_query($connect, $sql3);
        if ($res) {
            echo "sent message";
        } else {
            echo "error sending message";
        }
    }else{
        echo "something went wrong";
    }
}
