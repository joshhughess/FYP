<?php
include('connect.php');
include_once('simple_html_dom.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
    $username = $_SESSION['username'];
}else{
    include('nav.php');
}
echo "<title>All Climbs</title>";
echo "<h5>All Climbs</h5>";
echo "<p>Click on a climb to find out more about the climb, including climb type and some information.</p>";
echo "<p>Click on the 'More info' section within a climb</p>";
$sql = "SELECT * FROM climbs where climbID <= (select count(*)/2 from climbs)";
$res = mysqli_query($connect,$sql);
if(mysqli_num_rows($res)>0) {
    echo "<div class='row'><div class='col s12 l6'><ul class='collapsible'>";
    while ($row = mysqli_fetch_assoc($res)) {
        showClimbs($row);
    }
    echo "</ul></div>";
    $sql = "SELECT * FROM climbs where climbID >= (select count(*)/2 from climbs)";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
        echo "<div class='row'><div class='col s12 l6'><ul class='collapsible'>";
        while ($row = mysqli_fetch_assoc($res)) {
            showClimbs($row);
        }
        echo "</ul></div></div>";
    } else {
        echo "No climbs found.";
    }
}
?>
