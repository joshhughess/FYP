<?php
session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);


if($connect)
{
	if(isset($_POST['submit'])) {
        $firstName = mysqli_real_escape_string($connect, $_POST['fName']);
        $secondName = mysqli_real_escape_string($connect, $_POST['lName']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $testUserName = mysqli_real_escape_string($connect, $_POST['usernameI']);
        $testPassword = mysqli_real_escape_string($connect, $_POST['passwordI']);
        $sql = "INSERT INTO users(firstName, lastName, emailAddress, username, password) VALUES('$firstName','$secondName','$email','$testUserName', '$testPassword')";
        $_SESSION['username'] = $testUserName;

        if (mysqli_query($connect, $sql)) {
            echo "We created your account to login click here!!";
            echo "<a href='index.php' >Login</a>";
        } else {
            echo "Sorry, something went wrong! This username might already be taken, please try a different one";
            echo "<a href='registerForm.php'>Go back</a>";
        }
    }
}
else
{
	echo "Fail connection";
}
?>