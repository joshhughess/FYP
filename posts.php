<?php
/**
 * Created by PhpStorm.
 * User: joshh
 * Date: 18/04/2018
 * Time: 11:47
 */
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
if(isset($_GET['reportAlreadySent'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">You\'ve already posted a report for this</span>
                            <p>You can only report once.</p>
                        </div>
                    </div>
                </div>
           </div>';
}
if(isset($_GET['postID'])){
    $checkIfUserPosted = "SELECT * FROM post WHERE postID='".$_GET['postID']."' AND userID='".$_SESSION['userID']."'";
    $res = mysqli_query($connect,$checkIfUserPosted);
    $hasPosted = false;
    if($res){
        $hasPosted =true;
    }
    $sql = "SELECT * FROM post WHERE postID='".$_GET['postID']."'";
    $res = mysqli_query($connect,$sql);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            echo "<p>".$row['post']."</p>";
            $votes = $row['votesUp']-$row['votesDown'];
            echo "<span class='votes_count' id='votes_count".$row['postID']."'>".$votes." votes</span>";
            echo "<span class='vote_buttons' id='vote_buttons".$row['postID']."'>
            <a href='javascript:;' class='vote_upPost' id='".$row['postID']."'></a>
            <a href='javascript:;' class='vote_downPost' id='".$row['postID']."'></a>
            </span>";
            echo "<i class='material-icons options dropdown-trigger' data-activates='dropdown".$row['postID']."' data-beloworigin='true'>more_vert</i>";
            echo "<ul id='dropdown".$row['postID']."' class='dropdown-content'>
                <li><a href='#' class='modalSelect' id='".$row['postID']."'>Report</a></li>
            </ul>";
            echo "<h4>All comments</h4>";
            $findComments = "SELECT * FROM comments WHERE postID='".$_GET['postID']."'";
            $res = mysqli_query($connect,$findComments);
            if(mysqli_num_rows($res)>0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<p>".$row['comment']."</p>";
                    $votes = $row['votesUp']-$row['votesDown'];
                    echo "<span class='votes_count' id='votes_count".$row['commentID']."'>".$votes." votes</span>";
                    echo "<span class='vote_buttons' id='vote_buttons".$row['commentID']."'>
                    <a href='javascript:;' class='vote_upComment' id='".$row['commentID']."'></a>
                    <a href='javascript:;' class='vote_downComment' id='".$row['commentID']."'></a>
                    </span>";
                            echo "<i class='material-icons options dropdown-trigger' data-activates='dropdownComment".$row['commentID']."' data-beloworigin='true'>more_vert</i>";
                            echo "<ul id='dropdownComment".$row['commentID']."' class='dropdown-content'>
                        <li><a href='#' class='modalSelect' id='".$row['postID']."' commentID='".$row['commentID']."'>Report</a></li>";
                            if($hasPosted=true){
                                echo "<li><form method='post' action='removeComment.php'><input type='text' hidden value='".$row['commentID']."' name='commentID'><input type='text' hidden value='".$row['postID']."' name='postID'><button type='submit'>Remove</button></form></li>";
                            }
                    echo "</ul>";
                }
            }else{
                echo mysqli_error($connect);
            }
            echo '<div id="modal1" class="modal">
        <div class="modal-content">

        </div>
    </div>';
        }
    }else{
        echo mysqli_error($connect);
    }
}else{
    header("Location:index.php");
}
?>
<script>
    $(document).ready(function(){
        $('.dropdown-trigger').dropdown();
        if(window.location.href.indexOf("removedComment")>-1){
            Materialize.toast('You\'ve removed the comment', 3000);
        }
        $('.modalSelect').on('click',function(){
            $('.modal').modal();
            $('.modal').modal('open');
            $('.modal').html('<form action="report.php" method="post">' +
                '<h4>Report this post</h4>' +
                '<input type="text" value="'+$(this).attr('id')+'" hidden name="postID">' +
                '<input type="text" value="'+$(this).attr('commentID')+'" hidden name="commentID">'+
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
    });
</script>
<style>
    span.link a {
        font-size:150%;
        color: #000000;
        text-decoration:none;
    }
    a.vote_upPost, a.vote_downPost, a.vote_UpComment, a.vote_downComment {
        display:inline-block;
        background-repeat:none;
        background-position:center;
        height:16px;
        width:16px;
        margin-left:4px;
        text-indent:-900%;
    }

    a.vote_upPost, a.vote_UpComment {
        background:url('images/thumb_up.png');
    }

    a.vote_downPost, a.vote_downComment {
        background:url('images/thumb_down.png');
    }
    .options{
        cursor:default;
    }
</style>
<script type='text/javascript' src='js/sendVote.js'></script>