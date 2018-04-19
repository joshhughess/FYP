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

    echo "<title>Messages</title>";
    echo "<p class='checkURLconversation' style='display:none'>";
    if(isset($_GET['user'])){
        $findFromURL ="SELECT * FROM conversations WHERE userID='".$_GET['user']."' AND user2id='".$_SESSION['userID']."' OR user2ID='".$_GET['user']."' AND userID ='".$_SESSION['userID']."'";
        $res = mysqli_query($connect,$findFromURL);
        if(mysqli_num_rows($res)>0) {
            while ($row = mysqli_fetch_assoc($res)){
                echo $row['conversationID'];
            }
        }else{
            echo "N";
        }
    }
    echo "</p>";
    echo "<p class='checkURLuser' style='display:none'>";
    if(isset($_GET['user'])){
        echo findUsername($_GET['user']);
    }
    echo "</p>";
    $conversationArray = array();
    $checkUserHasMessages = "SELECT * FROM conversations WHERE userID='".$_SESSION['userID']."'";
    $res = mysqli_query($connect,$checkUserHasMessages);
    if(mysqli_num_rows($res)>0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $values=array();
            array_push($values,$row['conversationID']);
            array_push($values,$row['user2id']);
            array_push($conversationArray,$values);
        }
    }
    $checkUserHasMessages2 = "SELECT * FROM conversations WHERE user2id='".$_SESSION['userID']."'";
    $res = mysqli_query($connect,$checkUserHasMessages2);
    if(mysqli_num_rows($res)>0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $values=array();
            array_push($values,$row['conversationID']);
            array_push($values,$row['userID']);
            array_push($conversationArray,$values);
       }
    }
    if(!isset($_GET['user'])) {
        $checkAnyMessages = "SELECT * FROM conversations WHERE userID='" . $_SESSION['userID'] . "' OR user2id='" . $_SESSION['userID'] . "'";
        $res = mysqli_query($connect, $checkAnyMessages);
        if (mysqli_num_rows($res) == 0) {
            echo "No messages yet";
        }
    }
    echo "<table style='width:15%;float:left'>";
    $theConversations = array();
    for($i=0;$i<sizeof($conversationArray);$i++){
        $findUser = "SELECT * FROM users WHERE userID='".$conversationArray[$i][1]."'";
        $res = mysqli_query($connect,$findUser);
        if(mysqli_num_rows($res)>0){
            while($row = mysqli_fetch_assoc($res)){
                echo "<tr class='cRow' messageTo='".$row['username']."' convID='".$conversationArray[$i][0]."'>";
                echo "<td>".$row['username']."</td>";
                $findMessages = "SELECT * FROM messages WHERE conversationID='".$conversationArray[$i][0]."' ORDER  BY messageID ASC";
                $res = mysqli_query($connect,$findMessages);
                if(mysqli_num_rows($res)>0){
                    while($row = mysqli_fetch_assoc($res)){
                        $values = array();
                        array_push($values,$row['conversationID']);
                        array_push($values,$row['message']);
                        array_push($values,$row['messageTime']);
                        array_push($values,$row['sentFromID']);
                        array_push($values,$row['messageID']);
                        array_push($theConversations,$values);
                    }
                }
                echo "</tr>";
            }
        }else{
            echo mysqli_error($connect);
        }
    }
    if(isset($_GET['user'])){
        $checkConversation="SELECT * FROM conversations WHERE userID='".$_SESSION['userID']."' AND user2id='".$_GET['user']."' OR user2id='".$_SESSION['userID']."' AND userID='".$_GET['user']."'";
        $res = mysqli_query($connect,$checkConversation);
        if(mysqli_num_rows($res)==0) {
            echo "<tr class='cRow' messageTo='" . findUsername($_GET['user']) . "' convID='N'><td>" . findUsername($_GET['user']) . "</td></tr>";
        }
    }
    echo "</table>";
    echo "<div class='messages' style='width:85%;float:right;height: 500px;overflow: auto;'>";
    for($i=0;$i<sizeof($theConversations);$i++){
        if($_SESSION['userID']==$theConversations[$i][3]){
            echo "<div class='message conversation".$theConversations[$i][0]." grey lighten-3' messageDiv='".$theConversations[$i][4]."' style='margin-left:5%;'>";
        }else{
            echo "<div class='message conversation".$theConversations[$i][0]." grey lighten-3' messageDiv='".$theConversations[$i][4]."' style='margin-right:5%;'>";
        }
        echo "<a href='userProfile.php?id=".$theConversations[$i][3]."'<p>".findUsername($theConversations[$i][3])."</p></a>";
        echo "<p>".$theConversations[$i][1]."</p>";
        $format = 'Y-m-d H:i:s';
        $dateFormat = DateTime::createFromFormat($format,$theConversations[$i][2]);
        echo "<p>".$dateFormat->format("H:i")."</p>";
        echo "<p class='fullDate messageID".$theConversations[$i][4]."'><i>".$dateFormat->format('d-m-Y')."</i></p>";
        echo "</div>";
    }

    echo "</div><div class='replyMessage' style='float: right; width: 85%;bottom: 0;'><form method='post' action='sendMessage.php'>";
    echo "<input type='text' hidden id='replyTo' name='conversationID'>";
    echo "<input type='text' hidden id='messageTo' name='messageTo'>";
    echo "<textarea type='text' class='materialize-textarea sendMessage' autocomplete='off' name='sendMessage' data-length='256'></textarea>";
    echo "<button class='right btn waves-effect waves-light sendForm' type='button' name='reply'><i class=\"material-icons\">send</i></button>";
    echo "</div></div>";
    echo "</form></div>";
}else{
    header("Location:index.php?notLoggedin");
}
?>
<div id="modal1" class="modal">
    <div class="modal-content">

    </div>
</div>
<style>
    .fullDate{
        display:none;
    }
    .character-counter{
        float:none !important;
    }
    .sendMessage{
        padding:0 !important;
        width:88% !important;
    }
    .message{
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        padding:10px;
        margin-bottom: 5px;
    }

</style>
<script>
    $(document).ready(function(){
        $('.message').hide();
        var checkURLconversation = $('.checkURLconversation').html();
        var checkURLuser = $('.checkURLuser').html();
        if(checkURLconversation){
            $('.conversation'+checkURLconversation).show();
            $('#replyTo').val(checkURLconversation);
        }
        if(checkURLuser){
            $('#messageTo').val(checkURLuser);
            $('[messageTo='+checkURLuser+']').addClass('grey lighten-3');
        }
        $('.cRow').click(function(){
            $('.cRow').removeClass('grey lighten-3');
            $(this).addClass('grey lighten-3');
            $('.message').hide();
            var convID = $(this).attr('convID');
            var messageTo = $(this).attr('messageTo');
            $('.conversation'+convID).show()
            $('#replyTo').val(convID);
            $('#messageTo').val(messageTo);
        });
        $('.sendForm').on('click',function(){
            if($('#replyTo').val()!="" && $('#messageTo').val()!=""){
                if($('.sendMessage').val().length<=256 && $('.sendMessage').val().length>0){
                    $('form').submit();
                }else{
                    $('.modal').modal();
                    $('.modal').modal('open');
                    $('.modal-content').html("<p>Please ensure the message is less than 256 characters and greater than 0</p>");
                }
            }else{
                $('.modal').modal();
                $('.modal').modal('open');
                $('.modal-content').html("<p>Please select a user to message</p>");
            }

        });
        $('.message').on('click',function(){
            $('.fullDate').hide();
            var messageDiv = $(this).attr('messageDiv');
            $('.messageID'+messageDiv).show();
        });

        if(window.location.search==""){
            $('table tr:nth-child(1)').click();
        }
    });
</script>
