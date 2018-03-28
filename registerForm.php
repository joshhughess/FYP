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

	
	<div class = "container">
	
			
				
				<div class = "panel panel-default">
					
					<div class = "panel-body">
						
							<div class ="page-header">
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
						</div>
					
				</div>
					
			
			<div class = "navbar navbar-default navbar-fixed-bottom">
				<a href = "#" class = "navbar-text pull-left"><p>|Group Project  |</p>

				<a href = "#" class = "navbar-text pull-left"><p>|Home|   </p>
				<a href = "https://www.copyrightservice.co.uk/protect/p11_web_design_copyright"class = "navbar-btn btn-info btn pull-right">COPYRIGHT</a>
			</div>
	</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src = "js/bootstrap.js"></script>﻿
		<script src="js/main(2).js"></script>
		<script src="js/scroll-top.js"></script>
		<script src="js/dropdown.js"></script>

		<a class="scroll-top" href="#" title=Scrolltotop" style="display: inline;"></a>


	</body>
</html>