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
<script>
    $(document).ready(function(){
        $('#calendar').fullCalendar({
            // put your options and callbacks here
            editable: true,
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
            selectable:true
        });
    });
</script>