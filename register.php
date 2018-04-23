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
        if (mysqli_query($connect, $sql)) {
            $addPref = "INSERT INTO preferences(username) VALUES('".$testUserName."')";
            $res = mysqli_query($connect,$addPref);
            if($res){
                $addFollow = "INSERT INTO follow(follower_uName,following_uName,accepted) VALUES('$testUserName','$testUserName','1')";
                $res = mysqli_query($connect,$addFollow);
                if($res) {
                    include('styleLinks.php');
                    echo "<div class=\"row\">
                            <div class=\"col s12\">
                                <div class=\"card blue lighten-4\">
                                    <div class=\"card-content\">
                                        <span class=\"card-title\">Thank you for registering an account with us!</span>
                                        <form action='connect.php' method='post'>
                                        <input type='text' hidden value='" . $testUserName . "' name='username'>
                                        <input type='text' hidden value='" . $testPassword . "' name='password'>
                                        <button type='submit' class='btn green darken-2' name='submit'>Click here to login</button>
                                    </div>
                                </div>
                            </div>   
                        </div>               
                    </form>";
                }else{
                    header("Location:registerForm.php?somethingWrong");
                }
            }else{
                header("Location:registerForm.php?somethingWrong");
            }

        } else {
            header("Location:registerForm.php?somethingWrong");
        }
    }
}
else
{
	echo "Fail connection";
}
?>