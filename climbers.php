<?php
include 'connect.php';
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
echo "<style>
span.link a {
	font-size:150%;
	color: #000000;
	text-decoration:none;
}
a.vote_up, a.vote_down {
	display:inline-block;
	background-repeat:none;
	background-position:center;
	height:16px;
	width:16px;
	margin-left:4px;
	text-indent:-900%;
}

a.vote_up {
	background:url('images/thumb_up.png');
}

a.vote_down {
	background:url('images/thumb_down.png');
}	
</style>";
if(isset($_GET['reportSent'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">Thank you for reporting this post</span>
                            <p>We will look into this post as soon as we can.</p>
                        </div>
                    </div>
                </div>
           </div>';
}
echo "<script type='text/javascript' src='js/sendVote.js'></script>";
$userName=$_SESSION['username'];
echo "<title>All Climbers</title>";
echo '<div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab"><a class="active" href="#allClimbers">All Climbers</a></li>
        <li class="tab"><a href="#following">Following</a></li>
        <li class="tab"><a href="#followingRequests">Following Requests</a></li>
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
    .options{
    cursor:default;
    }
</style>";
echo "<div class='col s12' id='allClimbers'>";
$q = "SELECT * FROM users";
$r = mysqli_query($connect, $q);
if(!isset($_SESSION['username'])){
    while ($row = mysqli_fetch_assoc($r)){
            echo "<p><a href='userProfile.php?id=".$row['userID']."'>".$row['username']."</a></p>
            <p>Last Active: ".getTime($row['lastActive'])."</p>";
    }
}elseif(mysqli_num_rows($r)>0) { //table is non-empty
    while ($row = mysqli_fetch_assoc($r)){
        //dont show current user signed in
        if ($row['username'] != $_SESSION['username']) {
            $checkFollow = "SELECT * FROM follow WHERE follower_uName='" . $_SESSION['username'] . "' AND following_uName='" . $row['username'] . "'";
            $r2 = mysqli_query($connect, $checkFollow);
            if(mysqli_num_rows($r2)>0) { //table is non-empty
                while ($row2 = mysqli_fetch_assoc($r2)) {
                    if ($row2['accepted'] == '0') {
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button name='unfollow' type='submit'>Pending</button> </form>";
                    }else {
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button name='unfollow' type='submit'>Following</button> </form>";
                    }
                }
            }else {
                echo "<form id='follow' action='follow.php' method='post'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'> <button name='follow' type='submit'>Follow</button> </form>  ";
            }
        }
    }
}
echo "</div>";
echo "<div class='col s12' id='following'>";
$followArray = array();
$mySQL = "SELECT * FROM follow WHERE follower_uName='$userName'";
$r = mysqli_query($connect, $mySQL);
if(mysqli_num_rows($r)>0): //table is non-empty
    while($row = mysqli_fetch_assoc($r)):
        $followingName = $row['following_uName'];
        $findUser="SELECT * FROM users WHERE username='$followingName'";
        $res = mysqli_query($connect,$findUser);
        if(mysqli_num_rows($res)>0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $username = $row['username'];
                $checkPref = "SELECT * FROM preferences WHERE username='$followingName'";
                $res = mysqli_query($connect,$checkPref);
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        if($row['postVisAll']=="on"){
                            echo "anyone can view posts from ".$username;
                        }else{
                            $checkFollow = "SELECT * FROM follow WHERE follower_uName='$userName' AND following_uName='$followingName'";
                            $res = mysqli_query($connect,$checkFollow);
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $followID=findUserID($row['following_uName']);
                                    $findPosts = "SELECT * FROM post WHERE username='$followingName' ORDER BY postID DESC";
                                    $res = mysqli_query($connect,$findPosts);
                                    if (mysqli_num_rows($res) > 0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            $values= array();
                                            array_push($values,$row['postID']);
                                            array_push($values,$followID);
                                            array_push($values, $row['username']);
                                            array_push($values,$row['post']);
                                            array_push($values,($row['votesUp']-$row['votesDown']));
                                            array_push($followArray,$values);
//                                            echo "<p><a href='userProfile.php?id=".$followID."'>".$row['username']. "</a> - ".$row['post']."</p>";
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
                    $checkFollow = "SELECT * FROM follow WHERE follower_uName='$userName' AND following_uName='$followingName'";
                    $res = mysqli_query($connect,$checkFollow);
                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            $followUname=$row['following_uName'];
                            $findFollowID="SELECT * FROM users WHERE username='$followUname'";
                            $res = mysqli_query($connect,$findFollowID);
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $followID=$row['userID'];
                                }
                            }
                            $findPosts = "SELECT * FROM post WHERE username='$followingName' ORDER BY postID DESC";
                            $res = mysqli_query($connect,$findPosts);
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    //push values into array so that i can order by postID rather than by postID and userPosted
                                    $values= array();
                                    array_push($values,$row['postID']);
                                    array_push($values,$followID);
                                    array_push($values, $row['username']);
                                    array_push($values,$row['post']);
                                    array_push($values,($row['votesUp']-$row['votesDown']));
                                    array_push($followArray,$values);
                                }
                            } else {
                                echo "No posts available";
                            }
                        }
                    }

                }
            }
        }else{
            echo "User not found please try again. <a href='climbers.php'>Go back</a>";
        }
    endwhile;
endif;
//sort array by postID in ascending order
usort($followArray, function($a, $b) {
    return $a[0] < $b[0];
});
for($i=0;$i<(sizeof($followArray));$i++){
    echo "<p>";
    echo "<a href='userProfile.php?id=".$followArray[$i][1]."'>".$followArray[$i][2]."</a> - <a href='posts.php?postID=".$followArray[$i][0]."'>". $followArray[$i][3]."</a>";
    echo "</p>";
    echo "<span class='votes_count' id='votes_count".$followArray[$i][0]."'>".$followArray[$i][4]." votes</span>";
    echo "<span class='vote_buttons' id='vote_buttons".$followArray[$i][0]."'>
		<a href='javascript:;' class='vote_up' id='".$followArray[$i][0]."'></a>
		<a href='javascript:;' class='vote_down' id='".$followArray[$i][0]."'></a>
	</span>";
    echo "<i class='material-icons options dropdown-trigger' data-activates='dropdown".$followArray[$i][0]."' data-beloworigin='true'>more_vert</i>";
    echo "<ul id='dropdown".$followArray[$i][0]."' class='dropdown-content'>
            <li><a href='#' class='modalSelect' id='".$followArray[$i][0]."'>Report</a></li>
  </ul>";
    echo "<form action='comment.php' method='post'>
    <input type='text' hidden value='".$followArray[$i][0]."' name='postID'>
    <input type='text' name='comment' class='col s6'>
    <button type='submit'>Send</button>
</form><br>";
}
echo '<div id="modal1" class="modal">
        <div class="modal-content">

        </div>
    </div>';

echo "</div>";
echo "<script>
$(document).ready(function(){
   
   $('.dropdown-trigger').dropdown();
    $('.modalSelect').on('click',function(){
       $('.modal').modal();
       $('.modal').modal('open');
       $('.modal').html('<form action=\"report.php\" method=\"post\">' +
        '<h4>Report this post</h4>' +
         '<input type=\"text\" value=\"'+$(this).attr('id')+'\" hidden name=\"postID\">' +
          '<p>' +
                '<input name=\"group1\" type=\"radio\" id=\"radio1\"  value=\"offensiveLanguageBehaviour\" />' +
                '<label for=\"radio1\">Offensive language/ behaviour</label>' +
          '</p>' +
          '<p>' +
               '<input name=\"group1\" type=\"radio\" id=\"radio2\" value=\"abusiveHarrasive\" />' +
                '<label for=\"radio2\">Abusive or harrasive</label>' +
          '</p>' +
          '<p>' +
                '<input name=\"group1\" type=\"radio\" id=\"radio3\" value=\"spam\" />' +
                '<label for=\"radio3\">It\'s spam</label>' +
          '</p>'+
          '<label for=\"reportFor\">Comments</label>' +
          '<input type=\"text\" name=\"comments\">' +
          '<button type=\"submit\" name=\"postReport\" class=\"btn\">Send Report</button>' +
          '</form>');
//       $('.modal').html($(this).attr('id'));
       
    });
    if(window.location.search==\"?replied\"){
         Materialize.toast('You\'ve replied to the post', 3000);
    }
});
</script>";
echo "<div class='col s12' id='followingRequests'>";
$mySQL = "SELECT * FROM follow WHERE following_uName='".$_SESSION['username']."' AND accepted='0'";
$r = mysqli_query($connect, $mySQL);
if(mysqli_num_rows($r)>0) {
    while ($row = mysqli_fetch_assoc($r)) {
        echo $row['follower_uName']." has requested to follow you. Do you wish to accept? <form method='post' action='followResponse.php?action=yes' id='acceptYes'><input type='hidden' value='".$row['follower_uName']."' name='follower_uName'><input type='submit' value='Yes'></form><form id='acceptNo' method='post' action='followResponse.php?action=no'><input type='hidden' value='".$row['follower_uName']."' name='follower_uName'><input type='submit' value='No'></form>";
    }
}else{
    echo mysqli_error($connect);
}
echo "</div>";
function getTime($lastActiveTime){
    date_default_timezone_set('Europe/London');
    $dateTime = date("Y-m-d h:i:s");
    $userTime = new DateTime($lastActiveTime);
    $now = new DateTime($dateTime);
    $dateDiff = $userTime->diff($now);
    //if over a year
    if($dateDiff->format("%Y")>=1){
        return $dateDiff->format("%Y")." years ago.";
    }else{
        //if over 28 days
        if($dateDiff->format("%d")>=28){
            return $dateDiff->format("%m")." months ago.";
        }else{
            //if a day or over
            if($dateDiff->format("%d")>=1){
                return $dateDiff->format("%d")." days ago.";
            }else{
                //if over an hour
                if($dateDiff->format("%h")>1){
                    //if under 24 hours
                    if($dateDiff->format("%h")<=24){
                        return $dateDiff->format("%h")." hours ago";
                    }
                }else{
                    if($dateDiff->format("%m")<=1){
                        return "about a minute ago";
                    }else{
                        return $dateDiff->format("%m")." minutes ago";
                    }
                }
            }
        }
    }
}
?>
