<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
if(isset($_GET['key'])){
    if($_GET['key']==$_SESSION['passwordKey']) {
        if (isset($_SESSION['newPass'])) {
            $sql = "UPDATE users SET password='" . $_SESSION['newPass'] . "' WHERE userID='" . $_SESSION['userID'] . "'";
            $res = mysqli_query($connect, $sql);
            if ($res) {
                echo "<div class=\"row\">
                        <div class=\"col s12 m12\">
                          <div class=\"card orange lighten-3\">
                            <div class=\"card-content black-text\">
                              <span class=\"card-title\">Validated!</span>
                              <p>Thank you for validating your new password.</p>
                            </div>
                        </div>
                    </div>
                    </div>";
            } else {
                echo "<p>Seems to be something wrong here</p>";
            }
        }
    }
}
if(isset($_POST['acceptClimb'])){
    $sql = "UPDATE meetings SET accepted='1' WHERE meetingsID='".$_POST['climbMeetID']."'";
    if (!mysqli_query($connect, $sql)) {
        echo mysqli_error($connect);
    }
}
$username = $_SESSION['username'];
$findUserPost="SELECT * FROM post WHERE username='$username' ORDER BY postID DESC";
$res = mysqli_query($connect,$findUserPost);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        echo "<p>".$row['post']."</p>";
    }
}
?>
<?php
$sql = "SELECT * FROM meetings WHERE userID='".$_SESSION['userID']."' AND accepted='0'";
$res = mysqli_query($connect,$sql);
if(mysqli_num_rows($res)>0){
    echo "<h5>Your sent climbing meeting requests</h5>";
    echo "<table border='1'><tr><td>Climber</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Location</td></tr>";
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr><td>".findUsername($row['user2id'])."</td>";
        $startDate = new DateTime($row['start']);
        $endDate = new DateTime($row['end']);
        echo "<td>".$startDate->format('l jS F Y')."</td>";
        echo "<td>".$startDate->format('G:ia')."</td>";
        echo "<td>".$endDate->format('G:ia')."</td>";
        echo "<td>".$row['title']."</td></tr>";
    }
    echo "</table>";
}else {
    $sql = "SELECT * FROM meetings WHERE user2id='" . $_SESSION['userID'] . "' AND accepted='0'";
    $res = mysqli_query($connect,$sql);
    if(mysqli_num_rows($res)>0){
        echo "<h5>Your received climbing meeting requests</h5>";
        echo "<table border='1'><tr><td>Climber</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Location</td><td>Accept</td></tr>";
        while($row = mysqli_fetch_assoc($res)){
            echo "<tr><td>".findUsername($row['userID'])."</td>";
            $startDate = new DateTime($row['start']);
            $endDate = new DateTime($row['end']);
            echo "<td>".$startDate->format('l jS F Y')."</td>";
            echo "<td>".$startDate->format('G:ia')."</td>";
            echo "<td>".$endDate->format('G:ia')."</td>";
            echo "<td>".$row['title']."</td>";
            echo "<td><form method='post'><input type='text' hidden name='climbMeetID' value='".$row['meetingsID']."'><button class='btn waves-effect waves-light' type='submit' name='acceptClimb'>Accept</button></form></td></tr>";
        }
        echo "</table>";
    }else {
        echo "No meeting requests at the moment.";
    }
}
?>
<h5>Your climbing meetings</h5>
<?php
$sql = "SELECT * FROM meetings WHERE userID='".$_SESSION['userID']."' OR user2id='".$_SESSION['userID']."' AND accepted='1' ORDER BY start ASC";
$res = mysqli_query($connect,$sql);
if(mysqli_num_rows($res)>0){
    echo "<div id='calendar' style='width: 80%;margin:0 auto;'></div>";
}else {
    echo "No meetings yet.";
}
?>
<h6>Most popular climbs by you!</h6>
<?php
$sql = "SELECT climbID, COUNT(climbID) AS 'value_occurrence' FROM hasclimbed WHERE userID='".$_SESSION['userID']."' GROUP BY climbID ORDER BY 'value_occurrence' DESC LIMIT 2";
$res = mysqli_query($connect,$sql);
$popularArray = array();
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        array_push($popularArray,$row['climbID']);
    }
}else{
    echo "<p>Are you sure you've climbed anything?</p>";
}
$climbingTypeArray = array();
for($i=0;$i<sizeof($popularArray);$i++){
    $findClimbInformation = "SELECT * FROM climbs WHERE climbID='".$popularArray[$i]."'";
    $res = mysqli_query($connect,$findClimbInformation);
    if(mysqli_num_rows($res)>0){
        echo "<ul class='collapsible'>";
        while($row = mysqli_fetch_assoc($res)){
            echo "<li><div class='collapsible-header' style='display: block'><h5>".$row['name']." - ".$row['grade']."<a href='climb.php?id=".$row['climbID']."' class='right'><i class='material-icons' style='color:rgba(0,0,0,0.87)'>info_outline</i></a></h5></div>";
            echo "<div class='collapsible-body'><h6>Climbing Types</h6><ul class='collection'>";
            if($row['isSport']==1){
                echo "<li class='collection-item'>Sport</li>";
                array_push($climbingTypeArray,"isSport");
            }
            if($row['isTrad']==1){
                echo "<li class='collection-item'>Trad</li>";
                array_push($climbingTypeArray,"isTrad");
            }
            if($row['isTopRope']==1){
                echo "<li class='collection-item'>Top Rope</li>";
                array_push($climbingTypeArray,"isTopRope");
            }
            if($row['isBouldering']==1){
                echo "<li class='collection-item'>Bouldering</li>";
                array_push($climbingTypeArray,"isBouldering");
            }
            if($row['isMountaineering']==1){
                echo "<li class='collection-item'>Mountaneering</li>";
                array_push($climbingTypeArray,"isMountaineering");
            }
            if($row['isFreeSolo']==1){
                echo "<li class='collection-item'>Free Solo</li>";
                array_push($climbingTypeArray,"isFreeSolo");
            }
            echo "</ul>";
            echo $row['information']."</div>";
            echo "</li>";
        echo "</ul>";
        }
    }else{
        echo "something wrong";
    }
}
?>
<h6>Suggested climbs based on your most popular climbed</h6>
<?php
$values = array_count_values($climbingTypeArray);
arsort($values);
$popular = array_slice(array_keys($values), 0, 3, true);
$findRandomClimb = "SELECT * FROM climbs WHERE $popular[0]='1' AND climbID<>'".$popularArray[0]."' OR climbID<>'".$popularArray[1]."' OR $popular[1]='1' AND climbID<>'".$popularArray[0]."' OR climbID<>'".$popularArray[1]."' OR $popular[2]='1' AND climbID<>'".$popularArray[0]."' OR climbID<>'".$popularArray[1]."' ORDER BY RAND() LIMIT 1";
$res = mysqli_query($connect, $findRandomClimb);
if(mysqli_num_rows($res)>0){
    echo "<ul class='collapsible'>";
    while($row = mysqli_fetch_assoc($res)){
        echo "<li><div class='collapsible-header' style='display: block'><h5>".$row['name']." - ".$row['grade']."<a href='climb.php?id=".$row['climbID']."' class='right'><i class='material-icons' style='color:rgba(0,0,0,0.87)'>info_outline</i></a></h5></div>";
        echo "<div class='collapsible-body'><h6>Climbing Types</h6><ul class='collection'>";
        if($row['isSport']==1){
            echo "<li class='collection-item'>Sport</li>";
            array_push($climbingTypeArray,"isSport");
        }
        if($row['isTrad']==1){
            echo "<li class='collection-item'>Trad</li>";
            array_push($climbingTypeArray,"isTrad");
        }
        if($row['isTopRope']==1){
            echo "<li class='collection-item'>Top Rope</li>";
            array_push($climbingTypeArray,"isTopRope");
        }
        if($row['isBouldering']==1){
            echo "<li class='collection-item'>Bouldering</li>";
            array_push($climbingTypeArray,"isBouldering");
        }
        if($row['isMountaineering']==1){
            echo "<li class='collection-item'>Mountaneering</li>";
            array_push($climbingTypeArray,"isMountaineering");
        }
        if($row['isFreeSolo']==1){
            echo "<li class='collection-item'>Free Solo</li>";
            array_push($climbingTypeArray,"isFreeSolo");
        }
        echo "</ul>";
        echo $row['information']."</div>";
        echo "</li>";
        echo "</ul>";
    }
}else{
    echo "unable to find random climb based on your previous climbed";
}
?>
<h5>Update your preferences here:</h5>

<form id='pref' class="col s6" action='pref.php' method='post'>
    <div class="row">
        <div class="col s6">
            <p>
                <input type='checkbox' id='filled-in-box' class="filled-in" name='postVisAll'>
                <label for="filled-in-box">Allow people who aren't following me to view my posts?</label>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col s6">
            <p>
                <input type='checkbox' class='filled-in' id='filled-in-box1' name='allowAllFollow' value='Y'>
                <label for='filled-in-box1'>Allow anyone to follow me?</label>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s6">
            <button class="btn waves-effect waves-light" type="submit" name="post">Update</button>
        </div>
    </div>
</form>
<br>
<form id='pref' class="col s6" action='pref.php' method='post'>
    <div class="row">
        <div class="input-field col s6">
            <input placeholder="New Password" id="passwordInput" type='text' name='password'>
            <label for="passwordInput">Change Password?</label>
            <button type='submit' class='btn waves-effect waves-light' name='changePass'>Change Password</button>
        </div>
    </div>
</form>

<form id='logout' action='logout.php' method='post'>
    <button type='submit' class='btn waves-effect waves-light' name='submit' onClick='logout.php'>Logout</button>
</form>

<div id="modal1" class="modal">
    <div class="modal-content">

    </div>
</div>
<script>
    $(document).ready(function(){
        $('#calendar').fullCalendar({
            // put your options and callbacks here
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: {
                url:"events.php",
                type:'post',
                contentType: "application/json; charset=utf-8",
                error: function(req, err){ console.log('my message: ' + err); }
            },
            eventRender: function(event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable:true,
            eventClick: function(calEvent, jsEvent, view){
                $('.modal').modal();
                $('.modal').modal('open');
                $('.modal-content').html("<h4>"+calEvent.title);
                var userID = calEvent.userID;
                var user2id = calEvent.user2id
                $.ajax({url:"findUsername.php?userID="+userID,success:function(result){
                    if(result==123){
                        //same person
                        $.ajax({url:"findUsername.php?userID="+user2id,success:function(result){
                            $('.modal-content').append("with "+result+"</h4>");
                        }});
                    }else {
                        $('.modal-content').append("With "+result+"</h4>");
                    }
                }});
                $('.modal-content').append("<p>From "+msToTime(calEvent.start)+" to "+msToTime(calEvent.end)+"</p>");
            }
        });
        function msToTime(duration) {
            var milliseconds = parseInt((duration%1000)/100)
                , seconds = parseInt((duration/1000)%60)
                , minutes = parseInt((duration/(1000*60))%60)
                , hours = parseInt((duration/(1000*60*60))%24);

            hours = (hours < 10) ? "0" + hours : hours;
            minutes = (minutes < 10) ? "0" + minutes : minutes;
            seconds = (seconds < 10) ? "0" + seconds : seconds;

            return hours + ":" + minutes;
        }
    });
</script>