<?php
include('connect.php');
header('Content-Type:application/json');
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_GET['userID'])) {
    $userID = mysqli_real_escape_string($connect,$_GET['userID']);
    if ($userID == $_SESSION['userID']) {
        print json_encode(123);
    } else {
        print json_encode(findUsername($userID));
    }
}else{
    header("Location:index.php");
}
?>