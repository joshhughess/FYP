<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    header("Location:index.php?notLoggedin");
}
echo "<title>Profile</title>";
echo "<h5>Welcome to your profile ".$_SESSION['username']."</h5>";
echo "<p>You will notice there are 5 sections to view here, as shown in the tabs below. Simply click on a tab to view the content within</p>";
echo "<p>Your 'profile' section will show you all of your climbs and reviews. Your 'Posts' section will show you all of your posts. The 'meetings' tab will show you all meeting information. The 'Suggested climbs' tab will recommend you a climb! And the preferences can be changed in the 'Preferences', as well as the ability to logout, change password and deactivate your account.</p>";
if(isset($_GET['key'])){
    if($_GET['key']==$_SESSION['passwordKey']) {
        if (isset($_SESSION['newPass'])) {
            $encrypt = password_hash($_SESSION['newPass'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password='" . $encrypt . "' WHERE userID='" . $_SESSION['userID'] . "'";
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
    $climbMeetID=mysqli_real_escape_string($connect,$_POST['climbMeetID']);
    $sql = "UPDATE meetings SET accepted='1' WHERE id='".$climbMeetID."'";
    if (!mysqli_query($connect, $sql)) {
        echo mysqli_error($connect);
    }
}
$username = $_SESSION['username'];

echo '<div class="row">
    <div class="col">
      <ul class="tabs left">
        <li class="tab"><a class="active" href="#profile">Profile</a></li>
        <li class="tab"><a href="#posts">Posts</a></li>
        <li class="tab"><a href="#meetings">Meetings</a></li>
        <li class="tab"><a href="#suggestedClimbs">Suggested Climbs</a></li>
        <li class="tab"><a href="#preferences">Preferences</a></li>
      </ul>
    </div>';
echo '<script type=\'text/javascript\' src=\'js/sendVote.js\'></script>';
echo "<style>
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
echo "<div class='col s12' id='profile'>";
//work out average grade
$allClimbsArray = array();
$findAllClimbs = "SELECT * FROM hasClimbed WHERE userID='".$_SESSION['userID']."'";
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
$findInformationAndPicture = "SELECT picture, userID FROM users WHERE userID='".$_SESSION['userID']."'";
$res = mysqli_query($connect,$findInformationAndPicture);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        if($row['picture']!=null) {
            echo '<img class="hidden-image" style="display:none" src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '">';
            echo '<h5><img class="circle edit-image" style="background:50% 50% no-repeat;width:75px;height:75px" src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '"/>' . findUsername($row['userID']);
        }else{
            echo '<img class="hidden-image" style="display:none" src="http://localhost/myClimb/images/userProfile.png">';
            echo '<h5><img class="circle edit-image" style="background:50% 50% no-repeat;width:75px;height:75px" src="http://localhost/myClimb/images/userProfile.png"/>' . findUsername($row['userID']);
        }
        if(sizeof($mostPopularGrade)!=0){
            echo " - <small>(".$mostPopularGrade[0].")</small></h5>";
        }else{
            echo "</h5>";
        }
    }
}

echo "<form  method='post' class='uploadImage' action='upload.php' enctype='multipart/form-data'>
    <input style='display: none' type='file' class='fileUpload' name='fileToUpload' id='fileToUpload'>
</form>";
$allHasClimbedArray = array();
$findClimbs = "SELECT * FROM hasClimbed WHERE userID='".$_SESSION['userID']."'";
$res = mysqli_query($connect, $findClimbs);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        array_push($allHasClimbedArray,$row['climbID']);
    }
}
$values = array_count_values($allHasClimbedArray);
arsort($values);
$foundValuesArray = array();
$foundValuesArray = array_keys($values);
if(sizeof($foundValuesArray)!=0) {
    echo "<h5>Your climbs and reviews</h5>";
    for ($i = 0; $i < sizeof($foundValuesArray); $i++) {
        $findAllTypes = "SELECT * FROM climbs WHERE climbID='" . $foundValuesArray[$i] . "'";
        $res = mysqli_query($connect, $findAllTypes);
        if (mysqli_num_rows($res) > 0) {
            echo "<ul class='collapsible'>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<li><div class='collapsible-header' style='display: block'>
    <div class='row'><div class='col s9'>
    <div class='row'><div class='col s4'><img class='circle' style='background:50% 50% no-repeat;width:75px;height:75px;vertical-align:middle;margin-right:12px;' src='data:image/jpeg;base64,".base64_encode($row['image'])."'></div>
    <div class='col s8'><h5>".ucfirst($row['name'])." - ".$row['grade']."</h5></div></div>
    
    </div><div class='col s3'>
    <a href='climb.php?id=".$row['climbID']."' class='right' style='color:rgba(0,0,0,0.87)'>More info <i class='material-icons' >info_outline</i></a>
    </div></div></div>";
                echo "<div class='collapsible-body'><h6>Climbing Types</h6><ul class='collection'>";
                if($row['isSport']==1){
                    echo "<li class='collection-item'>Sport</li>";
                }
                if($row['isTrad']==1){
                    echo "<li class='collection-item'>Trad</li>";
                }
                if($row['isTopRope']==1){
                    echo "<li class='collection-item'>Top Rope</li>";
                }
                if($row['isBouldering']==1){
                    echo "<li class='collection-item'>Bouldering</li>";
                }
                if($row['isMountaineering']==1){
                    echo "<li class='collection-item'>Mountaneering</li>";
                }
                if($row['isFreeSolo']==1){
                    echo "<li class='collection-item'>Free Solo</li>";
                }
                echo "</ul>";
                echo "<p>".$row['information']. "</p>";
                $findReview = "SELECT * FROM review WHERE userID='".$_SESSION['userID']."' AND climbID='".$row['climbID']."'";
                $res = mysqli_query($connect, $findReview);
                if(mysqli_num_rows($res)>0){
                    echo "<hr>";
                    echo "<h6>Review(s)</h6>";
                    while($row = mysqli_fetch_assoc($res)){
                        echo "<p><b>".$row['title']."</b> - (".$row['starRating']."/5)</p>";
                        echo "<p>".$row['comments']."</p>";
                    }
                }
                echo "</div>";
                echo "</li>";
            }
            echo "</ul>";

        }
    }
}else{
    echo "This user has currently not climbed anything!";
}
echo "</div>";
echo "<div class='col s12' id='posts'>";
echo '<div id="modal2" class="modal">
        <div class="modal-content">

        </div>
    </div>';
$findUserPost="SELECT * FROM post WHERE username='$username' ORDER BY postID DESC";
$res = mysqli_query($connect,$findUserPost);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        showPost($row);
        echo "</div>";
    }
}else{
    echo "You don't appear to have posted any statuses, you can do this on the homepage, by using the floating action button at the bottom right of the screen";
}
echo "</div>";
echo "<div class='col s12' id='meetings'>";
$sql = "SELECT * FROM meetings WHERE userID='".$_SESSION['userID']."' AND accepted='0'";
$res = mysqli_query($connect,$sql);
echo "<h5>Your sent climbing meeting requests</h5>";
if(mysqli_num_rows($res)>0){
    echo "<table border='1'><tr><td>Climber</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Location</td></tr>";
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr><td>".findUsername($row['user2id'])."</td>";
        $startDate = new DateTime($row['start']);
        $endDate = new DateTime($row['end']);
        echo "<td>".$startDate->format('l jS F Y')."</td>";
        echo "<td>".$startDate->format('G:ia')."</td>";
        echo "<td>".$endDate->format('G:ia')."</td>";
        $title = $row['title'];
        $location = explode(" - ",$title);
        echo "<td>".$location[0]."</td></tr>";
    }
    echo "</table>";
}else {
    echo "You've sent no requests at the moment.";

}
$sql = "SELECT * FROM meetings WHERE user2id='" . $_SESSION['userID'] . "' AND accepted='0'";
$res = mysqli_query($connect,$sql);
echo "<h5>Your received climbing meeting requests</h5>";
if(mysqli_num_rows($res)>0){
    echo "<table border='1'><tr><td>Climber</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Location</td><td>Accept</td></tr>";
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr><td>".findUsername($row['userID'])."</td>";
        $startDate = new DateTime($row['start']);
        $endDate = new DateTime($row['end']);
        echo "<td>".$startDate->format('l jS F Y')."</td>";
        echo "<td>".$startDate->format('G:ia')."</td>";
        echo "<td>".$endDate->format('G:ia')."</td>";
        $title = $row['title'];
        $location = explode(" - ",$title);
        echo "<td>".$location[0]."</td>";
        echo "<td><form method='post'><input type='text' hidden name='climbMeetID' value='".$row['id']."'>
            <button class='btn waves-effect green darken-2' type='submit' name='acceptClimb'>Accept</button></form></td></tr>";
    }
    echo "</table>";
}else {
   echo "No meeting requests at the moment.";
}
?>
<h5>Your climbing meetings</h5>
<?php
$sql = "SELECT * FROM meetings WHERE userID='".$_SESSION['userID']."' OR user2id='".$_SESSION['userID']."' AND accepted='1'
 ORDER BY start ASC";
$res = mysqli_query($connect,$sql);
if(mysqli_num_rows($res)>0){
    echo "<div id='calendar' style='width: 80%;margin:0 auto;'></div>";
}else {
    echo "No meetings yet.";
}
echo '<div id="modal1" class="modal">
        <div class="modal-content">

        </div>
    </div>';
echo "</div>";
echo "<div class='col s12' id='suggestedClimbs'>";
echo "<div class='row'><div class='col s12 l6'>";
echo "<h5>Most popular climbs by you!</h5>";
$sql = "SELECT climbID, COUNT(climbID) AS 'value_occurrence' FROM hasclimbed WHERE userID='".$_SESSION['userID']."' GROUP BY climbID ORDER BY value_occurrence DESC LIMIT 3";
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
            showClimbs($row);
        echo "</ul>";
        }
    }else{
        echo "something wrong";
    }
}
echo "</div><div class='col s12 l6'>";
echo "<h5>Suggested climbs based on your previous climb types</h5>";
$allHasClimbedArray = array();
$findAllHasClimbedByUser = "SELECT * FROM hasClimbed WHERE userID='".$_SESSION['userID']."'";
$res = mysqli_query($connect, $findAllHasClimbedByUser);
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        array_push($allHasClimbedArray,$row['climbID']);
    }
}
//var_dump($allHasClimbedArray);
$allTypesArray = array();
for($i=0;$i<sizeof($allHasClimbedArray);$i++){
    $findAllTypes = "SELECT * FROM climbs WHERE climbID='".$allHasClimbedArray[$i]."'";
    $res = mysqli_query($connect,$findAllTypes);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            if($row['isSport']==1){
                array_push($allTypesArray,"isSport");
            }
            if($row['isTrad']==1){
                array_push($allTypesArray,"isTrad");
            }
            if($row['isTopRope']==1){
                array_push($allTypesArray,"isTopRope");
            }
            if($row['isBouldering']==1){
                array_push($allTypesArray,"isBouldering");
            }
            if($row['isMountaineering']==1){
                array_push($allTypesArray,"isMountaineering");
            }
            if($row['isFreeSolo']==1){
                array_push($allTypesArray,"isFreeSolo");
            }
            array_push($allGradesArray,$row['grade']);
        }
    }
}
//find most popular climbed types
$values = array_count_values($allTypesArray);
arsort($values);
$popular = array_slice(array_keys($values), 0, 3, true);
//find all climbID's climbed
$values = array_count_values($allHasClimbedArray);
arsort($values);
$foundValuesArray = array();
$foundValuesArray = array_keys($values);
//make the sql code dynamic to the different number of climbs per person
$findRandomClimb = "SELECT * FROM climbs WHERE";
for($i=0;$i<sizeof($popular);$i++) {
    if($i==0) {
        $findRandomClimb .= " $popular[$i]=1";
    }else{
        $findRandomClimb .= " OR $popular[$i]=1";
    }
    for ($j = 0; $j < sizeof($foundValuesArray); $j++) {
        $findRandomClimb .= " AND climbID<>'".$foundValuesArray[$j]."'";
    }
}
$findRandomClimb .= " ORDER BY RAND() LIMIT 1";
$res = mysqli_query($connect, $findRandomClimb);
if($res){
    if(mysqli_num_rows($res)>0){
        echo "<ul class='collapsible'>";
        while($row = mysqli_fetch_assoc($res)){
            showClimbs($row);
        }
        echo "</ul>";
    }else{
        echo "It doesn't appear you've climbed enough yet, we need at least 1 to choose from.";
    }
}else{
    echo "It doesn't appear you've climbed enough yet, we need at least 1 to choose from.";

}
echo "</div><div class='col s12 l6'>";
echo "<h5>Suggested climbs based on your preferences</h5>";
$preferenceArray = array();
$findPreferences = "SELECT * FROM preferences WHERE username='".$_SESSION['username']."'";
$res = mysqli_query($connect,$findPreferences);
if(mysqli_num_rows($res)>0) {
    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['isSport'] == "Y") {
            array_push($preferenceArray, "isSport");
        }
        if ($row['isTrad'] == "Y") {
            array_push($preferenceArray, "isTrad");
        }
        if ($row['isTopRope'] == "Y") {
            array_push($preferenceArray, "isTopRope");
        }
        if ($row['isBouldering'] == "Y") {
            array_push($preferenceArray, "isBouldering");
        }
        if ($row['isMountaineering'] == "Y") {
            array_push($preferenceArray, "isMountaineering");
        }
        if ($row['isFreeSolo'] == "Y") {
            array_push($preferenceArray, "isFreeSolo");
        }
    }
}
$findSuggestedBasedOnPref = "SELECT * FROM climbs WHERE";
for($i=0;$i<sizeof($preferenceArray);$i++) {
    if($i==0) {
        $findSuggestedBasedOnPref .= " $preferenceArray[$i]=1";
    }else{
        $findSuggestedBasedOnPref .= " OR $preferenceArray[$i]=1";
    }
    for ($j = 0; $j < sizeof($foundValuesArray); $j++) {
        $findSuggestedBasedOnPref .= " AND climbID<>'".$foundValuesArray[$j]."'";
    }
}
$findSuggestedBasedOnPref .= " ORDER BY RAND() LIMIT 1";
$res = mysqli_query($connect,$findSuggestedBasedOnPref);
if($res) {
    if (mysqli_num_rows($res) > 0) {
        echo "<ul class='collapsible'>";
        while ($row = mysqli_fetch_assoc($res)) {
            showClimbs($row);

        }
        echo "</ul>";
    }
}else{
    echo "It doesn't appear you've got any preferences yet.";
}
echo "</div><div class='col s12 l6'>";
echo "<h5>Suggested climbs based on previous grades</h5>";
$gradeValues = array_count_values($allGradesArray);
arsort($gradeValues);

$theGradesClimbed = array();
$theGradesClimbed = array_keys($gradeValues);

$theGradeNumbers = array();
for($i=0;$i<sizeof($theGradesClimbed);$i++){
    array_push($theGradeNumbers, preg_replace("/[^0-9,.]/", "", $theGradesClimbed[$i]));
}
$gradeNumberGroup = array_count_values($theGradeNumbers);
arsort($gradeNumberGroup);

$theGradeNumberValues = array();
$theGradeNumberValues = array_keys($gradeNumberGroup);
$findSuggestedBasedOnPrevGrades = "SELECT * FROM climbs WHERE";
for($i=0;$i<sizeof($theGradeNumberValues);$i++) {
    if($i==0) {
        $findSuggestedBasedOnPrevGrades .= " grade LIKE '%$theGradeNumberValues[$i]%'";
    }else{
        $findSuggestedBasedOnPrevGrades .= " OR grade LIKE '%$theGradeNumberValues[$i]%'";
    }
    for ($j = 0; $j < sizeof($foundValuesArray); $j++) {
        $findSuggestedBasedOnPrevGrades .= " AND climbID<>'".$foundValuesArray[$j]."'";
    }
}
$findSuggestedBasedOnPrevGrades .= " ORDER BY RAND() LIMIT 1";
$res = mysqli_query($connect,$findSuggestedBasedOnPrevGrades);
if($res) {
    if (mysqli_num_rows($res) > 0) {
        echo "<ul class='collapsible'>";
        while ($row = mysqli_fetch_assoc($res)) {
            showClimbs($row);
        }
        echo "</ul>";

    }else{
        echo "It doesn't appear that there are currently any other grades similar to this yet.";
    }
}else{
    echo "It doesn't appear you've climbed enough yet, we need at least 1 to choose from";
}
echo "</div></div></div>";
echo "<div class='col s12' id='preferences'>";
echo "<h5>Update your preferences here:</h5>";
$searchPref = "SELECT * FROM preferences WHERE username='".findUsername($_SESSION['userID'])."'";
$res = mysqli_query($connect,$searchPref);
if(mysqli_num_rows($res)>0){
    //autofill preferences
    while($row = mysqli_fetch_assoc($res)){
        $prefVisAll = $row['postVisAll'];
        $prefAllowAllFollow = $row['allowAllFollow'];
        $prefIsSport = $row['isSport'];
        $prefIsTrad = $row['isTrad'];
        $prefIsTopRope = $row['isTopRope'];
        $prefIsBouldering = $row['isBouldering'];
        $prefIsMountaineering = $row['isMountaineering'];
        $prefIsFreeSolo = $row['isFreeSolo'];
        $postCommentVoteCount = $row['postCommentVoteCount'];
    }
    echo "<form id='pref' class=\"col s6\" action='pref.php' method='post'>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' id='filled-in-box' class=\"filled-in\" name='postVisAll' value='Y'";
                    if($prefVisAll=='Y'){echo "checked";}
                    echo ">
                    <label for=\"filled-in-box\">Allow people who aren't following me to view my posts?</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' class='filled-in' id='filled-in-box1' name='allowAllFollow' value='Y'";
                    if($prefAllowAllFollow=='Y'){echo "checked";}
                    echo ">
                    <label for='filled-in-box1'>Allow anyone to follow me?</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input required type='text' id='postCommentVoteCount' name='postCommentVoteCount' value='".$postCommentVoteCount."'\>
                    <label for='postCommentVoteCount'>Number of votes before comment/post is not shown</label>
                </p>
            </div>
        </div>
        <h6>Preferred Climbing types</h6>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' class='filled-in' id='filled-in-isSport' name='isSport' value='Y'";
                    if($prefIsSport=='Y'){echo "checked";}
                    echo">
                    <label for='filled-in-isSport'>Sport</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' class='filled-in' id='filled-in-isTrad' name='isTrad' value='Y'";
                    if($prefIsTrad=='Y'){echo "checked";}
                    echo">
                    <label for='filled-in-isTrad'>Trad</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' class='filled-in' id='filled-in-isBouldering' name='isBouldering' value='Y'";
                    if($prefIsBouldering=='Y'){echo "checked";}
                    echo">
                    <label for='filled-in-isBouldering'>Bouldering</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' class='filled-in' id='filled-in-isTopRope' name='isTopRope' value='Y'";
                    if($prefIsTopRope=='Y'){echo "checked";}
                    echo">
                    <label for='filled-in-isTopRope'>Top Rope</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' class='filled-in' id='filled-in-isMountaineering' name='isMountaineering' value='Y'";
                    if($prefIsMountaineering=='Y'){echo "checked";}
                    echo">
                    <label for='filled-in-isMountaineering'>Mountaineering</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col s6\">
                <p>
                    <input type='checkbox' class='filled-in' id='filled-in-isFreeSolo' name='isFreeSolo' value='Y'";
                    if($prefIsFreeSolo=='Y'){echo "checked";}
                    echo ">
                    <label for='filled-in-isFreeSolo'>Free Solo</label>
                </p>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"input-field col s6\">
                <button class=\"btn waves-effect green darken-2\" type=\"submit\" name=\"post\">Update Preferences</button>
            </div>
        </div>
    </form>";
}else{
    echo "Something seems out of order, you don't seem to have any preference settings, please contact the website developers.";
}
?>
    <br>
    <form id='pref' class="col s6" action='pref.php' method='post'>
        <div class="row">
            <div class="input-field col s6">
                <input placeholder="New Password" id="passwordInput" type='text' name='password'>
                <label for="passwordInput">Change Password?</label>
                <button type='submit' class='btn waves-effect green darken-2' name='changePass'>Change Password</button>
            </div>
        </div>
    </form>

    <form id='logout' class="col s6" action='logout.php' method='post'>
        <div class="row">
            <div class="input-field col s6">
        <button type='submit' class='btn waves-effect yellow darken-2' name='submit' onClick='logout.php'>Logout</button>
            </div>
        </div>
    </form>
    <form id='deactivate' class="col s6" action='deactivate.php' method='post'>
        <div class="row">
            <div class="input-field col s6">
                <button type='submit' class='btn waves-effect red' name='deactivate' onClick='deactivate.php'>Deactivate acount</button>
            </div>
        </div>
    </form>
</div>
</div>
<style>
    .grayscale{
        -webkit-filter: grayscale(100%) !important; /* Safari 6.0 - 9.0 */
        filter: grayscale(100%) !important;
    }
</style>
<script>
    $(document).ready(function(){
        $('.dropdown-trigger').dropdown();

        $('.report').on('click',function(){
            $('#modal2').modal();
            $('#modal2').modal('open');
            $('#modal2').html('<form action="report.php" method="post">' +
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
        $('.edit-image').mouseover(function(){
            $(this).attr("src","http://localhost/myClimb/images/edit.png");
            $(this).addClass("grayscale");
        }).mouseleave(function(){
            $(this).removeClass("grayscale");
            $(this).attr("src",$('.hidden-image').attr("src"));
        }).on('click', function(){
            $('.fileUpload').click();
        });
        $("input:file").change(function (){
            $('.uploadImage').submit();
        });
        var string;
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
                $('#modal1').modal();
                $('#modal1').modal('open');
                var title = calEvent.title;
                var actualTitle = title.split(" - ");
                $('.modal-content').html("");
                var userID = calEvent.userID;
                var user2id = calEvent.user2id;
                $.ajax({url:"findUsername.php?userID="+userID,success:function(result){
                    if(result==123){
                        //same person
                        $.ajax({url:"findUsername.php?userID="+user2id,success:function(result){
                            $('.modal-content').append("<h4>"+actualTitle[0]+" with "+result+"</h4>");
                        }});
                    }else {
                        $('.modal-content').append("<h4>"+actualTitle[0]+" with "+result+"</h4>");
                    }
                    string = "<p>From "+msToTime(calEvent.start)+" to "+msToTime(calEvent.end)+"</p>";
                }});

            },
            eventColor: '#388E3C !important'
        });
        $(document).ajaxStop(function(){
            $('.modal-content').append(string);
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
<?php include('footer.php');?>
