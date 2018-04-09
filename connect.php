<?php
session_start();
$host = "localhost";
$userName = "root";
$password = "password";
$db = "myclimb";

$connect = mysqli_connect($host,$userName,$password, $db);


if($connect)
{
	if(isset($_POST['submit']))
	{
        date_default_timezone_set('Europe/London');
        $dateTime = date("Y-m-d h:i:s");
        $testUserName = mysqli_real_escape_string($connect,$_POST['username']);
		$testPassword = mysqli_real_escape_string($connect,$_POST['password']);
		$sql = "SELECT * FROM users WHERE '$testUserName' = username AND '$testPassword' = password";
		$sel = mysqli_query($connect,$sql);
		$checkUser = mysqli_fetch_array($sel);
			if($checkUser>0)
			{
			    $updateActivity = "UPDATE users SET lastActive='".$dateTime."' WHERE username='".$testUserName."'";
                $res = mysqli_query($connect,$updateActivity);
                if(!$res){echo mysqli_error($connect);}
                $_SESSION['username']=$testUserName;
                $_SESSION['userID']=$checkUser['userID'];
                $_SESSION['email']=$checkUser['emailAddress'];
                header('Location: index.php');
			}
			else
			{
				echo "Sorry this is incorrect. ";
				echo "<a href='index.php'>Go back and try again</a>";
			}
    }
mysqli_close($connect);
}
else
{
	echo "Fail connection";
}
function findUsername($userID)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE userID='" . $userID . "'";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            return $row['username'];
        }
    }
}
function findUserID($userName)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE username='" . $userName . "'";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            return $row['userID'];
        }
    }
}
function findUserEmail($userID)
{
    global $connect;
    $sql = "SELECT * FROM users WHERE userID='" . $userID . "'";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            return $row['emailAddress'];
        }
    }
}
function findClimbName($climbID){
    global $connect;
    $sql = "SELECT * FROM climbs WHERE climbID='".$climbID."'";
    $res = mysqli_query($connect, $sql);
    if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){
            return $row['name'];
        }
    }
}
?>