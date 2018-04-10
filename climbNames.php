<?php
include('connect.php');
header('Content-Type:application/json');
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_SESSION['username'])){
    $sql = "SELECT * FROM climbs";
    $res = mysqli_query($connect,$sql);
    $json = array();

    $rows = array();
    while($r = mysqli_fetch_assoc($res)) {
        array_push($rows, array($r['name'],"http://localhost/myClimb/images/landscape.png"));
    }

    $userFind = "SELECT * FROM users";
    $res = mysqli_query($connect,$userFind);
    while($r = mysqli_fetch_assoc($res)){
        array_push($rows,array($r['username'],"http://localhost/myClimb/images/userProfile.png"));
    }
    print json_encode($rows);

}
?>