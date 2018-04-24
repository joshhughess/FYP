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
if($_GET['id']){
    if(isset($_GET['reviewSent'])) {
        if ($_GET['reviewSent'] == 1) {
            echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">Thank you for your review on this climb</span>
                            <p>We hope you enjoyed it!</p>
                        </div>
                    </div>
                </div>
           </div>';
        }
    }elseif(isset($_GET['error'])){
        echo '<div class="row">
                <div class="col s12">
                    <div class="card red lighten-2">
                        <div class="card-content">
                            <span class="card-title">Unfortunately there was an error reviewing the climb</span>
                        </div>
                    </div>
                </div>
           </div>';
    }
    $sql = "SELECT * FROM climbs WHERE climbID='".$_GET['id']."'";
    $res = mysqli_query($connect,$sql);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)) {
            echo "<title>".$row['name']."</title>";
                echo "<form action='review.php' method='post'><h4><img class='circle' style='background:50% 50% no-repeat;width:150px;height:150px' src='data:image/jpeg;base64,".base64_encode($row['image'])."'>" . $row['name'] . " - ".$row['grade']."<input type='text' hidden name='climbID' class='climbID' value='".$row['climbID']."'>";
                if(isset($_SESSION['username'])) {
                    echo "<button class='btn waves-effect green darken-2 right' type='submit' name='hasClimbed'>I've climbed this route</button></form></h4>";
                }
            echo "<h6>Climbing Types</h6><ul class='collection'>";
            if($row['isSport']==1){
                echo "<li class='collection-item'>Sport</li>";
            }
            if($row['isTrad']==1){
                echo "<li class='collection-item'>Trad</li>";
            }
            if($row['isTopRope']==1){
                echo "<li class='collection-item'>Top Rope</li>";
            }
            if($row['isBouldering']==1){
                echo "<li class='collection-item'>Bouldering</li>";
            }
            if($row['isMountaineering']==1){
                echo "<li class='collection-item'>Mountaneering</li>";
            }
            if($row['isFreeSolo']==1){
                echo "<li class='collection-item'>Free Solo</li>";
            }
            echo "</ul>";
            echo "<p>".$row['information']."</p>";
            $sql = "SELECT * FROM review WHERE climbID='".$_GET['id']."'";
            $res = mysqli_query($connect,$sql);
            if(mysqli_num_rows($res)>0){
                echo "<h5>All reviews</h5>";
                echo "<ul class='collapsible'>";
                while($row=mysqli_fetch_assoc($res)){
                    echo "<li><div class='collapsible-header' style='display: block'><h5>".$row['title']." <small>By ".findUsername($row['userID'])."</small><span class='right'>".$row['starRating']."/5</span></h5></div>";
                    echo "<div class='collapsible-body'><p>".$row['comments']."</p></div></li>";
                }
                echo "</ul>";
            }else{
                echo "Currently no reviews for this climb, please let us know if you have climbed this!";
            }
        }
    }else{
        echo "could not find ID";
    }
}else{
    echo "error";
}
?>
<?php include('footer.php');?>
