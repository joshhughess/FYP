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
if(isset($_GET['id'])){
    echo '</nav>';
    $userID = $_GET['id'];
    echo "<button class='right btn waves-effect waves-light'><a href='messages.php?user=".$userID."' style='color:#fff'>Message user</a></button>";
    echo "<button class='right btn waves-effect waves-light'><a href='meetup.php?user=".$userID."' style='color:#fff'>Climb with user</a></button>";
    //work out average grade
    $allClimbsArray = array();
    $findAllClimbs = "SELECT * FROM hasClimbed WHERE userID='".$_GET['id']."'";
    $res = mysqli_query($connect,$findAllClimbs);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)) {
            array_push($allClimbsArray,$row['climbID']);
        }
    }
    $allGradesArray = array();
    for($i=0;$i<sizeof($allClimbsArray);$i++){
        $findAllGrades = "SELECT grade FROM climbs WHERE climbID='".$allClimbsArray[$i]."'";
        $res = mysqli_query($connect,$findAllGrades);
        if(mysqli_num_rows($res)>0){
            while($row = mysqli_fetch_assoc($res)) {
                array_push($allGradesArray,$row['grade']);
            }
        }
    }
    $values = array_count_values($allGradesArray);
    arsort($values);
    $mostPopularGrade = array_slice(array_keys($values), 0, 1, true);
    $findInformationAndPicture = "SELECT * FROM users WHERE userID='".$_GET['id']."'";
    $res = mysqli_query($connect,$findInformationAndPicture);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            echo "<title>".$row['username']."</title>";
            if($row['picture']!=null) {
                echo '<h5><img class="circle" style="background:50% 50% no-repeat;width:75px;height:75px" src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '"/>' . findUsername($row['userID']);
            }else{
                echo '<h5><img class="circle" style="background:50% 50% no-repeat;width:75px;height:75px" src="http://localhost/myClimb/images/userProfile.png"/>' . findUsername($row['userID']);
            }
            if(sizeof($mostPopularGrade)!=0){
                echo " - <small>(".$mostPopularGrade[0].")</small></h5>";
            }else{
                echo "</h5>";
            }
            echo "<p>".$row['information']."</p>";
        }
    }
    echo '<div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab"><a class="active" href="#posts">Posts</a></li>
                <li class="tab"><a href="#climbs">Climbs</a></li>
            </ul>
      </div>';
    echo "<style>
.tabs .tab a{
            color:#000;
        } /*Black color to the text*/

        .tabs .tab a:hover {
            background-color:#eee;
            color:#000;
        } /*Text color on hover*/

        .tabs .tab a.active {
            color:#000;
        } /*Background and text color when a tab is active*/

        .tabs .indicator {
            background-color:#000;
        } 

</style>";
    echo "<div class='col 12' id='posts'>";
    $findUser="SELECT * FROM users WHERE userID='$userID'";
    $res = mysqli_query($connect,$findUser);
    if(mysqli_num_rows($res)>0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $username = $row['username'];
            $checkPref = "SELECT * FROM preferences WHERE username='$username'";
            $res = mysqli_query($connect,$checkPref);
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    if($row['postVisAll']=="Y"){
                        echo "anyone can view posts from ".$username;
                    }else{
                        $checkFollow = "SELECT * FROM follow WHERE follower_uName='$currentUser' AND following_uName='$username'";
                        $res = mysqli_query($connect,$checkFollow);
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $findPosts = "SELECT * FROM post WHERE username='$username'";
                                $res = mysqli_query($connect,$findPosts);
                                if (mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo "<p>".$row['post']."</p>";
                                    }
                                } else {
                                    echo "No posts available";
                                }
                            }
                        }else{
                            echo "You need to be following this user to view their posts.";
                        }
                    }
                }
            }else {
                $findPosts = "SELECT * FROM post WHERE username='$username' ORDER BY 'datePost' DESC";
                $res = mysqli_query($connect,$findPosts);
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<p>".$row['username']. " - ".$row['post']." - ".$row['datePost']."</p>";
                    }
                } else {
                    echo "No posts available";
                }
            }
        }
    }else{
        echo "User not found please try again. <a href='climbers.php'>Go back</a>";
    }
    echo "</div>";
    echo "<div class='col 12' id='climbs'>";
    $allHasClimbedArray = array();
    $findClimbs = "SELECT * FROM hasClimbed WHERE userID='".$_GET['id']."'";
    $res = mysqli_query($connect, $findClimbs);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            array_push($allHasClimbedArray,$row['climbID']);
        }
    }
    if(sizeof($allHasClimbedArray)!=0) {
        for ($i = 0; $i < sizeof($allHasClimbedArray); $i++) {
            $findAllTypes = "SELECT * FROM climbs WHERE climbID='" . $allHasClimbedArray[$i] . "'";
            $res = mysqli_query($connect, $findAllTypes);
            if (mysqli_num_rows($res) > 0) {
                echo "<ul class='collapsible'>";
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<li><div class='collapsible-header' style='display: block'><h5>" . $row['name'] . " - " . $row['grade'] . "<a href='climb.php?id=" . $row['climbID'] . "' class='right'><i class='material-icons' style='color:rgba(0,0,0,0.87)'>info_outline</i></a></h5></div>";
                    echo "<div class='collapsible-body'><h6>Climbing Types</h6><ul class='collection'>";
                    if ($row['isSport'] == 1) {
                        echo "<li class='collection-item'>Sport</li>";
                    }
                    if ($row['isTrad'] == 1) {
                        echo "<li class='collection-item'>Trad</li>";
                    }
                    if ($row['isTopRope'] == 1) {
                        echo "<li class='collection-item'>Top Rope</li>";
                    }
                    if ($row['isBouldering'] == 1) {
                        echo "<li class='collection-item'>Bouldering</li>";
                    }
                    if ($row['isMountaineering'] == 1) {
                        echo "<li class='collection-item'>Mountaneering</li>";
                    }
                    if ($row['isFreeSolo'] == 1) {
                        echo "<li class='collection-item'>Free Solo</li>";
                    }
                    echo "</ul>";
                    echo $row['information'] . "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            }
        }
    }else{
        echo "This user has currently not climbed anything!";
    }
    echo "</div>
        </div>";
}else{
    header("Location:index.php");
}

function date_compare($a, $b)
{
    $t1 = strtotime($a[2]);
    $t2 = strtotime($b[2]);
    return $t1 - $t2;
}

?>