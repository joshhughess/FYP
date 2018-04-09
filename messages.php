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

echo "<p class='checkURLconversation' style='display:none'>";
if(isset($_GET['user'])){
    $findFromURL ="SELECT * FROM conversations WHERE userID='".$_GET['user']."' OR user2ID='".$_GET['user']."'";
    $res = mysqli_query($connect,$findFromURL);
    if(mysqli_num_rows($res)>0) {
        while ($row = mysqli_fetch_assoc($res)){
            echo $row['conversationID'];
        }
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
}else{
    $checkUserHasMessages = "SELECT * FROM conversations WHERE user2ID='".$_SESSION['userID']."'";
    $res = mysqli_query($connect,$checkUserHasMessages);
    if(mysqli_num_rows($res)>0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $values=array();
            array_push($values,$row['conversationID']);
            array_push($values,$row['userID']);
            array_push($conversationArray,$values);
       }
    }else {
       echo "no messages yet";
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
                    array_push($values,$row['image']);
                    array_push($values,$row['sentFromID']);
                    array_push($theConversations,$values);
                }
            }
            echo "</tr>";
        }
    }else{
        echo mysqli_error($connect);
    }
}
echo "</table>";
echo "<div class='messages' style='width:85%;float:right;height: 500px;overflow: auto;'>";
for($i=0;$i<sizeof($theConversations);$i++){
    if($_SESSION['userID']==$theConversations[$i][4]){
        echo "<div class='message conversation".$theConversations[$i][0]."' style='margin-left:5%;'>";
    }else{
        echo "<div class='message conversation".$theConversations[$i][0]."' style='margin-right:5%;'>";
    }
    echo "<p>".$theConversations[$i][1]."</p>";
    echo "<p>".$theConversations[$i][2]."</p>";
    echo "<p>".$theConversations[$i][3]."</p>";
    echo "<p>".findUsername($theConversations[$i][4])."</p>";
    echo "</div>";
}

echo "</div><div class='replyMessage' style='float: right; width: 85%;bottom: 0;'><form method='post' action='sendMessage.php'>";
echo "<input type='text' hidden id='replyTo' name='conversationID'>";
echo "<input type='text' hidden id='messageTo' name='messageTo'>";
echo "<input type='text' name='sendMessage'>";
echo "<button class='btn waves-effect waves-light' type='submit' name='reply'><i class=\"material-icons\">send</i></button>";
echo "</div></div>";
echo "</form></div>";
?>
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
            console.log(checkURLuser);
        }
        $('.cRow').click(function(){
            $('.message').hide();
            var convID = $(this).attr('convID');
            var messageTo = $(this).attr('messageTo');
            $('.conversation'+convID).show()
            $('#replyTo').val(convID);
            $('#messageTo').val(messageTo);
        });
    });
</script>
