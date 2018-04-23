<?php
include('connect.php');
header('Content-Type:application/json');
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_SESSION['username'])){
    $sql = "SELECT * FROM meetings WHERE userID='".$_SESSION['userID']."' AND accepted='1' 
    OR user2id='".$_SESSION['userID']."' AND accepted='1' ORDER BY start ASC";
    $res = mysqli_query($connect,$sql);
    $rows = array();
    while($r = mysqli_fetch_assoc($res)) {
        $rows[] = $r;
    }
    print json_encode($rows);
}else{
    header("Location:index.php?notLoggedin");
}
?>