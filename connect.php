<?php
session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);

if($connect)
{
	if(isset($_POST['submit'])) {
        date_default_timezone_set('Europe/London');
        $dateTime = date("Y-m-d h:i:s");
        $testUserName = mysqli_real_escape_string($connect, $_POST['username']);
        $testPassword = mysqli_real_escape_string($connect, $_POST['password']);
        $encrypt = password_hash($testPassword, PASSWORD_DEFAULT);
        $sql = "SELECT * FROM users WHERE '$testUserName' = username";
        $sel = mysqli_query($connect, $sql);
        if (mysqli_num_rows($sel) > 0) {
            while ($row = mysqli_fetch_assoc($sel)) {
                if (password_verify($testPassword, $row['password'])) {
                    $updateActivity = "UPDATE users SET lastActive='" . $dateTime . "' WHERE username='" . $testUserName . "'";
                    $res = mysqli_query($connect, $updateActivity);
                    if (!$res) {
                        echo mysqli_error($connect);
                    }
                    $_SESSION['username'] = $testUserName;
                    $_SESSION['userID'] = $row['userID'];
                    $_SESSION['email'] = $row['emailAddress'];
                    header('Location: index.php');
                } else {
                    echo 'Invalid password.';
                    echo "Sorry this is incorrect. ";
                    echo "<a href='index.php'>Go back and try again</a>";
                }
            }

        }else{
            echo "Sorry that was an incorrect username. <a href='index.php'>Go back and try again</a>";
        }
    }
mysqli_close($connect);
}
else
{
	echo "Fail connection";
}
function findUsername($userID)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE userID='" . $userID . "'";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            return $row['username'];
        }
    }
}
function findUserID($userName)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE username='" . $userName . "'";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            return $row['userID'];
        }
    }
}
function findUserEmail($userID)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE userID='" . $userID . "'";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            return $row['emailAddress'];
        }
    }
}
function findClimbName($climbID){
    global $connect;
    $sql = "SELECT * FROM climbs WHERE climbID='".$climbID."'";
    $res = mysqli_query($connect, $sql);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            return $row['name'];
        }
    }
}
function showPost($row){
    echo "<div class='grey lighten-4' style='margin-bottom:25px;padding: 35px;padding-top:0;-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;'>";
    echo"<a href='userProfile.php?id=" . findUserID($row['username']) . "'>" . $row['username'] . "</a> - <span>" . $row['post'] . "</span>";
    $votes = $row['votesUp']-$row['votesDown'];
    echo "<span style='margin-left:5%; class='votes_count' id='votes_count".$row['postID']."'>".$votes." votes</span>";
    echo "<span class='vote_buttons' id='vote_buttons".$row['postID']."'>
            <a href='javascript:;' class='vote_upPost' id='".$row['postID']."'></a>
            <a href='javascript:;' class='vote_downPost' id='".$row['postID']."'></a>
            </span>";
    echo "<i class='material-icons options dropdown-trigger' data-activates='dropdown".$row['postID']."' data-beloworigin='true'>more_vert</i>";
    echo "<ul id='dropdown".$row['postID']."' class='dropdown-content'>
                <li><a href='#' class='modalSelect' id='".$row['postID']."'>Report</a></li>
            </ul>";
    $format = 'Y-m-d H:i:s';
    $dateFormat = DateTime::createFromFormat($format,$row['datePost']);
    echo "<p>".$dateFormat->format("d-m-Y H:i")."</p>";
}
function showComment($row){
    global $connect;
    if(isset($_SESSION['username'])) {
        $checkIfUserPosted = "SELECT * FROM post WHERE postID='" . $row['postID'] . "' AND username='" . $_SESSION['username'] . "'";
        $res = mysqli_query($connect, $checkIfUserPosted);
        $hasPosted = false;
        if (mysqli_num_rows($res) > 0) {
            $hasPosted = true;
        }
        $checkCountLimit = "SELECT * FROM preferences WHERE username='" . $_SESSION['username'] . "'";
        $res = mysqli_query($connect, $checkCountLimit);
        if (mysqli_num_rows($res) > 0) {
            while ($row2 = mysqli_fetch_assoc($res)) {
                $postLimit = $row2['postCommentVoteCount'];
            }
        }
        if ($row['numberOfVotes'] > $postLimit) {
            echo "<div style='margin-left: 5%;'><a href='userProfile.php?id=" . $row['userID'] . "'>" . findUsername($row['userID']) . "</a> - <span>" . $row['comment'] . "</span>";
            $votes = $row['votesUp'] - $row['votesDown'];
            echo "<span style='margin-left:5%;' class='votes_count' id='votes_count" . $row['commentID'] . "'>" . $votes . " votes</span>";
            echo "<span class='vote_buttons' id='vote_buttons" . $row['commentID'] . "'>
                    <a href='javascript:;' class='vote_upComment' id='" . $row['commentID'] . "'></a>
                    <a href='javascript:;' class='vote_downComment' id='" . $row['commentID'] . "'></a>
                    </span>";
            echo "<i class='material-icons options dropdown-trigger' data-activates='dropdownComment" . $row['commentID'] . "' data-beloworigin='true'>more_vert</i>";
            echo "<ul id='dropdownComment" . $row['commentID'] . "' class='dropdown-content'>
                        <li><a href='#' class='modalSelect' id='" . $row['postID'] . "' commentID='" . $row['commentID'] . "'>Report</a></li>";
            if ($hasPosted == true) {
                echo "<li><form method='post' action='removeComment.php'><input type='text' hidden value='" . $row['commentID'] . "' name='commentID'><input type='text' hidden value='" . $row['postID'] . "' name='postID'><button type='submit'>Remove</button></form></li>";
            }
            echo "</ul></div>";
        }
    }else{
        echo "<div style='margin-left: 5%;'><a href='userProfile.php?id=" . $row['userID'] . "'>" . findUsername($row['userID']) . "</a> - <span>" . $row['comment'] . "</span>";
        $votes = $row['votesUp'] - $row['votesDown'];
        echo "<span style='margin-left:5%;' class='votes_count' id='votes_count" . $row['commentID'] . "'>" . $votes . " votes</span>";
        echo "<span class='vote_buttons' id='vote_buttons" . $row['commentID'] . "'>
                    <a href='javascript:;' class='vote_upComment' id='" . $row['commentID'] . "'></a>
                    <a href='javascript:;' class='vote_downComment' id='" . $row['commentID'] . "'></a>
                    </span>";
        echo "<i class='material-icons options dropdown-trigger' data-activates='dropdownComment" . $row['commentID'] . "' data-beloworigin='true'>more_vert</i>";
        echo "<ul id='dropdownComment" . $row['commentID'] . "' class='dropdown-content'>
                        <li><a href='#' class='modalSelect' id='" . $row['postID'] . "' commentID='" . $row['commentID'] . "'>Report</a></li>";
        if ($hasPosted == true) {
            echo "<li><form method='post' action='removeComment.php'><input type='text' hidden value='" . $row['commentID'] . "' name='commentID'><input type='text' hidden value='" . $row['postID'] . "' name='postID'><button type='submit'>Remove</button></form></li>";
        }
        echo "</ul></div>";
    }
}
function showPostOrder($followArray){
    global $connect;
    if(isset($_SESSION['username'])) {
        $userPref = findPrefCount($_SESSION['username']);
        if ($followArray[4] > $userPref) {
            echo "<div class='grey lighten-4' style='margin-bottom:25px;padding: 35px;padding-top:0;-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;'><p>";
            echo "<a href='userProfile.php?id=" . $followArray[1] . "'>" . $followArray[2] . "</a> - <span>" . $followArray[3] . "</span>";
            echo "<span style='margin-left:5%;'>";
            echo "<span class='votes_count' id='votes_count" . $followArray[0] . "'>" . $followArray[4] . " votes</span>";
            echo "<span class='vote_buttons' id='vote_buttons" . $followArray[0] . "'>
		<a href='javascript:;' class='vote_upPost' id='" . $followArray[0] . "'></a>
		<a href='javascript:;' class='vote_downPost' id='" . $followArray[0] . "'></a>
	</span>";
            echo "<i class='material-icons options dropdown-trigger' data-activates='dropdown" . $followArray[0] . "' data-beloworigin='true'>more_vert</i>";
            echo "<ul id='dropdown" . $followArray[0] . "' class='dropdown-content'>
            <li><a href='#' class='modalSelect' id='" . $followArray[0] . "'>Report</a></li>
  </ul>";
            echo "</span></p>";
            $format = 'Y-m-d H:i:s';
            $dateFormat = DateTime::createFromFormat($format, $followArray[5]);
            echo "<p>" . $dateFormat->format("d-m-Y H:i") . "</p>";
            $find3comments = "SELECT * FROM comments WHERE postID='" . $followArray[0] . "' ORDER BY numberOfVotes DESC LIMIT 3";
            $res = mysqli_query($connect, $find3comments);
            if (mysqli_num_rows($res)) {
                echo "<div style='margin-left: 5%;'>";
                while ($row = mysqli_fetch_assoc($res)) {
                    showComment($row);
                }
                echo "</div>";
                echo "<a href='posts.php?postID=" . $followArray[0] . "'>See all comments</a>";
            } else {
                echo "<a href='posts.php?postID=" . $followArray[0] . "'>View post</a>";
            }
            echo "<form action='comment.php' method='post'>
    <input type='text' hidden value='" . $followArray[0] . "' name='postID'>
    <input type='text' name='comment' class='col s6'>
    <button type='submit'>Send</button>
</form></div>";
        }
    }elseif($followArray[4]>-4){
        echo "<div class='grey lighten-4' style='margin-bottom:25px;padding: 35px;padding-top:0;-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;'><p>";
        echo "<a href='userProfile.php?id=" . $followArray[1] . "'>" . $followArray[2] . "</a> - <span>" . $followArray[3] . "</span>";
        echo "<span style='margin-left:5%;'>";
        echo "<span class='votes_count' id='votes_count" . $followArray[0] . "'>" . $followArray[4] . " votes</span>";
        echo "<span class='vote_buttons' id='vote_buttons" . $followArray[0] . "'>
		<a href='javascript:;' class='vote_upPost' id='" . $followArray[0] . "'></a>
		<a href='javascript:;' class='vote_downPost' id='" . $followArray[0] . "'></a>
	</span>";
        echo "<i class='material-icons options dropdown-trigger' data-activates='dropdown" . $followArray[0] . "' data-beloworigin='true'>more_vert</i>";
        echo "<ul id='dropdown" . $followArray[0] . "' class='dropdown-content'>
            <li><a href='#' class='modalSelect' id='" . $followArray[0] . "'>Report</a></li>
  </ul>";
        echo "</span></p>";
        $format = 'Y-m-d H:i:s';
        $dateFormat = DateTime::createFromFormat($format, $followArray[5]);
        echo "<p>" . $dateFormat->format("d-m-Y H:i") . "</p>";
        $find3comments = "SELECT * FROM comments WHERE postID='" . $followArray[0] . "' ORDER BY numberOfVotes DESC LIMIT 3";
        $res = mysqli_query($connect, $find3comments);
        if (mysqli_num_rows($res)) {
            echo "<div style='margin-left: 5%;'>";
            while ($row = mysqli_fetch_assoc($res)) {
                showComment($row);
            }
            echo "</div>";
            echo "<a href='posts.php?postID=" . $followArray[0] . "'>See all comments</a>";
        } else {
            echo "<a href='posts.php?postID=" . $followArray[0] . "'>View post</a>";
        }
        echo "</div>";
    }
}
function findPrefCount($username){
    global $connect;
    $findPrefCount = "SELECT * FROM preferences WHERE username='".$username."'";
    $res = mysqli_query($connect,$findPrefCount);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            return $row['postCommentVoteCount'];
        }
    }

}
function showClimbs($row){
    echo "<li><div class='collapsible-header' style='display: block'><h5><img class='circle' style='background:50% 50% no-repeat;width:75px;height:75px' src='data:image/jpeg;base64,".base64_encode($row['image'])."'>".$row['name']." - ".$row['grade']."<a href='climb.php?id=".$row['climbID']."' class='right'><i class='material-icons' style='color:rgba(0,0,0,0.87)'>info_outline</i></a></h5></div>";
    echo "<div class='collapsible-body'><h6>Climbing Types</h6><ul class='collection'>";
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
    echo $row['information']."</div>";
    echo "</li>";
}
?>