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
        if(isset($_GET['user'])) {
            $currentUser = $_SESSION['username'];

            if(isset($_GET['endDateBefore'])){
                echo '<div class="row">
                        <div class="col s12">
                            <div class="card red darken-1">
                                <div class="card-content">
                                    <span class="card-title">Unfortunately, something went wrong, please make sure that the end time is after the start time.</span>
                                </div>
                            </div>
                        </div>
                   </div>';
            }elseif(isset($_GET['startDateTaken'])){
                echo '<div class="row">
                        <div class="col s12">
                            <div class="card red darken-1">
                                <div class="card-content">
                                    <span class="card-title">It appears you\'ve already got that start time taken, you can\'t overlap existing meetings</span>
                                </div>
                            </div>
                        </div>
                   </div>';
            }elseif(isset($_GET['endDateTaken'])){
                echo '<div class="row">
                        <div class="col s12">
                            <div class="card red darken-1">
                                <div class="card-content">
                                    <span class="card-title">It appears you\'ve already got that end time taken, you can\'t overlap existing meetings</span>
                                </div>
                            </div>
                        </div>
                   </div>';
            }elseif(isset($_GET['datesOutside'])){
                echo '<div class="row">
                        <div class="col s12">
                            <div class="card red darken-1">
                                <div class="card-content">
                                    <span class="card-title">Unfortunately, something went wrong, please make sure that times are not overlapping an existing meeting.</span>
                                </div>
                            </div>
                        </div>
                   </div>';
            }

            echo "<title>Meetup</title>";
            echo "<form method='post' class='meeting' action='meet.php?user=" . $_GET['user'] . "'>";
            echo "<p>Select a date to book a climbing session with " . findUsername($_GET['user']) . "</p>";
            echo "<p>Date: <input type='text' class='datepicker' name='date' required></p>";
            echo "<p>Select a start time</p>";
            echo "<input type='text' class='timepicker startTime' name='startTime' required>";
            echo "<p>Select a end time</p>";
            echo "<input type='text' class='timepicker endTime' name='endTime' required>";
            echo "<p>Name of place</p>";
            echo "<input type='text' name='placeName' id='placeName' autocomplete='off' required>";
            echo "<button type='button' name='meetup' class='btn green darken-2' id='meetup'>Meet up</button>";
            echo "</form>";
            echo '<div id="modal1" class="modal">
            <div class="modal-content">
    
            </div>
        </div>';
        }else{
            header("Location:index.php");
        }
    }else{
        header("Location:index.php?notLoggedin");
    }
?>
<script>
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
        var theNames;
        var data = {};
        $.ajax({url:"names.php?climb",success:function(result){
            theNames = result;
            for (var i = 0; i < theNames.length; i++) {
                data[theNames[i][0]] = theNames[i][1];
            }
            $('#placeName').autocomplete({
                data: data,
            });
        }});
        $('#meetup').on('click',function(){
            if($('.datepicker').val()==""){
                $('.modal').modal();
                $('.modal').modal('open');
                $('.modal-content').html("<p>Please make enter a date</p>");
            }else{
                if($('.startTime').val()==""){
                    $('.modal').modal();
                    $('.modal').modal('open');
                    $('.modal-content').html("<p>Please make enter a start time</p>");
                }else {
                    if($('.endTime').val()==""){
                        $('.modal').modal();
                        $('.modal').modal('open');
                        $('.modal-content').html("<p>Please make enter an end time</p>");
                    }else {
                        var isFound=false;
                        for (var i = 0; i < theNames.length; i++) {
                            if ($('#placeName').val() == theNames[i][0]) {
                                isFound=true;
                                console.log("found");
                            }
                        }
                        if(isFound){
                            $('.meeting').submit();
                        }else {
                            $('.modal').modal();
                            $('.modal').modal('open');
                            $('.modal-content').html("<p>Please make sure that the name of location is an existing place from the dropdown menu</p>");
                        }
                    }
                }
            }
        })
    });
</script>
