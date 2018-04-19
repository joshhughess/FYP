<?php
include 'connect.php';
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_SESSION['username'])) {
    $currentUser = $_SESSION['username'];

    //manipulate datetime etc
    $startDateChose = new DateTime($_POST['date']." ".$_POST['startTime'].":00");
    $endDateChose = new DateTime($_POST['date']." ".$_POST['endTime'].":00");
    $isAfter = $startDateChose<$endDateChose;
    //if date is after
    $checkStartDateBetween = array();
    $checkEndDateBetween = array();
    $checkDatesOutside = array();
    if($isAfter==1){
        $sql = "SELECT * FROM meetings WHERE userID='".$_SESSION['userID']."'";
        $res = mysqli_query($connect,$sql);
        if(mysqli_num_rows($res)>0) {
            while ($row = mysqli_fetch_assoc($res)) {
                //check start date is between rows in DB
                if($startDateChose->format('Y-m-d H:i:s') >= $row['start'] && $startDateChose->format('Y-m-d H:i:s') <= $row['end']){
                    array_push($checkStartDateBetween,'true');
                }else{
                    array_push($checkStartDateBetween,'false');
                }
                //check end date is between rows in DB
                if($endDateChose->format('Y-m-d H:i:s') >= $row['start'] && $endDateChose->format('Y-m-d H:i:s') <= $row['end']){
                    array_push($checkEndDateBetween,'true');
                }else{
                    array_push($checkEndDateBetween,'false');
                }
                //check dates are not on either side of dates in database
                if($startDateChose->format('Y-m-d H:i:s') <= $row['start'] && $endDateChose->format('Y-m-d H:i:s') >= $row['end']){
                    array_push($checkDatesOutside,'true');
                }else{
                    array_push($checkDatesOutside,'false');
                }
            }
        }
        if (count(array_unique($checkStartDateBetween)) === 1 && end($checkStartDateBetween) === 'true') {
            $allStartTaken = true;
        }else{
            $allStartTaken = false;
        }
        if (count(array_unique($checkEndDateBetween)) === 1 && end($checkEndDateBetween) === 'true') {
            $allEndTaken = true;
        }else{
            $allEndTaken = false;
        }
        if (count(array_unique($checkDatesOutside)) === 1 && end($checkDatesOutside) === 'true') {
            $datesOutside = true;
        }else{
            $datesOutside = false;
        }
        if($allStartTaken){
            echo "start date taken";
        }else{
            if($allEndTaken){
                echo "end date taken";
            }elseif ($datesOutside){
                echo "dates outside";
            }else{
                $sql = "INSERT INTO meetings(userID,user2id,start,end,title) VALUES('".$_SESSION['userID']."','".$_GET['user']."','".$startDateChose->format('Y-m-d H:i:s')."','".$endDateChose->format('Y-m-d H:i:s')."','".$_POST['placeName']." - with ".findUsername($_SESSION['userID'])."')";
                $res = mysqli_query($connect,$sql);
                if($res) {
                    $to = findUserEmail($_GET['user']);
                    $subject = findUsername($_SESSION['userID'])." has asked to climb with you!";
                    $message = "<html><body>";
                    $message .= "<p>".findUsername($_SESSION['userID'])." has asked to climb with you. Please confirm</p>";
                    $message .= "<table border='1'><tr><td>Climber</td><td>Date</td><td>Start Time</td><td>End Time</td><td>Location</td></tr>";
                    $message .= "<tr><td>".findUsername($_SESSION['userID'])."</td>";
                    $message .= "<td>".$startDateChose->format('l jS F Y')."</td>";
                    $message .= "<td>".$startDateChose->format('G:ia')."</td>";
                    $message .= "<td>".$endDateChose->format('G:ia')."</td>";
                    $message .= "<td>".$_POST['placeName']."</td></tr></table>";
                    $message .= "<a href='localhost/myClimb/profile.php'>Click here</a> to accept invitation</body></html>";
                    $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
                    $headers .= 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    //*********************redirect is dodgy!!!!!
                    if(mail($to,$subject,$message,$headers)){
                        $true = true;
    //                    header("Location: userProfile.php");
                    }
                    if($true){
                        echo "true";
                        header("Location:profile.php");
                        exit();
                    }
                }
            }
        }
    }else{
        echo "end date before";
    }
}else{
    header("Location:index.php?notLoggedin");
}
?>