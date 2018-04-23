<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
if(isset($_GET['somethingWrong'])){
    echo '<div class="row">
                <div class="col s12">
                    <div class="card red lighten-2">
                        <div class="card-content">
                            <span class="card-title">Sorry something went wrong, please try again.</span>
                        </div>
                    </div>
                </div>
           </div>';
}

?>

<title>Register</title>
<body onload="captcha()">
<form id="form1" class="registerForm" method="post" name="contactForm" action="register.php">
<p>Please enter your contact details to register an account and be able to upload news to the website:</p>
    <div class="row">
        <div class="input-field col s12 l6">
            <input type="text" required="required" maxlength="30" name="fName">
            <label for="fName" data-error="Must not be longer than 30 characters">First Name</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 l6">
            <input type="text" required="required" maxlength="25" name="lName">
            <label for="lName" data-error="Must not be longer than 25 characters">Last Name</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 l6">
            <input type="email" class="validate" required="required" pattern="[^ @]*@[^@]*" maxlength="60" name="email">
            <label for="email" data-error="Incorrect email found">Email Address</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 l6">
            <input type="text" name="usernameI" autocomplete="off" required="required" maxlength="20">
            <label for="usernameI" data-error="Must not be longer than 20 characters">Username</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 l6">
                <input type="password" name="passwordI" autocomplete="off" required="required"  maxlength="20">
                <label for="passwordI" data-error="Must not be longer than 20 characters">Password</label>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 l6">
            <p>Catpcha</p>
            <input type="text" class="col s9" id="mainCaptcha" readonly class="noselect">
            <input type="image" class="col s1" alt="refresh" src="images/refresh.jpg" id="refresh" onClick="captcha();" />
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 l6">
            <input id="userInput" class="col s12 l8" type="text" name="usersCaptcha" required="required"><i class="material-icons" id="tick" style="display:none">check</i>
            <label for="usersCaptcha">Enter Captcha</label>
            <input type="button" class="btn yellow darken-2 col s12 l4" value="Check Captcha" onClick="validateForm(); if(validateForm()){$('#tick').show();}else{alert('Captcha is not correct')}" name="checkCaptcha">
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 l6">
            <button class="btn sendRegister green darken-2 col s12 l4" name="submit">Submit</button>
        </div>
    </div>
</form>
<style>
    #mainCaptcha
    {
        color:transparent;
        text-shadow:0 0 2px rgba(0,0,0,0.5);
    }
    .noselect{
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .noselect::-moz-selection {
        background: transparent;
    }
    .noselect::selection {
        background: transparent;
    }
</style>
<script>
    $(document).ready(function(){
       $('.sendRegister').on('click',function(){
           if(validateForm()){
               $('.registerForm').submit();
           }else{
               alert("Captcha is incorrect");
           }
       });
    });
    var alpha=['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B',  'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    var randomLetter = alpha[Math.floor(Math.random() * alpha.length)];
    function captcha()
    {
        for(var i = 0;i<7;i++)
        {
            var first = alpha[Math.floor(Math.random() * alpha.length)];
            var second = alpha[Math.floor(Math.random() * alpha.length)];
            var third = alpha[Math.floor(Math.random() * alpha.length)];
            var fourth = alpha[Math.floor(Math.random() * alpha.length)];
            var fifth = alpha[Math.floor(Math.random() * alpha.length)];
            var sixth = alpha[Math.floor(Math.random() * alpha.length)];
            var seventh = alpha[Math.floor(Math.random() * alpha.length)];
        }
        var code = first + ' ' + second + ' ' + third + ' ' + fourth + ' ' + fifth + ' ' + sixth + ' ' + seventh;
        document.getElementById("mainCaptcha").value = code;
    }
    function validateForm()
    {
        var captcha = removeSpaces(document.getElementById('mainCaptcha').value);
        var userInput = removeSpaces(document.getElementById('userInput').value);
        if(captcha != userInput)
        {
            return false;
        }
        else
        {
            return true;
        }
        function removeSpaces(string)
        {
            return string.split(' ').join('');
        }
    }
</script>

	</body>
</html>