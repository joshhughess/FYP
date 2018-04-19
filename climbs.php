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
$sql = "SELECT * FROM climbs";
$res = mysqli_query($connect,$sql);
echo "</nav>";
if(mysqli_num_rows($res)>0){
    echo "<ul class='collapsible'>";
    while($row = mysqli_fetch_assoc($res)){
        showClimbs($row);
    }
    echo "</ul>";
}else{
    echo "No climbs found.";
}

?>