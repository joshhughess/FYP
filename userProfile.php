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
    if(isset($_SESSION['username'])) {
        echo "<button class='right btn waves-effect waves-light'><a href='messages.php?user=" . $userID . "' style='color:#fff'>Message user</a></button>";
        echo "<button class='right btn waves-effect waves-light'><a href='meetup.php?user=" . $userID . "' style='color:#fff'>Climb with user</a></button>";
    }
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
        <div class="col s6">
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
    echo "<div class='col s12' id='posts'>";
    $notFollowing = false;
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
                        $findPosts = "SELECT * FROM post WHERE username='$username' ORDER BY postID DESC";
                        $res = mysqli_query($connect,$findPosts);
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                showPost($row);
                                $findComments = "SELECT * FROM comments WHERE postID='".$row['postID']."' ORDER BY numberOfVotes DESC";
                                $result = mysqli_query($connect,$findComments);
                                if(mysqli_num_rows($result)>0){
                                    while($row2 = mysqli_fetch_assoc($result)){
                                        showComment($row2);
                                    }
                                    echo "<form action='comment.php' method='post'>
                                            <input type='text' hidden value='" . $row['postID'] . "' name='postID'>
                                            <input type='text' name='comment' class='col s6'>
                                            <button type='submit'>Send</button>
                                            </form>";
                                    echo "</div>";
                                }else{
                                    echo "<p>No comments on this post</p>";
                                    echo "<form action='comment.php' method='post'>
                                            <input type='text' hidden value='" . $row['postID'] . "' name='postID'>
                                            <input type='text' name='comment' class='col s6'>
                                            <button type='submit'>Send</button>
                                            </form>";
                                    echo "</div>";

                                }
                            }
                        }
                    }else{
                        $checkFollow = "SELECT * FROM follow WHERE follower_uName='$currentUser' AND following_uName='$username' AND accepted='1'";
                        $res = mysqli_query($connect,$checkFollow);
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $findPosts = "SELECT * FROM post WHERE username='$username' ORDER BY postID DESC";
                                $res = mysqli_query($connect,$findPosts);
                                if (mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        showPost($row);
                                        $findComments = "SELECT * FROM comments WHERE postID='".$row['postID']."' ORDER BY numberOfVotes DESC";
                                        $result = mysqli_query($connect,$findComments);
                                        if(mysqli_num_rows($result)>0){
                                             while($row2 = mysqli_fetch_assoc($result)){
                                                 showComment($row2);
                                             }
                                            echo "<form action='comment.php' method='post'>
                                            <input type='text' hidden value='" . $row['postID'] . "' name='postID'>
                                            <input type='text' name='comment' class='col s6'>
                                            <button type='submit'>Send</button>
                                            </form>";
                                             echo "</div>";
                                        }else{
                                            echo "<p>No comments on this post</p>";
                                            echo "<form action='comment.php' method='post'>
                                            <input type='text' hidden value='" . $row['postID'] . "' name='postID'>
                                            <input type='text' name='comment' class='col s6'>
                                            <button type='submit'>Send</button>
                                            </form>";
                                            echo "</div>";

                                        }
                                    }
                                } else {
                                    echo "No posts available";
                                }
                            }
                        }else{
                            echo "You need to be following this user to view their posts.";
                            $notFollowing = true;
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
    echo "<div class='col s12' id='climbs'>";
    if(!$notFollowing) {
        $allHasClimbedArray = array();
        $findClimbs = "SELECT * FROM hasClimbed WHERE userID='" . $_GET['id'] . "'";
        $res = mysqli_query($connect, $findClimbs);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                array_push($allHasClimbedArray, $row['climbID']);
            }
        }
        $values = array_count_values($allHasClimbedArray);
        arsort($values);
        $foundValuesArray = array();
        $foundValuesArray = array_keys($values);
        if (sizeof($foundValuesArray) != 0) {
            for ($i = 0; $i < sizeof($foundValuesArray); $i++) {
                $findAllTypes = "SELECT * FROM climbs WHERE climbID='" . $allHasClimbedArray[$i] . "'";
                $res = mysqli_query($connect, $findAllTypes);
                if (mysqli_num_rows($res) > 0) {
                    echo "<ul class='collapsible'>";
                    while ($row = mysqli_fetch_assoc($res)) {
                        showClimbs($row);
                    }
                    echo "</ul>";
                }
            }
        } else {
            echo "This user has currently not climbed anything!";
        }
    }else{
        echo "You need to be following this user in order to see what they have climbed.";
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
<div id="modal1" class="modal">
    <div class="modal-content">

    </div>
</div>
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
<script>
    $(document).ready(function(){
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
        $('.dropdown-trigger').dropdown();

    })
</script>
