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
        $encrypt =  password_hash($testPassword, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users(firstName, lastName, emailAddress, username, password) VALUES('$firstName','$secondName','$email','$testUserName', '$encrypt')";
        $_SESSION['username'] = $testUserName;

        if (mysqli_query($connect, $sql)) {
            $addPref = "INSERT INTO preferences(username) VALUES('".$testUserName."')";
            $res = mysqli_query($connect,$addPref);
            if($res){
                echo "We created your account to login click here";
                echo "<form action='connect.php' method='post'>
<input type='text' hidden value='".$testUserName."' name='username'>
<input type='text' hidden value='".$encrypt."' name='password'>
<button type='submit' class='btn' name='submit'>Login</button>
</form>";
            }else{
                echo "register failed";
                echo mysqli_error($connect);
            }

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