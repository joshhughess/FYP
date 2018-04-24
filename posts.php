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
if(isset($_GET['postID'])){
    $postID = mysqli_real_escape_string($connect,$_GET['postID']);
    $sql = "SELECT * FROM post WHERE postID='".$postID."'";
    $res = mysqli_query($connect,$sql);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            echo "<title>".$row['post']."</title>";
            showPost($row);
            $findComments = "SELECT * FROM comments WHERE postID='".$postID."' ORDER BY numberOfVotes DESC";
            $res = mysqli_query($connect,$findComments);
            if(mysqli_num_rows($res)>0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    showComment($row);
                }

            }
            echo "<form action='comment.php' class='col s12' method='post'>
                        <div class='row'><div class=\"input-field col s12\">
                        <input type='text' hidden value='" . $postID . "' name='postID'>
                        <input type='text' required='required' data-length='256' maxlength='256' name='comment' >
                        <button class='btn waves-effect waves-green green darken-2' type='submit'>Send</button>
                        </div></div>
                    </form>";
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
        $('input#comment').characterCounter();
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
</div>
<script type='text/javascript' src='js/sendVote.js'></script>
<?php include('footer.php');?>
