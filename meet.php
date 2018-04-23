<?php
include 'connect.php';
$connect = mysqli_connect($host,$userName,$password, $db);
if(isset($_SESSION['username'])) {
    $currentUser = $_SESSION['username'];

    if(isset($_GET['user'])) {
        $postDate = mysqli_real_escape_string($connect,$_POST['date']);
        $postStart = mysqli_real_escape_string($connect,$_POST['startTime']);
        $postEnd = mysqli_real_escape_string($connect,$_POST['endTime']);
        $placeName = mysqli_real_escape_string($connect,$_POST['placeName']);
        //manipulate datetime etc
        $startDateChose = new DateTime($postDate . " " . $postStart . ":00");
        $endDateChose = new DateTime($postDate . " " . $postEnd . ":00");


        $isAfter = $startDateChose < $endDateChose;
        //if date is after
        $checkStartDateBetween = array();
        $checkEndDateBetween = array();
        $checkDatesOutside = array();
        if ($isAfter == 1) {
            $sql = "SELECT * FROM meetings WHERE userID='" . $_SESSION['userID'] . "' AND accepted='1'
            OR user2id='" . $_SESSION['userID'] . "' AND accepted='1'";
            $res = mysqli_query($connect, $sql);
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    //check start date is between rows in DB
                    echo $row['start']."<br>";
                    if ($startDateChose->format('Y-m-d H:i:s') >= $row['start']
                        && $startDateChose->format('Y-m-d H:i:s') <= $row['end']) {
                        array_push($checkStartDateBetween, true);
                    } else {
                        array_push($checkStartDateBetween, false);
                    }
                    //check end date is between rows in DB
                    if ($endDateChose->format('Y-m-d H:i:s') >= $row['start']
                        && $endDateChose->format('Y-m-d H:i:s') <= $row['end']) {
                        array_push($checkEndDateBetween, true);
                    } else {
                        array_push($checkEndDateBetween, false);
                    }
                    //check dates are not on either side of dates in database
                    if ($startDateChose->format('Y-m-d H:i:s') <= $row['start']
                        && $endDateChose->format('Y-m-d H:i:s') >= $row['end']) {
                        array_push($checkDatesOutside, true);
                    } else {
                        array_push($checkDatesOutside, false);
                    }
                }
            }
            if (in_array(1, $checkStartDateBetween)){
                $allStartTaken = true;
            }else{
                $allStartTaken = false;
            }
            if (in_array(1, $checkEndDateBetween)){
                $allEndTaken = true;
            }else{
                $allEndTaken = false;
            }
            if (in_array(1, $checkDatesOutside)){
                $datesOutside = true;
            }else{
                $datesOutside = false;
            }
            echo "dates outside: ".var_dump($datesOutside);
            echo "<br>";
            echo "end inside: ".var_dump($allEndTaken);
            echo "<br>";
            echo "start inside: ".var_dump($allStartTaken);
            if ($allStartTaken) {
                header("Location:meetup.php?user=".$_GET['user']."&startDateTaken");
            } else {
                if ($allEndTaken) {
                    header("Location:meetup.php?user=".$_GET['user']."&endDateTaken");
                } elseif ($datesOutside) {
                    header("Location:meetup.php?user=".$_GET['user']."&datesOutside");
                } else {
                    $sql = "INSERT INTO meetings(userID,user2id,start,end,title) 
                      VALUES('" . $_SESSION['userID'] . "','" . $_GET['user'] . "',
                      '" . $startDateChose->format('Y-m-d H:i:s') . "',
                      '" . $endDateChose->format('Y-m-d H:i:s') . "',
                      '" . $placeName . " - with " . findUsername($_SESSION['userID']) . "')";
                    $res = mysqli_query($connect, $sql);
                    if ($res) {
                        $to = findUserEmail($_GET['user']);
                        $subject = findUsername($_SESSION['userID']) . " has asked to climb with you!";
                        $message = "<html><body>";
                        $message .= "<p>" . findUsername($_SESSION['userID']) . " has asked to climb with you. Please confirm</p>";
                        $message .= "<table border='1'><tr><td>Climber</td>
                        <td>Date</td><td>Start Time</td><td>End Time</td><td>Location</td></tr>";
                        $message .= "<tr><td>" . findUsername($_SESSION['userID']) . "</td>";
                        $message .= "<td>" . $startDateChose->format('l jS F Y') . "</td>";
                        $message .= "<td>" . $startDateChose->format('G:ia') . "</td>";
                        $message .= "<td>" . $endDateChose->format('G:ia') . "</td>";
                        $message .= "<td>" . $placeName . "</td></tr></table>";
                        $message .= "<a href='localhost/myClimb/profile.php'>Click here</a> to accept invitation</body></html>";
                        $headers = 'From: auto.myclimb@gmail.com' . "\r\n";
                        $headers .= 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        //*********************redirect is dodgy!!!!!
                        if (mail($to, $subject, $message, $headers)) {
                            $true = true;
                            //                    header("Location: userProfile.php");
                        }
                        if ($true) {
                            header("Location:profile.php");
                            exit();
                        }
                    }
                }
            }
        } else {
            header("Location:meetup.php?user=".$_GET['user']."&endDateBefore");
        }
    }else{
        header("Location:index.php");
    }
}else{
    header("Location:index.php?notLoggedin");
}
?>