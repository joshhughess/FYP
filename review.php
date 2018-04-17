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
if($_POST['climbID']){
    echo "<title>Review - ".findClimbName($_POST['climbID'])."</title>";
    $sql = "INSERT INTO hasClimbed(climbID,userID) VALUES ('".$_POST['climbID']."','".$_SESSION['userID']."')";
    $res = mysqli_query($connect,$sql);
    if($res){
        echo '<div class="row">
                <div class="col s12">
                    <div class="card blue lighten-4">
                        <div class="card-content">
                            <span class="card-title">You\'ve climbed <b>' . findClimbName($_POST['climbID']) . '</b></span>
                            <p>Feel free to leave a review below!</p>
                        </div>
                    </div>
                </div>
           </div>';
        echo "<form method='post' action='sendReview.php'>";
        echo "<input name='climbID' hidden type='text' value='".$_POST['climbID']."'>";
        echo "<input name='rating' class='theRating' hidden type='text'>";
        echo "<label for='reviewTitle'>Title for review</label>";
        echo "<input type='text' id='reviewTitle' name='reviewTitle'>";
        echo "<label for='reviewComments'>Leave your comments about the climb below</label>";
        echo "<input type='text' id='reviewComments' name='reviewComments'>";
        echo "<p>Star Rating:</p>";
        echo "<select id='starRating'>";
        echo "  <option value='1'>1</option>";
        echo "  <option value='2'>2</option>";
        echo "  <option value='3'>3</option>";
        echo "  <option value='4'>4</option>";
        echo "  <option value='5'>5</option>";
        echo "</select>";
        echo "<button class='btn waves-effect waves-light' type='submit' name='saveReview'> Save your review</button>";
        echo "</form>";
    }else{
        echo "something went wrong please try again";
    }
}
?>
<script>
    $(function() {
        $('#starRating').barrating({
            theme: 'fontawesome-stars',
            onSelect: function(value, text, event) {
                if (typeof(event) !== 'undefined') {
                    // rating was selected by a user
                    $('.theRating').val(value);
                    console.log(value);
                }
            }
        });
    });
</script>
