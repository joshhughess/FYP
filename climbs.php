<?php
include('connect.php');
include_once('simple_html_dom.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
$username = $_SESSION['username'];

// Create DOM from URL or file
$html = file_get_html('http://www.rockclimbing.com/routes/Europe/England/Peak_District/Aldery_Cliff/');

// Find all images
foreach($html->find('table.ftable tbody tr th:nth-child(2)') as $element)
    echo $element->src . '<br>';