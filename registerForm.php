<?php
include('connect.php');
$connect = mysqli_connect($host,$userName,$password, $db);
include('styleLinks.php');
if(isset($_SESSION['username'])){
    include('navLogin.php');
}else{
    include('nav.php');
}
?>
<body onload="captcha()">
<form id="form1" method="post" name="contactForm" action="register.php">
<p>Please enter your contact details to register an account and be able to upload news to the website:</p>
<p>First Name:</p>
<input type="text" required placeholder="Enter your First Name" maxlength="30" name="fName">
<p>Last Name:</p>
<input type="text" required placeholder="Enter your Last Name" maxlength="25" name="lName">
<p>Email Address:</p>
<input type="email" required placeholder="Enter your Email" pattern="[^ @]*@[^@]*" maxlength="60" name="email">
<p>Username:</p>
<input type="text" name="usernameI" required placeholder="Enter your username" maxlength="20">
<p>Password:</p>
<input type="password" name="passwordI" required placeholder="Enter a password" maxlength="20"><br>
<input type="text" id="mainCaptcha" readonly class="noselect">
<input type="image" alt="refresh" src="images/refresh.jpg" id="refresh" onClick="captcha();" />
<p>Enter the captcha code above here:</p>
<p><input id="userInput" type="text" placeholder="Enter the CAPTCHA here" required><img src="images/tick.png" id="tick" style="display:none;width: 21px;"></p>
<p><input type="button" value="Check Captcha" onClick="validateForm(); if(validateForm()){$('#tick').show();}" name="checkCaptcha"></p>
<p><input type="submit" value="Submit" name="submit"></p>
</form>
<style>
    #mainCaptcha
    {
        color:transparent;
        text-shadow:0 0 2px rgba(0,0,0,0.5);
    }
</style>
<script>
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