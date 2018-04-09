<?php
include('connect.php');
header('Content-Type:application/json');
$connect = mysqli_connect($host,$userName,$password, $db);
if($_GET['userID']==$_SESSION['userID']) {
    print json_encode(123);
}else {
    print json_encode(findUsername($_GET['userID']));
}
?>