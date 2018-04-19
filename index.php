<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
    $userName=$_SESSION['username'];
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

$mySQL = "SELECT * FROM follow WHERE follower_uName='".$userName."'";
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
                    $checkFollow = "SELECT * FROM follow WHERE follower_uName='$userName' AND following_uName='$followingName' AND accepted='1'";
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
                echo "User not found please try again. <a href='climbers.php'>Go back</a>";
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
echo "<a class='btn dropdown-triggerCount' data-activates='dropdownCounter' data-beloworigin='true'>Sort by</a>
<ul id='dropdownCounter' class='dropdown-content'>
            <li><a href='#' id='mostVotes'>Most Votes</a></li>
            <li><a href='#' id='new'>Newest</a></li>
  </ul>";
echo "<div class='wrapper'>";
echo "<div class='allPosts left' style='width: 65%;'>";
for($i=0;$i<(sizeof($followArray));$i++){
    showPostOrder($followArray[$i]);
}
echo "</div>";
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
echo "</div>";
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
?>
<script>
    $(document).ready(function(){

        $('.dropdown-trigger').dropdown();
        $('.dropdown-triggerCount').dropdown();
        $('#mostVotes').on('click',function(){
            window.location.href = window.location.origin+window.location.pathname+'?mostVotes';
        });
        $('#new').on('click',function(){
            window.location.href = window.location.origin+window.location.pathname+'?new';
        });
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
                '<label for="reportFor">Comments</label>' +
                '<input type="text" name="comments">' +
                '<button type="submit" name="postReport" class="btn">Send Report</button>' +
                '</form>');
//       $('.modal').html($(this).attr('id'));

        });
        if(window.location.search=="?replied"){
            Materialize.toast('You\'ve replied to the post', 3000);
        }
        $('.btn-floating').on('click',function(){
            $('.modal').modal();
            $('.modal').modal('open');
            $('.modal').html("<form id='postForm' method='post' action='enterPost.php'><input required data-length='200' id='sendMessage' type='text' name='post'><button type='button' class='btn' id='sendPost'>Post</button></form>");
            $(document).keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    console.log($('#sendMessage').val().length);
                    if ($('#sendMessage').val().length <= 200 && $('#sendMessage').val().length > 0) {
                        $('#postForm').submit();
                    } else {
                        alert("Please make sure post is between 1 and 200 characters");
                    }
                }
            });
            $('#sendPost').on('click',function(){
                console.log("test");
                console.log($('#sendMessage').val().length);
                if($('#sendMessage').val().length<=200 && $('#sendMessage').val().length>0){
                    $('#postForm').submit();
                }else{
                    alert("Please make sure post is between 1 and 200 characters");
                }
            });
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

