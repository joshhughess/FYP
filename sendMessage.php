<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])) {
    $currentUser = mysqli_real_escape_string($connect,$_SESSION['username']);
}else{
    header("Location:index.php?notLoggedin");
}
date_default_timezone_set('Europe/London');
$dateTime = date("Y-m-d H:i:s");
$now = new DateTime($dateTime);

$messageTo = mysqli_real_escape_string($connect,$_POST['messageTo']);
$sendMessage = mysqli_real_escape_string($connect,$_POST['sendMessage']);
if($_POST['conversationID']=="N"){
    $sql = "INSERT INTO conversations(userID,user2id) 
      VALUES('".findUserID($currentUser)."','".findUserID($messageTo)."')";
    $res = mysqli_query($connect,$sql);
    if($res) {
        $sql2 = "SELECT * FROM conversations 
          WHERE userID='" . findUserID($currentUser) . "' AND user2id='" . findUserID($messageTo) . "'";
        $res = mysqli_query($connect, $sql2);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $conversationID = $row['conversationID'];
            }
        }
        $sql3 = "INSERT INTO messages(conversationID,message,messageTime,sentFromID) 
          VALUES('" . $conversationID . "','" . $sendMessage. "',NOW(),'" . findUserID($currentUser) . "')";
        $res = mysqli_query($connect, $sql3);
        if ($res) {
            //send email
            $to = findUserEmail(findUserID($messageTo));
            $subject = findUsername($_SESSION['userID']) . " has messaged you!";
            $message = "<html><body>";
            $message .= "<p>" . findUsername($_SESSION['userID']) . " has messaged you, go to your messages to view the message</p>";
            $message .= "<a href='localhost/myClimb/messages.php?user=".findUserID($messageTo)."'>Go to message</a></body></html>";
            $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            //*********************redirect is dodgy!!!!!
            if (mail($to, $subject, $message, $headers)) {
                $true = true;
                //                    header("Location: userProfile.php");
            }
            if ($true) {
                header("Location:messages.php?user=".findUserID($messageTo));
                exit();
            }
            header("Location:messages.php?user=".findUserID($messageTo));
        } else {
            echo "error sending message";
        }
    }else{
        echo "something went wrong";
    }
}else {
    $checkExists = false;
    $checkConversation = "SELECT * FROM conversations WHERE userID='" . findUserID($currentUser) . "'
     AND user2id='" . findUserID($messageTo) . "'";
    $res = mysqli_query($connect, $checkConversation);
    if (mysqli_num_rows($res) > 0) {
        $checkExists = true;
    } else {
        $checkConversation2 = "SELECT * FROM conversations WHERE userID='" . findUserID($messageTo) . "'
         AND user2id='" . findUserID($currentUser) . "'";
        $res = mysqli_query($connect, $checkConversation2);
        if (mysqli_num_rows($res) > 0) {
            $checkExists = true;
        }else {
            echo mysqli_error($connect);
        }
    }
    if ($checkExists == true) {
        $conversationID = mysqli_real_escape_string($connect,$_POST['conversationID']);
        $sql = "INSERT INTO messages(conversationID,message,messageTime,sentFromID) 
            VALUES('" . $conversationID . "','" . $sendMessage . "',NOW(),'" . findUserID($currentUser) . "')";
        $res = mysqli_query($connect, $sql);
        if ($res) {
            //send email
            $to = findUserEmail(findUserID($messageTo));
            $subject = findUsername($_SESSION['userID']) . " has messaged you!";
            $message = "<html><body>";
            $message .= "<p>" . findUsername($_SESSION['userID']) . " has messaged you, go to your messages to view the message</p>";
            $message .= "<a href='localhost/myClimb/messages.php?user=".findUserID($messageTo)."'>Go to message</a></body></html>";
            $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            //*********************redirect is dodgy!!!!!
            if (mail($to, $subject, $message, $headers)) {
                $true = true;
                //                    header("Location: userProfile.php");
            }
            if ($true) {
                header("Location:messages.php?user=".findUserID($messageTo));
                exit();
            }
            header("Location:messages.php?user=" . findUserID($_POST['messageTo']));
        } else {
            echo mysqli_error($connect);
        }
    }
}
