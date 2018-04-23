<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
    $username=$_SESSION['username'];
}else{
    include('nav.php');
}
echo "<title>My Climb</title>";
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
}elseif(isset($_GET['notLoggedin'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">You need to be logged in to access this page</span>
                            <p>You can register <a href="registerForm.php">here</a>.</p>
                        </div>
                    </div>
                </div>
           </div>';
}elseif(isset($_GET['reportAlreadySent'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">You\'ve already reported this post</span>
                        </div>
                    </div>
                </div>
           </div>';
}elseif(isset($_GET['removedComment'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">You\'ve removed the comment from your post</span>
                        </div>
                    </div>
                </div>
           </div>';
}elseif(isset($_GET['repliedComment'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">Thank you for leaving a reply to this post</span>
                        </div>
                    </div>
                </div>
           </div>';
}elseif(isset($_GET['incorrectLogin'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card red lighten-2">
                        <div class="card-content">
                            <span class="card-title">That was an incorrect login please try again</span>
                        </div>
                    </div>
                </div>
           </div>';
}


$followArray = array();
$postVisArray = array();
$findUser="SELECT * FROM users";
$res = mysqli_query($connect,$findUser);
if(mysqli_num_rows($res)>0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $username = $row['username'];
        $checkPref = "SELECT * FROM preferences WHERE username='$username'";
        $res2 = mysqli_query($connect, $checkPref);
        if (mysqli_num_rows($res2) > 0) {
            while ($row2 = mysqli_fetch_assoc($res2)) {
                if ($row2['postVisAll'] == "Y") {
                    $findPosts = "SELECT * FROM post WHERE username='$username' ORDER BY postID DESC";
                    $res3 = mysqli_query($connect, $findPosts);
                    if (mysqli_num_rows($res3) > 0) {
                        while ($row3 = mysqli_fetch_assoc($res3)) {
                            $values = array();
                            array_push($values, $row3['postID']);
                            array_push($values, findUserID($username));
                            array_push($values, $row3['username']);
                            array_push($values, $row3['post']);
                            array_push($values, ($row3['votesUp'] - $row3['votesDown']));
                            array_push($values, $row3['datePost']);
                            array_push($followArray, $values);
                            array_push($postVisArray,$username);
//                                            echo "<p><a href='userProfile.php?id=".$followID."'>".$row['username']. "</a> - ".$row['post']."</p>";
                        }
                    }
                }
            }
        }
    }
}

$mySQL = "SELECT * FROM follow WHERE follower_uName='".$username."'";
$r = mysqli_query($connect, $mySQL);
$alreadyIn=false;
if(mysqli_num_rows($r)>0): //table is non-empty
    while($row = mysqli_fetch_assoc($r)):
        $followingName = $row['following_uName'];
        $checkPrefFirst = "SELECT * FROM preferences WHERE username='".$followingName."'";
        $result = mysqli_query($connect,$checkPrefFirst);
        if(mysqli_num_rows($result)>0){
            while($thisRow = mysqli_fetch_assoc($result)){
                if($thisRow['postVisAll']=="Y"){
                    $alreadyIn=true;
                }
            }
        }
        if(!$alreadyIn) {
            $findUser = "SELECT * FROM users WHERE username='$followingName'";
            $res = mysqli_query($connect, $findUser);
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $username = $row['username'];
                    $checkFollow = "SELECT * FROM follow WHERE follower_uName='$username' AND following_uName='$followingName' AND accepted='1'";
                    $res = mysqli_query($connect, $checkFollow);
                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            $followID = findUserID($row['following_uName']);
                            $findPosts = "SELECT * FROM post WHERE username='$followingName' ORDER BY postID DESC";
                            $res = mysqli_query($connect, $findPosts);
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $values = array();
                                    array_push($values, $row['postID']);
                                    array_push($values, $followID);
                                    array_push($values, $row['username']);
                                    array_push($values, $row['post']);
                                    array_push($values, ($row['votesUp'] - $row['votesDown']));
                                    array_push($values, $row['datePost']);
                                    array_push($followArray, $values);
//                                            echo "<p><a href='userProfile.php?id=".$followID."'>".$row['username']. "</a> - ".$row['post']."</p>";
                                }
                            }
                        }
                    }
                }
            } else {
                echo "User not found please try again. <a href='index.php'>Go back</a>";
            }
        }
    endwhile;
endif;
//sort array by postID in ascending order
if(isset($_GET['mostVotes'])){
    usort($followArray, function ($a, $b) {
        return $a[4] < $b[4];
    });
}else {
    usort($followArray, function ($a, $b) {
        return $a[0] < $b[0];
    });
}
if(isset($_SESSION['username'])){
    echo "<h5>".$_SESSION['username']."'s News Feed</h5>";
}else{
    echo "<h5>News Feed</h5>";
}
echo '<div class=\'wrapper\'><div style=\'width: 65%;\' class="left"><div class="input-field col s12">
    <select class="mySelect">
      <option value="test" disabled selected>Choose your option</option>
      <option value="mostVotes">Most Votes</option>
      <option value="new">Newest</option>
    </select>
    <label>Choose a filter for the posts</label>
  </div></div>';
echo "<div class='featuredClimb right' style='width:32.5%'><h4>Featured Climb</h4>";
$findRandClimb = "SELECT * FROM climbs ORDER BY RAND() LIMIT 1";
$res = mysqli_query($connect,$findRandClimb);
if(mysqli_num_rows($res)>0){
    echo "<ul class='collapsible'>";
    while($row = mysqli_fetch_assoc($res)){
        showClimbs($row);
    }
    echo "</ul>";
}else{
    echo mysqli_error($connect);
}
echo "<title>All Climbers</title>";
echo '<div class="row">
        <div>
            <ul class="tabs" style="height: 58px;">
                <li class="tab"><a class="active" href="#allClimbers">All Climbers</a></li>';
if(isset($_SESSION['username'])) {
    echo '<li class="tab"><a href="#following">Following</a></li>
        <li class="tab"><a href="#followingRequests">Following Requests</a></li>';
}
echo '            </ul>
      </div>';

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
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post' style='margin-bottom:30px;'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button class='btn btn-waves green darken-2 right' name='unfollow' type='submit'>Pending</button> </form>";
                    }else {
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post' style='margin-bottom:30px;'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button class='btn btn-waves green darken-2 right' name='unfollow' type='submit'>Following</button> </form>";
                    }
                }
            }else {
                echo "<form id='follow' action='follow.php' method='post' style='margin-bottom:30px;'><a href='userProfile.php?id=".$row['userID']."'>" . $row['firstName'] . " " . $row['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'> <button class='btn btn-waves green darken-2 right' name='follow' type='submit'>Follow</button> </form>  ";
            }
        }
    }
}
echo "</div>";
if(isset($_SESSION['username'])) {
    echo "<div class='col s12' id='following'>";
    $findSelfFollow = "SELECT * FROM follow WHERE follower_uName='" . $_SESSION['username'] . "' AND following_uName='" . $_SESSION['username'] . "'";
    $res = mysqli_query($connect, $findSelfFollow);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $selfFollowID = $row['followID'];
        }
    } else {
        echo mysqli_error($connect);
    }
    $findFollowing = "SELECT * FROM follow WHERE follower_uName='" . $_SESSION['username'] . "' AND followID!='" . $selfFollowID . "' AND accepted='1'";
    $res = mysqli_query($connect, $findFollowing);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            if ($row['following_uName'] != $_SESSION['username']) {
                $otherUser = $row['following_uName'];
            }
            $findUser = "SELECT * FROM users WHERE username='" . $otherUser . "'";
            $res2 = mysqli_query($connect, $findUser);
            if (mysqli_num_rows($res2) > 0) {
                while ($row2 = mysqli_fetch_assoc($res2)) {
                    if ($row['accepted'] == '0') {
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post' style='margin-bottom:30px;'><a href='userProfile.php?id=" . $row2['userID'] . "'>" . $row2['firstName'] . " " . $row2['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row2['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button class='btn btn-waves green darken-2 right' name='unfollow' type='submit'>Pending</button> </form>";
                    } else {
                        echo "<form id='unfollow' action='follow.php?action=unfollow' method='post' style='margin-bottom:30px;'><a href='userProfile.php?id=" . $row2['userID'] . "'>" . $row2['firstName'] . " " . $row2['lastName'] . "</a><input name='followingName' type='hidden' value='" . $row2['username'] . "'><input name='followerName' type='hidden' value='" . $_SESSION['username'] . "'><button class='btn btn-waves green darken-2 right' name='unfollow' type='submit'>Following</button> </form>";
                    }
                }
            } else {
                echo "unable to find user";
            }
        }
    } else {
        echo "You don't seem to be following anyone at the moment. Don't be shy!";
    }
    echo "</div>";
    echo "<div class='col s12' id='followingRequests'>";
    $mySQL = "SELECT * FROM follow WHERE following_uName='" . $_SESSION['username'] . "' AND accepted='0'";
    $r = mysqli_query($connect, $mySQL);
    if (mysqli_num_rows($r) > 0) {
        while ($row = mysqli_fetch_assoc($r)) {
            echo $row['follower_uName'] . " has requested to follow you. Do you wish to accept? <form method='post' action='followResponse.php?action=yes' id='acceptYes'><input type='hidden' value='" . $row['follower_uName'] . "' name='follower_uName'><input type='submit' class='btn btn-waves green darken-2' value='Yes'></form><form id='acceptNo' method='post' action='followResponse.php?action=no'><input type='hidden' value='" . $row['follower_uName'] . "' name='follower_uName'><input type='submit' value='No'class='btn btn-waves green darken-2' ></form>";
        }
    } else {
        echo mysqli_error($connect);
    }
    echo "</div>";
}

echo "</div>";
echo "</div>";

echo "<div class='allPosts left' style='width: 65%;'>";
for($i=0;$i<(sizeof($followArray));$i++){
    showPostOrder($followArray[$i]);
}
echo "</div>";

if(isset($_SESSION['username'])) {
    echo '<div id="modal1" class="modal">
        <div class="modal-content">

        </div>
    </div>';
    echo '<div class="fixed-action-btn">
    <a class="btn-floating btn-large red">
      <i class="large material-icons">mode_edit</i>
    </a>
  </div>';
}
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
<script>
    $(document).ready(function(){
        $('select').material_select();
        $('.mySelect').on('change',function(){
            window.location.href = window.location.origin+window.location.pathname+'?'+$(this).find(":selected").val();
        })
        $('input#comment').characterCounter();
        $('.dropdown-trigger').dropdown();
        $('.modalSelect').on('click',function(){
            $('.modal').modal();
            $('.modal').modal('open');
            $('.modal').html('<form action="report.php" method="post">' +
                '<h4>Report this post</h4>' +
                '<input type="text" value="'+$(this).attr('id')+'" hidden name="postID">' +
                '<p>' +
                '<input name="group1" type="radio" id="radio1"  value="offensiveLanguageBehaviour" />' +
                '<label for="radio1">Offensive language/ behaviour</label>' +
                '</p>' +
                '<p>' +
                '<input name="group1" type="radio" id="radio2" value="abusiveHarrasive" />' +
                '<label for="radio2">Abusive or harrasive</label>' +
                '</p>' +
                '<p>' +
                '<input name="group1" type="radio" id="radio3" value="spam" />' +
                '<label for="radio3">It\'s spam</label>' +
                '</p>'+
                '<div class=\'row\'><div class="input-field col s12">'+
                '<label for="comments">Comments</label>' +
                '<input class="reviewComments" type="text" data-length="256" name="comments">' +

                '<button type="submit" name="postReport" class="btn waves-effect waves-green green darken-2">Send Report</button>' +
                '</div></div>'+
                '</form>');
            $('.reviewComments').characterCounter();
//       $('.modal').html($(this).attr('id'));

        });
        if(window.location.search=="?replied"){
            Materialize.toast('You\'ve replied to the post', 3000);
        }
        $('.btn-floating').on('click',function(){
            $('.modal').modal();
            $('.modal').modal('open');
            $('.modal').html("<form id='postForm' class='col s12' method='post' action='enterPost.php'>" +
                    "<div class='row'><div class=\"input-field col s12\">"+
                "<input required='required' data-length='200' maxlength='200' id='sendMessage' type='text' name='post'>" +
                "<label for=\"post\">Enter your post</label>" +
                "<button type='submit' class='btn waves-effect green darken-2' id='sendPost'>Post</button>" +
                "</div></div>"+
                "</form>");
                $('input#sendMessage').characterCounter();

        });
        if($(window).width()<=950){
            $('.featuredClimb').removeClass('right');
            $('.featuredClimb').css('width','100%');
            $('.allPosts').removeClass('right');
            $('.allPosts').css('width','100%');
        }
        $(window).resize(function() {
            // This will execute whenever the window is resized
            $(window).height(); // New height
            $(window).width(); // New width
            if($(window).width()<=950){
                $('.featuredClimb').removeClass('right');
                $('.featuredClimb').css('width','100%');
                $('.allPosts').removeClass('right');
                $('.allPosts').css('width','100%');
            }else{
                $('.featuredClimb').addClass('right');
                $('.featuredClimb').css('width','32.5%');
                $('.allPosts').addClass('left');
                $('.allPosts').css('width','65%');
            }
        });
    })
</script>
<style>
    .tabs .tab a{
        color:#000;
        padding:0 10px;
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
    span.link a {
        font-size:150%;
        color: #000000;
        text-decoration:none;
    }
    a.vote_upPost, a.vote_upComment, a.vote_downPost, a.vote_downComment {
        display:inline-block;
        background-repeat:none;
        background-position:center;
        height:16px;
        width:16px;
        margin-left:4px;
        text-indent:-900%;
    }

    a.vote_upPost, a.vote_upComment {
        background:url('images/thumb_up.png');
    }

    a.vote_downPost, a.vote_downComment    {
        background:url('images/thumb_down.png');
    }
</style>
<script type='text/javascript' src='js/sendVote.js'></script>

