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
echo "<form method='post' action='meet.php?user=".$_GET['user']."'>";
echo "<p>Select a date to book a climbing session with ".findUsername($_GET['user'])."</p>";
echo "<p>Date: <input type='text' class='datepicker' name='date'></p>";
echo "<p>Select a start time</p>";
echo "<input type='text' class='timepicker' name='startTime'>";
echo "<p>Select a end time</p>";
echo "<input type='text' class='timepicker' name='endTime'>";
echo "<p>Name of place</p>";
echo "<input type='text' name='placeName'>";
echo "<button type='submit' name='meetup'>Meet up</button>";
echo "</form>";
?>
<script>
//    $(document).ready(function(){
//        $('.datepicker').datepicker();
//    });
    $(document).ready(function(){
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year,
            today: 'Today',
            clear: 'Clear',
            close: 'Ok',
            format: 'yyyy-mm-dd',
            closeOnSelect: true // Close upon selecting a date,
        });
        $('.timepicker').pickatime({
            default: 'now', // Set default time: 'now', '1:30AM', '16:30'
            fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
            twelvehour: false, // Use AM/PM or 24-hour format
            donetext: 'OK', // text for done-button
            cleartext: 'Clear', // text for clear-button
            canceltext: 'Cancel', // Text for cancel-button
            autoclose: true, // automatic close timepicker
            ampmclickable: true,    // make AM PM clickable
            aftershow: function(){} //Function for after opening timepicker
        });
    });
</script>
